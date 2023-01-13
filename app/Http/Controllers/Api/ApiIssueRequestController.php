<?php

namespace App\Http\Controllers\Api;

use App\Filter\ApplicationFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ModelResource;
use App\Models\Application;
use App\Models\Client;
use App\Models\IssueAcception;
use App\Services\IssueRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiIssueRequestController extends Controller
{
    public function __construct(public IssueRequestService $issueRequestService)
    {
    }

    public function index(Request $request, ApplicationFilters $filters)
    {
        return $this->issueRequestService->index($filters);
    }


    public function create($application_id)
    {
        $application = new ApplicationResource(Application::application($application_id)->firstOrFail());
        if ($application->status->code != 'storage') {
            return response()->json(['warning' => __('The car is not yet in storage')]);
        }

        if ($application->issuance) {
            return response()->json(['warning' => __('The application for issuing a car already exists')]);
        }

        $individualLegalOptions = Client::issuanceIndividualLegalOptions();

        $title = __('Application for issue');
        return response()->json(compact('title', 'application', 'individualLegalOptions'));
    }


    public function store(Request $request, $id)
    {

        list($clientData, $issueData) = $this->issueRequestService->validate($request);

        $application = Application::application($id)->firstOrFail();
        if ($application->issuance) {
            return response()->json(['warning' => __('The application for issuing a car already exists')]);
        }
        $client = Client::create($clientData);
        if ($client->exists) {
            $isIssue = $application->issueAcceptions()->create([
                'client_id' => $client->id,
                'user_id' => auth()->user()->id,
                'is_issue' => true,
                'arriving_at' => $issueData['arriving_at'],
                'arriving_interval' => $issueData['arriving_interval'],
            ]);
        }
        if ($isIssue->exists) {
            return response()->json(['success' => __('Saved.')]);
        }
        return response()->json(['error' => __('Error.')]);
    }


    public function show($id)
    {
        //
    }


    public function edit($issue_request_id)
    {
        $title = __('Editing an application for issuance');
        $individualLegalOptions = Client::issuanceIndividualLegalOptions();
        $issueRequest = new ModelResource(IssueAcception::issuance($issue_request_id)->with('application')->firstOrFail());

        $application = new ApplicationResource($issueRequest->application);
        $client = $issueRequest->client;

        if ($application->status->code != 'storage') {
            return response()->json(['warning' => __('The car is not yet in storage')]);
        }
        return compact('title', 'issueRequest', 'client', 'individualLegalOptions');
    }


    public function update(Request $request, $issue_request_id)
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
        $issueRequest = IssueAcception::issuance($issue_request_id)->firstOrFail();

        $issueRequest->client->update($clientData);
        $result = $issueRequest->update($issueData);

        if ($result) {
            return response()->json(['success' => __('Saved.')]);
        }
        return response()->json(['success' => __('Error')]);
    }


    public function destroy($issue_request_id)
    {
        $issueRequest = IssueAcception::issuance($issue_request_id)->firstOrFail();

        $result = $issueRequest->delete();

        if ($result) {
            return response()->json(['success' => __('Deleted')]);
        }
        return response()->json(['success' => __('Error')]);
    }
}
