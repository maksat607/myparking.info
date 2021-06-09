<?php

use App\Http\Controllers\LegalController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserChildrenController;
use App\Http\Controllers\UserController;
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
    Route::post('permissions/sync', [PermissionController::class, 'sync'])->name('permissions.sync')
        ->middleware(['check_legal', 'check_child_owner_legal']);

    /*Legal*/
//    Route::get('legal', [LegalController::class, 'edit'])->name('legal');
    Route::resource('legals', LegalController::class);
    Route::get('legals/user/{user}/all', [LegalController::class, 'allForUser'])->name('legals.all');
    Route::get('legals/user/{user}/legal/{legal}', [LegalController::class, 'viewForUser'])->name('legals.view');
    Route::get('legals/parking/{parking}/all', [LegalController::class, 'allForParking'])->name('legals.parkings.all');
    Route::get('legals/parking/{parking}/legal/{legal}', [LegalController::class, 'viewForParking'])
        ->name('legals.parkings.view');

    /*Profile*/
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');

    /*Parkings*/
    Route::resource('parkings', ParkingController::class);
});
