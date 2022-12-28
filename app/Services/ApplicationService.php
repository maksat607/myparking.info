<?php

namespace App\Services;

use App\Enums\Color;
use App\Filter\ApplicationFilters;
use App\Http\Controllers\AttachmentController;
use App\Http\Resources\ModelResource;
use App\Models\Application;
use App\Models\ApplicationData;
use App\Models\CarType;
use App\Models\Parking;
use App\Models\Partner;
use App\Models\Pricing;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApplicationService
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, ApplicationFilters $filters, $status_id = null)
    {

        $statuses = Status::where('is_active', true)->pluck('id')->toArray();

        $status = ($status_id) ? Status::findOrFail($status_id) : null;
        $status_name = ($status) ? $status->name : 'Все';
        $status_sort = ($status) ? $status->status_sort : 'arriving_at';
        $totals = ApplicationTotalsService::totals($statuses, $filters, $status_id);
        $r = [];
        $applications = Application::
        applications()
            ->filter($filters)
            ->when(!$status_id, function ($query) use ($statuses) {
                return $query->whereIn('status_id', $statuses);
            })
            ->when($status_id, function ($query, $status_id) {
                return $query->where('status_id', $status_id);
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
//            ->with('viewRequests')
            ->orderBy($status_sort, 'desc')
            ->orderBy('id', 'desc')
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
        return compact('title', 'applications', 'totals');
    }

    /**
     * @param Application $application
     * @return array
     */
    public function userDatasForNewApplication(Application $application): array
    {
        $carTypes = CarType::where('is_active', 1)
            ->select('id', 'name')
            ->orderBy('rank', 'desc')->orderBy('name', 'ASC')
            ->get();

        if (auth()->user()->partner || auth()->user()->hasRole(['Partner']) || auth()->user()->hasRole(['PartnerOperator'])) {
            $partners = auth()->user()->partner()->get();
            $parkings = auth()->user()->partnerParkings();
//            $parkings = Parking::all();
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
        $parkings = ModelResource::collection($parkings);
        $partners = ModelResource::collection($partners);
        $colors = Color::getColors();

        $user = User::where('id', auth()->user()->getUserOwnerId())->first();
        $managers = $user->children()->role('Manager')->orderBy('name', 'asc')->get();
        $statuses = ModelResource::collection(Status::statuses($application)->get()->filterStatusesByRole());


        $title = __('Create a Request');
        return compact(
            'title',
            'carTypes',
            'partners',
            'parkings',
            'managers',
            'statuses',
            'colors',
        );
    }

    public function store(Request $request, $attachmentController)
    {
        $response = Http::get(config('app.carapi') . '/cars?name=Прочее');
        $noTypeCar = json_decode($response->body(), true);
        $required = true;
        $returned = false;
        if (isset($request->car_data['returned'])) {
            $returned = true;
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
        $car_type = null;

        if (isset($request->car_data['car_type_id'])) {
            $car_type = $request->car_data['car_type_id'];
        }

        $this->validateStoreData($carRequest, $required, $returned, $car_type, $noTypeCar, $statuses, $applicationRequest);


        $applicationDataArray = get_object_vars(new ApplicationData());
        $applicationData = array_merge($applicationDataArray, $applicationRequest, $carRequest);


        if (isset($applicationData['vin_array'])) {
            $applicationData['vin'] = $applicationData['vin_array'];
        }

        unset($applicationData['car_series_body']);

        foreach ($applicationData as $key => $value) {
            if ($value === '' || $value === null || $value === 'null') {
                if ($key == 'issued_by' || $key == 'issued_at') {
                    continue;
                }
                unset($applicationData[$key]);
            }
        }


        if (!in_array($applicationData['status_id'], [1, 7])) {
            $applicationData['arrived_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }


//        if (isset($applicationData['car_type_id']) && in_array($applicationData['car_type_id'], [1, 2, 6, 7, 8])) {
        if (isset($applicationData['car_type_id']) && $applicationData['car_type_id'] != $noTypeCar) {
            $applicationData['car_title'] = $this->getCarTitle($applicationData);
        }
        if ($applicationData['status_id'] == 2 && auth()->user()->hasRole(['Manager'])) {
            $applicationData['accepted_by'] = auth()->user()->id;
        }

//=========
        return $this->storeApplication($request, $applicationData, $attachmentController);
    }

    public function validateStoreData($carRequest, bool $required, bool $returned, $car_type, $noTypeCar, array $statuses, $applicationRequest): void
    {
        $validator = Validator::make($carRequest, [
            'vin_array' => $required ? [
                'exclude_if:returned,1',
                'vin_code',
//                'size:8',
                'required_without:license_plate',
                $returned ? '' : 'unique_custom:applications,vin',
                'nullable'
            ] : [],
            'license_plate' => $required ? [
                'exclude_if:returned,1',
                $returned ? '' : 'unique_custom:applications,license_plate',
                'nullable'
            ] : [],
            'car_type_id' => ['integer', 'required'],
            'car_mark_id' => ($car_type == $noTypeCar) ? ['integer'] : ['integer', 'required'],
            'car_model_id' => ['integer'],
            'year' => ['integer'],
            'car_key_quantity' => ['integer'],
            'status_id' => ['exists:statuses,id', Rule::in($statuses)]
        ]);

        if (isset($carRequest['vin_array'])) {
            $validator->sometimes('returned', function ($attribute, $value, $fail) use ($carRequest) {
                $count = Application::where('vin', $carRequest['vin_array'])->count();
                if ($count < 1) {
                    $fail('Нет такого дубликата!');
                }
            }, function ($input) {
                return $input->returned == 1;
            });
        }



        $validator->validate();

        Validator::make($applicationRequest, [
            'external_id' => ['required'],
            'partner_id' => ['integer', 'required', 'exists:partners,id'],
            'parking_id' => ['integer', 'required', 'exists:parkings,id'],
        ])->validate();
    }

    /**
     * @param array $applicationData
     * @return string
     */
    public function getCarTitle(array $applicationData): string
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

    /**
     * @param Request $request
     * @param array $applicationData
     * @param $application
     * @return mixed
     */
    public function storeApplication(Request $request, array $applicationData, $attachmentController, $application = null)
    {
        return DB::transaction(function () use ($request, $applicationData, $attachmentController) {
            $application = auth()->user()->applications()->create($applicationData);
            if ($application->status_id == 7) {
                $application->issueAcceptions()->create([
                    'is_issue' => false,
                    'user_id' => auth()->id()
                ]);
            }

            if (count($attachmentsDoc = $attachmentController->storeToModelDoc($request, 'docs')) > 0) {
                $application->attachments()->saveMany($attachmentsDoc);
            }
            if (count($attachments = $attachmentController->storeToModel($request, 'images')) > 0) {
                $application->attachments()->saveMany($attachments);
            }
            return $application;
        });
    }
    /**
     * @param Request $request
     * @return array[]
     */
    public function checkApplicationDuplicate(Request $request): array
    {
        $licensePlateDuplicates = (isset($request->license_plate) && strlen($request->license_plate) >= 3)
            ?
            Application::with('status')
                ->where(function ($query) {
                    if (!auth()->user()->hasRole('SuperAdmin')) {
                        $query->whereIn('accepted_by', auth()->user()->getUsersAdmin())
                            ->orWhereIn('user_id', auth()->user()->getUsersAdmin());
                    }
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
                } elseif (strlen($singleVin) > 2) {
                    $searchVin = true;
                }
            }

            if ($searchVin) {
                $singleVin = $vinArray[0];
                $vinQuery = Application::with('status')
                    ->where(function ($query) {
                        if (!auth()->user()->hasRole('SuperAdmin')) {
                            $query->whereIn('accepted_by', auth()->user()->getUsersAdmin())
                                ->orWhereIn('user_id', auth()->user()->getUsersAdmin());
                        }
                    })
                    ->where('vin', 'like', '%' . $singleVin . '%');

                $vinDuplicates = $vinQuery->get()->toArray();
            }
        }
        return array($licensePlateDuplicates, $vinDuplicates);
    }
}
