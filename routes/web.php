<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

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

Route::get('/clear-cache', function(){
	Artisan::call('view:clear');
	Artisan::call('config:clear');
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
});

// Authentication Routes
Route::get('/', [LoginController::class, 'showLogin'])->name('login.show');
Route::get('/auth/github/redirect', [LoginController::class, 'handleRedirect'])->name('login.redirect');
Route::get('/auth/github/callback', [LoginController::class, 'handleLogin'])->name('login.handle');
Route::post('/logout', [LoginController::class, 'handleLogout'])->name('logout.handle');

Route::get('/home', [UserController::class, 'index'])->name('home');