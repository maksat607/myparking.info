<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

//use Spatie\Permission\Models\Role;

class PermissionController extends AppController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:permission_update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->reject(function ($item) {
            return str_starts_with($item->name, 'el_');
        });

        $title = __('Permissions');
        return view('permissions.index', compact('roles', 'permissions', 'title'));
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sync(Request $request)
    {
        $data = $request->except(['_token', 'buttons']);
//        $exists = Role::with('permissions')->get()->filterPermissions($request->has('buttons'));

        if($request->has('buttons')){
            return redirect()->back()->with('success', __('Saved.'));
        }

        $roles = Role::all();
        foreach ($roles as $role) {
            if (array_key_exists($role->name, $data)) {
                $role->syncPermissions(
                    $data[$role->name]
//                    array_merge(
//                        $data[$role->name],
//                        $exists[$role->name] ?? []
//                    )
                );
            } else {
                $role->syncPermissions([]);
            }
        }

        return redirect()->back()->with('success', __('Saved.'));
    }

    public function buttons()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->filter(function ($item) {
            return str_starts_with($item->name, 'el_');
        });
        $title = __('Permissions');
        $type = 'buttons';
        return view('permissions.index', compact('roles', 'permissions', 'title', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Permission::create([
            'name' => 'el_' . $request->element . "_" . $request->code,
            'guard_name' => 'web',
            'ru' => $request->text
        ]);
        return redirect()->back();
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
