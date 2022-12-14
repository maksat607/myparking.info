<?php

namespace App\Http\Controllers\Api;

use App\Filter\ApplicationFilters;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Pricing;
use App\Models\Status;
use App\Services\ApplicationTotalsService;
use Illuminate\Http\Request;

class ApiApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ApplicationFilters $filters, $status_id = null)
    {
//        if (request()->has('uncheckFilters')) {
//            return redirect()->to(url()->current());
//        }

        $this->authorize('viewAny', Application::class);
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
            ->with('viewRequests')
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
