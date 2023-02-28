<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use App\Notifications\CreateUserNotifications;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PartnerUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['role:SuperAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Partner $partner)
    {
        $this->authorize('issetPartnerUser', $partner);
        $title = __('Create a new Partner Account');
        return view('partners.user.create', compact('title', 'partner'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Partner $partner)
    {

        $this->authorize('issetPartnerUser', $partner);
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['boolean'],
        ])->validate();

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'email_verified_at' => now()
        ];

        dd($userData);
        try {
            DB::beginTransaction();
            $userModel = User::create($userData);
            $userModel->assignRole('Partner');

            $partner->user()->associate($userModel);
            $partner->save();
            DB::commit();

            $partner->notify(new CreateUserNotifications($request->password));

            return redirect()->route('partners.index')->with('success', __('Saved.'));

        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Error') . ': ' . __('Failed to save'));
        }
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id, $partner_id)
    {
        $partner = Partner::where('id', $partner_id)->where('user_id', $user_id)->firstOrFail();
        $partner_user = $partner->user;
        $title = __('Edit Partner Account: Account', ['account' => $partner_user->name]);
        return view('partners.user.create', compact('title', 'partner', 'partner_user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $partner_id)
    {
        $partner = Partner::where('id', $partner_id)->where('user_id', $user_id)->firstOrFail();
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($partner->user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['boolean'],
        ])->validate();

        $user = $partner->user;
        $user->name = $request->name;
        $user->email = $request->email;
        if (!is_null($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->status;

        return ($user->save())
            ? redirect()->route('partners.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $partner_id)
    {
        $partner = Partner::where('id', $partner_id)->where('user_id', $user_id)->firstOrFail();

        return ($partner->user->delete())
            ? redirect()->route('partners.index')->with('success', __('Deleted.'))
            : redirect()->back()->with('error', __('Error'));
    }
}
