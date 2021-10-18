<?php

namespace App\Http\Controllers;

use App\Enums\Color;
use App\Filter\ApplicationFilters;
use App\Interfaces\ExportInterface;
use App\Models\Application;
use App\Models\ApplicationData;
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
    public function __construct(AttachmentController $AttachmentController)
    {
        $this->AttachmentController = $AttachmentController;
//        $this->exporter = $exporter;

        $this->middleware(['permission:application_view'])->only('index', 'show');
        $this->middleware(['permission:application_create'])->only('create', 'store');
        $this->middleware(['permission:application_update'])->only('edit', 'update');
        $this->middleware(['permission:application_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ApplicationFilters $filters, $status_id = null)
    {
        $statuses = Status::where('is_active', true)->pluck('id')->toArray();
        $status_name = ($status_id) ? Status::findOrFail($status_id)->name : 'Все';

        $applications = Application::
            applications()
            ->filter($filters)
            ->when($status_id, function($query, $status_id) {
                return $query->where('status_id', $status_id);
            })
            ->when(!$status_id, function($query) use ($statuses){
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
            ->orderBy('id', 'desc')
            ->get();

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
        if($request->get('direction') == 'row') {
            return view('applications.index_status', compact('title', 'applications'));
        } else {
            return view('applications.index', compact('title', 'applications'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $carList = CarType::where('is_active', 1)
            ->select('id','name')
            ->orderBy('rank', 'desc')->orderBy('name', 'ASC')
            ->get();
        if(auth()->user()->partner) {
            $partners = auth()->user()->partner()->get();
            $parkings = auth()->user()->partnerParkings;
        } else {
            $partners = Partner::all();
            $parkings = Parking::parkings()->get();
        }
        $colors = Color::getColors();


        $title = __('Create a Request');
        return view('applications.create', compact(
            'title',
                 'carList',
                    'partners',
                    'parkings',
                    'colors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $carRequest = $request->car_data;
        $applicationRequest = $request->app_data;

        Validator::make($carRequest, [
            'vin_array' => [
                    'required_without:license_plate',
                    'unique:applications,vin'
                ],
            'license_plate' => ['unique:applications,license_plate'],
            'car_type_id' => ['integer', 'required'],
            'car_mark_id' => ['integer', 'required'],
            'car_model_id' => ['integer', 'required'],
            'year' => ['integer', 'required'],
            'car_key_quantity' => ['integer', 'required', 'max:4', 'min:0'],
        ])->validate();

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
        $applicationData['vin'] = $applicationData['vin_array'];
        unset($applicationData['car_series_body']);


        foreach ($applicationData as $key => $value) {
            if ( $value === '' || $value === null || $value === 'null') {
                unset($applicationData[$key]);
            }
        }
        if (isset($applicationData['status_id'])) {
            $applicationData['status_id'] = 1;
        } else {
            $applicationData['status_id'] = 7;
        }

        if (isset($applicationData['car_type_id']) && in_array($applicationData['car_type_id'], [1,2,6,7,8]) ) {
            $searchFilters = [];
            if (isset($applicationData['car_mark_id']) && is_numeric($applicationData['car_mark_id']) && $applicationData['car_mark_id'] > 0 ) {
                $searchFilters[] = ['car_marks.id', $applicationData['car_mark_id']];
            }
            if (isset($applicationData['car_model_id']) && is_numeric($applicationData['car_model_id']) && $applicationData['car_model_id'] > 0 ) {
                $searchFilters[] = ['car_models.id', $applicationData['car_model_id']];
            }
            if (isset($applicationData['car_generation_id']) && is_numeric($applicationData['car_generation_id']) && $applicationData['car_generation_id'] > 0 ) {
                $searchFilters[] = ['car_generations.id', $applicationData['car_generation_id']];
            }
            $carTitleData = DB::table('car_types')
                ->select('car_types.name as car_type', 'car_marks.name as car_mark', 'car_models.name as car_model', 'car_generations.name as car_generation')
                ->leftJoin('car_marks',  'car_types.id', '=', 'car_marks.car_type_id')
                ->leftJoin('car_models', 'car_marks.id', '=', 'car_models.car_mark_id')
                ->leftJoin('car_generations', 'car_models.id', '=', 'car_generations.car_model_id')
                ->where( $searchFilters )
                ->first();

            $applicationData['car_title'] = '';
            if (isset($applicationData['car_mark_id']) && is_numeric($applicationData['car_mark_id']) ) {
                $applicationData['car_title'] .= "{$carTitleData->car_mark}";
            }
            if (isset($applicationData['car_model_id']) && is_numeric($applicationData['car_model_id']) ) {
                $applicationData['car_title'] .= " {$carTitleData->car_model}";
            }
            if (isset($applicationData['car_generation_id']) && is_numeric($applicationData['car_generation_id']) ) {
                $applicationData['car_title'] .= " {$carTitleData->car_generation}";
            }
            if (isset($applicationData['year']) ) {
                $applicationData['car_title'] .= " {$applicationData['year']}";
            }
        }

        $application = auth()->user()->applications()->create($applicationData);
        if ($applicationData['status_id'] != 1) {
            $application->issueAcceptions()->create([
                'is_issue' => false,
                'user_id' => auth()->id()
            ]);
        }

//        event(new ApplicationUpdated(Application::find($application['id']), $applicationData));

        $attachments = $this->AttachmentController->storeToModel($request,'images');

        if (count($attachments) > 0) {
            $application->attachments()->saveMany($attachments);
        }

        return ($application->exists)
            ? redirect()->route('applications.create')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $carTypes = CarType::where('is_active', 1)
            ->select('id','name')
            ->orderBy('rank', 'desc')->orderBy('name', 'ASC')
            ->get();
        $partners = Partner::all();
        $parkings = Parking::parkings()->get();
        $colors = Color::getColors();

        $application = Application::application($id)->firstOrFail();

        $carMarks = null;
        $carModels = null;
        $carYears = null;
        $carGenerations = null;
        $carSeriess = null;
        $carModifications = null;
        $carEngines = null;
        $carTransmissions = null;
        $carGears = null;
        if($application->car_type_id){
            $carMarks = $this->getCarMarkList($application->car_type_id);
        }
        if($application->car_mark_id){
            $carModels = $this->getCarModelList($application->car_mark_id);
        }
        if($application->car_model_id){
            $carYears = $this->filteredItems($this->getCarYearList($application->car_model_id));
        }
        if($application->year){
            $carGenerations = $this->getCarGenerationList($application->car_model_id, $application->year);
        }
        if($application->car_generation_id){
            $carSeriess = $this->getCarSeriesList($application->car_model_id, $application->car_generation_id);

        }
        if($application->car_series_id){
            $carModifications = $this->getCarModificationList($application->car_model_id, $application->car_series_id, $application->year);
        }
        if($application->car_modification_id){
            $carEngines = $this->getCarEngineList($application->car_modification_id);
        }
        if($application->car_engine_id){
            $carTransmissions = $this->getCarTransmissionList($application->car_modification_id);
        }
        if($application->car_gear_id){
            $carGears = $this->getCarGearList($application->car_modification_id);
        }

        $attachments = $application->attachments()->select('id', 'url as src')->get()->toArray();
        $dataApplication = [
            'modelId' => $application->car_model_id,
            'car_mark_id' => $application->car_mark_id,
            'modificationId' => $application->car_modification_id,
            'year' => $application->year
        ];

//        $exterior_damage = $application->exterior_damage;
//        $interior_damage = $application->interior_damage;
//        $condition_engine = $application->condition_engine;
//        $condition_electric = $application->condition_electric;
//        $condition_gear = $application->condition_gear;
//        $condition_transmission = $application->condition_transmission;

        $title = __('Update a Request');
        return view('applications.edit', compact(
            'title',
            'partners',
            'parkings',
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
        ));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carRequest = $request->car_data;
        $applicationRequest = $request->app_data;

        $application = Application::application($id)->firstOrFail();

        Validator::make($carRequest, [
            'vin_array' => [
                'required_without:license_plate',
                Rule::unique('applications', 'vin')->ignore($application->id)
            ],
            'license_plate' => [Rule::unique('applications', 'license_plate')->ignore($application->id)],
            'car_type_id' => ['integer', 'required'],
            'car_mark_id' => ['integer', 'required'],
            'car_model_id' => ['integer', 'required'],
            'year' => ['integer', 'required'],
            'car_key_quantity' => ['integer', 'required', 'max:4', 'min:0'],
            'preloaded.*' => ['nullable', 'sometimes', 'exists:attachments,id']
        ])->validate();

        Validator::make($applicationRequest, [
            'external_id' => ['required'],
            'partner_id' => ['integer', 'required', 'exists:partners,id'],
            'parking_id' => ['integer', 'required', 'exists:parkings,id'],
        ])->validate();

        $applicationDataArray = get_object_vars(new ApplicationData());
        $applicationData = array_merge($applicationDataArray, $applicationRequest, $carRequest);

        $applicationData['vin'] = $applicationData['vin_array'];
        unset($applicationData['car_series_body']);


        foreach ($applicationData as $key => $value) {
            if ( $value === '' || $value === null || $value === 'null') {
                unset($applicationData[$key]);
            }
        }

        if (isset($applicationData['car_type_id']) && in_array($applicationData['car_type_id'], [1,2,6,7,8]) ) {
            $searchFilters = [];
            if (isset($applicationData['car_mark_id']) && is_numeric($applicationData['car_mark_id']) && $applicationData['car_mark_id'] > 0 ) {
                $searchFilters[] = ['car_marks.id', $applicationData['car_mark_id']];
            }
            if (isset($applicationData['car_model_id']) && is_numeric($applicationData['car_model_id']) && $applicationData['car_model_id'] > 0 ) {
                $searchFilters[] = ['car_models.id', $applicationData['car_model_id']];
            }
            if (isset($applicationData['car_generation_id']) && is_numeric($applicationData['car_generation_id']) && $applicationData['car_generation_id'] > 0 ) {
                $searchFilters[] = ['car_generations.id', $applicationData['car_generation_id']];
            }
            $carTitleData = DB::table('car_types')
                ->select('car_types.name as car_type', 'car_marks.name as car_mark', 'car_models.name as car_model', 'car_generations.name as car_generation')
                ->leftJoin('car_marks',  'car_types.id', '=', 'car_marks.car_type_id')
                ->leftJoin('car_models', 'car_marks.id', '=', 'car_models.car_mark_id')
                ->leftJoin('car_generations', 'car_models.id', '=', 'car_generations.car_model_id')
                ->where( $searchFilters )
                ->first();

            $applicationData['car_title'] = '';
            if (isset($applicationData['car_mark_id']) && is_numeric($applicationData['car_mark_id']) ) {
                $applicationData['car_title'] .= "{$carTitleData->car_mark}";
            }
            if (isset($applicationData['car_model_id']) && is_numeric($applicationData['car_model_id']) ) {
                $applicationData['car_title'] .= " {$carTitleData->car_model}";
            }
            if (isset($applicationData['car_generation_id']) && is_numeric($applicationData['car_generation_id']) ) {
                $applicationData['car_title'] .= " {$carTitleData->car_generation}";
            }
            if (isset($applicationData['year']) ) {
                $applicationData['car_title'] .= " {$applicationData['year']}";
            }
        }

        if (isset($applicationData['accept'])) {
            $applicationData['status_id'] = 2;
            $applicationData['arrived_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $application->acceptions()->delete();
        }

        $isUpdate = $application->update($applicationData);
      /*  if ($applicationData['status_id'] != 1) {
            $application->issueAcceptions()->create([
                'is_issue' => false
            ]);
        }*/

//        event(new ApplicationUpdated(Application::find($application['id']), $applicationData));

        if($request->has('preloaded')) {
            $attachmentsDelete = $application->attachments()->whereNotIn('id', $request->preloaded)->get();
            $attachmentsDelete->each(function ($item, $key) {
                $this->AttachmentController->delete($item);
            });

        }

        $attachments = $this->AttachmentController->storeToModel($request,'images');

        if (count($attachments) > 0) {
            $application->attachments()->saveMany($attachments);
        }

        return ($isUpdate)
            ? redirect()->route('applications.edit', ['application' => $application->id])->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $application = Application::application($id)->firstOrFail();
//        $application->client()->delete();
//        $application->viewRequests()->delete();
        $application->attachments->each(function ($item, $key) {
            $this->AttachmentController->delete($item);
        });
        $result = Application::destroy($application->id);
        return ( $result )
            ? redirect()->route('applications.index', ['application' => $application->id])->with('success', __('Deleted.'))
            : redirect()->back()->with('error', __('Error'));
    }

    public function deny($application_id)
    {
        $application = Application::application($application_id)->firstOrFail();
        $status = Status::find(6);

        if($application->exists) {
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

        if($application->issuance) {
            $client = $application->issuance->client;
        }

        $documentOptions = Client::issuanceDocumentOptions();
        $individualLegalOptions = Client::issuanceIndividualLegalOptions();
        $preferredContactMethodOptions = Client::issuancePreferredContactMethodOptions();

        $title = __('Application for issuance');
        return view('applications.issuance', compact(
            'title',
                    'application',
                    'client',
                    'documentOptions',
                    'individualLegalOptions',
                    'preferredContactMethodOptions'
        ));
    }

    public function issuance(Request $request, $application_id)
    {
        $clientData = $request->client;

        Validator::make($clientData, [
            'issuance_document' => ['string', 'nullable'],
            'lastname' => ['string', 'nullable'],
            'firstname' => ['string', 'nullable'],
            'middlename' => ['string', 'nullable'],
            'phone' => ['numeric', 'nullable'],
            'email' => ['email', 'nullable'],
        ])->validate();


        foreach ($clientData as $key => $value) {
            if ( is_null($value) || $value == 'null') {
                unset($clientData[$key]);
            }
        }

        $application = Application::application($application_id)->firstOrFail();

        if(!$application->issuance) {
            $client = Client::create($clientData);
        } else {
            $client = $application->issuance->client;
        }

        if($client->exists) {
            $application->update([
                'status_id' => 3,
                'client_id' => $client->id,
                'issued_at' => Date::now()
            ]);
            $application->issuance()->delete();
            return redirect()->route('applications.index')->with('success', __('Saved.'));
        } else {
            return redirect()->back()->with('error', __('Error'));
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

        if(!empty($application)) {

            $pricing = Pricing::where([
                ['partner_id', $application->partner_id],
                ['car_type_id', $application->car_type_id]
            ])
                ->select('discount_price', 'regular_price', 'free_days')
                ->first();

            $application['pricing'] = $pricing;
            $application->currentParkingCost = $application->currentParkingCost;

            $htmlRender = view('applications.ajax.modal', compact('application'))->render();
            return response()->json(['success' => true, 'html'=>$htmlRender]);

        }
        return null;
    }

    public function checkDuplicate(Request $request)
    {
        $licensePlateDuplicates = ( isset($request->license_plate) && strlen($request->license_plate) >= 3)
            ? Application::select('applications.id as id', 'car_title', 'vin', 'license_plate', 'statuses.code as status_code')
                ->join('statuses', 'statuses.id', '=', 'applications.status_id')
                ->where([
                    ['license_plate', 'like', '%' . $request->license_plate . '%'],
                    ['applications.id', '<>', $request->id],
                    ['applications.user_id', '=', auth()->id()]
                ])
                ->get()->toArray()
            : [];
        $vinDuplicates = [];
        if (is_array($request->vin) && count($request->vin) > 0 ) {
            $searchVin = false;
            $vinArray = $request->vin;
            foreach ($vinArray as $key => $singleVin) {
                if (empty($singleVin)) {
                    unset($vinArray[$key]);
                }
                else if (strlen($singleVin) > 2){
                    $searchVin = true;
                }
            }
            if ($searchVin) {
                $vinQuery = Application::select('applications.id as id', 'car_title', 'vin', 'license_plate', 'statuses.code as status_code')
                    ->join('statuses', 'statuses.id', '=', 'applications.status_id')
                    ->where('applications.id', '<>', $request->id)
                    ->where('applications.user_id', '=', auth()->id())
                    ->where(function($query) use( $vinArray ) {
                        foreach ($vinArray as $singleVin) {
                            $query->orWhere('vin', 'like', '%' .$singleVin . '%');
                        }
                    });
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
        }
        else if ($type_id == 2 || $type_id == 6 || $type_id == 7 || $type_id == 8 ) {
            $carMarks = CarMark::where([
                ['car_marks.is_active', 1],
                ['car_marks.car_type_id', $type_id],
            ])
                ->select('car_marks.id as id', 'car_marks.name as name')
                ->groupBy('car_marks.id', 'car_marks.name')
                ->orderBy('car_marks.rank', 'asc')->orderBy('car_marks.name', 'ASC')
                ->get();
        }

        if (count($carMarks) > 0) {
            $carMarks = CarMark::setLogo($carMarks);
            return $carMarks;
        }
    }

    public function getCarModelList($mark_id)
    {
        if (isset($mark_id) && is_numeric($mark_id)) {

            $carModels = CarModel::where(['car_mark_id' => $mark_id])
                ->select('id','name')
                ->orderBy('rank', 'asc')->orderBy('name', 'ASC')->get();

            if (count($carModels) > 0) {
                return $carModels ;
            }
            else {
                return ['id' => 0,'name' => __('Unknown Model')];
            }
        }
    }

    public function getCarYearList($model_id) {
        if (isset($model_id) && is_numeric($model_id)) {

            $carYears = CarGeneration::where(['car_model_id' => $model_id])
                ->select(DB::raw("min(year_begin) as year_begin,max(year_end) as year_end") )
                ->groupBy('car_model_id')
                ->first();

            return isset($carYears->year_begin) ? $carYears : (object)['year_begin' => 1950, 'year_end' => Carbon::now()->year];
        }

    }

    public function getCarGenerationList($model_id, $year) {

        if (isset($model_id) && is_numeric($model_id)) {

            $searchFilter = [['car_model_id', $model_id]];
            if (isset($year) && is_numeric($year) && $year > 0) {
                $searchFilter[] = ['year_begin', '<=', $year];
                $searchFilter[] = ['year_end', '>=', $year];
            }
            $carGenerations = CarGeneration::where($searchFilter)
                ->select('id','name')
                ->get();
            return $carGenerations;
        }
    }

    public function getCarSeriesList($model_id, $generation_id) {
        if (isset($model_id) && is_numeric($model_id)) {
            $searchFilter[] = ['car_model_id', $model_id];
            if (isset($generation_id) && is_numeric($generation_id) && $generation_id > 0) {
                $searchFilter[] = ['car_generation_id', $generation_id];
            }

            $carSeries = CarSeries::where($searchFilter)
                ->select('id','name')
                ->get();
            $returnValues = [];
            foreach ($carSeries as $singleSeries) {
                $returnValues[] = (object)['id'=> $singleSeries->id, 'name'=> $singleSeries->name, 'body'=> $singleSeries->body_name];
            }
            return $returnValues;
        }
    }

    public function getCarModificationList($model_id, $series_id, $year) {
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
                ->where(function($q) use ( $yearFilter) {
                    $q->where($yearFilter)
                        ->orWhereNull('year_begin')
                        ->orWhereNull('year_end');
                })
                ->select('id','name')
                ->get();
            return $carModifications;
        }
    }

    public function getCarEngineList($modification_id) {
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

    public function getCarTransmissionList($modification_id) {
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

    public function getCarGearList($modification_id) {
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

    public function filteredItems($carYears) {
        if (!empty($carYears)) {
            $filteredItems = [];
            if (isset($carYears->year_begin) && isset($carYears->year_end)) {
                $currentYear = $carYears->year_end;
                while ($currentYear >= $carYears->year_begin) {
                    $filteredItems[] = (object)['name' => $currentYear, 'id' => $currentYear];
                    $currentYear--;
                }
            }
            else if (isset($carYears->year_begin)) {
                $currentYear = date('Y');
                while ($currentYear >= $carYears->year_begin) {
                    $filteredItems[] = (object)['name' => $currentYear, 'id' => $currentYear];
                    $currentYear--;
                }
            }
            else {
                $filteredItems[] = (object)['name' => 'Год Не Указан', 'id' => 0];
            }

            return $filteredItems;
        }
        else {
            return (object)['name' => 'Год Не Указан', 'id' => 0];
        }
    }

    public function toggleFavorite(Request $request, Application $application)
    {
        $application->favorite = !$application->favorite;
        $application->save();

        return ( $application->favorite === true )
            ? response()->json([
                'favorite' => $application->favorite,
                'message'=>__('Added to favorite'),
                'class' => 'newcart__save',
                'remove_class' => 'newcart__nosave'
            ])
            : response()->json([
                'favorite' => $application->favorite,
                'message'=>__('Removed from favorite'),
                'class' => 'newcart__nosave',
                'remove_class' => 'newcart__save'
            ]);
    }
}
