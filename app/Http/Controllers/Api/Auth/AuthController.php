<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {

//        JWTAuth::factory()->setTTL(1440000);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token = auth()->guard('api')->setTTL(7200)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $user->token = $token;

        return new UserResource($user);

    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully logged out.'], 200);
    }

}
