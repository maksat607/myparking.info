<?php

use App\Http\Controllers\Api\ApiApplicationController;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

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
Route::post('/v1/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function ($router) {
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
//    Route::get('applications', ApiApplicationController::class,array("as" => "api"));

    Route::get('/applications/{status_id?}', [ApiApplicationController::class, 'index'])
        ->where('status_id', '[0-9]+')
        ->middleware(['check_legal', 'check_child_owner_legal']);
    Route::get('/applications/create/{application?}', [apiApplicationController::class, 'create'])
        ->middleware(['check_legal', 'check_child_owner_legal']);
    Route::post('/applications/store', [apiApplicationController::class, 'store'])
        ->middleware(['check_legal', 'check_child_owner_legal']);
    Route::post('/application/{application}/upload', [apiApplicationController::class, 'addPhotos']);
    Route::get('/application/check-duplicate', [apiApplicationController::class, 'checkDuplicate'])
        ->middleware(['check_legal', 'check_child_owner_legal']);


    Route::post('image', [\App\Http\Controllers\Api\ImageController::class, 'imageStore']);

});
