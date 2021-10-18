<?php

use App\Http\Controllers\AcceptingRequestController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\IssueRequestController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerTypeController;
use App\Http\Controllers\PartnerUserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserChildrenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewRequestController;
use App\Models\Partner;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
 * Auth routes
 */
Auth::routes([
//    'register' => false,
]);

/*
 * Email verification
 */
Route::get('/email/verify', function () {
    $title = __('Verify Your Email Address');
    return view('auth.verify', compact('title'));
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('resent', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');


Route::get('/', function () {
    /*if(Auth::check()) {
        return redirect('/home');
    }*/
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('check_legal')->name('home');


/*Auth Group*/
Route::middleware(['auth', 'verified'])->group(function(){
    /*Users*/
    Route::resource('users', UserController::class)
        ->middleware(['check_legal', 'check_child_owner_legal']);
    Route::resource('users.children', UserChildrenController::class)
        ->middleware(['check_legal', 'check_child_owner_legal']);

    /*Permissions*/
    Route::resource('permissions', PermissionController::class)->only('index')
        ->middleware(['check_legal', 'check_child_owner_legal']);
    Route::post('/permissions/sync', [PermissionController::class, 'sync'])->name('permissions.sync')
        ->middleware(['check_legal', 'check_child_owner_legal']);

    /*Legal*/
//    Route::get('legal', [LegalController::class, 'edit'])->name('legal');
    Route::resource('legals', LegalController::class);
    Route::get('/legals/user/{user}/all', [LegalController::class, 'allForUser'])->name('legals.all');
    Route::get('/legals/user/{user}/legal/{legal}', [LegalController::class, 'viewForUser'])->name('legals.view');
    Route::get('/legals/parking/{parking}/all', [LegalController::class, 'allForParking'])->name('legals.parkings.all');
    Route::get('/legals/parking/{parking}/legal/{legal}', [LegalController::class, 'viewForParking'])
        ->name('legals.parkings.view');

    /*Profile*/
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    /*Parkings*/
    Route::resource('parkings', ParkingController::class);

    /*PartnerTypes*/
    Route::resource('partner-types', PartnerTypeController::class)
        ->except(['show', 'destroy']);

    /*Partners*/
    Route::resource('partners', PartnerController::class)
        ->except(['show', 'destroy']);

    Route::get('/partner-users/partner/{partner}/create', [PartnerUserController::class, 'create'])
        ->name('partner-users.create');
    Route::post('/partner-users/partner/{partner}/', [PartnerUserController::class, 'store'])
        ->name('partner-users.store');
    Route::get('/partner-users/{partner_user}/partner/{partner}/edit', [PartnerUserController::class, 'edit'])
        ->name('partner-users.edit');
    Route::put('/partner-users/{partner_user}/partner/{partner}/', [PartnerUserController::class, 'update'])
        ->name('partner-users.update');
    Route::delete('/partner-users/{partner_user}/partner/{partner}/', [PartnerUserController::class, 'destroy'])
        ->name('partner-users.destroy');

    Route::get('/partner/parkings/', [PartnerController::class, 'parkingList'])
        ->name('partner.parkings');

    Route::get('/partner/parkings/search', [PartnerController::class, 'getParkings'])
        ->name('partner.parkings.search');

    Route::post('/partner/parkings/add', [PartnerController::class, 'addParking'])
        ->name('partner.parkings.add');

    Route::delete('/partner/parkings/remove/{parking}/', [PartnerController::class, 'removeParking'])
        ->name('partner.parkings.remove');

    /*Cars select AJAX*/
    Route::get('/car/mark/list/{type_id}', [CarController::class, 'carMarkList']);
    Route::get('/car/model/list/{mark_id}', [CarController::class, 'carModelList']);
    Route::get('/car/year/list/{model_id}', [CarController::class, 'carYearList']);
    Route::get('/car/generation/list/{model_id}/{year}', [CarController::class, 'carGenerationList']);
    Route::get('/car/series/list/{model_id}/{generation_id}', [CarController::class, 'carSeriesList']);
    Route::get('/car/modification/list/{model_id}/{series_id}/{year}', [CarController::class, 'carModificationList']);
    Route::get('/car/characteristic/engine/{modification_id}', [CarController::class, 'carEngineList']);
    Route::get('/car/characteristic/transmission/{modification_id}', [CarController::class, 'carTransmissionList']);
    Route::get('/car/characteristic/gear/{modification_id}', [CarController::class, 'carGearList']);

    /*Applications*/
    Route::get('/applications/{status_id?}', [ApplicationController::class, 'index'])
        ->where('status_id', '[0-9]+')
        ->name('applications.index');
    Route::resource('applications', ApplicationController::class)->except('index');
    Route::get('/application/check-duplicate', [ApplicationController::class, 'checkDuplicate'])
        ->name('application.check-duplicate');
    Route::get('/application/accepting-request', [AcceptingRequestController::class, 'index'])
        ->name('application.accepting.request');
    Route::get('/application/deny/{application_id}', [ApplicationController::class, 'deny'])
        ->name('application.deny');
    Route::get('/application/favorite/{application}', [ApplicationController::class, 'toggleFavorite'])
        ->name('application.favorite');
    Route::get('/application/{application}/issuance/create', [ApplicationController::class, 'issuanceCreate'])
        ->name('application.issuance.create');
    Route::post('/application/{application}/issuance', [ApplicationController::class, 'issuance'])
        ->name('application.issuance');
    Route::get('/application/get-model-content/{application_id}', [ApplicationController::class, 'getModelContent'])
        ->name('application.get.model.content');

    /*Attachments*/
    Route::get('/destroy/{attachment}', [AttachmentController::class, 'destroy'])->name( 'attachment.destroy');

    /*View Request*/
    Route::get('view-requests', [ViewRequestController::class, 'index'])
        ->name( 'view_requests.index');
    Route::get('applications/{application}/view-requests/create', [ViewRequestController::class, 'create'])
        ->name( 'view_requests.create');
    Route::post('applications/{application}/view-requests', [ViewRequestController::class, 'store'])
        ->name( 'view_requests.store');
    Route::get('view-requests/{view_request}/edit', [ViewRequestController::class, 'edit'])
        ->name( 'view_requests.edit');
    Route::put('view-requests/{view_request}', [ViewRequestController::class, 'update'])
        ->name( 'view_requests.update');
    Route::delete('view-requests/{view_request}', [ViewRequestController::class, 'destroy'])
        ->name( 'view_requests.destroy');

    /*IssueRequest*/
    Route::get('issue-requests', [IssueRequestController::class, 'index'])
        ->name( 'issue_requests.index');
    Route::get('applications/{application}/issue-requests/create', [IssueRequestController::class, 'create'])
        ->name( 'issue_requests.create');
    Route::post('applications/{application}/issue-requests', [IssueRequestController::class, 'store'])
        ->name( 'issue_requests.store');
    Route::get('issue-requests/{issue_request}/edit', [IssueRequestController::class, 'edit'])
        ->name( 'issue_requests.edit');
    Route::put('issue-requests/{issue_request}', [IssueRequestController::class, 'update'])
        ->name( 'issue_requests.update');
    Route::delete('issue-requests/{issue_request}', [IssueRequestController::class, 'destroy'])
        ->name( 'issue_requests.destroy');
});
