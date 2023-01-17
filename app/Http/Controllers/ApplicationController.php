<?php

namespace App\Http\Controllers;

use App\Enums\AppUsers;
use App\Events\ApplicationChat;
use App\Events\NewNotification;
use App\Filter\ApplicationFilters;
use App\Helpers\ParseString;
use App\Interfaces\ExportInterface;
use App\Models\Application;
use App\Models\ApplicationHasPending;
use App\Models\Attachment;
use App\Models\CarCharacteristicValue;
use App\Models\CarGeneration;
use App\Models\CarMark;
use App\Models\CarModel;
use App\Models\CarModification;
use App\Models\CarSeries;
use App\Models\Client;
use App\Models\Pricing;
use App\Models\Status;
use App\Notifications\ApplicationNotifications;
use App\Notifications\UserNotification;
use App\Services\ApplicationService;
use App\Services\ApplicationTotalsService;
use App\Services\MakeFormData;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Session;
use Toastr;
use Zip;

class ApplicationController extends AppController
{
    protected $AttachmentController;
    private $exporter;
    private ApplicationService $applicationService;

    public function __construct(ExportInterface $exporter, AttachmentController $AttachmentController)
    {

        $this->AttachmentController = $AttachmentController;
        $this->exporter = $exporter;

        $this->applicationService = new ApplicationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, ApplicationFilters $filters, $status_id = null)
    {
        if (request()->has('uncheckFilters')) {
            return redirect()->to(url()->current());
        }
        $this->authorize('viewAny', Application::class);
        $result = $this->applicationService->index($request, $filters, $status_id);
        switch ($request->get('direction', 'column')) {
            case 'table':
                return view('applications.index_table', $result);
            case 'row':
                return view('applications.index_row', $result);
            default:
                return view('applications.index', $result);
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
        $result = $this->applicationService->userDatasForNewApplication($application);
        if ($application->exists) {
            $updateData = array_merge(
                $this->applicationUpdateData($application),
                $result
            );
            return view('applications.duplicate_create', $updateData);
        } else {
            return view('applications.create', $result);
        }
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

    public function getCarMarkList($type_id)
    {
        $carMarks = [];
        if ($type_id == 1) {
            $carMarks = CarMark::where([
                ['car_marks.is_active', 1],
                ['car_marks.car_type_id', $type_id],
                ['car_generations.year_begin', '>=', 1990],
                ['car_generations.year_end', '<=', 2022],
            ])
                ->leftJoin('car_models', 'car_marks.id', '=', 'car_models.car_mark_id')
                ->leftJoin('car_generations', 'car_models.id', '=', 'car_generations.car_model_id')
                ->select('car_marks.id as id', 'car_marks.name as name')
                ->groupBy('car_marks.id', 'car_marks.name')
                ->orderBy('car_marks.rank', 'asc')->orderBy('car_marks.name', 'ASC')
                ->get();
//        } else if ($type_id == 2 || $type_id == 6 || $type_id == 7 || $type_id == 8) {
        } elseif ($type_id != 27) {
            $carMarks = CarMark::where([
                ['car_marks.is_active', 1],
                ['car_marks.car_type_id', $type_id],
            ])
                ->select('car_marks.id as id', 'car_marks.name as name')
                ->groupBy('car_marks.id', 'car_marks.name')
                ->orderBy('car_marks.rank', 'asc')->orderBy('car_marks.name', 'ASC')
                ->get();
        }

        if (count($carMarks) > 0 && $type_id != 27) {
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
            } elseif (isset($carYears->year_begin)) {
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
            ])
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
//        $request->dump();
//        $translation = new MakeFormData();
//        $translation->applicationNestedArray($request->except('_token'));

        $this->authorize('create', Application::class);
        $application = $this->applicationService->store($request, $this->AttachmentController);
        if ($application) {
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
    public function edit(Application $application)
    {
        $this->authorize('update', $application);
        $result = $this->applicationService->edit($application);
        return view('applications.edit', $result);
    }

    public function addAttachmentsFromPopup(Request $request, $id)
    {

//         return $request->all();

        $application = Application::application($id)->firstOrFail();
//        $this->authorize('update', $application);

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
    public function update(Request $request, Application $application)
    {

//        return response()->json($request->all());
        $this->authorize('update', $application);
        list($application, $isUpdate) = $this->applicationService->update($request, $application);

        if ($isUpdate) {
            Toastr::success(__('Updated.'));
            return redirect()->route('applications.index', ['status_id' => $application->status->id]);
        }

        Toastr::error(__('Error'));
        return redirect()->back()->with('error', __('Error'));
    }

    /**
     * @param array $applicationData
     */
    public function getCarTitle(array $applicationData)
    {
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

        $response = Http::post(config('app.carapi') . '/title', $searchFilters);

        $carTitleData = json_decode($response->body());


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
        return $applicationData['car_title'];
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
                $application->update(['deleted_by' => auth()->user()->id, 'car_additional' => $application->car_additional . '<br>' . 'Причина удаления:' . '<br>' . $request->car_additional]);
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
                $application->update(['rejected_by' => auth()->user()->id, 'car_additional' => $application->car_additional . '<br>' . 'Причина отклонения:' . '<br>' . $request->car_additional]);
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
            if ($application->issuance()) {
                $application->issuance()->delete();
            }
            Toastr::success(__('Issued.'));
            return redirect()->route('applications.index');
        } else {
            Toastr::error(__('Error'));
            return redirect()->back();
        }
    }

    public function getModelChatContent(Request $request, $application_id)
    {
        if ($request->has('notification') && $application = Application::find($application_id)) {
            $notification = $application->notifications()->find($request->notification);
            if ($notification) {
                $notification->markAsRead();
            }
        }
        $htmlRender = view('notifications.modalchat', $this->applicationService->renderModal($request, $application_id))->render();
        if ($htmlRender == null) {
            return null;
        }
        return response()->json(['success' => true, 'html' => $htmlRender]);
    }




    public function getModelContent(Request $request, $application_id)
    {
        $htmlRender = view('applications.ajax.modal', $this->applicationService->renderModal($request, $application_id))->render();
        if ($htmlRender == null) {
            return null;
        }
        return response()->json(['success' => true, 'html' => $htmlRender]);
    }

    public function checkDuplicate(Request $request)
    {
        list($licensePlateDuplicates, $vinDuplicates) = $this->applicationService->checkApplicationDuplicate($request);

        return response()->json([
            'license_plate' => $licensePlateDuplicates,
            'vin' => $vinDuplicates,
        ]);
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

        $totals = ApplicationTotalsService::totals(Status::activeStatuses(), $filters);
        $duplicateIDs = [];
        $groupBy = $request->get('group-by', 'vin');
        if ($groupBy === 'license_plate') {
            $groupBy = 'license_plate';


            $t = collect(DB::select(DB::raw(
                "

                    SELECT count(`id`) as cnt, GROUP_CONCAT(`id`) as ids
                    FROM `applications`
                    WHERE license_plate IS NOT NULL AND license_plate NOT LIKE '%Нет учета%'
                    group by license_plate
                    having cnt > 1

                "
            )))
                ->map(function ($item) use (&$duplicateIDs) {
                    $duplicateIDs = array_merge($duplicateIDs, explode(',', $item->ids));
                });
        } elseif ($groupBy === 'vin') {
            collect(DB::select(DB::raw(
                "
                    SELECT count(`id`) as cnt, GROUP_CONCAT(`id`) as ids
                    FROM `applications`
                    WHERE vin IS NOT NULL AND vin <> ''
                    group by vin having cnt > 1"
            )))
                ->map(function ($item) use (&$duplicateIDs) {
                    $duplicateIDs = array_merge($duplicateIDs, explode(',', $item->ids));
                });
        }
        $dups = [];
        Application::
        applications()
            ->filter($filters)
            ->whereIn('id', $duplicateIDs)
            ->orderBy($groupBy)
            ->where('status_id', '!=', 8)
            ->get()
            ->map(function ($item) use (&$dups, $groupBy) {
                $dups[mb_strtoupper($item[$groupBy])][] = [
                    'id' => $item->id,
                    'vin' => $item->vin,
                    'arrived_at' => $item->arrived_at,
                    'issued_at' => $item->issued_at,
                    'status_id' => $item->status_id,
                    'license_plate' => $item->license_plate,
                    'returned' => $item->returned
                ];
            });
//        dump($dups);

        $duplicatedApps = collect($dups)->map(function ($item) {
            return collect($item)->countBy(function ($app) {
                if ($app['returned']) {
                    return 'returned';
                }
                return 'not_returned';
            });
        })
            ->reject(function ($item) {
                return ($item['not_returned'] ?? 0) < 2;
            })
            ->keys();
        $colors = [];

//        foreach ($duplicatedApps as $duplicatedApp) {
//            $applicationQuery = Application::where($groupBy, $duplicatedApp)
//                ->orderBy('arrived_at', 'desc')
//                ->first();
//            $applicationQuery->returned = 1;
//            $applicationQuery->save();
//        }

        $applicationQuery = Application::
        applications()
            ->filter($filters)
            ->whereIn($groupBy, $duplicatedApps)
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
            ->orderBy($groupBy, 'desc');
        $applications = $applicationQuery
            ->paginate(24)
            ->withQueryString()
            ->tap(function ($items) use ($groupBy, &$colors) {
                $items->map(function ($item) use ($groupBy, &$colors) {
                    $colors [$item->$groupBy] = '#' . bin2hex(openssl_random_pseudo_bytes(3));
                });
            });

        $title = __('Duplicate');
        if ($request->get('direction') == 'row') {
            return view('applications.index_status', compact('title', 'applications', 'totals'));
        } else {
            return view('applications.index', compact('title', 'applications', 'totals', 'colors'));
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

    public function approved(Request $request)
    {
        $application = Application::findOrFail($request->appId);
        $application->status_id = 2;
        if ($request->has('notChangeDate')) {
            $date = date('Y-m-d H:i:s', strtotime($application->arriving_at));
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date)->startOfDay();
            $application->arrived_at = $date;
        } else {
            $application->arrived_at = now()->format('Y-m-d H:i:s');
        }
        $application->save();
        ApplicationHasPending::where('application_id', $request->appId)->delete();
        Toastr::success(__('Updated.'));
        return redirect()->route('applications.index', ['status_id' => $application->status->id]);
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

        $app = null;
        if (auth()->user()->hasRole(['SuperAdmin', 'Admin', 'Manager', 'Moderator'])) {
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
                $app->free_parking = $request->has('free_parking') ? filter_var($request->free_parking, FILTER_VALIDATE_BOOLEAN) : $app->free_parking;
                $app->save();
            }
        }


        return $app;
    }

    public function sendChatMessage(Request $request, Application $application)
    {
        $message = [
            'type' => $request->type,
            'user_id' => auth()->id(),
            'role' => auth()->user()->getRole(),
            'message' => $request->message
        ];

        $application->notify(new ApplicationNotifications($message));

        $notifications = $application->notifications->filter(function ($item) use ($request) {
            return $item->data['type'] == $request->type;
        });
        $userapp = new AppUsers($application);
        $users = $request->type == 'partner' ? $userapp->allUsers() : $userapp->storageUsers();
        event(new ApplicationChat(
            array_merge(
                [
                    'date' => now()->format('d.m.Y H:i'),
                    'app_id' => $application->id,
                    'count' => $notifications->count(),
                ],
                $message
            )
        ));
        $userMessage =
            [
                'short' => auth()->user()->getRole() . ' ' . auth()->user()->email . ' написал сообщение' . " Авто {$application->car_title} (VIN {$application->vin})",
                'long' => now()->format('d.m.Y H:i') . "... Авто {$application->car_title} (VIN {$application->vin}). Написал " . auth()->user()->getRole() . " " . auth()->user()->email,
                'id' => $application->id,
                'user_id' => auth()->id(),
                'chat' => true,
            ];


        $users = collect($users)->reject(function ($item) {
            return $item->id == auth()->id();
        });
        \Illuminate\Support\Facades\Notification::send($users, new UserNotification($userMessage));

        event(new NewNotification(array_merge(['users' => collect($users)->pluck('id')], $userMessage)));

        if ($request->has('moderator')) {
            return redirect()->back()->with('success', 'Отправлено');
        }

        $htmlRender = view('components.' . $request->type . '-messages', [$request->type . 'Notifications' => $notifications])->render();
        return response()->json(['success' => true, 'html' => $htmlRender]);
    }

    public function download_zipped_photos(Application $application)
    {
        $file = new Filesystem();
        $file->cleanDirectory(public_path("downloads"));
        $zip_file = "downloads/{$application->car_title}.zip";
        $zip = new \ZipArchive();
        $zip->open(public_path($zip_file), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($application->attachments as $file) {
            $zip->addFile(public_path("uploads/{$file->name}"), $file->name);
        }
        $hasFile = $zip->numFiles;
        $zip->close();

        return $hasFile > 0 ?
            response()->download(($zip_file)) :
            redirect()->back()->with('warning', 'Фотографии нету:(');
    }
}
