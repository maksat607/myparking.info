<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ViewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ViewRequestController extends AppController
{
    protected $AttachmentController;
    public function __construct(AttachmentController $AttachmentController)
    {
        $this->AttachmentController = $AttachmentController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewRequests = ViewRequest::viewRequests()->with(['application'])->get();

        $title = __('View Requests');
        return view('view_request.index', compact('title', 'viewRequests'));
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
        $status = ViewRequest::getStatuses(1);

        $title = __('Application for inspection');
        return view('view_request.create', compact(
            'title', 'application', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {

        $viewRequest = $request->view_request;

        Validator::make($viewRequest, [
            'arriving_at'=>'required',
            'arriving_interval' => 'required',
        ])->validate();

        foreach ($viewRequest as $key => $value) {
            if ( is_null($value) || $value == 'null') {
                unset($viewRequest[$key]);
            }
        }

        $application = Application::application($id)->firstOrFail();
        if($application->status->code != 'storage') {
            return redirect()->back()->with('warning', __('The car is not yet in storage'));
        }

        $viewRequest['created_user_id'] = auth()->user()->id;
        $viewRequest['user_id'] = auth()->user()->id;
        $viewRequest['application_id'] = $application->id;
        $viewRequestResult = ViewRequest::create(
            $viewRequest
        );

        $attachments = $this->AttachmentController->storeToModel($request,'images');

        if (count($attachments) > 0) {
            $viewRequest->attachments()->saveMany($attachments);
        }

        return ($viewRequestResult->exists)
            ? redirect()->route('view_requests.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ViewRequest  $viewRequest
     * @return \Illuminate\Http\Response
     */
    public function edit($view_request_id)
    {
        $viewRequest = ViewRequest::viewRequest($view_request_id)->firstOrFail();
        $application = $viewRequest->application;
        if($application->status->code != 'storage') {
            return redirect()->back()->with('warning', __('The car is not yet in storage'));
        }
        $status = ViewRequest::getStatuses();

        $attachments = $viewRequest->attachments()->select('id', 'url as src')->get()->toArray();

        $title = __('Application for inspection');
        return view('view_request.edit', compact(
            'title', 'application', 'viewRequest', 'attachments', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ViewRequest  $viewRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $view_request_id)
    {
        $viewRequestData = $request->view_request;

        Validator::make($viewRequestData, [
            'arriving_at'=>'required',
            'arriving_interval' => 'required',
        ])->validate();

        foreach ($viewRequestData as $key => $value) {
            if ( is_null($value) || $value == 'null') {
                unset($viewRequestData[$key]);
            }
        }
        if (in_array($viewRequestData['status_id'], [2,3])) {
            $viewRequestData['finished_at'] = Carbon::now()->toDateTimeString();
            $viewRequest['reviewed_by'] = auth()->user()->id;
        }

        $viewRequest = ViewRequest::viewRequest($view_request_id)->firstOrFail();

        $result = $viewRequest->update($viewRequestData);
        $attachments = $this->AttachmentController->storeToModel($request,'images');

        if (count($attachments) > 0) {
            $viewRequest->attachments()->saveMany($attachments);
        }
        return ($result)
            ? redirect()->route('view_requests.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ViewRequest  $viewRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($view_request_id)
    {
        $viewRequest = ViewRequest::viewRequest($view_request_id)->firstOrFail();

        $viewRequest->attachments->each(function ($item, $key) {
            $this->AttachmentController->delete($item);
        });
        $result = ViewRequest::destroy($viewRequest->id);
        return ( $result )
            ? redirect()->route('view_requests.index')->with('success', __('Deleted.'))
            : redirect()->back()->with('error', __('Error'));
    }
}
