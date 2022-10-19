<?php

namespace App\Http\Controllers;

use App\Filter\ApplicationFilters;
use App\Models\Application;
use App\Models\Status;
use App\Models\ViewRequest;
use App\Services\ApplicationTotalsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Toastr;

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
    public function index(Request $request, ApplicationFilters $filters)
    {
        if (request()->has('uncheckFilters')) {
            return redirect()->to(url()->current());
        }
        $app_ids = [];
        $viewRequests = ViewRequest::viewRequests()->with(['application']);
        $viewRequests->get()->map(function ($r) use (&$app_ids){
            if($r->applicationWithParking()!=false){
                $app_ids[] = $r->applicationWithParking()->id;
            }
        });

        $viewRequests = $viewRequests
            ->whereHas('application', function(Builder $query) use ($filters){
                $query->filter($filters);
            })
            ->whereIn('application_id',$app_ids)
            ->orderBy('updated_at', 'desc')
            ->paginate( config('app.paginate_by', '25') )
            ->withQueryString();
            ;
        $totals = ApplicationTotalsService::totals(Status::activeStatuses(),  $filters);
        /*$applications = Application::applications()->filter($filters)
            ->whereHas('viewRequests')
            ->paginate( config('app.paginate_by', '25') )
            ->withQueryString();*/
//        dd($viewRequests);
        $title = __('View Requests');
        /*if($request->get('direction') == 'row') {
            return view('applications.index_status', compact('title', 'applications'));
        } else {*/
            return view('view_request.index', compact('title', 'viewRequests','totals'));
        /*}*/
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
            $viewRequestResult->attachments()->saveMany($attachments);
        }

        if ($viewRequestResult->exists) {
            Toastr::success(__('Saved.'));
            return redirect()->route('view_requests.index');
        }

        Toastr::error(__('Error'));
        return redirect()->back();
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

        if ($result) {
            Toastr::success(__('Saved.'));
            return redirect()->route('view_requests.index');
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
    public function destroy($view_request_id)
    {
        $viewRequest = ViewRequest::viewRequest($view_request_id)->firstOrFail();

        $viewRequest->attachments->each(function ($item, $key) {
            $this->AttachmentController->delete($item);
        });
        $result = ViewRequest::destroy($viewRequest->id);
        if ( $result ) {
            Toastr::success(__('Deleted.'));
            return redirect()->route('view_requests.index');
        }

        Toastr::error(__('Error'));
        return redirect()->back();

    }
}
