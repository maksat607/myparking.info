<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Carbon\Carbon;
use App\Interfaces\ExportInterface;
use Illuminate\Http\Request;

use App\Models\Application;
use App\Models\Pricing;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Exports\ExcelExport;

use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
    private $exporter;

    public function __construct(ExportInterface $exporter)
    {
        $this->exporter = $exporter;

        $this->middleware(['role:SuperAdmin|Admin|Manager'])->except('reportByPartner', 'csvByPartner');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportByEmployee(Request $request)
    {
        $user = User::where('id', auth()->user()->getUserOwnerId())->first();
        $parking = $user->parkings()->orderBy('title', 'ASC')->get();
        $data = $this->dataByEmployee($request);
        $title = __('Employee Report');
        return view('report.employee', compact('data', 'parking', 'title'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function csvByEmployee(Request $request)
    {
        $data = $this->dataByEmployee($request);
        return Excel::download(new ExcelExport($data), 'report.xlsx');
        return $this->exporter->export($data);
    }

    private function dataByEmployee(Request $request)
    {
        $sortBy = 'applications.arrived_at';

        $dates = explode(' — ', $request->dates);
        $monthAgo = Carbon::now()->startOfDay()->subMonths(1);
        $now = Carbon::now()->endOfDay();

        $startTime = (isset($dates[0]) && $dates[0] != '')
            ? Carbon::createFromFormat('d-m-Y', $dates[0])->startOfDay() : $monthAgo;

        $endTime = (isset($dates[1]) && $dates[1] != '')
            ? Carbon::createFromFormat('d-m-Y', $dates[1])->endOfDay() : $now;

//        SELECT users.name, accepted.cnt as accepted_number, issued.cnt as issued_number, reviewed.cnt as reviewed_number FROM `users`
//
//        left join (
//            SELECT count(accepted_by) as cnt, accepted_by from applications
//            group by accepted_by
//        ) as accepted on accepted.accepted_by = users.id
//
//        left join (
//            SELECT count(issued_by) as cnt, issued_by from applications
//            group by issued_by
//        ) as issued on issued.issued_by = users.id
//
//        left join (
//            SELECT count(reviewed_by) as cnt, reviewed_by from view_requests
//            group by reviewed_by
//        ) as reviewed on reviewed.reviewed_by = users.id
//
//        where accepted.cnt is not null OR issued.cnt is not null OR reviewed.cnt is not null

        $acceptedQuery = DB::table('applications')
            ->selectRaw('count(accepted_by) as cnt, accepted_by')
            ->whereNotNull('arrived_at')
            ->where([
                ['arrived_at', '>=', $startTime->format('Y-m-d H:i:s')],
                ['arrived_at', '<=', $endTime->format('Y-m-d H:i:s')]
            ])
            ->groupBy('accepted_by');

        $issuedQuery = DB::table('applications')
            ->selectRaw('count(issued_by) as cnt, issued_by')
            ->whereNotNull('issued_at')
            ->where([
                ['issued_at', '>=', $startTime->format('Y-m-d H:i:s')],
                ['issued_at', '<=', $endTime->format('Y-m-d H:i:s')]
            ])
            ->groupBy('issued_by');

        $reviewedQuery = DB::table('view_requests')
            ->selectRaw('count(reviewed_by) as cnt, reviewed_by')
            ->join('applications', 'applications.id', '=', 'view_requests.application_id')
            ->whereNotNull('finished_at')
            ->where([
                ['finished_at', '>=', $startTime->format('Y-m-d H:i:s')],
                ['finished_at', '<=', $endTime->format('Y-m-d H:i:s')]
            ])
            ->groupBy('reviewed_by');

        if (isset($request->parking_id)) {
            $acceptedQuery->whereIn('parking_id', explode(',', $request->parking_id));
            $issuedQuery->whereIn('parking_id', explode(',', $request->parking_id));
            $reviewedQuery->whereIn('applications.parking_id', explode(',', $request->parking_id));
        }

        $userQuery = User::selectRaw("users.name, IFNULL(accepted.cnt, 0) as accepted_number, IFNULL(issued.cnt, 0) as issued_number, IFNULL(reviewed.cnt, 0) as reviewed_number")
            ->leftJoinSub($acceptedQuery, 'accepted', function ($join) {
                $join->on('users.id', '=', 'accepted.accepted_by');
            })
            ->leftJoinSub($issuedQuery, 'issued', function ($join) {
                $join->on('users.id', '=', 'issued.issued_by');
            })
            ->leftJoinSub($reviewedQuery, 'reviewed', function ($join) {
                $join->on('users.id', '=', 'reviewed.reviewed_by');
            })
            ->orWhereNotNull('accepted.cnt')
            ->orWhereNotNull('issued.cnt')
            ->orWhereNotNull('reviewed.cnt');


        return [
            'columns' => [
                'name' => 'Сотрудник',
                'accepted_number' => 'Принял',
                'reviewed_number' => 'Показал',
                'issued_number' => 'Выдал',
            ],
            'data' => $userQuery->get(),
            'sql' => $userQuery->toSql(),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportByPartner(Request $request)
    {
        $partners = Partner::orderBy('name', 'ASC')->get();
        $user = User::where('id', auth()->user()->getUserOwnerId())->first();
        $parking = $user->parkings()->orderBy('title', 'ASC')->get();

        $data = $this->dataByPartner($request,);

        $orderBy = $request->get('order-by', 'asc');
        $orderBy = $orderBy == 'asc' ? 'desc' : 'asc';

        $title = __('Partner Report');
        return view('report.partner', compact('data', 'partners', 'parking', 'title', 'orderBy'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function csvByPartner(Request $request)
    {
        $data = $this->dataByPartner($request);
        return Excel::download(new ExcelExport($data), 'report.xlsx');
        return $this->exporter->export($data);
    }

    private function dataByPartner(Request $request)
    {
        $sortBy = 'applications.arrived_at';

        $dates = array_filter(explode(' — ', $request->dates), 'trim');
        $monthAgo = Carbon::now()->startOfDay()->subMonths(1);
        $now = Carbon::now()->endOfDay();

        $startTime = (isset($dates[0]) && $dates[0] != '')
            ? Carbon::createFromFormat('d-m-Y', $dates[0])->startOfDay() : $monthAgo;

        $endTime = (isset($dates[1]) && $dates[1] != '')
            ? Carbon::createFromFormat('d-m-Y', $dates[1])->endOfDay() : $startTime;

        if ((!isset($dates[0])) && (!isset($dates[1]))) {
            $startTime = $monthAgo;
            $endTime = $now;
        }

        $applicationQuery = Application
            ::select(['applications.id', 'external_id', 'car_title', 'car_marks.name as car_mark_name', 'car_models.name as car_model_name', 'year', 'vin', 'license_plate', 'car_types.name as car_type_name', 'statuses.name as status_name', 'pricings.regular_price', 'pricings.discount_price', 'arrived_at', 'issued_at', 'free_parking', 'returned'])
            ->leftJoin('car_marks', 'car_marks.id', '=', 'applications.car_mark_id')
            ->leftJoin('car_models', 'car_models.id', '=', 'applications.car_model_id')
            ->join('statuses', 'statuses.id', '=', 'applications.status_id')
            ->join('car_types', 'car_types.id', '=', 'applications.car_type_id')
            ->leftJoin('pricings', function ($join) {
                $join->on('pricings.partner_id', '=', 'applications.partner_id');
                $join->on('pricings.car_type_id', '=', 'applications.car_type_id');
            })
            ->whereNotNull('arrived_at');

        $status_id = $request->query('status_id', 'arrived');

        if ($status_id == "arrived") {
            //arrived at filter
            $applicationQuery->where('arrived_at', '<=', $endTime->format('Y-m-d H:i:s'));
            $applicationQuery->where(function ($query) use ($startTime, $endTime) {
                $query->whereNull('issued_at')
                    ->orWhere(function ($innerQuery) use ($startTime, $endTime) {
                        $innerQuery
//                            ->where('issued_at', '<=', $endTime->format('Y-m-d H:i:s'))
                            ->where('issued_at', '>=', $startTime->format('Y-m-d H:i:s'));
                    });
            });
        } elseif ($status_id == "issued") {

            /*$applicationQuery->whereBetween('issued_at', [
                $startTime->format('Y-m-d H:i:s'),
                $endTime->format('Y-m-d H:i:s')
            ]);*/

            $applicationQuery->where(function ($query) use ($startTime, $endTime) {
                $query->whereNull('issued_at')
                    ->orWhere(function ($innerQuery) use ($startTime, $endTime) {
                        $innerQuery
                            ->where('issued_at', '<=', $endTime->format('Y-m-d H:i:s'))
                            ->where('issued_at', '>=', $startTime->format('Y-m-d H:i:s'));
                    });
            });

            $applicationQuery->where(function ($query) use ($startTime, $endTime) {
                $query->whereNull('arriving_at')
                    ->orWhere(function ($innerQuery) use ($startTime, $endTime) {
                        $innerQuery
                            ->where('arriving_at', '<=', $endTime->format('Y-m-d H:i:s'));
//                            ->where('arriving_at', '>=', $startTime->format('Y-m-d H:i:s'));
                    });
            });

        } elseif ($status_id == 'instorage') {
            $applicationQuery->where('status_id',2);
        }


        if (isset($request->partner_id)) {
            $applicationQuery->whereIn('applications.partner_id', $request->partner_id);
//            $applicationQuery->whereIn('applications.partner_id', explode(',', $request->partner_id) );
        }

        if (isset($request->status_id)) {
            if ($request->status_id == "arrived") {
                $applicationQuery->whereIn('applications.status_id', [2, 3]);
            } elseif ($request->status_id == "issued") {
                $applicationQuery->whereIn('applications.status_id', [3]);
            }
        }

        if (isset($request->parking_id)) {
//            $applicationQuery->whereIn('applications.parking_id', explode(',', $request->parking_id));
            $applicationQuery->whereIn('applications.parking_id', $request->parking_id);
        }

        if (isset($request->favorite)) {
            $applicationQuery->where('applications.favorite', 1);
        }


        $applications = $applicationQuery->get();

//        print_r($applicationQuery
//            ->orderBy($sortBy, 'desc')->toSql());
//        die;

        foreach ($applications as $key => $item) {
            if (empty($item->car_mark_name) && empty($item->car_model_name)) {
                $item->car_mark_name = $item->car_title;
            }
            $item->parkingCostInDateRange($startTime, $endTime);
        }

        $arr = [
            "id" => null,
    "external_id" => null,
    "car_title" => null,
    "car_mark_name" => 'ИТОГО',
    "car_model_name" => null,
    "year" => null,
    "vin" => null,
    "license_plate" =>null,
    "car_type_name" => null,
    "status_name" => null,
    "regular_price" => null,
    "discount_price" => null,
    "arrived_at" =>null,
    "issued_at" => null,
    "free_parking" => null,
    "returned" => null,
    "start" =>null,
        "end" => null,
            "parked_days" => $applications->sum('parked_days'),
    "parked_price" => $applications->sum('parked_price'),
        ];

        $applications->push(collect(json_decode(json_encode($arr), FALSE)));

        return [
            'columns' => [
                'external_id' => 'Номер убытка',
                'car_mark_name' => 'Марка',
                'car_model_name' => 'Модель',
                'year' => 'Год',
                'car_type_name' => 'Тип Авто',
                'vin' => 'VIN',
                'license_plate' => 'Гос. номер',
                'status_name' => 'Статус',
                'formated_arrived_at' => 'Постановка',
                'formated_issued_at' => 'Выдано',
                'parked_days' => 'Кол-во дней',
                'parked_price' => 'Сумма'],
            'sss' => $applicationQuery
                ->orderBy($sortBy, 'desc')->toSql(),
            'data' => $applications,
        ];
    }

    public function reportAllPartner(Request $request)
    {

        $user = User::where('id', auth()->user()->getUserOwnerId())->first();
        $parking = $user->parkings()->orderBy('title', 'ASC')->get();
        $data = $this->dataAllPartner($request);
        $total = (object)$data['data']->pop();

        $title = __('All Partners Report');
        return view('report.all-partner', compact('data', 'parking', 'total', 'title'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function csvAllPartner(Request $request)
    {
        $data = $this->dataAllPartner($request);
        return Excel::download(new ExcelExport($data), 'report.xlsx');
        return $this->exporter->export($data);
    }

    private function dataAllPartner(Request $request)
    {
        $sortBy = 'applications.arrived_at';


        $dates = explode(' — ', $request->dates);
        $startOfMonth = Carbon::now()->firstOfMonth()->startOfDay();
        $now = Carbon::now()->endOfDay();

        $startTime = (isset($dates[0]) && $dates[0] != '')
            ? Carbon::createFromFormat('d-m-Y', $dates[0])->startOfDay() : $startOfMonth;

        $endTime = (isset($dates[1]) && $dates[1] != '')
            ? Carbon::createFromFormat('d-m-Y', $dates[1])->endOfDay() : $now;

        // issued application section
        $issueQuery = DB::table('applications as ap2')
            ->selectRaw('count(*) as issued')
            ->selectRaw('ap2.partner_id')
            ->selectRaw('ap2.parking_id')
            ->whereNotNull('issued_at')
            ->where([
                ['issued_at', '>=', $startTime->format('Y-m-d H:i:s')],
                ['issued_at', '<=', $endTime->format('Y-m-d H:i:s')]
            ]);
        if (isset($request->parking_id)) {
            $issueQuery->whereIn('ap2.parking_id', explode(',', $request->parking_id));
        }

        $issuedApplications = $issueQuery
            ->groupBy('ap2.partner_id', 'ap2.parking_id');


        // arrived application section

        $applicationQuery = Application::
        selectRaw("count(applications.id) as arrived, applications.parking_id, applications.partner_id, IFNULL(ap2.issued, 0) as issued, partners.name as partner_name, parkings.title as parking_name,
            SUM(IF(arrived_at > ?, DATEDIFF(?, arrived_at), DATEDIFF(?, ?) ) ) as total_days", [
            $startTime->format('Y-m-d H:i:s'),
            $endTime->format('Y-m-d H:i:s'),
            $endTime->format('Y-m-d H:i:s'),
            $startTime->format('Y-m-d H:i:s')
        ])
            ->join('partners', 'partners.id', '=', 'applications.partner_id')
            ->join('parkings', 'parkings.id', '=', 'applications.parking_id')
            ->leftJoinSub($issueQuery, 'ap2', function ($join) {
                $join->on('ap2.parking_id', '=', 'applications.parking_id');
                $join->on('ap2.partner_id', '=', 'applications.partner_id');
            })
            ->whereNotNull('arrived_at')
            ->whereNull('issued_at')
            ->where('arrived_at', '<=', $endTime->format('Y-m-d H:i:s'));

        if (isset($request->parking_id)) {
            $applicationQuery->whereIn('applications.parking_id', explode(',', $request->parking_id));
        }

        $applications = $applicationQuery
            ->groupBy('applications.partner_id', 'applications.parking_id', 'issued')
            ->orderBy('applications.partner_id', 'desc')->orderBy('applications.parking_id', 'desc')
            ->get();
        $total = [
            'partner_name' => 'Итого',
            'parking_name' => '',
            'arrived' => 0,
            'issued' => 0,
            'total_days' => 0,
        ];
        foreach ($applications as $key => $item) {
            $total['arrived'] += $item['arrived'];
            $total['issued'] += $item['issued'];
            $total['total_days'] += $item['total_days'];
        }


        $applications[] = $total;

        return [
            'columns' => [
                'partner_name' => 'Партнер',
                'parking_name' => 'Стоянка',
                'arrived' => 'Хранится',
                'issued' => 'Выдано',
                'total_days' => 'Всего дней',
            ],
            'data' => $applications,
        ];
    }
}
