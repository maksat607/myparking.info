<?php

namespace App\Services;

use App\Filter\ApplicationFilters;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IssueRequestService
{
    public function index(ApplicationFilters $filters)
    {
        $totals = ApplicationTotalsService::totals(Status::activeStatuses(), $filters);
        $applications = Application::applications()
            ->where('status_id', '!=', 8)
            ->with(['attachments', 'partner', 'parking', 'acceptions', 'issuance', 'viewRequests'])
            ->filter($filters)
            ->whereHas('issuance')
            ->paginate(config('app.paginate_by', '25'))
            ->withQueryString();
        $applications = ApplicationResource::collection($applications);
        $title = __('Issue Requests');
        return compact('title', 'applications', 'totals');
    }


    public function validate(Request $request)
    {
        $clientData = $request->client;
        $issueData = $request->issue_request;

        Validator::make($clientData, [
            'inn' => ['string', 'nullable'],
            'organization_name' => ['string', 'nullable'],
            'fio' => ['string', 'nullable'],
            'phone' => ['numeric', 'nullable'],
        ])->validate();

        Validator::make($issueData, [
            'arriving_at' => ['required'],
            'arriving_interval' => ['required'],
        ])->validate();


        foreach ($clientData as $key => $value) {
            if (is_null($value) || $value == 'null') {
                unset($clientData[$key]);
            }
        }
        return array($clientData, $issueData);
    }

}
