<?php

namespace App\Http\Controllers;

use App\Models\IssueAcception;
use Illuminate\Http\Request;

class AcceptingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $acceptingRequests = IssueAcception::issuances()->where('is_issue', false)->with(['application'])->get();
//        dd($acceptingRequests);
        $title = __('Accepting Requests');
        return view('applications.accepting', compact('title', 'acceptingRequests'));
    }
}
