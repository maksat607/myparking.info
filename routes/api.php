<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Auth\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:api','prefix' => 'v1'], function ($router) {
    Route::post('/me', [AuthController::class, 'me']);
    Route::apiResource('applications', \App\Http\Controllers\Api\ApiApplicationController::class,array("as" => "api"));
});

//Route::middleware('auth:api')->get('/users', function (Request $request) {
//    return $request->user();
//});
