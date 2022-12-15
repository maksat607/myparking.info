<?php

namespace App\Http\Controllers\Api;

use App\Filter\ApplicationFilters;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Pricing;
use App\Models\Status;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApiApplicationController extends Controller
{
    private ApplicationService $applicationService;
    public function __construct()
    {
        $this->applicationService = new ApplicationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ApplicationFilters $filters, $status_id = null)
    {
        $this->authorize('viewAny', Application::class);
        return $this->applicationService->index($request, $filters, $status_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
