<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends AppController
{
    public function edit()
    {
        $user = auth()->user();
        $title = __('Profile');

        return view('profile.edit', compact('user', 'title'));
    }

    public function update(Request $request)
    {
//        $request->dd();
        $user = \auth()->user();

        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['filled', 'string', 'email', 'max:255',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value !== $user->email) {
                        $fail(trans('validation.not_change'));
                    }
                }],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ])->validate();


        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->partner->moderation = $request->has('moderation');
        $user->partner->save();

        if(!is_null($request->password)) {
            $user->password = Hash::make($request->password);
        }

        return ($user->save())
            ? redirect()->back()->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }
}
