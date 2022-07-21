<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IssueController;

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

Route::get('/clear-cache', function () {
	Artisan::call('view:clear');
	Artisan::call('config:clear');
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
});

// Authentication Routes
Route::controller(LoginController::class)->group(function () {
	Route::get('/', 'showLogin')->name('login.show');
	Route::get('/auth/github/redirect', 'handleRedirect')->name('login.redirect');
	Route::get('/auth/github/callback', 'handleLogin')->name('login.handle');
	Route::post('/logout', 'handleLogout')->name('logout.handle');
});

Route::get('/home', [UserController::class, 'index'])->name('home');

Route::controller(IssueController::class)->name('issue.')->group(function () {
	Route::get('/{repository_uid}/issues/list', 'index')->name('list');
	
	Route::prefix('issue')->group(function () {
		Route::post('/store', 'store')->name('store');
		Route::get('/edit/{issue_uid}', 'edit')->name('edit');
		Route::post('/update/{issue_uid}', 'update')->name('update');
		Route::delete('/delete/{issue_uid}', 'delete')->name('delete');
	});
});