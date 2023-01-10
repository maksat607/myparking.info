<?php

namespace App\Http\Controllers\Api;

use App\Filter\ApplicationFilters;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Client;
use App\Models\IssueAcception;
use App\Services\IssueRequestService;
use Illuminate\Http\Request;

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
        $application = Application::application($application_id)->firstOrFail();
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
        $issueRequest = IssueAcception::issuance($issue_request_id)->firstOrFail();
        $application = $issueRequest->application;
        $client = $issueRequest->client;
        return $application;
        if ($application->status->code != 'storage') {
            return response()->json(['warning' => __('The car is not yet in storage')]);
        }

        $individualLegalOptions = Client::issuanceIndividualLegalOptions();

        $title = __('Editing an application for issuance');

        return response()->json([compact('title', 'issueRequest', 'client', 'application', 'individualLegalOptions')]);
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
