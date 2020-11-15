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

Route::post('/company/profile/upload', [App\Http\Controllers\UserCompanyController::class, 'uploadGoogleDrive'])->name('uploadDrive');
Route::post('/company/profile/createFolder', [App\Http\Controllers\UserCompanyController::class, 'createFolderGoogleDrive'])->name('createFolder');
Route::post('/company/profile/deleteFile', [App\Http\Controllers\UserCompanyController::class, 'deleteFileGoogleDrive'])->name('deleteFile');
Route::get('/company/profile/data', [App\Http\Controllers\UserCompanyController::class, 'showProfileData'])->name('showProfileData');
Route::post('/company/profile/data/update/password',[App\Http\Controllers\UserCompanyController::class, 'updateProfilePassword'])->name('updateProfilePassword');
Route::post('/company/profile/data/update',[App\Http\Controllers\UserCompanyController::class, 'updateProfileData'])->name('updateProfileData');
Route::post('/company/profile/addEmployee',[App\Http\Controllers\UserCompanyController::class, 'addEmployee'])->name('addEmployee');
Auth::routes(['verify'=>true]);

Route::get('/company/profile/', [App\Http\Controllers\UserCompanyController::class, 'index'])->name('home')->middleware('verified');

Route::get('/login/admin', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm']);
Route::get('/login/company', [App\Http\Controllers\Auth\LoginController::class, 'showCompanyLoginForm'])->name('company');
Route::get('/login/employee', [App\Http\Controllers\Auth\LoginController::class,'showEmployeeLoginForm'])->name('employee');

Route::post('/login/admin', [App\Http\Controllers\Auth\LoginController::class,'adminLogin']);
Route::post('/login/company', [App\Http\Controllers\Auth\LoginController::class,'companyLogin']);
Route::post('/login/employee', [App\Http\Controllers\Auth\LoginController::class,'employeeLogin']);

Route::get('/login/company/verifySuccess', [App\Http\Controllers\UserCompanyController::class, 'showVerifySuccess'])->name('OvereniHotovo');

Route::post('/company/profile/uploadImage',[App\Http\Controllers\UserCompanyController::class, 'uploadImage'])->name('uploadImage');

Route::post('/company/profile/deleteOldImage',[App\Http\Controllers\UserCompanyController::class, 'deleteOldImage'])->name('deleteOldImage');

Route::group(['middleware' => 'auth:employee'], function () {
    Route::view('/employee', '/home_user');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::view('/admin', 'admin');
});

Route::get('/logout', [App\Http\Controllers\HomeController::class, 'loggedOut']);


