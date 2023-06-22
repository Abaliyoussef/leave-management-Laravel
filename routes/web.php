<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\loginController;
use App\Http\Controllers\auth\registerController;
use App\Http\Controllers\auth\passwordResetController;

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

Route::get('/forgot-password',[passwordResetController::class, 'forgotPassword'] )->name('password.request');
Route::post('/forgot-password',[passwordResetController::class, 'sendEmail'] )->name('password.request');

Route::get('/reset-password/{token}', [passwordResetController::class,'showResetForm'])->name('password.reset');
Route::post('/reset-password',[passwordResetController::class, 'resetPassword'] )->name('password.update');



