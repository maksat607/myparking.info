<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $request->dd();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, Parking $parking)
    {
        dump($parking->prices($request->partner));
        $request->dd();
    }

    /**
     * Display the specified resource.
     *
     * @param Price $price
     * @return Response
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Price $price
     * @return Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Price $price
     * @return Response
     */
    public function update(Request $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Price $price
     * @return Response
     */
    public function destroy(Price $price)
    {
        //
    }
}
