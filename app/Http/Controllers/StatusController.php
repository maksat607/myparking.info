<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Status::where('is_active', true)->orderBy('rank', 'desc')->orderBy('name', 'asc')->get();
    }
    /**
     * Display a viewable statuses of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewableStatuses()
    {
        return response()->json([
            'items'=>Status::viewableStatuses()
        ]);
    }

    /**
     * Display a setable statuses of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setableStatuses()
    {
        return response()->json([
            'items'=>Status::setableStatuses()
        ]);
    }

    /**
     * Display a setable statuses of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        return response()->json([
            'items'=> Status::where('is_active', true)->orderBy('rank', 'desc')->orderBy('name', 'asc')->get()
        ]);
    }
    /**
     * Display a listing of the resource with pagination.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request) {

        $searchFilter = [];

        $mainQuery = Status::where($searchFilter)
            ->orderBy('is_active', 'desc')->orderBy('rank', 'desc')->orderBy('name', 'asc');

        if ( isset($request->search_text) && strlen($request->search_text) > 1) {
            $searchText = '%' .$request->search_text. '%';
            $mainQuery->where(function($query) use ( $searchText )  {
                $query->orWhere('name', 'like', $searchText)
                    ->orWhere('code', 'like', $searchText);
            });
        }
        $items = $mainQuery->paginate( config('app.paginate_by', '25') )->onEachSide(2);

        return response()->json([
            'items'=> $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'=>'required|unique:statuses',
            'code'=>'required|unique:statuses',
            'rank' => 'integer',
        ]);
        $input = $request->all();
        $input['is_active'] = $request->has('is_active');
        $item = Status::create(
            $input
        );


        if( $item ) {
            return response()->json([
                ['message'=>__('Status Created'), 'class' => 'is-success']
            ]);
        }
        return response()->json([
            ['message'=>__('Status Not Created'), 'class' => 'is-danger']
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Status $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Status $status)
    {
        return response()->json([
            'item'=> $status
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Status $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Status $status)
    {
        $request->validate([
            'name'=>'required',
            'rank' => 'integer'
        ]);
        $input = $request->all();
        $input['is_active'] = $request->has('is_active');
        $result = $status->update($input);

        if( $result ) {
            return response()->json([
                ['message'=>__('Status Updated'), 'class' => 'is-success']
            ]);
        }
        return response()->json([
            ['message'=>__('Status Not Updated'), 'class' => 'is-danger']
        ]);
    }
}
