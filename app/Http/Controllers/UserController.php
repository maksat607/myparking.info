<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Role;
use App\Models\User;
use App\Notifications\CreateUserNotifications;
use App\Notifications\UserNotification;
use App\Scopes\RoleScope;
use App\Services\MakeFormData;
use App\Services\UserService;
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
    public function __construct(public UserService $userService)
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

        $users = User::users()->with(['legals','children'])->get();
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

        $roles = $this->userService->roles();
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

        $this->authorize('issetPartnerOperator', auth()->user());
        $user = $this->userService->store($request);
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
    public function edit(User $user)
    {
        $this->authorize('updateUser', $user);
        list($roles, $title) = $this->userService->edit($user);
        return view('users.edit', compact('user', 'roles', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('updateUser', $user);
        $user = $this->userService->update($request, $user);

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
    public function destroy(User $user)
    {
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

    public function message(Request $request, User $user)
    {
        $title = "Сообщение от " . auth()->user()->getRoleNames()->first() . ' ' . auth()->user()->email;
        if ($request->appId) {
            $application = Application::find($request->appId);
            $content['app_id'] = $request->appId;
            $content['car_title'] = $application->car_title;
        }
        if ($request->message) {
            $content = array_merge(['message' => $request->message, 'title' => $title, 'id' => auth()->user()->id], $content);
            $user->notify(new UserNotification($content));
        }
        return redirect()->back()->with('success', 'Отправлено');
    }

    public function sendMessage(User $user)
    {
        $htmlRender = view('users.modals.message', compact('user'))->render();
        return response()->json(['success' => true, 'html' => $htmlRender]);
    }



}
