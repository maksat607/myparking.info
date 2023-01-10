<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApiUserController extends Controller
{
    public function __construct(public UserService $userService)
    {
        $this->middleware(['check_legal', 'check_child_owner_legal']);
        $this->middleware(['permission:user_view'])->only('index', 'show');
        $this->middleware(['permission:user_create'])->only('create', 'store');
        $this->middleware(['permission:user_update'])->only('edit', 'update');
        $this->middleware(['permission:user_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::users()->with(['legals','children'])->get();
        $title = __('Users');
        return response()->json(compact('users', 'title'), 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $this->authorize('issetPartnerOperator', auth()->user());
        $roles = $this->userService->roles();
//        @dump($roles);
        $title = __('Create new user');
        return response()->json(compact('roles', 'title'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('issetPartnerOperator', auth()->user());
        $user = $this->userService->store($request);
        return response()->json(['success' => __('Saved.')], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::user($id)->firstOrFail();
        $this->authorize('viewUser', $user);
        $title = __('View user :User', ['user' => $user->name]);
        return response()->json(compact('user', 'title'), 200);
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
    public function destroy(User $user)
    {
        $this->authorize('deleteUser', $user);
        if (Gate::denies('delete_self', $user)) {
            return response()->json(['warning' => __('You are not allowed to delete it.')]);
        }
        $user->roles()->detach();
        $user->legals()->delete();

        return ($user->delete())
            ? response()->json(['success' => __('Deleted.')])
            : response()->json(['error' => __('Error')]);
    }

    public function allUserParking(User $user)
    {
        $managerParkings = $user->managerParkings;
        $title = __('Parking lots of the user :User', ['user' => $user->name]);

        return response()->json(compact('managerParkings', 'title'), 200);
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
        return response()->json(['success' => 'Отправлено']);
    }

    public function sendMessage(User $user)
    {
        $htmlRender = view('users.modals.message', compact('user'))->render();
        return response()->json(['success' => true, 'html' => $htmlRender]);
    }
}
