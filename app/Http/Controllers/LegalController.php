<?php

namespace App\Http\Controllers;

use App\Models\Legal;
use App\Models\Parking;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LegalController extends AppController
{
    public function __construct()
    {

        $this->middleware(['role:Admin|SuperAdmin'])->except([
            'allForParking',
            'viewForParking',
            'allForUser',
            'viewForUser',
        ]);

        $this->middleware(['role:SuperAdmin'])->except([
            'allForParking',
            'viewForParking',
            'index',
            'create',
            'show',
            'store',
            'edit',
            'update',
            'destroy',
        ]);


        $this->middleware(['permission:legal_view'])->only('index', 'show');
        $this->middleware(['permission:legal_create'])->only('create', 'store');
        $this->middleware(['permission:legal_update'])->only('edit', 'update');
        $this->middleware(['permission:legal_delete'])->only('destroy');
    }


    public function allForUser($id)
    {
        $user = User::role(['SuperAdmin', 'Admin'])->findOrFail($id);
        $legals = $user->legals;
        $title = __('Legal entities of the user :User', ['user'=>$user->name]);

        return view('legals.admin.index', compact('legals', 'title'));
    }

    public function viewForUser($user_id, $legal_id)
    {
//        $legal = User::findOrFail($user_id)->legals()->where('id', $legal_id)->firstOrFail();
        $legal = Legal::where('id', $legal_id)->where('user_id', $user_id)->firstOrFail();

        $title = __('View legal entity: :Legal', ['legal'=>$legal->name]);

        return view('legals.admin.view', compact('legal', 'title'));
    }

    public function allForParking($id)
    {
        $parking = Parking::where('id', $id)->firstOrFail();
        $legals = $parking->legals;
        $title = __('Legal entities of the parking :Parking', ['parking'=>$parking->title]);

        return view('legals.admin.index', compact('legals', 'parking', 'title'));
    }

    public function viewForParking($parking_id, $legal_id)
    {
        $parking = Parking::where('id', $parking_id)->firstOrFail();
        $legal = $parking->legals->find($legal_id);
//        $legal = $parking->legals->where('id', $legal_id)->first();

        if(is_null($legal)) {
            abort(404);
        };

        $title = __('View legal entity: :Legal', ['legal'=>$legal->name]);

        return view('legals.admin.view', compact('legal', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->hasRole(['SuperAdmin'])){
            $legals = Legal::all();
        }else{
            $legals = auth()->user()->legals;
        }
        $title = __('Legal entities');

        return view('legals.index', compact('legals', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $title = __('Create new legal entity');

        return view('legals.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'reg_number' => ['required', 'string', 'min:5', 'max:255', 'unique:legals'],
            'inn' => ['required', 'string', 'min:5', 'unique:legals'],
            'kpp' => ['required', 'string', 'min:5', 'unique:legals'],
            'status' => ['boolean'],
        ])->validate();

        $legalData = [
            'name' => $request->name,
            'reg_number' => $request->reg_number,
            'inn' => $request->inn,
            'kpp' => $request->kpp,
            'status' => $request->input('status', 0),
        ];

        $newLegal = Auth::user()->legals()->create($legalData);

        return ($newLegal->exists)
            ? redirect()->route('legals.index')->with('success', __('Saved.'))
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
        $legal = auth()->user()->legals()->where('id', $id)->firstOrFail();
        $title = __('View legal entity: :Legal', ['legal' => $legal->name]);

        return view('legals.show', compact('legal', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->hasRole(['SuperAdmin'])){
            $legal = Legal::where('id', $id)->firstOrFail();
        }else{
            $legal = auth()->user()->legals()->where('id', $id)->firstOrFail();
        }
//        $legal = Legal::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        $title = __('Edit legal entity: :Legal', ['legal' => $legal->name]);

        return view('legals.edit', compact('legal', 'title'));
        }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->hasRole(['SuperAdmin'])) {
            $legal = Legal::where('id', $id)->firstOrFail();
        }
        else{
            $legal = auth()->user()->legals()->where('id', $id)->firstOrFail();
        }

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'reg_number' => ['required', 'string', 'min:5', 'max:255', Rule::unique('legals')->ignore($legal->id)],
            'inn' => ['required', 'string', 'min:5', Rule::unique('legals')->ignore($legal->id)],
            'kpp' => ['required', 'string', 'min:5', Rule::unique('legals')->ignore($legal->id)],
            'status' => ['boolean'],
        ])->validate();


        $legal->name = $request->name;
        $legal->reg_number = $request->reg_number;
        $legal->inn = $request->inn;
        $legal->kpp = $request->kpp;
        $legal->status = $request->input('status', 0);

        return ($legal->save())
            ? redirect()->route('legals.index')->with('success', __('Updated.'))
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
        $legal = auth()->user()->legals()->where('id', $id)->firstOrFail();

        return ($legal->delete())
            ? redirect()->back()->with('success', __('Deleted.'))
            : redirect()->back()->with('error', __('Error'));

    }

}
