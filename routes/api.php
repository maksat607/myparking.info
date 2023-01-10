<?php

use App\Http\Controllers\Api\ApiApplicationController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiIssueRequestController;
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

    Route::group(['prefix' => 'application'], function ($router) {
        Route::get('/{application}/issuance/create', [ApiApplicationController::class, 'issuanceCreate']);
    });
    Route::group(['prefix' => 'applications'], function ($router) {
        Route::get('/{status_id?}', [ApiApplicationController::class, 'index'])
            ->where('status_id', '[0-9]+');
        Route::get('/create/{application?}', [apiApplicationController::class, 'create']);
        Route::get('/{application}/edit', [apiApplicationController::class, 'edit']);
        Route::put('/{application}', [apiApplicationController::class, 'update']);
        Route::post('/store', [apiApplicationController::class, 'store']);
        Route::post('/{application}/upload', [apiApplicationController::class, 'addPhotos']);
        Route::get('/check-duplicate', [apiApplicationController::class, 'checkDuplicate']);
        Route::post('image', [\App\Http\Controllers\Api\ImageController::class, 'imageStore']);
        Route::get('/get-model-content/{application_id}', [ApiApplicationController::class, 'getModelContent']);
        Route::get('/get-model-content-app-chat/{application_id}', [ApiApplicationController::class, 'getModelChatContent']);
    });

    Route::resource('users', ApiUserController::class, array("as" => "api"))
        ->middleware(['check_legal', 'check_child_owner_legal']);
    Route::get('/users/{user}/parking/all', [ApiUserController::class, 'allUserParking']);
    Route::post('/users/{user}/message', [ApiUserController::class, 'message']);


    Route::get('issue-requests', [ApiIssueRequestController::class, 'index']);
    Route::get('applications/{application}/issue-requests/create', [ApiIssueRequestController::class, 'create']);
    Route::post('applications/{application}/issue-requests', [ApiIssueRequestController::class, 'store']);
    Route::get('issue-requests/{issue_request}/edit', [ApiIssueRequestController::class, 'edit']);
    Route::put('issue-requests/{issue_request}', [ApiIssueRequestController::class, 'update']);
    Route::delete('issue-requests/{issue_request}', [ApiIssueRequestController::class, 'destroy']);




});
