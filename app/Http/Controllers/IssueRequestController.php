<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Client;
use App\Models\IssueAcception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IssueRequestController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $issueRequests = IssueAcception::issuances()->with(['application'])->where('is_issue', true)->get();

        $title = __('Issue Requests');
        return view('issue_request.index', compact('title', 'issueRequests'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($application_id)
    {
        $application = Application::application($application_id)->firstOrFail();
        if($application->status->code != 'storage') {
            return redirect()->back()->with('warning', __('The car is not yet in storage'));
        }

        if($application->issuance) {
            return redirect()->back()->with('warning', __('The application for issuing a car already exists'));
        }

        $documentOptions = Client::issuanceDocumentOptions();
        $individualLegalOptions = Client::issuanceIndividualLegalOptions();
        $preferredContactMethodOptions = Client::issuancePreferredContactMethodOptions();

        $title = __('Application for issue');
        return view('issue_request.create', compact(
            'title', 'application', 'documentOptions',
                    'individualLegalOptions', 'preferredContactMethodOptions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {

        $clientData = $request->client;
        $issueData = $request->issue_request;

        Validator::make($clientData, [
            'issuance_document' => ['string', 'nullable'],
            'lastname' => ['string', 'nullable'],
            'firstname' => ['string', 'nullable'],
            'middlename' => ['string', 'nullable'],
            'phone' => ['numeric', 'nullable'],
            'email' => ['email', 'nullable'],
        ])->validate();

        Validator::make($issueData, [
            'arriving_at'=>['required'],
            'arriving_interval' => ['required'],
        ])->validate();


        foreach ($clientData as $key => $value) {
            if ( is_null($value) || $value == 'null') {
                unset($clientData[$key]);
            }
        }

        $application = Application::application($id)->firstOrFail();
        if($application->issuance) {
            return redirect()->back()->with('warning', __('The application for issuing a car already exists'));
        }

        $client = Client::create($clientData);
        if($client->exists) {
            $isIssue = $application->issueAcceptions()->create([
                'client_id' => $client->id,
                'user_id' => auth()->user()->id,
                'is_issue' => true,
                'arriving_at' => $issueData['arriving_at'],
                'arriving_interval' => $issueData['arriving_interval'],
            ]);

        }

        return ($isIssue->exists)
            ? redirect()->route('issue_requests.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $issue_request_id
     * @return \Illuminate\Http\Response
     */
    public function edit($issue_request_id)
    {
        $issueRequest = IssueAcception::issuance($issue_request_id)->firstOrFail();
        $application = $issueRequest->application;
        $client = $issueRequest->client;

        if($application->status->code != 'storage') {
            return redirect()->back()->with('warning', __('The car is not yet in storage'));
        }

        $documentOptions = Client::issuanceDocumentOptions();
        $individualLegalOptions = Client::issuanceIndividualLegalOptions();
        $preferredContactMethodOptions = Client::issuancePreferredContactMethodOptions();

        $title = __('Application for inspection');
        return view('issue_request.edit', compact(
            'title', 'issueRequest', 'client', 'application', 'documentOptions',
            'individualLegalOptions', 'preferredContactMethodOptions'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $issue_request_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $issue_request_id)
    {
        $clientData = $request->client;
        $issueData = $request->issue_request;

        Validator::make($clientData, [
            'issuance_document' => ['string', 'nullable'],
            'lastname' => ['string', 'nullable'],
            'firstname' => ['string', 'nullable'],
            'middlename' => ['string', 'nullable'],
            'phone' => ['numeric', 'nullable'],
            'email' => ['email', 'nullable'],
        ])->validate();

        Validator::make($issueData, [
            'arriving_at'=>['required'],
            'arriving_interval' => ['required'],
        ])->validate();


        foreach ($clientData as $key => $value) {
            if ( is_null($value) || $value == 'null') {
                unset($clientData[$key]);
            }
        }
        $issueRequest = IssueAcception::issuance($issue_request_id)->firstOrFail();

        $issueRequest->client()->update($clientData);
        $result = $issueRequest->update($issueData);

        return ($result)
            ? redirect()->route('issue_requests.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ViewRequest  $viewRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($issue_request_id)
    {
        $issueRequest = IssueAcception::issuance($issue_request_id)->firstOrFail();

        $result = $issueRequest->delete();

        return ( $result )
            ? redirect()->route('issue_requests.index')->with('success', __('Deleted.'))
            : redirect()->back()->with('error', __('Error'));
    }
}
