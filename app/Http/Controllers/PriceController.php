<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Price;
use App\Models\PriceForPartner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($request, $parking) {
            Price::where('partner_id', (int)$request->get('partner_id'))->where('parking_id', $parking->id)->delete();
            Price::insert($request->pricings);
            PriceForPartner::where('partner_id', (int)$request->get('partner_id'))->where('parking_id', $parking->id)->delete();
            PriceForPartner::create(['partner_id' => (int)$request->get('partner_id'), 'parking_id' => $parking->id]);
        });
        return redirect()->back()->with('success', __('Saved.'));
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
