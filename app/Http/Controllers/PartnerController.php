<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partners = Partner::with('partnerType')->get();
        $title = __('Partners');

        return view('partners.index', compact('title', 'partners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partner_types = PartnerType::all();
        $title = __('Create new Partner');

        return view('partners.create', compact('title', 'partner_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $partnerData = [
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'rank' => $request->rank,
            'partner_type_id' => $request->partner_type,
            'status' => $request->status,
        ];

        $partner = Partner::create($partnerData);

        return ($partner->exists)
            ? redirect()->route('partners.index')->with('success', __('Saved.'))
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
        $partner = Partner::findOrFail($id);
        $partner_types = PartnerType::all();
        $title = __('Edit partner :Partner', ['partner' => $partner->name]);
        return view('partners.edit', compact('title', 'partner', 'partner_types'));
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
        $partner = Partner::findOrFail($id);

        $this->validator($request->all())->validate();

        $request->request->add(['partner_type_id' => $request->partner_type]);
        $is_update = $partner->update($request->except(['partner_type']));

//        $is_update = Partner::where('id', $id)->update($request->except(['partner_type', '_token', '_method']));


        return ($is_update)
            ? redirect()->route('partners.index')->with('success', __('Saved.'))
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

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100', 'unique:partner_types'],
            'address' => ['string', 'max:100', 'nullable'],
            'phone' => ['numeric', 'min:20', 'nullable'],
            'email' => ['string', 'email', 'nullable'],
            'rank' => ['required', 'numeric', 'min:0'],
            'partner_type' => ['required', 'exists:partner_types,id'],
            'status' => ['boolean'],
        ]);
    }
}
