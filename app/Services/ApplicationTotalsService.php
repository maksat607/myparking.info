<?php

namespace App\Services;

use App\Filter\ApplicationFilters;
use App\Models\Application;
use App\Models\Status;
use App\Models\ViewRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class ApplicationTotalsService
{
    public static function totals($statuses, ApplicationFilters $filters, $status_id = null)
    {
        $totals = app(ApplicationTotalsService::class);
        return $totals->getTotals($statuses, $filters, $status_id);
    }

    public function getTotals($statuses, ApplicationFilters $filters, $status_id = null)
    {

        $app_ids = [];
//        $viewRequests = ViewRequest::viewRequests()->with(['application']);
//        $viewRequests->get()->map(function ($r) use (&$app_ids) {
//            if ($r->applicationWithParking() != false) {
//                $app_ids[] = $r->applicationWithParking()->id;
//            }
//        });
//
//        $viewRequestsTotal = $viewRequests
//            ->whereHas('application', function (Builder $query) use ($filters) {
//                $query->filter($filters);
//            })
//            ->whereIn('application_id', $app_ids)
//            ->orderBy('updated_at', 'desc')
//            ->paginate(config('app.paginate_by', '25'))
//            ->withQueryString()->total();
        $issuanceTotal = Application::applications()
            ->filter($filters)
            ->where('status_id', '!=', 8)
            ->whereHas('issuance')
            ->count();

        $totals = Application::
        applications()
            ->filter($filters)
            ->when(!$status_id, function ($query) use ($statuses) {
                return $query->whereIn('status_id', $statuses);
            })
            ->groupBy('status_id')
            ->selectRaw('count(*) as total, status_id')
            ->pluck('total', 'status_id')
            ->toArray();
        foreach ($statuses as $status) {
            if (!isset($totals[$status])) {
                $totals[$status] = 0;
            }
        }
        $totals['all'] = array_sum($totals);
        $totals['issue_requests'] = $issuanceTotal;
//        $totals[12] = $viewRequestsTotal;

        return $totals;
    }


}
