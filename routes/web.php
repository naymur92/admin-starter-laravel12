<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // users
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {   // 'middleware' => ['role:super-admin'], 
        Route::put('change-status/{user}', [UserController::class, 'changeStatus'])->name('change-status');
        Route::get('change-password/{user}', [UserController::class, 'changePassword'])->name('change-password');
        Route::put('update-password/{user}', [UserController::class, 'updatePassword'])->name('update-password');
    });

    // user profile
    Route::group(['prefix' => 'user-profile', 'as' => 'user-profile.'], function () {
        Route::get('/', [UserProfileController::class, 'userProfile'])->name('show');
        Route::get('/edit', [UserProfileController::class, 'editUserProfile'])->name('edit');
        Route::put('/update', [UserProfileController::class, 'updateUserProfile'])->name('update');
        Route::get('/change-password', [UserProfileController::class, 'changePassword'])->name('change-password');
        Route::put('/update-password', [UserProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('/change-profile-picture', [UserProfileController::class, 'changeProfilePicture'])->name('change-profile-picture');
    });

    // permissions
    Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
    });

    // roles
    Route::resource('roles', RoleController::class)->except(['create', 'edit']);
    Route::get('roles/{role}/json', [RoleController::class, 'getJson'])->name('roles.getjson');
});

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
]);

Route::post('oauth-admin-app/token', [AccessTokenController::class, 'issueToken'])->middleware(['throttle:60,2']);
