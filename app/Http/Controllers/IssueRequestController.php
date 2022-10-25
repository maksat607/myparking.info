<?php

namespace App\Http\Controllers;

use App\Filter\ApplicationFilters;
use App\Models\Application;
use App\Models\Client;
use App\Models\IssueAcception;
use App\Models\Status;
use App\Models\ViewRequest;
use App\Services\ApplicationTotalsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Toastr;

class IssueRequestController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ApplicationFilters $filters)
    {
        if (request()->has('uncheckFilters')) {
            return redirect()->to(url()->current());
        }
        $totals = ApplicationTotalsService::totals(Status::activeStatuses(),  $filters);
        $applications = Application::applications()
            ->where('status_id','!=',8)
            ->with(['attachments', 'partner', 'parking', 'acceptions', 'issuance', 'viewRequests'])
            ->filter($filters)
            ->whereHas('issuance')
            ->paginate( config('app.paginate_by', '25') )
            ->withQueryString()
            ;

        $title = __('Issue Requests');
        /*if($request->get('direction') == 'row') {
            return view('applications.index_status', compact('title', 'applications'));
        } else {
            return view('issue_request.index', compact('title', 'issueRequests'));
        }*/
        switch ($request->get('direction', 'column')) {
            case 'table':
                return view('applications.index_table', compact('title', 'applications','totals'));
            case 'row':
                return view('applications.index_row', compact('title', 'applications','totals'));
            default:
                return view('applications.index', compact('title', 'applications','totals'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($application_id)
    {
        $application = Application::application($application_id)->firstOrFail();
//        dd($application);
        if($application->status->code != 'storage') {
            return redirect()->back()->with('warning', __('The car is not yet in storage'));
        }

        if($application->issuance) {
            return redirect()->back()->with('warning', __('The application for issuing a car already exists'));
        }

        $individualLegalOptions = Client::issuanceIndividualLegalOptions();

        $title = __('Application for issue');
        return view('issue_request.create', compact(
            'title', 'application',
                    'individualLegalOptions',
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
            'inn' => ['string', 'nullable'],
            'organization_name' => ['string', 'nullable'],
            'fio' => ['string', 'nullable'],
            'phone' => ['numeric', 'nullable'],
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

        if ($isIssue->exists) {
            Toastr::success(__('Saved.'));
            return redirect()->route('issue_requests.index');
        }

        Toastr::error(__('Error'));
        return redirect()->back();
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

        $individualLegalOptions = Client::issuanceIndividualLegalOptions();

        $title = __('Editing an application for issuance');

        return view('issue_request.edit', compact(
            'title', 'issueRequest', 'client', 'application', 'individualLegalOptions'
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
            'inn' => ['string', 'nullable'],
            'organization_name' => ['string', 'nullable'],
            'fio' => ['string', 'nullable'],
            'phone' => ['numeric', 'nullable'],
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

        $issueRequest->client->update($clientData);
        $result = $issueRequest->update($issueData);

        if ($result) {
            Toastr::success(__('Saved.'));
            return redirect()->route('issue_requests.index');
        }

        Toastr::error(__('Error'));
        return redirect()->back();
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

        if ( $result ) {
            Toastr::success(__('Deleted.'));
            return redirect()->route('issue_requests.index');
        }

        Toastr::error(__('Error'));
        return redirect()->back();
    }
}
