<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authorization Routes
// Route::post('register', [AuthController::class, 'register'])->middleware(['throttle:60,1', 'api.headers']);
Route::prefix('auth')->group(function () {
    Route::post('/token', [AuthController::class, 'issueToken'])->middleware(['throttle:60,1', 'api.token.headers']);
    Route::post('/token/refresh', [AuthController::class, 'refresh'])->middleware(['throttle:60,1', 'api.token.headers']);
});


// Protected routes
Route::middleware('auth:api')->group(function () {

    // test route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
