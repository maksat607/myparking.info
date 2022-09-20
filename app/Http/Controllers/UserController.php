<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Notifications\CreateUserNotifications;
use App\Notifications\UserNotification;
use App\Scopes\RoleScope;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends AppController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

//        $this->authorizeResource(User::class, 'user');


        $this->middleware(['permission:user_view'])->only('index', 'show');
        $this->middleware(['permission:user_create'])->only('create', 'store');
        $this->middleware(['permission:user_update'])->only('edit', 'update');
        $this->middleware(['permission:user_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::users()->with('legals')->get();
        $title = __('Users');

        if ($users->isEmpty()) {
            return view('users.empty', compact('title'));
        }

        return view('users.index', compact('users', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('issetPartnerOperator', auth()->user());
        $roles = Role::whereNotIn('name', ['Partner', 'PartnerOperator'])
            ->pluck('name')
            ->reject(function ($value, $key) {
                return $value == 'SuperAdmin';
            })->all();
        $title = __('Create new user');

        return view('users.create', compact('roles', 'title'));
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
//        dd(11);
        $this->authorize('issetPartnerOperator', auth()->user());
        $this->addRoleToRequest($request);
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required'],
            'role' => ['exists:roles,name', 'required'],
            'status' => ['boolean'],
        ])->validate();

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->input('status', 0),
        ];

        if (isNotAdminRole($request->role)) {
            $userData['parent_id'] = auth()->id();
        }

        $user = User::create($userData);
        $user->roles()->detach();
        $user->assignRole($request->role);

        $user->notify(new CreateUserNotifications($request->password));

        $user->email_verified_at = Carbon::now();
        $user->save();
        return ($user->exists)
            ? redirect()->route('users.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = User::user($id)->firstOrFail();
        $this->authorize('viewUser', $user);
        $title = __('View user :User', ['user' => $user->name]);

        return view('users.show', compact('user', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::user($id)->firstOrFail();
        $this->authorize('updateUser', $user);
        $roles = Role::query()
            ->when($user->hasRole(['Partner']), function ($query) {
                $query->where('name', 'Partner');
            })
            ->when(!$user->hasRole(['Partner', 'PartnerOperator']), function ($query) {
                $query->whereNotIn('name', ['Partner', 'PartnerOperator']);
            })
            ->pluck('name');

        $title = __('Edit user :User', ['user' => $user->name]);

        return view('users.edit', compact('user', 'roles', 'title'));
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
        $user = User::user($id)->firstOrFail();
        $this->authorize('updateUser', $user);

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['required'],
            'role' => ['exists:roles,name'],
            'status' => ['boolean'],
        ])->validate();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if (!is_null($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->input('status', 0);;

        if (isNotAdminRole($request->role)) {
            $userData['parent_id'] = auth()->id();
        }

        /*Возможный вариант удаления роли перед сохранением
        DB::table('model_has_roles')->where('model_id',$id)->delete();*/

        $user->roles()->detach();
        $user->assignRole($request->role);

        return ($user->save())
            ? redirect()->route('users.index')->with('success', __('Updated.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::user($id)->firstOrFail();
        $this->authorize('deleteUser', $user);
        if (Gate::denies('delete_self', $user)) {
            return redirect()->back()->with('warning', __('You are not allowed to delete it.'));
        }
        $user->roles()->detach();
        $user->legals()->delete();

        return ($user->delete())
            ? redirect()->back()->with('success', __('Deleted.'))
            : redirect()->back()->with('error', __('Error'));

    }

    protected function addRoleToRequest(Request &$request, $is_role = 'Partner', $role = 'PartnerOperator')
    {
        if (auth()->user()->hasRole($is_role)) {
            $request->request->add(['role' => $role]);
        }
    }

    public function allUserParking($id)
    {
        $user = User::user($id)->firstOrFail();
        $managerParkings = $user->managerParkings;
        $title = __('Parking lots of the user :User', ['user' => $user->name]);

        return view('users.parking.index', compact('managerParkings', 'title'));
    }

    public function notifications()
    {
        $title = 'Уведомление';
        return view('users.notifications.index', compact('title'));
    }
    public function message(Request $request,User $user)
    {
        $title = "Сообщение";
        if(auth()->user()->hasRole('SuperAdmin')){
            $title = "Сообщение от суперадмина";
        }
        if(auth()->user()->hasRole('Admin')){
            $title = "Сообщение от администратора";
        }

        if($request->message){
            $user->notify(new UserNotification(['message'=>$request->message,'title'=>$title]));
        }
        return redirect()->back()->with('success', 'Отправлено');
    }
    public function sendMessage(User $user)
    {
        $htmlRender = view('users.modals.message', compact('user'))->render();
        return response()->json(['success' => true, 'html' => $htmlRender]);
    }
}
