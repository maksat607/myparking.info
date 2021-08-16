<?php

namespace App\Http\Controllers;

use App\Enums\Color;
use App\Interfaces\ExportInterface;
use App\Models\Application;
use App\Models\ApplicationData;
use App\Models\CarType;
use App\Models\Parking;
use App\Models\Partner;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends AppController
{
    protected $AttachmentController;
    private $exporter;
    public function __construct(AttachmentController $AttachmentController)
    {
        $this->AttachmentController = $AttachmentController;
//        $this->exporter = $exporter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status_id = null)
    {
        $statuses = Status::where('is_active', true)->pluck('id')->toArray();
        $status_name = ($status_id) ? Status::findOrFail($status_id)->name : 'Все';

        $applications = Application::
            when($status_id, function($query, $status_id) {
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
            ->paginate(16);


        $title = __($status_name);
        return view('applications.index', compact('title', 'applications', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $carList = CarType::where('is_active', 1)
            ->select('id','name')
            ->orderBy('rank', 'desc')->orderBy('name', 'ASC')
            ->get();
        $partners = Partner::all();
        $parkings = Parking::parkings()->get();
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
            'vin_array.*' => [
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

        $applicationData['condition_gear'] = json_encode($applicationData['condition_gear']);
        $applicationData['condition_engine'] = json_encode($applicationData['condition_engine']);
        $applicationData['condition_electric'] = json_encode($applicationData['condition_electric']);
        $applicationData['condition_transmission'] = json_encode($applicationData['condition_transmission']);
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
                'is_issue' => false
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function acceptions($application_id)
    {
        $application = Application::find($application_id);
        $status = Status::find(2);

        if($application->exists) {
            $application->arrived_at = Carbon::now()->format('Y-m-d H:i:s');
            $application->status()->associate($status);
            $application->acceptions()->delete();

            if($application->save()) {
                $htmlRender = view('applications.ajax.article', compact('application'))->render();

                return response()->json(['success' => true, 'id'=>$application->id, 'html'=>$htmlRender]);
            }

        }
        return null;
    }

    public function deny($application_id)
    {
        $application = Application::find($application_id);
        $status = Status::find(6);

        if($application->exists) {
            $application->status()->associate($status);
            $application->acceptions()->delete();

            if($application->save()) {
                $htmlRender = view('applications.ajax.article', compact('application'))->render();

                return response()->json(['success' => true, 'id'=>$application->id, 'html'=>$htmlRender]);
            }

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
}
