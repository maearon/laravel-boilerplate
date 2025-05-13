<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticPagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\MicropostsController;
use App\Http\Controllers\RelationshipsController;
use App\Http\Controllers\PasswordResetsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Static pages
Route::get('/', [StaticPagesController::class, 'home'])->name('root');
Route::get('/help', [StaticPagesController::class, 'help'])->name('help');
Route::get('/about', [StaticPagesController::class, 'about'])->name('about');
Route::get('/contact', [StaticPagesController::class, 'contact'])->name('contact');

// Users
Route::resource('users', UsersController::class);
Route::get('/signup', [UsersController::class, 'create'])->name('signup');
Route::get('/users/{user}/following', [UsersController::class, 'following'])->name('users.following');
Route::get('/users/{user}/followers', [UsersController::class, 'followers'])->name('users.followers');
Route::get('/account/activate/{token}', [UsersController::class, 'activate'])->name('account.activate');

// Sessions
Route::get('/login', [SessionsController::class, 'create'])->name('login');
Route::post('/login', [SessionsController::class, 'store'])->name('login.store');
Route::delete('/logout', [SessionsController::class, 'destroy'])->name('logout');

// Microposts
Route::resource('microposts', MicropostsController::class, ['only' => ['store', 'destroy']]);

// Relationships
Route::post('/relationships', [RelationshipsController::class, 'store'])->name('relationships.store');
Route::delete('/relationships', [RelationshipsController::class, 'destroy'])->name('relationships.destroy');

// Password resets
Route::get('/password/reset', [PasswordResetsController::class, 'create'])->name('password.email');
Route::post('/password/email', [PasswordResetsController::class, 'store'])->name('password.email.store');
Route::get('/password/reset/{token}', [PasswordResetsController::class, 'edit'])->name('password.reset');
Route::post('/password/reset', [PasswordResetsController::class, 'update'])->name('password.update');
