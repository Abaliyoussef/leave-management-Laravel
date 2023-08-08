<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\loginController;
use App\Http\Controllers\auth\registerController;
use App\Http\Controllers\auth\passwordResetController;
use App\Http\Controllers\users\UsersManagementController;
use App\Http\Controllers\departement\departementManagementController;
use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\manager\managerController;
use App\Http\Controllers\holiday\HolidayController;
use App\Http\Controllers\conges\congeController;
use App\Http\Controllers\employe\EmployeController;

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
    return view('auth.login');
});
//geust
Route::get('/login',[loginController::class, 'login'] )->name('login');
Route::post('/login',[loginController::class, 'authenticate'] )->name('login');
Route::get('/register',[registerController::class, 'register'] )->name('register');
Route::post('/register',[registerController::class, 'store'] )->name('register');
Route::get('/logout',[loginController::class, 'logout'] )->name('logout');
Route::get('/forgot-password',[passwordResetController::class, 'forgotPassword'] )->name('password.request');
Route::post('/forgot-password',[passwordResetController::class, 'sendEmail'] )->name('password.request');
Route::get('/reset-password/{token}', [passwordResetController::class,'showResetForm'])->name('password.reset');
Route::post('/reset-password',[passwordResetController::class, 'resetPassword'] )->name('password.update');



//admin + manager
Route::middleware(['auth', 'hasRole:admin,manager'])->group(function () {
Route::get('/users/index',[UsersManagementController::class, 'index'] )->name('users.index');
Route::get('users/search',[UsersManagementController::class, 'searchUsers'] )->name('users.search');

});


//admin
Route::middleware(['auth', 'hasRole:admin'])->group(function () {

Route::get('/users/create',[UsersManagementController::class, 'create'] )->name('users.create');
Route::post('/users/create',[UsersManagementController::class, 'store'] )->name('users.store');
Route::get('/users/edit/{id}',[UsersManagementController::class, 'edit'] )->name('users.edit');
Route::put('/users/update/{id}',[UsersManagementController::class, 'update'] )->name('users.update');
Route::delete('/users/delete/{id}',[UsersManagementController::class, 'destroy'] )->name('users.destroy');
Route::resource('departements', departementManagementController::class);
Route::get('/departs/search',[departementManagementController::class, 'searchDeparts'] )->name('admin.departements.search');
Route::get('/users/desactivated',[UsersManagementController::class, 'getAllDesactivatedUsers'] )->name('admin.users.desactives');
Route::get('/users/desactivated/search',[UsersManagementController::class, 'searchDesactivatedUsers'] )->name('admin.users.desactives.search');
Route::post('/users/activate/{user}',[UsersManagementController::class, 'activate'] )->name('admin.users.activate');
Route::post('/users/desactivate/{user}',[UsersManagementController::class, 'desactivate'] )->name('admin.users.desactivate');
Route::get('/users/new-registred',[UsersManagementController::class, 'getAllNotVerifiedUsers'] )->name('admin.users.new-registred');
Route::get('/users/new-registred/search',[UsersManagementController::class, 'searchNotVerifiedUsers'] )->name('admin.users.new-registred.search');
Route::post('/users/verify/{user}',[UsersManagementController::class, 'verify'] )->name('admin.users.new-registred.verify');

});

// user + admin + manager
Route::middleware(['auth'])->group(function () {
Route::put('/profile/{id}',[registerController::class, 'updateProfil'] )->name('profile.update');
Route::get('/profile',[registerController::class, 'profile'] )->name('profile');

});


//manager +  user
Route::middleware(['auth', 'hasRole:user,manager'])->group(function () {
Route::get('/conge/create/{id}',[congeController::class, 'createConge'] )->name('conge.create');
Route::post('/conge/create',[congeController::class, 'storeConge'] )->name('conge.store');
Route::put('/conge/update/{id}',[congeController::class, 'updateConge'] )->name('conge.update');
Route::get('/generate-decision/{id}', [congeController::class,'generateDecision'])->name('conge.decision');
Route::get('/generate-pv/{id}', [congeController::class,'generatePV'])->name('conge.procesVerbal');
Route::get('/generate-decision-ar/{id}', [congeController::class,'generateDecisionArabic'])->name('conge.decision.ar');
Route::get('/generate-pv-ar/{id}', [congeController::class,'generatePVArabic'])->name('conge.procesVerbal.ar');
Route::delete('/conge/delete/{id}',[congeController::class, 'delete'] )->name('conge.delete');
Route::resource('holidays', HolidayController::class);

});
//manager
Route::middleware(['auth', 'hasRole:manager'])->group(function () {

Route::get('/suggestion/create/{id}',[congeController::class, 'createSuggestion'] )->name('suggestion.create');
Route::get('/conge/nouveau-demande',[congeController::class, 'allPendingConges'] )->name('manager.conge.new-demands');
Route::get('/conge/nouveau-demande/search',[congeController::class, 'searchAllPendingConges'] )->name('manager.conge.new-demands.search');
Route::get('/conge/all-demande',[congeController::class, 'allNotPendingConges'] )->name('manager.conge.all-demands');
Route::get('/conge/all-demande/search',[congeController::class, 'searchAllNotPendingConges'] )->name('manager.conge.all-demands.search');
Route::get('/conge/inProgress-conges',[congeController::class, 'inProgressConges'] )->name('manager.conge.inProgressConges');
Route::get('/conge/archived-conges',[congeController::class, 'archivedConges'] )->name('manager.conge.archived');
Route::get('/conge/archived-conges/search',[congeController::class, 'searchArchivedConges'] )->name('manager.conge.archived.search');
Route::get('/conge/demandes-annulation',[congeController::class, 'demandeAnnulationConges'] )->name('manager.conge.demandes-annulation');
Route::get('/conge/demandes-annulation/search',[congeController::class, 'searchdemandeAnnulationConges'] )->name('manager.conge.demandes-annulation.search');

});

//employe
Route::middleware(['auth', 'hasRole:user'])->group(function () {

Route::get('/mesConges/{id}',[congeController::class, 'userNotPendingConges'] )->name('employe.Allconges');
Route::get('/mespropositiondeconges/{id}',[congeController::class, 'userSuggestedConges'] )->name('employe.conge.proposition');
Route::get('/mescongesexpires/{id}',[congeController::class, 'userExpiredConges'] )->name('employe.conge.expires');
Route::get('/employe-holidays', [HolidayController::class,'holidays'])->name('employe.holidays');
Route::get('/generate-demande/{id}', [congeController::class,'generateDemande'])->name('conge.demande');

});




