<?php

namespace App\Http\Controllers;

use App\Filter\ApplicationFilters;
use App\Models\Application;
use App\Models\IssueAcception;
use Illuminate\Http\Request;

class AcceptingRequestController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ApplicationFilters $filters)
    {
//        $acceptingRequests = IssueAcception::issuances()->where('is_issue', false)->with(['application'])->get();
        $applications = Application::applications()->filter($filters)
            ->whereHas('acceptions')
            ->paginate( config('app.paginate_by', '25') )
            ->withQueryString();

        $title = __('Accepting Requests');
        if($request->get('direction') == 'row') {
            return view('applications.index_status', compact('title', 'applications'));
        } else {
            return view('applications.index', compact('title', 'applications'));
        }
    }
}
