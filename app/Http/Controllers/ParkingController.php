<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ParkingController extends AppController
{
    public function __construct()
    {
        $this->middleware(['permission:parking_view'])->only('index', 'show');
        $this->middleware(['permission:parking_create'])->only('create', 'store');
        $this->middleware(['permission:parking_update'])->only('edit', 'update');
        $this->middleware(['permission:parking_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parkings = Parking::parkings()->with('legals')->get();
        $title = __('Parking lots');

        return view('parkings.index', compact('title', 'parkings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('Create new Parking');
        $user = auth()->user();
        $children = $user->children()->without('owner')->get();
        $legals = ($user->owner)
            ? $user->owner->legals()->without('owner')->get()
            : $user->legals()->without('owner')->get();

        return view('parkings.create', compact('user', 'children', 'legals', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'legals' => ['required', 'array'],
            'user' => ['exists:users,id', 'filled'],
        ])->validate();

        $parkingData = [
            'title' => $request->title,
            'code' => $request->code,
            'address' => $request->address,
        ];

        if(!is_null($request->timezone)) {
            $parkingData['timezone'] = $request->timezone;
        }

        try {
            DB::beginTransaction();

            $user = ($request->has('user')) ? User::findOrFail($request->user) : auth()->user();

            $parking = $user->parkings()->create($parkingData);
            $parking->legals()->attach($request->legals);


            DB::commit();

            return redirect()->route('parkings.index')->with('success', __('Saved.'));

        } catch (QueryException $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('error', __('Error') . ': ' . __('Failed to save'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parking = Parking::parking($id)->firstOrFail();
        $this->authorize('viewParking', $parking);
        $title = __('View parking :Parking', ['parking' => $parking->title]);

        return view('parkings.show', compact('parking', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $parking = Parking::parking($id)->with('legals')->firstOrFail();
        $this->authorize('updateParking', $parking);
        $user = (!is_null($parking->owner->owner)) ? $parking->owner->owner : $parking->owner;
        $legals = $user->legals()->without('owner')->get();
        $children = $user->children()->without('owner')->get();
        $title = __('Edit parking :Parking', ['parking' => $parking->title]);

        return view('parkings.edit', compact('parking', 'legals', 'user', 'children', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'legals' => ['required', 'array'],
            'user' => ['exists:users,id', 'filled'],
        ])->validate();

        try {
            DB::beginTransaction();

            $user = ($request->has('user')) ? User::findOrFail($request->user) : auth()->user();

            $parking = $parking = Parking::parking($id)->with('legals')->firstOrFail();
            $this->authorize('updateParking', $parking);

            $parking->title = $request->title;
            $parking->code = $request->code;
            $parking->address = $request->address;

            if(!is_null($request->timezone)) {
                $parking->timezone = $request->timezone;
            }


            $parking->owner()->associate($user);

            $parking->save();
            $parking->legals()->detach();
            $parking->legals()->attach($request->legals);

            DB::commit();

            return redirect()->route('parkings.index')->with('success', __('Saved.'));

        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Error') . ': ' . __('Failed to save'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parking = Parking::parking($id)->with('legals')->firstOrFail();
        $this->authorize('deleteParking', $parking);
        $parking->legals()->detach();

        return ($parking->delete())
            ? redirect()->back()->with('success', __('Deleted.'))
            : redirect()->back()->with('error', __('Error'));
    }
}
