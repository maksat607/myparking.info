<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Notifications\CreateUserNotifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserService
{
    public function roles()
    {
        return Role::whereNotIn('name', ['Partner', 'PartnerOperator'])
            ->pluck('name')
            ->reject(function ($value, $key) {
                return $value == 'SuperAdmin';
            })->all();
    }

    public function store(Request $request)
    {
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
            'email_verified_at' => now(),
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
        return $user;
    }

    protected function addRoleToRequest(Request &$request, $is_role = 'Partner', $role = 'PartnerOperator')
    {
        if (auth()->user()->hasRole($is_role)) {
            $request->request->add(['role' => $role]);
        }
    }

    /**
     * @param $user
     * @return array
     */
    public function edit($user): array
    {
        $roles = Role::query()
            ->when($user->hasRole(['Partner']), function ($query) {
                $query->where('name', 'Partner');
            })
            ->when(!$user->hasRole(['Partner', 'PartnerOperator']), function ($query) {
                $query->whereNotIn('name', ['Partner', 'PartnerOperator']);
            })
            ->pluck('name');

        $title = __('Edit user :User', ['user' => $user->name]);
        return array($roles, $title);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
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
        $user->status = $request->input('status', 0);

        if (isNotAdminRole($request->role)) {
            $userData['parent_id'] = auth()->id();
        }

        /*Возможный вариант удаления роли перед сохранением
        DB::table('model_has_roles')->where('model_id',$id)->delete();*/

        $user->roles()->detach();
        $user->assignRole($request->role);
        return $user;
    }


}
