<?php

namespace App\Http\Controllers;

use App\Models\PartnerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PartnerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partner_types = PartnerType::all();
        $title = __('Partner type.');
        return view('partner.type.index', compact('title', 'partner_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('Create new Partner type');
        return view('partner.type.create', compact('title'));
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
            'name' => ['required', 'string', 'max:100', 'unique:partner_types'],
            'rank' => ['required', 'numeric', 'min:0'],
            'status' => ['boolean'],
        ])->validate();

        $typeData = [
            'name' => $request->name,
            'rank' => $request->rank,
            'status' => $request->status,
        ];

        $partner_type = PartnerType::create($typeData);

        return ($partner_type->exists)
            ? redirect()->route('partner-types.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partner_type = PartnerType::findOrFail($id);
        $title = __('Edit partner type :Type', ['type' => $partner_type->name]);
        return view('partner.type.edit', compact('title', 'partner_type'));
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
        $partner_type = PartnerType::findOrFail($id);

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', Rule::unique('partner_types')->ignore($partner_type->id)],
            'rank' => ['required', 'numeric', 'min:0'],
            'status' => ['boolean'],
        ])->validate();

        $partner_type->name = $request->name;
        $partner_type->rank = $request->rank;
        $partner_type->status = $request->status;


        return ($partner_type->save())
            ? redirect()->route('partner-types.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
