<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\MicropostsController;
use App\Http\Controllers\Api\SessionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [SessionsController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [SessionsController::class, 'logout']);

    Route::get('/users', [UsersController::class, 'index']);
    Route::post('/users', [UsersController::class, 'store']);
    Route::get('/users/{user}', [UsersController::class, 'show']);
    Route::put('/users/{user}', [UsersController::class, 'update']);
    Route::delete('/users/{user}', [UsersController::class, 'destroy']);

    Route::get('/users/{user}/microposts', [UsersController::class, 'microposts']);
    Route::get('/users/{user}/following', [UsersController::class, 'following']);
    Route::get('/users/{user}/followers', [UsersController::class, 'followers']);

    Route::get('/microposts', [MicropostsController::class, 'index']);
    Route::post('/microposts', [MicropostsController::class, 'store']);
    Route::get('/microposts/{micropost}', [MicropostsController::class, 'show']);
    Route::put('/microposts/{micropost}', [MicropostsController::class, 'update']);
    Route::delete('/microposts/{micropost}', [MicropostsController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
