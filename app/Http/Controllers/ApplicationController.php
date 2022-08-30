<?php

namespace App\Http\Controllers;

use App\Enums\Color;
use App\Filter\ApplicationFilters;
use App\Interfaces\ExportInterface;
use App\Models\Application;
use App\Models\ApplicationData;
use App\Models\Attachment;
use App\Models\CarCharacteristicValue;
use App\Models\CarGeneration;
use App\Models\CarMark;
use App\Models\CarModel;
use App\Models\CarModification;
use App\Models\CarSeries;
use App\Models\CarType;
use App\Models\Client;
use App\Models\Parking;
use App\Models\Partner;
use App\Models\Pricing;
use App\Models\Status;
use App\Models\User;
use Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApplicationController extends AppController
{
    protected $AttachmentController;
    private $exporter;

    public function __construct(ExportInterface $exporter, AttachmentController $AttachmentController)
    {
        $this->AttachmentController = $AttachmentController;
        $this->exporter = $exporter;

//        $this->middleware('can:viewAny,App\Models\Application')->only('index', 'show');
        /*        $this->middleware(['permission:application_view'])->only('index', 'show');
                $this->middleware(['permission:application_create'])->only('create', 'store');
                $this->middleware(['permission:application_update'])->only('edit', 'update');
                $this->middleware(['permission:application_delete'])->only('destroy');*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ApplicationFilters $filters, $status_id = null)
    {

        $this->authorize('viewAny', Application::class);
        $statuses = Status::where('is_active', true)->pluck('id')->toArray();

        $status = ($status_id) ? Status::findOrFail($status_id) : null;
        $status_name = ($status) ? $status->name : 'Все';
        $status_sort = ($status) ? $status->status_sort : 'arriving_at';

        $applications = Application::
        applications()
            ->filter($filters)
            ->where('status_id','!=','8')
            ->when($status_id, function ($query, $status_id) {
                return $query->where('status_id', $status_id);
            })
            ->when(!$status_id, function ($query) use ($statuses) {
                return $query->whereIn('status_id', $statuses);
            })
            ->with('parking')
            ->with('issuedBy')
            ->with('acceptedBy')
            ->with('status')
            ->with('attachments')
            ->with('carType')
            ->with('partner')
            ->with('issueAcceptions')
            ->with('acceptions')
            ->with('issuance')
            ->with('viewRequests')
            ->orderBy($status_sort, 'desc')
            ->paginate(config('app.paginate_by', '25'))->withQueryString();


        foreach ($applications as $key => $item) {
            $pricing = Pricing::where([
                ['partner_id', $item->partner_id],
                ['car_type_id', $item->car_type_id]
            ])
                ->select('discount_price', 'regular_price', 'free_days')
                ->first();

            $applications[$key]['pricing'] = $pricing;
            $item->currentParkingCost = $item->currentParkingCost;

        }

        $title = __($status_name);
        switch ($request->get('direction', 'column')) {
            case 'table':
                return view('applications.index_table', compact('title', 'applications'));
            case 'row':
                return view('applications.index_row', compact('title', 'applications'));
            default:
                return view('applications.index', compact('title', 'applications'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Application $application)
    {
        $this->authorize('create', Application::class);
        $carTypes = CarType::where('is_active', 1)
            ->select('id', 'name')
            ->orderBy('rank', 'desc')->orderBy('name', 'ASC')
            ->get();

        if (auth()->user()->partner || auth()->user()->hasRole(['Partner'])) {
            $partners = auth()->user()->partner()->get();
//            $parkings = auth()->user()->partnerParkings;
            $parkings = Parking::all();
        } else {
            $partners = Partner::all();
            $parkings = Parking::parkings()->get();
        }
        if (auth()->user()->hasRole(['Admin'])) {
            $partners = auth()->user()->partners;
        }
        if (auth()->user()->hasRole(['SuperAdmin'])) {
            $partners = Partner::all();
        }

        if (!auth()->user()->hasRole(['SuperAdmin|Admin|Partner'])) {
            $partners = auth()->user()->owner->partners;

        }
        $colors = Color::getColors();

        $user = User::where('id', auth()->user()->getUserOwnerId())->first();
        $managers = $user->children()->role('Manager')->orderBy('name', 'asc')->get();
        $statuses = Status::statuses($application)->get();


        $title = __('Create a Request');

        if ($application->exists) {
            $updateData = array_merge(
                $this->applicationUpdateData($application),
                compact(
                    'title',
                    'carTypes',
                    'partners',
                    'parkings',
                    'managers',
                    'statuses',
                    'colors')
            );
            return view('applications.duplicate_create', $updateData);
        } else {
            return view('applications.create', compact(
                'title',
                'carTypes',
                'partners',
                'parkings',
                'managers',
                'statuses',
                'colors'));
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
//        $request->dd();
        $required = true;
        $returned = false;
        if (isset($request->car_data['returned'])) {
            $returned = true;
        }
        if (isset($request->car_data['vin_status']) && isset($request->car_data['license_plate_status'])) {
            $required = false;
        }
        $this->authorize('create', Application::class);
        $carRequest = $request->car_data;
        $applicationRequest = $request->app_data;
        $statuses = [1, 7];
        if (auth()->user()->hasRole(['Manager', 'Admin', 'SuperAdmin'])) {
            $statuses = [1, 2, 3, 4, 5, 6, 7];
        }
        $car_type = null;

        if (isset($request->car_data['car_type_id'])) {
            $car_type = $request->car_data['car_type_id'];
        }

        $validator = Validator::make($carRequest, [
            'vin_array' => $required ? [
                'exclude_if:returned,1',
                'required_without:license_plate',
                $returned ? '' : 'unique:applications,vin',
                'nullable'
            ] : [],
            'license_plate' => $required ? [
                'exclude_if:returned,1',
                $returned ? '' : 'unique:applications,license_plate',
                'nullable'] : [],
            'car_type_id' => ['integer', 'required'],
            'car_mark_id' => ($car_type == 27) ? ['integer'] : ['integer', 'required'],
            'car_model_id' => ['integer'],
            'year' => ['integer'],
            'car_key_quantity' => ['integer', 'required', 'max:4', 'min:0'],
            'status_id' => ['exists:statuses,id', Rule::in($statuses)]
        ]);

        $validator->sometimes('returned', function ($attribute, $value, $fail) use ($carRequest) {
            $count = Application::where('vin', $carRequest['vin_array'])->count();
            if ($count < 1) {
                $fail('Нет такого дубликата!');
            }
        }, function ($input) {
            return $input->returned == 1;
        });

        $validator->validate();

        Validator::make($applicationRequest, [
            'external_id' => ['required'],
            'partner_id' => ['integer', 'required', 'exists:partners,id'],
            'parking_id' => ['integer', 'required', 'exists:parkings,id'],
        ])->validate();


        /*if ($carValidator->fails() || $applicationValidator->fails()) {
            return response()->json(['errors'=>['car'=>$carValidator->errors(), 'application'=>$applicationValidator->errors()]], 422);
        }*/
        /*if (auth()->user()->hasRole('doer')) {
            $applicationValidator = Validator::make($applicationRequest, [
                'status_id' => 'required',
            ]);
            if ($applicationValidator->fails()) {
                return response()->json(['errors'=>['application'=>$applicationValidator->errors()]], 422);
            }
            $applicationRequest['parking_id'] = auth()->user()->parking_id;
        }*/

        $applicationDataArray = get_object_vars(new ApplicationData());
        $applicationData = array_merge($applicationDataArray, $applicationRequest, $carRequest);

        /*$applicationData['condition_gear'] = json_encode($applicationData['condition_gear']);
        $applicationData['condition_engine'] = json_encode($applicationData['condition_engine']);
        $applicationData['condition_electric'] = json_encode($applicationData['condition_electric']);
        $applicationData['condition_transmission'] = json_encode($applicationData['condition_transmission']);*/
//        $applicationData['services'] = json_encode($applicationData['services']);
//        $applicationData['exterior_damage'] = json_encode($applicationData['exterior_damage']);
//        $applicationData['interior_damage'] = json_encode($applicationData['interior_damage']);
//        dd($applicationData['vin_array']);
        if ($required) {
            $applicationData['vin'] = $applicationData['vin_array'];
        }
        unset($applicationData['car_series_body']);

        foreach ($applicationData as $key => $value) {
            if ($value === '' || $value === null || $value === 'null') {
                if ($key == 'issued_by' || $key == 'issued_at') continue;
                unset($applicationData[$key]);
            }
        }


        /*        if (isset($applicationData['status_id'])) {
                    $applicationData['status_id'] = 1;
                } else {
                    $applicationData['status_id'] = 7;
                }
                if(auth()->user()->hasRole('Admin')) {
                    if(isset($applicationData['status_admin'])) {
                        $applicationData['status_id'] = $applicationData['status_admin'];
                    }
                }*/

        if (!in_array($applicationData['status_id'], [1, 7])) {
            $applicationData['arrived_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }


        if (isset($applicationData['car_type_id']) && in_array($applicationData['car_type_id'], [1, 2, 6, 7, 8])) {
            $searchFilters = [];
            if (isset($applicationData['car_mark_id']) && is_numeric($applicationData['car_mark_id']) && $applicationData['car_mark_id'] > 0) {
                $searchFilters[] = ['car_marks.id', $applicationData['car_mark_id']];
            }
            if (isset($applicationData['car_model_id']) && is_numeric($applicationData['car_model_id']) && $applicationData['car_model_id'] > 0) {
                $searchFilters[] = ['car_models.id', $applicationData['car_model_id']];
            }
            if (isset($applicationData['car_generation_id']) && is_numeric($applicationData['car_generation_id']) && $applicationData['car_generation_id'] > 0) {
                $searchFilters[] = ['car_generations.id', $applicationData['car_generation_id']];
            }
            $carTitleData = DB::table('car_types')
                ->select('car_types.name as car_type', 'car_marks.name as car_mark', 'car_models.name as car_model', 'car_generations.name as car_generation')
                ->leftJoin('car_marks', 'car_types.id', '=', 'car_marks.car_type_id')
                ->leftJoin('car_models', 'car_marks.id', '=', 'car_models.car_mark_id')
                ->leftJoin('car_generations', 'car_models.id', '=', 'car_generations.car_model_id')
                ->where($searchFilters)
                ->first();

            $applicationData['car_title'] = '';
            if (isset($applicationData['car_mark_id']) && is_numeric($applicationData['car_mark_id'])) {
                $applicationData['car_title'] .= "{$carTitleData->car_mark}";
            }
            if (isset($applicationData['car_model_id']) && is_numeric($applicationData['car_model_id'])) {
                $applicationData['car_title'] .= " {$carTitleData->car_model}";
            }
            if (isset($applicationData['car_generation_id']) && is_numeric($applicationData['car_generation_id'])) {
                $applicationData['car_title'] .= " {$carTitleData->car_generation}";
            }
            if (isset($applicationData['year'])) {
                $applicationData['car_title'] .= " {$applicationData['year']}";
            }
        }
        if ($applicationData['status_id'] == 2 && auth()->user()->hasRole(['Manager'])) {

            $applicationData['accepted_by'] = auth()->user()->id;
        }
//        dd($applicationData);
        $application = auth()->user()->applications()->create($applicationData);
        if ($application->status_id == 7) {
            $application->issueAcceptions()->create([
                'is_issue' => false,
                'user_id' => auth()->id()
            ]);
        }

//        event(new ApplicationUpdated(Application::find($application['id']), $applicationData));

        $attachments = $this->AttachmentController->storeToModel($request, 'images');

        if (count($attachments) > 0) {
            $application->attachments()->saveMany($attachments);
        }

        if ($application->exists) {
            Toastr::success(__('Saved.'));
            return redirect()->route('applications.index')->with('success', __('Saved.'));
        }
        Toastr::error(__('Error'));
        return redirect()->back()->with('error', __('Error'));
    }

    /**
     * Display the specified resource.
     *status_id
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function generateAct(Request $request, Application $application)
    {

        return $this->exporter->export([
            'application' => $application
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {

        $application = Application::application($id)->firstOrFail();


        $this->authorize('update', $application);

        $carTypes = CarType::where('is_active', 1)
            ->select('id', 'name')
            ->orderBy('rank', 'desc')->orderBy('name', 'ASC')
            ->get();
        $partners = Partner::all();
        $parkings = Parking::parkings()->get();
        $colors = Color::getColors();


        $user = User::where('id', auth()->user()->getUserOwnerId())->first();
        $managers = $user->children()->role('Manager')->orderBy('name', 'asc')->get();

        $statuses = Status::statuses($application)->get();

        extract($this->applicationUpdateData($application));


//        $exterior_damage = $application->exterior_damage;
//        $interior_damage = $application->interior_damage;
//        $condition_engine = $application->condition_engine;
//        $condition_electric = $application->condition_electric;
//        $condition_gear = $application->condition_gear;
//        $condition_transmission = $application->condition_transmission;
        $dateDataApplication = ($application->arriving_at) ? $application->arriving_at->format('d-m-Y') : now()->format('d-m-Y');
        $dateTime = $dateDataApplication . ' ' . $application->arriving_interval;
        $title = __('Update a Request');

        return view('applications.edit', compact(
            'title',
            'partners',
            'parkings',
            'managers',
            'statuses',
            'colors',
            'application',
            'attachments',
            'dataApplication',
            'carTypes',
            'carMarks',
            'carModels',
            'carYears',
            'carGenerations',
            'carSeriess',
            'carModifications',
            'carEngines',
            'carTransmissions',
            'carGears',
            'dateTime'
        ));

    }

    public function addAttachmentsFromPopup(Request $request, $id)
    {

        // return $request->all();

        $application = Application::application($id)->firstOrFail();
        $this->authorize('update', $application);

        if ($request->has('doc') && $request->doc == 'true') {
            request()->request->remove('doc');
            // return 'creating doc'.$request->doc;
            $attachments = $this->AttachmentController->storeToModelDoc($request, 'docspopup');
        } else {
            // return 'creating image'.$request->doc;
            if ($request->has('doc')) {
                request()->request->remove('doc');
            }
            $attachments = $this->AttachmentController->storeToModel($request, 'imagespopup');
        }


        if (count($attachments) > 0) {
            $application->attachments()->saveMany($attachments);
        }
        $result = [];
        foreach ($attachments as $attachment) {
            $name = $attachment->name;
            if (str_contains($attachment->name, '^')) {
                $name = explode("^", $attachment->name)[1];
            }
            $attachment->name = $name;
            $result[$name] = $attachment->id;
        }
        return ($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        $request->dd();
        $required = true;
        $returned = false;
        if (isset($request->car_data['returned'])) {
            $returned = true;
        }
        $application = Application::application($id)->firstOrFail();
        $this->authorize('update', $application);

        if (isset($request->car_data['car_type_id'])) {
            $car_type = $request->car_data['car_type_id'];
        }

        if (isset($request->car_data['vin_status']) && isset($request->car_data['license_plate_status'])) {
            $required = false;
        }

        $carRequest = $request->car_data;
        $applicationRequest = $request->app_data;

        $statuses = [1, 7];
        if (auth()->user()->hasRole(['Manager', 'Admin', 'SuperAdmin'])) {
            $statuses = [1, 2, 3, 4, 5, 6, 7];
        }


        $validator = Validator::make($carRequest, [
            'vin_array' => $required ? [
                'exclude_if:returned,1',
                'required_without:license_plate',
                $returned ? '' : Rule::unique('applications', 'vin')->ignore($application->id),
                'nullable'
            ] : [],
            'license_plate' => $required ? [
                'exclude_if:returned,1',
                $returned ? '' : Rule::unique('applications', 'license_plate')->ignore($application->id),
                'nullable'
            ] : [],

            'car_type_id' => ['integer', 'required'],
            'car_mark_id' => ($car_type == 27) ? ['integer'] : ['integer', 'required'],
            'car_model_id' => ['integer'],
            'year' => ['integer'],
            'car_key_quantity' => ['integer', 'required', 'max:4', 'min:0'],
            'preloaded.*' => ['nullable', 'sometimes', 'exists:attachments,id']
        ]);
        $validator->sometimes('returned', function ($attribute, $value, $fail) use ($carRequest) {
            $count = Application::where('vin', $carRequest['vin_array'])->count();
            if ($count < 1) {
                $fail('Нет такого дубликата!');
            }
        }, function ($input) {
            return $input->returned == 1;
        });

        $validator->validate();

        Validator::make($applicationRequest, [
            'external_id' => ['required'],
            'partner_id' => ['integer', 'required', 'exists:partners,id'],
            'parking_id' => ['integer', 'required', 'exists:parkings,id'],
        ])->validate();

        $applicationDataArray = get_object_vars(new ApplicationData());
        $applicationData = array_merge($applicationDataArray, $applicationRequest, $carRequest);

        if (isset($applicationData['vin_array'])) $applicationData['vin'] = $applicationData['vin_array'];

        unset($applicationData['car_series_body']);


        foreach ($applicationData as $key => $value) {
            if ($value === '' || $value === null || $value === 'null') {
                if ($key == 'issued_by' || $key == 'issued_at') continue;
                unset($applicationData[$key]);
            }
        }

        if (isset($applicationData['car_type_id']) && in_array($applicationData['car_type_id'], [1, 2, 6, 7, 8])) {
            $searchFilters = [];
            if (isset($applicationData['car_mark_id']) && is_numeric($applicationData['car_mark_id']) && $applicationData['car_mark_id'] > 0) {
                $searchFilters[] = ['car_marks.id', $applicationData['car_mark_id']];
            }
            if (isset($applicationData['car_model_id']) && is_numeric($applicationData['car_model_id']) && $applicationData['car_model_id'] > 0) {
                $searchFilters[] = ['car_models.id', $applicationData['car_model_id']];
            }
            if (isset($applicationData['car_generation_id']) && is_numeric($applicationData['car_generation_id']) && $applicationData['car_generation_id'] > 0) {
                $searchFilters[] = ['car_generations.id', $applicationData['car_generation_id']];
            }
            $carTitleData = DB::table('car_types')
                ->select('car_types.name as car_type', 'car_marks.name as car_mark', 'car_models.name as car_model', 'car_generations.name as car_generation')
                ->leftJoin('car_marks', 'car_types.id', '=', 'car_marks.car_type_id')
                ->leftJoin('car_models', 'car_marks.id', '=', 'car_models.car_mark_id')
                ->leftJoin('car_generations', 'car_models.id', '=', 'car_generations.car_model_id')
                ->where($searchFilters)
                ->first();

            $applicationData['car_title'] = '';
            if (isset($applicationData['car_mark_id']) && is_numeric($applicationData['car_mark_id'])) {
                $applicationData['car_title'] .= "{$carTitleData->car_mark}";
            }
            if (isset($applicationData['car_model_id']) && is_numeric($applicationData['car_model_id'])) {
                $applicationData['car_title'] .= " {$carTitleData->car_model}";
            }
            if (isset($applicationData['car_generation_id']) && is_numeric($applicationData['car_generation_id'])) {
                $applicationData['car_title'] .= " {$carTitleData->car_generation}";
            }
            if (isset($applicationData['year'])) {
                $applicationData['car_title'] .= " {$applicationData['year']}";
            }
        }

        if ($applicationData['status_id'] != 7) {
            $date = date('Y-m-d H:i:s', strtotime($request->app_data['arriving_at']));
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date)->startOfDay();
            $applicationData['arrived_at'] = $date;
            $application->acceptions()->delete();
            $application->acceptedBy()->associate(auth()->user());
        }

        /*        if(auth()->user()->hasRole('Admin') && !isset($applicationData['accept'])) {
                    if(isset($applicationData['status_admin'])) {
                        $applicationData['status_id'] = $applicationData['status_admin'];
                    }
                }*/
        if (isset($request->car_data['vin_status'])) {
            $applicationData['vin'] = null;
            unset($applicationData['vin_status']);
        }
        if (isset($request->car_data['license_plate_status'])) {
            $applicationData['license_plate'] = null;
            unset($applicationData['license_plate_status']);
        }
//        dump($applicationData);
//        $request->dd();
//        dd($applicationData);
        $isUpdate = $application->update($applicationData);

        if ($application->status_id == 7) {
            $application->issueAcceptions()->create([
                'is_issue' => false,
                'user_id' => auth()->id()
            ]);
        }

        /*  if ($applicationData['status_id'] != 1) {
              $application->issueAcceptions()->create([
                  'is_issue' => false
              ]);
          }*/

//        event(new ApplicationUpdated(Application::find($application['id']), $applicationData));


        $attachments = $this->AttachmentController->storeToModel($request, 'images');

        if (count($attachments) > 0) {
            $application->attachments()->saveMany($attachments);
        }

        if ($isUpdate) {
            Toastr::success(__('Updated.'));
            return redirect()->route('applications.index', ['status_id' => $application->status->id]);
        }

        Toastr::error(__('Error'));
        return redirect()->back()->with('error', __('Error'));
    }


    public function removeAttachment($attachment)
    {
        return Attachment::where('id', $attachment)->delete();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $application = Application::application($id)->firstOrFail();
        $this->authorize('delete', $application);

        $result = $application->update(['status_id' => 8]);

        if ($result) {
            Toastr::success(__('Deleted.'));
            return redirect()->back();
        }

        Toastr::error(__('Error'));
        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {

        $application = Application::application($id)->firstOrFail();
        $this->authorize('delete', $application);

        $result = $application->update(['status_id' => 8]);

        if ($result) {
            if ($request->has('car_additional')) {
                $application->update(['car_additional' => $application->car_additional . '<br>' . 'Причина удаления:' . '<br>' . $request->car_additional]);
            }
            Toastr::success(__('Deleted.'));
            return redirect()->back();
        }

        Toastr::error(__('Error'));
        return redirect()->back();
    }

    public function deny(Request $request, $application_id)
    {

        $application = Application::application($application_id)->firstOrFail();
        $status = Status::find(6);

        if ($application->exists) {
            if ($request->has('car_additional')) {
                $application->update(['car_additional' => $application->car_additional . '<br>' . 'Причина отклонения:' . '<br>' . $request->car_additional]);
                //$application->car_additional = $request->car_additional;
            }
            $application->status()->associate($status);
            $application->acceptions()->delete();

            return ($application->save())
                ? redirect()->back()->with('success', __('Saved.'))
                : redirect()->back()->with('error', __('Error'));

        }
        return null;
    }

    public function issuanceCreate($application_id)
    {
        $application = Application::application($application_id)->firstOrFail();
        $client = null;

        if ($application->issuance) {
            $client = $application->issuance->client;
        }

        $individualLegalOptions = Client::issuanceIndividualLegalOptions();

        $title = __('Issue a car');
        return view('applications.issuance', compact(
            'title',
            'application',
            'client',
            'individualLegalOptions',
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function issuance(Request $request, $application_id)
    {

        $clientData = $request->client;

        Validator::make($clientData, [
            'inn' => ['string', 'nullable'],
            'organization_name' => ['string', 'nullable'],
            'fio' => ['string', 'nullable'],
            'phone' => ['numeric', 'nullable'],
        ])->validate();

        Validator::make($request->app_data, [
            'issued_at' => ['date'],
        ])->validate();

        foreach ($clientData as $key => $value) {
            if (is_null($value) || $value == 'null') {
                unset($clientData[$key]);
            }
        }

        $application = Application::application($application_id)->firstOrFail();

        if (!$application->issuance) {
            $client = Client::create($clientData);
        } else {
            $client = tap($application->issuance->client)->update($clientData);
        }

        $attachments = $this->AttachmentController->storeToModelDoc($request, 'docs');

        if (count($attachments) > 0) {
            $application->attachments()->saveMany($attachments);
        }

        if ($client->exists) {
            $application->issuedBy()->associate(auth()->user());
            $application->update([
                'status_id' => 3,
                'client_id' => $client->id,
                'issued_at' => $request->app_data['issued_at']
            ]);
            $application->issuance()->delete();
            Toastr::success(__('Issued.'));
            return redirect()->route('applications.index');
        } else {
            Toastr::error(__('Error'));
            return redirect()->back();
        }
    }

    public function getModelContent($application_id)
    {
        $application = Application::where('id', $application_id)
            ->with('parking')
            ->with('issuedBy')
            ->with('acceptedBy')
            ->with('status')
            ->with('attachments')
            ->with('carType')
            ->with('partner')
            ->with('issueAcceptions')
            ->with('acceptions')
            ->with('issuance')->first();

        if (!empty($application)) {

            $pricing = Pricing::where([
                ['partner_id', $application->partner_id],
                ['car_type_id', $application->car_type_id]
            ])
                ->select('discount_price', 'regular_price', 'free_days')
                ->first();

            $application['pricing'] = $pricing;
            $application->currentParkingCost = $application->currentParkingCost;

            $htmlRender = view('applications.ajax.modal', compact('application'))->render();
            return response()->json(['success' => true, 'html' => $htmlRender]);

        }
        return null;
    }

    public function checkDuplicate(Request $request)
    {

        $licensePlateDuplicates = (isset($request->license_plate) && strlen($request->license_plate) >= 3)
            ?
            Application::with('status')
                ->where(function ($query) {
                    $query->whereIn('accepted_by', auth()->user()->getUsersAdmin())
                        ->orWhereIn('user_id', auth()->user()->getUsersAdmin());
                })
                ->where('license_plate', 'like', '%' . $request->license_plate . '%')
                ->get()->toArray()
            : [];
        $vinDuplicates = [];
        if (is_array($request->vin) && count($request->vin) > 0) {

            $searchVin = false;
            $vinArray = $request->vin;
            foreach ($vinArray as $key => $singleVin) {
                if (empty($singleVin)) {
                    unset($vinArray[$key]);
                } else if (strlen($singleVin) > 2) {
                    $searchVin = true;
                }
            }

            if ($searchVin) {

                $singleVin = $vinArray[0];
                $vinQuery = Application::with('status')
                    ->where(function ($query) {
                        $query->whereIn('accepted_by', auth()->user()->getUsersAdmin())
                            ->orWhereIn('user_id', auth()->user()->getUsersAdmin());
                    })
                    ->where('vin', 'like', '%' . $singleVin . '%');

                $vinDuplicates = $vinQuery->get()->toArray();

            }

        }

        return response()->json([
            'license_plate' => $licensePlateDuplicates,
            'vin' => $vinDuplicates,
        ]);
    }

    public function getCarMarkList($type_id)
    {
        $carMarks = [];
        if ($type_id == 1) {
            $carMarks = CarMark::where([
                ['car_marks.is_active', 1],
                ['car_marks.car_type_id', $type_id],
                ['car_generations.year_begin', '>=', 1990],
                ['car_generations.year_end', '<=', 2020],
            ])
                ->leftJoin('car_models', 'car_marks.id', '=', 'car_models.car_mark_id')
                ->leftJoin('car_generations', 'car_models.id', '=', 'car_generations.car_model_id')
                ->select('car_marks.id as id', 'car_marks.name as name')
                ->groupBy('car_marks.id', 'car_marks.name')
                ->orderBy('car_marks.rank', 'asc')->orderBy('car_marks.name', 'ASC')
                ->get();
        } else if ($type_id == 2 || $type_id == 6 || $type_id == 7 || $type_id == 8) {
            $carMarks = CarMark::where([
                ['car_marks.is_active', 1],
                ['car_marks.car_type_id', $type_id],
            ])
                ->select('car_marks.id as id', 'car_marks.name as name')
                ->groupBy('car_marks.id', 'car_marks.name')
                ->orderBy('car_marks.rank', 'asc')->orderBy('car_marks.name', 'ASC')
                ->get();
        }

        if (count($carMarks) > 0 && $type_id != 5) {
            $carMarks = CarMark::setLogo($carMarks);
            return $carMarks;
        }
    }

    public function getCarModelList($mark_id)
    {
        if (isset($mark_id) && is_numeric($mark_id)) {

            $carModels = CarModel::where(['car_mark_id' => $mark_id])
                ->select('id', 'name')
                ->orderBy('rank', 'asc')->orderBy('name', 'ASC')->get();

            if (count($carModels) > 0) {
                return $carModels;
            } else {
                return ['id' => 0, 'name' => __('Unknown Model')];
            }
        }
    }

    public function getCarYearList($model_id)
    {
        if (isset($model_id) && is_numeric($model_id)) {

            $carYears = CarGeneration::where(['car_model_id' => $model_id])
                ->select(DB::raw("min(year_begin) as year_begin,max(year_end) as year_end"))
                ->groupBy('car_model_id')
                ->first();

            return isset($carYears->year_begin) ? $carYears : (object)['year_begin' => 1950, 'year_end' => Carbon::now()->year];
        }

    }

    public function getCarGenerationList($model_id, $year)
    {

        if (isset($model_id) && is_numeric($model_id)) {

            $searchFilter = [['car_model_id', $model_id]];
            if (isset($year) && is_numeric($year) && $year > 0) {
                $searchFilter[] = ['year_begin', '<=', $year];
                $searchFilter[] = ['year_end', '>=', $year];
            }
            $carGenerations = CarGeneration::where($searchFilter)
                ->select('id', 'name')
                ->get();
            return $carGenerations;
        }
    }

    public function getCarSeriesList($model_id, $generation_id)
    {
        if (isset($model_id) && is_numeric($model_id)) {
            $searchFilter[] = ['car_model_id', $model_id];
            if (isset($generation_id) && is_numeric($generation_id) && $generation_id > 0) {
                $searchFilter[] = ['car_generation_id', $generation_id];
            }

            $carSeries = CarSeries::where($searchFilter)
                ->select('id', 'name')
                ->get();
            $returnValues = [];
            foreach ($carSeries as $singleSeries) {
                $returnValues[] = (object)['id' => $singleSeries->id, 'name' => $singleSeries->name, 'body' => $singleSeries->body_name];
            }
            return $returnValues;
        }
    }

    public function getCarModificationList($model_id, $series_id, $year)
    {
        if (isset($model_id) && is_numeric($model_id) && isset($series_id) && is_numeric($series_id)) {
            $yearFilter = [];
            if (isset($year) && is_numeric($year) && $year > 0) {
                $yearFilter[] = ['year_begin', '<=', $year];
                $yearFilter[] = ['year_end', '>=', $year];
            }
            $carModifications = CarModification::where([
                    ['car_model_id', $model_id],
                    ['car_series_id', $series_id]
                ]
            )
                ->where(function ($q) use ($yearFilter) {
                    $q->where($yearFilter)
                        ->orWhereNull('year_begin')
                        ->orWhereNull('year_end');
                })
                ->select('id', 'name')
                ->get();
            return $carModifications;
        }
    }

    public function getCarEngineList($modification_id)
    {
        if (isset($modification_id) && is_numeric($modification_id)) {

            $carEngines = CarCharacteristicValue::where([
                ['car_modification_id', $modification_id],
                ['car_characteristic_id', 12]
            ])
                ->select('id', 'value as name')
                ->get();
            return $carEngines;
        }
    }

    public function getCarTransmissionList($modification_id)
    {
        if (isset($modification_id) && is_numeric($modification_id)) {

            $carTransmissions = CarCharacteristicValue::where([
                ['car_modification_id', $modification_id],
                ['car_characteristic_id', 24]
            ])
                ->select('id', 'value as name')
                ->get();
            return $carTransmissions;
        }
    }

    public function getCarGearList($modification_id)
    {
        if (isset($modification_id) && is_numeric($modification_id)) {

            $carGears = CarCharacteristicValue::where([
                ['car_modification_id', $modification_id],
                ['car_characteristic_id', 27]
            ])
                ->select('id', 'value as name')
                ->get();
            return $carGears;
        }
    }

    public function filteredItems($carYears)
    {
        if (!empty($carYears)) {
            $filteredItems = [];
            if (isset($carYears->year_begin) && isset($carYears->year_end)) {
                $currentYear = $carYears->year_end;
                while ($currentYear >= $carYears->year_begin) {
                    $filteredItems[] = (object)['name' => $currentYear, 'id' => $currentYear];
                    $currentYear--;
                }
            } else if (isset($carYears->year_begin)) {
                $currentYear = date('Y');
                while ($currentYear >= $carYears->year_begin) {
                    $filteredItems[] = (object)['name' => $currentYear, 'id' => $currentYear];
                    $currentYear--;
                }
            } else {
                $filteredItems[] = (object)['name' => 'Год Не Указан', 'id' => 0];
            }

            return $filteredItems;
        } else {
            return (object)['name' => 'Год Не Указан', 'id' => 0];
        }
    }

    public function toggleFavorite(Request $request, Application $application)
    {
        $application->favorite = !$application->favorite;
        $application->save();

        return ($application->favorite === true)
            ? response()->json([
                'favorite' => $application->favorite,
                'message' => __('Added to favorite'),
                'class' => 'select-favorite',
                'remove_class' => ''
            ])
            : response()->json([
                'favorite' => $application->favorite,
                'message' => __('Removed from favorite'),
                'class' => '',
                'remove_class' => 'select-favorite'
            ]);
    }

    /**
     * Display a listing of the duplicate resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicate(Request $request, ApplicationFilters $filters)
    {
        $duplicateIDs = null;
        $groupBy = $request->get('group-by', 'vin');
        if ($groupBy === 'license_plate') {
            $groupBy = 'license_plate';
            $duplicateIDs = DB::select(DB::raw(
                "SELECT GROUP_CONCAT(ids) as tmp FROM (
                    SELECT count(`id`) as cnt, GROUP_CONCAT(`id`) as ids
                    FROM `applications`
                    WHERE license_plate IS NOT NULL AND license_plate NOT LIKE '%Нет учета%'
                    group by license_plate
                    having cnt > 1 )
                T1"))[0];
        } else if ($groupBy === 'vin') {
            $duplicateIDs = DB::select(DB::raw(
                "SELECT GROUP_CONCAT(ids) as tmp FROM (
                    SELECT count(`id`) as cnt, GROUP_CONCAT(`id`) as ids, GROUP_CONCAT(`returned`) as returned_values
                    FROM `applications`
                    WHERE vin IS NOT NULL AND vin <> ''
                    group by vin having cnt > 1 )
                as T1
                where T1.returned_values like '%0%'"))[0];
        }

        if (isset($duplicateIDs->tmp)) {
            $applicationQuery = Application::
            applications()
                ->filter($filters)
                ->whereIn('id', explode(',', $duplicateIDs->tmp))
                ->with('parking')
                ->with('issuedBy')
                ->with('acceptedBy')
                ->with('status')
                ->with('attachments')
                ->with('carType')
                ->with('partner')
                ->with('issueAcceptions')
                ->with('acceptions')
                ->with('issuance')
                ->with('viewRequests')
                ->orderBy('arrived_at', 'desc');

            $applications = $applicationQuery->paginate(config('app.paginate_by', '25'))->withQueryString();
            foreach ($applications as $key => $item) {
                $pricing = Pricing::where([
                    ['partner_id', $item->partner_id],
                    ['car_type_id', $item->car_type_id]
                ])
                    ->select('discount_price', 'regular_price', 'free_days')
                    ->first();

                $applications[$key]['pricing'] = $pricing;
                $item->currentParkingCost = $item->currentParkingCost;
            }
        }

        $title = __('Duplicate');
        if ($request->get('direction') == 'row') {
            return view('applications.index_status', compact('title', 'applications'));
        } else {
            return view('applications.index', compact('title', 'applications'));
        }

    }

    /**
     * Display a listing of the duplicate resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDuplicatesByID(Request $request, Application $application)
    {
        $duplicateIDs = null;
        $vinDuplicates = DB::table('applications')
            ->selectRaw('GROUP_CONCAT(id) as ids')
            ->where('vin', $application->vin)
            ->groupBy('vin');

        $duplicates = DB::table('applications')
            ->selectRaw('GROUP_CONCAT(id) as ids')
            ->where('license_plate', $application->license_plate)
            ->groupBy('license_plate')
            ->union($vinDuplicates)
            ->get()->toArray();

        $ids = isset($duplicates[0]->ids) ? explode(',', $duplicates[0]->ids) : [];
        $ids = isset($duplicates[1]->ids) ? array_unique(array_merge($ids, explode(',', $duplicates[1]->ids))) : $ids;

        //print_r($ids);
        $items = $application->getDuplicates()
            ->with('parking')
            ->with('partner')
            ->with('status')
            ->with('attachments')
            ->get();

        return response()->json([
            'items' => $items,
        ]);

    }

    /**
     * Display a listing of the duplicate resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function mergeDuplicate(Request $request, Application $application)
    {
        $applicationData = $request->application;
        foreach ($applicationData as $key => $value) {
            if ($value === '' || $value === null || $value === 'null') {
                unset($applicationData[$key]);
            }
        }
        $application->update($applicationData);
        $ids = $application->getDuplicates()->pluck('id')->toArray();

        if (($key = array_search($application->id, $ids)) !== false) {
            unset($ids[$key]);
        }

        Attachment::whereIn('attachable_id', $ids)
            ->where('attachable_type', 'App\Application')
            ->update(['attachable_id' => $application->id]);

        Application::destroy($ids);

        return response()->json([
            ['message' => __('Applications merged'), 'class' => 'is-success']
        ]);

    }

    protected function applicationUpdateData($application)
    {
        $carMarks = null;
        $carModels = null;
        $carYears = null;
        $carGenerations = null;
        $carSeriess = null;
        $carModifications = null;
        $carEngines = null;
        $carTransmissions = null;
        $carGears = null;

        if ($application->car_type_id) {
            $carMarks = $this->getCarMarkList($application->car_type_id);
        }
        if ($application->car_mark_id) {
            $carModels = $this->getCarModelList($application->car_mark_id);
        }
        if ($application->car_model_id) {
            $carYears = $this->filteredItems($this->getCarYearList($application->car_model_id));
        }
        if ($application->year) {
            $carGenerations = $this->getCarGenerationList($application->car_model_id, $application->year);
        }
        if ($application->car_generation_id) {
            $carSeriess = $this->getCarSeriesList($application->car_model_id, $application->car_generation_id);

        }
        if ($application->car_series_id) {
            $carModifications = $this->getCarModificationList($application->car_model_id, $application->car_series_id, $application->year);
        }
        if ($application->car_modification_id) {
            $carEngines = $this->getCarEngineList($application->car_modification_id);
        }
        if ($application->car_engine_id) {
            $carTransmissions = $this->getCarTransmissionList($application->car_modification_id);
        }
        if ($application->car_gear_id) {
            $carGears = $this->getCarGearList($application->car_modification_id);
        }

        $attachments = $application->attachments()->select('id', 'thumbnail_url', 'url')->get();
        $dataApplication = [
            'modelId' => $application->car_model_id,
            'car_mark_id' => $application->car_mark_id,
            'modificationId' => $application->car_modification_id,
            'year' => $application->year
        ];

        return compact(
            'application',
            'attachments',
            'dataApplication',
            'carMarks',
            'carModels',
            'carYears',
            'carGenerations',
            'carSeriess',
            'carModifications',
            'carEngines',
            'carTransmissions',
            'carGears'
        );
    }

    public function assignStatus(Request $request): bool
    {
        if (auth()->user()->hasRole(['SuperAdmin', 'Admin'])) {
            $app = Application::find($request->appid);
            if ($app) {
                $app->status_id = $request->statusid;
                $app->save();
            }
        }
        return true;
    }

    public function updateSystemData(Request $request)
    {

        if (auth()->user()->hasRole(['SuperAdmin', 'Admin', 'Manager'])) {

            $app = Application::find($request->appid);
            if ($app) {
                $app->accepted_by = $request->acceptedId;
                $app->issued_by = $request->issuedId;
                $app->parking_id = $request->parkingId;
                $app->partner_id = $request->partnerId;
                $app->arrived_at = ($request->arriving_at_modal) ? Carbon::parse($request->arriving_at_modal)->format('Y-m-d H:i:s') : null;
                $app->issued_at = ($request->issued_at_modal) ? Carbon::parse($request->issued_at_modal)->format('Y-m-d H:i:s') : null;
                $app->vin = $request->vin;
                $app->license_plate = $request->plate;
                $app->returned = $request->has('repeat') ? filter_var($request->repeat, FILTER_VALIDATE_BOOLEAN) : $app->returned;
                $app->save();

            }
        }


        return $app;
    }
}
