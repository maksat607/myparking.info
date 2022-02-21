<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Notifications\CreateUserNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserChildrenController extends AppController
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
    public function index($id)
    {
        $user = User::role(['SuperAdmin', 'Admin', 'Partner'])->findOrFail($id);
        $children = $user->children;
        $title = __('Your Users');

        if($user->children->isEmpty()) {
            return view('users.children.empty', compact('user', 'title'));
        }

        return view('users.children.index', compact('user', 'children', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user = User::role(['SuperAdmin', 'Admin'])->findOrFail($id);
        $roles = Role::whereIn('name', ['Manager', 'Operator'])->pluck('name');
        $title = __('Create new user for :User', ['User' => $user->name]);

        return view('users.children.create', compact('user', 'roles', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'numeric'],
            'role' => ['exists:roles,name', 'required'],
            'status' => ['boolean'],
        ])->validate();

        $user = User::role(['SuperAdmin', 'Admin'])->findOrFail($id);

        $childData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'parent_id' => $id,
        ];

        $childModel = new User($childData);
        $child = $user->children()->save($childModel);

        $child->assignRole($request->role);

        $child->notify(new CreateUserNotifications($request->password));

        return ($child->exists)
            ? redirect()->route('users.children.index', ['user' => $user->id])->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $child_id)
    {
        $child = User::where('id', $child_id)->where('parent_id', $user_id)->firstOrFail();
        $title = __('View user :User', ['user' => $child->name]);

        return view('users.children.show', compact('child', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id, $child_id)
    {
        $child = User::where('id', $child_id)->where('parent_id', $user_id)->firstOrFail();
        $roles = Role::whereIn('name', ['Manager', 'Operator'])->pluck('name');
        $title = __('Edit user :User', ['user' => $child->name]);

        return view('users.children.edit', compact('child', 'roles', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $child_id)
    {
        $child = User::where('id', $child_id)->where('parent_id', $user_id)->firstOrFail();

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($child->id)],
            'phone' => ['required', 'numeric'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['exists:roles,name'],
            'status' => ['boolean'],
        ])->validate();

        $child->name = $request->name;
        $child->email = $request->email;
        $child->phone = $request->phone;
        if(!is_null($request->password)) {
            $child->password = Hash::make($request->password);
        }
        $child->status = $request->status;
        $child->parent_id = $user_id;

        $child->roles()->detach();
        $child->assignRole($request->role);

        return ($child->save())
            ? redirect()->route('users.children.index', ['user' => $user_id])->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $child_id)
    {
        $child = User::where('id', $child_id)->where('parent_id', $user_id)->firstOrFail();
        if (Gate::denies('delete_self', $child)) {
            return redirect()->back()->with('warning', __('You are not allowed to delete it.'));
        }
        $child->roles()->detach();
        return ($child->delete())
            ? redirect()->back()->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }
}
