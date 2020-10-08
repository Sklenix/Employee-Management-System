<?php

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


Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');
Route::post('/welcome/send',[App\Http\Controllers\WelcomeController::class, 'send'])->name('sendEmail');

Auth::routes(['verify'=>true]);

Route::get('/company/profile/', [App\Http\Controllers\UserCompanyController::class, 'index'])->name('home')->middleware('verified');

Route::get('/login/admin', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm']);
Route::get('/login/company', [App\Http\Controllers\Auth\LoginController::class, 'showCompanyLoginForm'])->name('company');
Route::get('/login/employee', [App\Http\Controllers\Auth\LoginController::class,'showEmployeeLoginForm'])->name('employee');

Route::post('/login/admin', [App\Http\Controllers\Auth\LoginController::class,'adminLogin']);
Route::post('/login/company', [App\Http\Controllers\Auth\LoginController::class,'companyLogin']);
Route::post('/login/employee', [App\Http\Controllers\Auth\LoginController::class,'employeeLogin']);

Route::get('/login/company/verifySuccess', [App\Http\Controllers\UserCompanyController::class, 'showVerifySuccess'])->name('OvereniHotovo');

Route::group(['middleware' => 'auth:employee'], function () {
    Route::view('/employee', '/home_user');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::view('/admin', 'admin');
});

Route::get('/logout', [App\Http\Controllers\HomeController::class, 'loggedOut']);


