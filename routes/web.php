<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\loginController;
use App\Http\Controllers\auth\registerController;
use App\Http\Controllers\auth\passwordResetController;
use App\Http\Controllers\admin\userManagementController;
use App\Http\Controllers\admin\departementManagementController;

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

Route::get('/', function () {
    return view('home');
});

Route::get('/login',[loginController::class, 'login'] )->name('login');
Route::post('/login',[loginController::class, 'authenticate'] )->name('login');
Route::get('/register',[registerController::class, 'register'] )->name('register');
Route::post('/register',[registerController::class, 'store'] )->name('register');
Route::get('/logout',[loginController::class, 'logout'] )->name('logout');

Route::get('/forgot-password',[passwordResetController::class, 'forgotPassword'] )->name('password.request');
Route::post('/forgot-password',[passwordResetController::class, 'sendEmail'] )->name('password.request');

Route::get('/reset-password/{token}', [passwordResetController::class,'showResetForm'])->name('password.reset');
Route::post('/reset-password',[passwordResetController::class, 'resetPassword'] )->name('password.update');

Route::get('/dashboard',[loginController::class, 'dashboard'] )->name('dashboard');

Route::resource('users', userManagementController::class);
Route::get('usrs/search',[userManagementController::class, 'searchUsers'] )->name('admin.users.search');

Route::resource('departements', departementManagementController::class);
Route::get('/departs/search',[departementManagementController::class, 'searchDeparts'] )->name('admin.departements.search');

Route::get('/usrs/desactives',[userManagementController::class, 'getAllDesactivatedUsers'] )->name('admin.users.desactives');
Route::get('/usrs/desactives/search',[userManagementController::class, 'searchDesactivatedUsers'] )->name('admin.users.desactives.search');
Route::post('/usrs/activate/{user}',[userManagementController::class, 'activate'] )->name('admin.users.activate');

Route::get('/usrs/new-registred',[userManagementController::class, 'getAllNotVerifiedUsers'] )->name('admin.users.new-registred');
Route::get('/usrs/new-registred/search',[userManagementController::class, 'searchNotVerifiedUsers'] )->name('admin.users.new-registred.search');
Route::post('/usrs/verify/{user}',[userManagementController::class, 'verify'] )->name('admin.users.new-registred.verify');

