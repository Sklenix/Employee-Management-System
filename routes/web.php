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

/* Uvodni strana zobrazeni hlavni stranky plus odeslani emailu skrze formular */
Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');
Route::post('/welcome/send',[App\Http\Controllers\WelcomeController::class, 'send'])->name('sendEmail');

/* Sekce pro zobrazeni formularu pro prihlaseni*/
Route::get('/login/admin', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])->name('showAdminLoginForm');
Route::get('/login/company', [App\Http\Controllers\Auth\LoginController::class, 'showCompanyLoginForm'])->name('company');
Route::get('/login/employee', [App\Http\Controllers\Auth\LoginController::class,'showEmployeeLoginForm'])->name('employee');

/* Samotne provedeni prihlaseni */
Route::post('/login/admin', [App\Http\Controllers\Auth\LoginController::class,'adminLogin']);
Route::post('/login/company', [App\Http\Controllers\Auth\LoginController::class,'companyLogin']);
Route::post('/login/employee', [App\Http\Controllers\Auth\LoginController::class,'employeeLogin']);

/* Sekce pro logout a pro overovaci stranku */
Auth::routes(['verify'=>true]);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'loggedOut']);

Route::group(['middleware' => 'auth:company'], function () {
    /* ---------------Dashboard operace------------------ */
    /* AJAX v Dashboardu */
    Route::get('/employeesDashboard/show', [\App\Http\Controllers\UserCompanyController::class, 'getAllEmployees'])->name('dashboard.getAllEmployees');
    Route::get('/shiftsDashboard/show', [\App\Http\Controllers\UserCompanyController::class, 'getAllShifts'])->name('dashboard.getAllShifts');
    Route::get('/dashboard/googleFilesCheckboxes/show', [\App\Http\Controllers\UserCompanyController::class, 'getAllGoogleDriveFilesCheckboxes'])->name('dashboard.getAllGoogleDriveFiles');
    Route::get('/dashboard/googleFoldersOptions/show', [\App\Http\Controllers\UserCompanyController::class, 'getAllGoogleDriveFoldersOptions'])->name('dashboard.getAllGoogleDriveFoldersOptions');

    /* Datatable dovolenych zamestnancu */
    Route::get('/company/employees/vacations', [\App\Http\Controllers\VacationsController::class, 'index'])->name('vacations.index');
    Route::get('/company/employees/vacations/list', [\App\Http\Controllers\VacationsController::class, 'getEmployeeVacations'])->name('vacations.list');
    Route::resource('/company/employees/VacationActions', \App\Http\Controllers\VacationsController::class);
    Route::put('/company/employees/vacation/agreed/{id}', [\App\Http\Controllers\VacationsController::class, 'vacationAgree'])->name('vacations.vacationAgree');
    Route::put('/company/employees/vacation/disagreed/{id}', [\App\Http\Controllers\VacationsController::class, 'vacationDisagree'])->name('vacations.vacationDisagree');
    Route::put('/company/employees/vacation/seen/{id}', [\App\Http\Controllers\VacationsController::class, 'vacationSeen'])->name('vacations.vacationSeen');
    Route::put('/company/employees/vacation/sent/{id}', [\App\Http\Controllers\VacationsController::class, 'vacationSent'])->name('vacations.vacationSent');

    /* Datatable nemocenskych zamestnancu */
    Route::get('/company/employees/diseases', [\App\Http\Controllers\DiseasesController::class, 'index'])->name('diseases.index');
    Route::get('/company/employees/diseases/list', [\App\Http\Controllers\DiseasesController::class, 'getEmployeeDiseases'])->name('diseases.list');
    Route::resource('/company/employees/DiseaseActions', \App\Http\Controllers\DiseasesController::class);
    Route::put('/company/employees/disease/agreed/{id}', [\App\Http\Controllers\DiseasesController::class, 'diseaseAgree'])->name('diseases.diseaseAgree');
    Route::put('/company/employees/disease/disagreed/{id}', [\App\Http\Controllers\DiseasesController::class, 'diseaseDisagree'])->name('diseases.diseaseDisagree');
    Route::put('/company/employees/disease/seen/{id}', [\App\Http\Controllers\DiseasesController::class, 'diseaseSeen'])->name('diseases.diseaseSeen');
    Route::put('/company/employees/disease/sent/{id}', [\App\Http\Controllers\DiseasesController::class, 'diseaseSent'])->name('diseases.diseaseSent');

    /* Datatable nahlaseni zamestnancu */
    Route::get('/company/employees/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/company/employees/reports/list', [\App\Http\Controllers\ReportsController::class, 'getEmployeeReports'])->name('reports.list');
    Route::resource('/company/employees/ReportActions', \App\Http\Controllers\ReportsController::class);
    Route::put('/company/employees/report/agreed/{id}', [\App\Http\Controllers\ReportsController::class, 'reportAgree'])->name('reports.reportAgree');
    Route::put('/company/employees/report/disagreed/{id}', [\App\Http\Controllers\ReportsController::class, 'reportDisagree'])->name('reports.reportDisagree');
    Route::put('/company/employees/report/seen/{id}', [\App\Http\Controllers\ReportsController::class, 'reportSeen'])->name('reports.reportSeen');
    Route::put('/company/employees/report/sent/{id}', [\App\Http\Controllers\ReportsController::class, 'reportSent'])->name('reports.reportSent');

    /* Generator souboru v menu */
    Route::get('/company/generator', [\App\Http\Controllers\FileGeneratorController::class, 'index'])->name('generator.index');
    Route::get('/company/generator/generateEmployees', [\App\Http\Controllers\FileGeneratorController::class, 'generateEmployeesList'])->name('generator.employeesList');
    Route::get('/company/generator/generateShifts', [\App\Http\Controllers\FileGeneratorController::class, 'generateShiftsList'])->name('generator.shiftsList');
    Route::get('/company/generator/generateCompanyProfile', [\App\Http\Controllers\FileGeneratorController::class, 'generateCompanyProfile'])->name('generator.companyProfile');
    Route::get('/company/generator/generateEmployeeShifts', [\App\Http\Controllers\FileGeneratorController::class, 'generateEmployeeShifts'])->name('generator.generateEmployeeShifts');
    Route::get('/company/generator/generateShiftEmployees', [\App\Http\Controllers\FileGeneratorController::class, 'generateShiftEmployees'])->name('generator.generateShiftEmployees');
    Route::get('/company/generator/generateEmployeesRatings', [\App\Http\Controllers\FileGeneratorController::class, 'generateEmployeesRatings'])->name('generator.generateEmployeesRatings');
    Route::get('/company/generator/generateEmployeeCurrentShifts', [\App\Http\Controllers\FileGeneratorController::class, 'generateEmployeeCurrentShifts'])->name('generator.generateEmployeeCurrentShifts');

    /* Odebrani zamestnance v menu */
    Route::post('/company/profile/Employee',[App\Http\Controllers\UserCompanyController::class, 'deleteEmployee'])->name('dashboard.deleteEmployee');

    /* Odebrani smen(y) v menu */
    Route::post('/company/profile/deleteShift',[App\Http\Controllers\UserCompanyController::class, 'deleteShift'])->name('dashboard.deleteShift');

    /* Pridani smeny v menu */
    Route::post('/company/profile/addShift',[App\Http\Controllers\UserCompanyController::class, 'addShift'])->name('addShift');

    /* Jazyky */
    Route::post('/company/profile/addLanguage',[App\Http\Controllers\UserCompanyController::class, 'addLanguage'])->name('addLanguage');
    Route::post('/company/profile/removeLanguage',[App\Http\Controllers\UserCompanyController::class, 'removeLanguage'])->name('removeLanguage');
    /* ------------Dashboard operace konec--------------- */

    /* Zmena profilove fotky zamestnance v seznamu zaměstnanců */
    Route::post('/company/profile/uploadImageProfileEmployee',[App\Http\Controllers\EmployeeDatatableController::class, 'uploadImageEmployeeProfile'])->name('uploadImageProfileEmployee');
    Route::post('/company/profile/deleteOldImageProfileEmployee',[App\Http\Controllers\EmployeeDatatableController::class, 'deleteOldImageEmployeeProfile'])->name('deleteOldImageEmployeeProfile');

    /* Samostatny datatable pro zobrazeni hodnoceni zamestnancu */
    Route::get('/company/employees/ratings', [\App\Http\Controllers\RatingDatatableController::class, 'index'])->name('ratings.index');
    Route::get('/company/employees/ratings/list', [\App\Http\Controllers\RatingDatatableController::class, 'getRatings'])->name('ratings.list');
    Route::get('/company/employees/ratings/rate/{id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'editRate'])->name('ratings.rate');
    Route::put('/company/employees/ratings/rate/edit/{id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'updateRate'])->name('ratings.rateedit');

    /* Hodnoceni zamestnance v datatable */
    Route::get('/company/employees/rate/{id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'editRate'])->name('employees.rate');
    Route::put('/company/employees/rate/edit/{id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'updateRate'])->name('employees.rateedit');

    /* Prirazeni smen k zamestnancovi v datatable zamestnancu */
    Route::get('/company/employees/assign/{id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'assignShift'])->name('employees.assign');
    Route::put('/company/employees/assign/edit/{id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'updateassignShift'])->name('employees.assignedit');

    /* Datatable akce ke zranenim */
    Route::get('/company/injuries', [\App\Http\Controllers\InjuriesDatatableController::class, 'index'])->name('injuries.index');
    Route::get('/company/injuries/list', [\App\Http\Controllers\InjuriesDatatableController::class, 'getInjuries'])->name('injuries.list');
    Route::post('/company/injuries/options/shift', [\App\Http\Controllers\InjuriesDatatableController::class, 'getEmployeeShiftsSelect'])->name('injuries.selectShift');
    Route::get('/company/injuries/get/shift/start/{shift_id}', [\App\Http\Controllers\InjuriesDatatableController::class, 'getShiftStart'])->name('injuries.getShiftStart');
    Route::resource('/company/injuriesactions', \App\Http\Controllers\InjuriesDatatableController::class);

    /* Datatable akce k zamestnancum */
    Route::get('/company/employees', [\App\Http\Controllers\EmployeeDatatableController::class, 'index'])->name('employees.index');
    Route::get('/company/employees/list', [\App\Http\Controllers\EmployeeDatatableController::class, 'getEmployees'])->name('employees.list');
    Route::resource('/company/employeesactions', \App\Http\Controllers\EmployeeDatatableController::class);

    /* Datatable akce k dochazce */
    Route::get('/company/attendance', [\App\Http\Controllers\EmployeeAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/company/attendance/list/{id}', [\App\Http\Controllers\EmployeeAttendanceController::class, 'getAttendance'])->name('attendance.list');
    Route::resource('/company/attendanceactions', \App\Http\Controllers\EmployeeAttendanceController::class);
    Route::get('/attendance/option/{id}/{zamestnanec_id}', [\App\Http\Controllers\EmployeeAttendanceController::class, 'getAttendanceOptions'])->name('attendance.getAttendanceOptions');

    /* Datatable akce ke smenam */
    Route::resource('/company/shiftsactions', \App\Http\Controllers\ShiftDatatableController::class);
    Route::get('/company/shifts', [\App\Http\Controllers\ShiftDatatableController::class, 'index'])->name('shifts.index');
    Route::get('/company/shifts/list', [\App\Http\Controllers\ShiftDatatableController::class, 'getShifts'])->name('shifts.list');

    /* Prirazeni zamestnancu ke smene v datatable smen */
    Route::get('/company/shifts/assign/{id}', [\App\Http\Controllers\ShiftDatatableController::class, 'assignEmployee'])->name('shifts.assign');
    Route::put('/company/shifts/assign/edit/{id}', [\App\Http\Controllers\ShiftDatatableController::class, 'updateassignEmployee'])->name('shifts.assignedit');

    /* Zobrazeni moznosti dochazky v datatable smen */
    Route::get('/shift/attendance/options/{id}', [\App\Http\Controllers\ShiftDatatableController::class, 'getAttendanceOptions'])->name('shifts.getAttendanceOptions');

    /* Zobrazeni nadokna pro checkin, checkout, absenci a poznamku*/
    Route::get('/shift/attendance/options/checkin/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\ShiftDatatableController::class, 'showCheckin'])->name('shifts.showCheckin');
    Route::get('/shift/attendance/options/checkout/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\ShiftDatatableController::class, 'showCheckOut'])->name('shifts.showCheckOut');
    Route::get('/shift/attendance/options/absence/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\ShiftDatatableController::class, 'showAbsence'])->name('shifts.showAbsence');
    Route::get('/shift/attendance/options/note/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\ShiftDatatableController::class, 'showAttendanceNote'])->name('shifts.showAttendanceNote');

    /* Ulozeni check-in do databaze */
    Route::put('/shift/attendance/options/checkin/update/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\ShiftDatatableController::class, 'updateCheckIn'])->name('shifts.updateCheckIn');
    /* Ulozeni check-out do databaze*/
    Route::put('/shift/attendance/options/checkout/update/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\ShiftDatatableController::class, 'updateCheckOut'])->name('shifts.updateCheckOut');
    /* Ulozeni absence do databaze */
    Route::put('/shift/attendance/options/absence/update/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\ShiftDatatableController::class, 'updateAbsence'])->name('shifts.updateAbsence');
    /* Ulozeni poznamky do databaze */
    Route::put('/shift/attendance/options/note/update/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\ShiftDatatableController::class, 'updateAttendanceNote'])->name('shifts.updateAttendanceNote');

    /* Zobrazeni moznosti dochazky v datatable zamestnancu */
    Route::get('/employee/attendance/options/{id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'getAttendanceOptions'])->name('employee.getAttendanceOptions');

    /* Zobrazeni nadokna pro checkin, checkout, absenci a poznamku (datatable zamestnancu)*/
    Route::get('/employee/attendance/options/checkin/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'showCheckin'])->name('employee.showCheckin');
    Route::get('/employee/attendance/options/checkout/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'showCheckOut'])->name('employee.showCheckOut');
    Route::get('/employee/attendance/options/absence/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'showAbsence'])->name('employee.showAbsence');
    Route::get('/employee/attendance/options/note/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'showAttendanceNote'])->name('employee.showAttendanceNote');

    /* Ulozeni check-in do databaze (datatable zamestnancu) */
    Route::put('/employee/attendance/options/checkin/update/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'updateCheckIn'])->name('employee.updateCheckIn');
    /* Ulozeni check-out do databaze (datatable zamestnancu) */
    Route::put('/employee/attendance/options/checkout/update/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'updateCheckOut'])->name('employee.updateCheckOut');
    /* Ulozeni absence do databaze (datatable zamestnancu) */
    Route::put('/employee/attendance/options/absence/update/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'updateAbsence'])->name('employee.updateAbsence');
    /* Ulozeni poznamky do databaze (datatable zamestnancu) */
    Route::put('/employee/attendance/options/note/update/{zamestnanec_id}/{smena_id}', [\App\Http\Controllers\EmployeeDatatableController::class, 'updateAttendanceNote'])->name('employee.updateAttendanceNote');

    /* Statistiky */
    Route::get('/company/statistics', [\App\Http\Controllers\StatisticsController::class, 'index'])->name('statistics.index');

    /* Smazani uctu firmy */
    Route::post('/company/profile/delete', [App\Http\Controllers\UserCompanyController::class, 'deleteCompanyProfile'])->name('deleteCompanyProfile');

    /* Statistiky */
    /* Zmena roku u grafu novych zamestnancu dle mesicu */
    Route::get('/company/statistics/employee/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeEmployeeGraphYear'])->name('changeEmployeeGraphYear');
    /* Zmena roku u grafu zapsanych smen dle mesicu*/
    Route::get('/company/statistics/shift/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeShiftGraphYear'])->name('changeShiftGraphYear');
    /* Zmena roku u grafu analyzy dochazky */
    Route::get('/company/statistics/attendances/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeAttendanceGraphYear'])->name('changeAttendanceGraphYear');
    /* Zmena roku u grafu prirazenych smen */
    Route::get('/company/statistics/shiftsassigned/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeShiftsAssignedGraphYear'])->name('changeShiftsAssignedGraphYear');
    /* Zmena roku u grafu celkovych hodin smen dle mesicu */
    Route::get('/company/statistics/shiftstotalhours/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeShiftsTotalHoursGraphYear'])->name('changeShiftsTotalHoursGraphYear');
    /* Zmena roku u grafu celkove odpracovanych hodin v ramci smen dle mesicu */
    Route::get('/company/statistics/shiftstotalworkedhours/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeShiftsTotalWorkedHoursGraphYear'])->name('changeShiftsTotalWorkedHoursGraphYear');
    /* Zmena roku u grafu celkovych zpozdenich v hodinach v ramci smen dle mesicu */
    Route::get('/company/statistics/shiftstotallatehours/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeShiftsTotalLateHoursGraphYear'])->name('changeShiftsTotalLateHoursGraphYear');
    /* Zmena roku u grafu poctu zpozdenich v ramci smen dle mesicu */
    Route::get('/company/statistics/lateflagscount/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeShiftsLateFlagsCountGraphYear'])->name('changeShiftsLateFlagsCountGraphYear');
    /* Zmena roku u grafu poctu zraneni v ramci smen dle mesicu */
    Route::get('/company/statistics/injuriesflagscount/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeShiftsInjuriesFlagsCountGraphYear'])->name('changeShiftsInjuriesFlagsCountGraphYear');
    /* Zmena roku u grafu poctu dovolenych v ramci smen dle mesicu */
    Route::get('/company/statistics/vacations/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeVacationsGraphYear'])->name('changeVacationsGraphYear');
    /* Zmena roku u grafu poctu nemocenskych v ramci smen dle mesicu */
    Route::get('/company/statistics/diseases/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeDiseasesGraphYear'])->name('changeDiseasesGraphYear');
    /* Zmena roku u grafu poctu nahlasenich v ramci smen dle mesicu */
    Route::get('/company/statistics/reports/chart/year/{rok}', [App\Http\Controllers\StatisticsController::class, 'changeReportsGraphYear'])->name('changeReportsGraphYear');

    Route::post('/company/profile/upload', [App\Http\Controllers\UserCompanyController::class, 'uploadGoogleDrive'])->name('uploadDrive');
    Route::post('/company/profile/createFolder', [App\Http\Controllers\UserCompanyController::class, 'createFolderGoogleDrive'])->name('createFolder');
    Route::post('/company/profile/deleteFile', [App\Http\Controllers\UserCompanyController::class, 'deleteFileGoogleDrive'])->name('deleteFile');
    Route::get('/company/profile', [App\Http\Controllers\UserCompanyController::class, 'showCompanyProfileData'])->name('showCompanyProfileData');
    Route::post('/company/profile/update/password',[App\Http\Controllers\UserCompanyController::class, 'updateProfilePassword'])->name('updateProfilePassword');
    Route::post('/company/profile/update',[App\Http\Controllers\UserCompanyController::class, 'updateProfileData'])->name('updateProfileData');
    Route::post('/company/profile/addEmployee',[App\Http\Controllers\UserCompanyController::class, 'addEmployee'])->name('addEmployee');

    Route::get('/company/dashboard/', [App\Http\Controllers\UserCompanyController::class, 'index'])->name('home')->middleware('verified');

    Route::get('/login/company/verifySuccess', [App\Http\Controllers\UserCompanyController::class, 'showVerifySuccess'])->name('OvereniHotovo');
    Route::post('/company/profile/uploadImage',[App\Http\Controllers\UserCompanyController::class, 'uploadImage'])->name('uploadImage');
    Route::post('/company/profile/deleteOldImage',[App\Http\Controllers\UserCompanyController::class, 'deleteOldImage'])->name('deleteOldImage');

});

Route::group(['middleware' => 'auth:employee'], function () {
    /* Datatable aktualnich smen */
    Route::get('/employee/currentShifts', [\App\Http\Controllers\EmployeeCurrentShiftsController::class, 'index'])->name('shifts.currentShiftsEmployee');
    Route::get('/employee/shifts/current', [\App\Http\Controllers\EmployeeCurrentShiftsController::class, 'getEmployeeCurrentShifts'])->name('shifts.getCurrentEmployeeShiftsList');
    Route::get('/employee/currentshiftActions/{shift_id}', [\App\Http\Controllers\EmployeeCurrentShiftsController::class, 'showCurrentShiftDetail'])->name('shifts.showCurrentShiftDetail');
    Route::put('/employee/checkin/update/{smena_id}', [\App\Http\Controllers\EmployeeCurrentShiftsController::class, 'updateEmployeeCheckin'])->name('employee.updateEmployeeCheckin');
    Route::put('/employee/checkout/update/{smena_id}', [\App\Http\Controllers\EmployeeCurrentShiftsController::class, 'updateEmployeeCheckout'])->name('employee.updateEmployeeCheckout');

    /* Datatable dovolenych zamestnancu */
    Route::get('/employee/vacations', [\App\Http\Controllers\EmployeeVacationController::class, 'index'])->name('employee_vacations.index');
    Route::get('/employee/vacations/list', [\App\Http\Controllers\EmployeeVacationController::class, 'getEmployeeVacations'])->name('employee_vacations.list');
    Route::resource('/employee/VacationActionsEmployee', \App\Http\Controllers\EmployeeVacationController::class);
    Route::put('/employee/vacation/apply/{id}', [\App\Http\Controllers\EmployeeVacationController::class, 'vacationApply'])->name('employee_vacations.vacationApply');
    Route::put('/employee/vacation/deleteApply/{id}', [\App\Http\Controllers\EmployeeVacationController::class, 'vacationDeleteApply'])->name('employee_vacations.vacationDeleteApply');

    /* Datatable nemocenskych zamestnancu */
    Route::get('/employee/diseases', [\App\Http\Controllers\EmployeeDiseaseController::class, 'index'])->name('employee_diseases.index');
    Route::get('/employee/diseases/list', [\App\Http\Controllers\EmployeeDiseaseController::class, 'getEmployeeDiseases'])->name('employee_diseases.list');
    Route::resource('/employee/DiseaseActionsEmployee', \App\Http\Controllers\EmployeeDiseaseController::class);
    Route::put('/employee/disease/apply/{id}', [\App\Http\Controllers\EmployeeDiseaseController::class, 'diseaseApply'])->name('employee_diseases.diseaseApply');
    Route::put('/employee/disease/deleteApply/{id}', [\App\Http\Controllers\EmployeeDiseaseController::class, 'diseaseDeleteApply'])->name('employee_diseases.diseaseDeleteApply');

    /* Datatable nahlaseni zamestnancu */
    Route::get('/employee/reports', [\App\Http\Controllers\EmployeeReportController::class, 'index'])->name('employee_reports.index');
    Route::get('/employee/reports/list', [\App\Http\Controllers\EmployeeReportController::class, 'getEmployeeReports'])->name('employee_reports.list');
    Route::resource('/employee/ReportActionsEmployee', \App\Http\Controllers\EmployeeReportController::class);
    Route::put('/employee/report/apply/{id}', [\App\Http\Controllers\EmployeeReportController::class, 'reportApply'])->name('employee_reports.reportApply');
    Route::put('/employee/report/deleteApply/{id}', [\App\Http\Controllers\EmployeeReportController::class, 'reportDeleteApply'])->name('employee_reports.reportDeleteApply');

    /* Datatable historie zraneni */
    Route::get('/employee/injuries', [\App\Http\Controllers\EmployeeInjuriesController::class, 'index'])->name('employee_injuries.index');
    Route::get('/employee/injury/list', [\App\Http\Controllers\EmployeeInjuriesController::class, 'getEmployeeInjuries'])->name('employee_injuries.list');

    /* Statistiky */
    Route::get('/employee/statistics', [\App\Http\Controllers\EmployeeStatisticsController::class, 'index'])->name('employee_statistics.index');

    /* Datatable historie smen */
    Route::get('/employee/allShifts', [\App\Http\Controllers\EmployeeAllShiftsController::class, 'index'])->name('shifts.AllShiftsEmployee');
    Route::get('/employee/shifts/all', [\App\Http\Controllers\EmployeeAllShiftsController::class, 'getAllEmployeeShiftsList'])->name('shifts.getAllEmployeeShiftsList');

    /* Generovani souboru */
    Route::get('/employee/generator', [\App\Http\Controllers\EmployeeFileGeneratorController::class, 'index'])->name('employee_generator.index');
    Route::get('/employee/generator/generate/Vacations', [\App\Http\Controllers\EmployeeFileGeneratorController::class, 'generateVacationsList'])->name('employee_generator.vacationsList');
    Route::get('/employee/generator/generate/Diseases', [\App\Http\Controllers\EmployeeFileGeneratorController::class, 'generateDiseasesList'])->name('employee_generator.diseasesList');
    Route::get('/employee/generator/generate/Reports', [\App\Http\Controllers\EmployeeFileGeneratorController::class, 'generateReportsList'])->name('employee_generator.reportsList');
    Route::get('/employee/generator/generate/EmployeeProfile', [\App\Http\Controllers\EmployeeFileGeneratorController::class, 'generateEmployeeProfile'])->name('employee_generator.employeeprofile');
    Route::get('/employee/generator/generate/CurrentShifts', [\App\Http\Controllers\EmployeeFileGeneratorController::class, 'generateCurrentShiftsList'])->name('employee_generator.currentshiftsList');
    Route::get('/employee/generator/generate/ShiftHistory', [\App\Http\Controllers\EmployeeFileGeneratorController::class, 'generateShiftHistoryList'])->name('employee_generator.shifthistoryList');

    Route::post('/employee/createFolder', [App\Http\Controllers\UserEmployeeController::class, 'createFolderGoogleDrive'])->name('createFolderEmployee');
    Route::post('/employee/uploadFile', [App\Http\Controllers\UserEmployeeController::class, 'uploadGoogleDrive'])->name('uploadDriveEmployee');
    Route::post('/employee/deleteFile', [App\Http\Controllers\UserEmployeeController::class, 'deleteFileGoogleDrive'])->name('deleteFileEmployee');
    Route::get('/dashboard/googleFilesCheckboxes/employee/show/', [\App\Http\Controllers\UserEmployeeController::class, 'getAllGoogleDriveFilesCheckboxes'])->name('dashboard.getAllGoogleDriveFilesEmployee');
    Route::get('/dashboard/googleFoldersOptions/employee/show/', [\App\Http\Controllers\UserEmployeeController::class, 'getAllGoogleDriveFoldersOptions'])->name('dashboard.getAllGoogleDriveFoldersOptionsEmployee');
    Route::get('/employee/dashboard/', [App\Http\Controllers\UserEmployeeController::class, 'index'])->name('homeEmployee');

    /* Smazani uctu zamestnance */
    Route::post('/employee/profile/delete', [App\Http\Controllers\UserEmployeeController::class, 'deleteEmployeeProfile'])->name('deleteEmployeeProfile');
    Route::get('/employee/profile/', [App\Http\Controllers\UserEmployeeController::class, 'showEmployeeProfileData'])->name('showEmployeeProfileData');
    Route::post('/employee/profile/update',[App\Http\Controllers\UserEmployeeController::class, 'updateEmployeeProfileData'])->name('updateEmployeeProfileData');
    Route::post('/employee/profile/update/password',[App\Http\Controllers\UserEmployeeController::class, 'updateEmployeeProfilePassword'])->name('updateEmployeeProfilePassword');
    Route::post('/company/profile/uploadEmployeeImage',[App\Http\Controllers\UserEmployeeController::class, 'uploadEmployeeImage'])->name('uploadEmployeeImage');
    Route::post('/company/profile/deleteEmployeeOldImage',[App\Http\Controllers\UserEmployeeController::class, 'deleteEmployeeOldImage'])->name('deleteEmployeeOldImage');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin/dashboard/', [App\Http\Controllers\UserAdminController::class, 'index'])->name('homeAdmin');

    /* Smazani uctu admina */
    Route::post('/admin/profile/delete', [App\Http\Controllers\UserAdminController::class, 'deleteAdminProfile'])->name('deleteAdminProfile');

    Route::get('/admin/profile/', [App\Http\Controllers\UserAdminController::class, 'showAdminProfileData'])->name('showAdminProfileData');
    Route::post('/admin/profile/update',[App\Http\Controllers\UserAdminController::class, 'updateAdminProfileData'])->name('updateAdminProfileData');
    Route::post('/admin/profile/update/password',[App\Http\Controllers\UserAdminController::class, 'updateAdminProfilePassword'])->name('updateAdminProfilePassword');

    /* Datatable firem */
    Route::get('/admin/companies', [\App\Http\Controllers\AdminCompaniesDatatable::class, 'index'])->name('admin_companies.index');
    Route::get('/admin/companies/list', [\App\Http\Controllers\AdminCompaniesDatatable::class, 'getCompanies'])->name('admin_companies.list');
    Route::resource('/admin/CompanyActions', \App\Http\Controllers\AdminCompaniesDatatable::class);

    /* Generovani souboru */
    Route::get('/admin/generator', [\App\Http\Controllers\AdminFileGeneratorController::class, 'index'])->name('admin_generator.index');
    Route::get('/admin/generator/generate/companies', [\App\Http\Controllers\AdminFileGeneratorController::class, 'generateCompaniesList'])->name('admin_generator.companiesList');

    /* Statistiky */
    Route::get('/admin/statistics', [\App\Http\Controllers\AdminStatisticsController::class, 'index'])->name('admin_statistics.index');
});
