<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@login');

//public API-s

Route::get('/contract-types', 'App\Http\Controllers\ContractTypeController@index');
Route::get('/educations', 'App\Http\Controllers\EducationController@index');
Route::get('/employer-categories', 'App\Http\Controllers\EmployerCategoryController@index');
Route::get('/job-categories', 'App\Http\Controllers\JobCategoryController@index');
Route::get('/job-subcategories', 'App\Http\Controllers\JobSubCategoryController@index');
Route::get('/kantones', 'App\Http\Controllers\KantoneController@index');
Route::get('/languages', 'App\Http\Controllers\LanguageController@index');
Route::get('/plzs', 'App\Http\Controllers\PlzController@index');
Route::get('/prices', 'App\Http\Controllers\PriceController@index');
Route::get('/jobs', 'App\Http\Controllers\JobController@index');
Route::get('/jobs/{slug}', 'App\Http\Controllers\JobController@showPublicJob');


Route::get('/soft-skills', 'App\Http\Controllers\SoftSkillController@index');
// Route::put('/soft-skills/{id}', 'App\Http\Controllers\SoftSkillController@update');
Route::post('/soft-skills', 'App\Http\Controllers\SoftSkillController@store');
Route::get('/employe-file/{path}', 'App\Http\Controllers\EmployeeController@getEmployeeFile');
Route::get('/get-file', 'App\Http\Controllers\EmployerController@getEmployerFile');
Route::get('/employer-profile/{slug}', 'App\Http\Controllers\EmployerController@getPublicEmployerProfile');

Route::post('/send-reset-password-link', 'App\Http\Controllers\UserController@sendPassowrdResetLink');
Route::get('/show-password-reset-form/{token}', 'App\Http\Controllers\UserController@showPasswordResetForm');
Route::post('/reset-forgot-password', 'App\Http\Controllers\UserController@resetForgotPassword');
Route::get('/verify-account/{token}', 'App\Http\Controllers\UserController@verifyAccount');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/deactivate-account', 'App\Http\Controllers\UserController@deactivateUserAccount');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', "user-role:admin"]], function () {
    Route::get('/employees', 'App\Http\Controllers\EmployeeController@index');
    Route::get('/employers', 'App\Http\Controllers\EmployerController@index');

    Route::post('/soft-skills', 'App\Http\Controllers\SoftSkillController@store');
    Route::put('/soft-skills/{id}', 'App\Http\Controllers\SoftSkillController@update');
    Route::delete('/soft-skills/{id}', 'App\Http\Controllers\SoftSkillController@destroy');

    Route::post('/education', 'App\Http\Controllers\EducationController@store');
    Route::put('/education/{id}', 'App\Http\Controllers\EducationController@update');
    Route::delete('/education/{id}', 'App\Http\Controllers\EducationController@destroy');

    Route::get('/employers', 'App\Http\Controllers\EmployerController@getAllEmployers');
    Route::put('/activate-employers/{id}', 'App\Http\Controllers\EmployerController@activateEmployerProfile');
    Route::get('/non-active-employers', 'App\Http\Controllers\EmployerController@getNoneActivatedEmployers');
    
    Route::get('/jobs-last-month', 'App\Http\Controllers\JobController@getLastMonthJobs');

    Route::get('/employer-jobs/{id}', 'App\Http\Controllers\JobController@getJobsOfEmployer');
    Route::get('/employer-job-costs/{id}', 'App\Http\Controllers\JobController@getCostOfEmployerJob');
    Route::put('/store-payments', 'App\Http\Controllers\JobController@storePayments');

    Route::post('/admin-account', 'App\Http\Controllers\UserController@createAdmin');
    Route::get('/admin-account', 'App\Http\Controllers\UserController@getAdmins');
    Route::delete('/admin-account/{id}', 'App\Http\Controllers\UserController@deleteAdminAccount');
    
});

Route::group(['prefix' => 'employee', 'middleware' => ['auth:api', "user-role:employee"]], function () {
    Route::get('/', 'App\Http\Controllers\EmployeeController@show');
    Route::put('/', 'App\Http\Controllers\EmployeeController@update');
    Route::delete('/', 'App\Http\Controllers\EmployeeController@destroy');
    Route::post('/upload-image', 'App\Http\Controllers\EmployeeController@uploadProfileImage');
    Route::get('/get-file', 'App\Http\Controllers\EmployeeController@getEmployeeFile');
    //soft-skills
    Route::get('/soft-skills', 'App\Http\Controllers\EmployeeSoftSkillController@index');
    Route::post('/soft-skills', 'App\Http\Controllers\EmployeeSoftSkillController@store');
    Route::put('/soft-skills', 'App\Http\Controllers\EmployeeSoftSkillController@update');
    Route::delete('/soft-skills', 'App\Http\Controllers\EmployeeSoftSkillController@destroy');
    //contract-types
    Route::get('/contract-types', 'App\Http\Controllers\EmployeeContractTypeController@index');
    Route::post('/contract-types', 'App\Http\Controllers\EmployeeContractTypeController@store');
    Route::put('/contract-types', 'App\Http\Controllers\EmployeeContractTypeController@update');
    Route::delete('/contract-types', 'App\Http\Controllers\EmployeeContractTypeController@destroy');
    //educations
    Route::get('/educations', 'App\Http\Controllers\EmployeeEducationController@index');
    Route::get('/educations/{id}', 'App\Http\Controllers\EmployeeEducationController@show');
    Route::post('/educations', 'App\Http\Controllers\EmployeeEducationController@store');
    Route::put('/educations/{id}', 'App\Http\Controllers\EmployeeEducationController@update');
    Route::delete('/educations/{id}', 'App\Http\Controllers\EmployeeEducationController@destroy');
    //job-sub-categories
    Route::get('/job-sub-categories', 'App\Http\Controllers\EmployeeJobSubCategoryController@index');
    Route::post('/job-sub-categories', 'App\Http\Controllers\EmployeeJobSubCategoryController@store');
    Route::put('/job-sub-categories', 'App\Http\Controllers\EmployeeJobSubCategoryController@update');
    Route::delete('/job-sub-categories', 'App\Http\Controllers\EmployeeJobSubCategoryController@destroy');
    //languages
    Route::get('/languages', 'App\Http\Controllers\EmployeeLanguageController@index');
    Route::post('/languages', 'App\Http\Controllers\EmployeeLanguageController@store');
    Route::put('/languages', 'App\Http\Controllers\EmployeeLanguageController@update');
    Route::delete('/languages', 'App\Http\Controllers\EmployeeLanguageController@destroy');
    //work_experiences
    Route::get('/work_experiences', 'App\Http\Controllers\EmployeeExperienceController@index');
    Route::post('/work_experiences', 'App\Http\Controllers\EmployeeExperienceController@store');
    Route::put('/work_experiences/{id}', 'App\Http\Controllers\EmployeeExperienceController@update');
    Route::delete('/work_experiences/{id}', 'App\Http\Controllers\EmployeeExperienceController@destroy');
    // favorite job
    Route::get('/favorite-jobs', 'App\Http\Controllers\JobFavouriteController@index');
    Route::put('/favorite-jobs/{id}', 'App\Http\Controllers\JobFavouriteController@update');
    // documents
    Route::get('/documents', 'App\Http\Controllers\EmployeeDocumentController@index');
    Route::get('/documents/{id}', 'App\Http\Controllers\EmployeeDocumentController@show');
    Route::post('/documents', 'App\Http\Controllers\EmployeeDocumentController@store');
    Route::delete('/documents/{id}', 'App\Http\Controllers\EmployeeDocumentController@destroy');

    // contact requests
    Route::get('/contact_requests', 'App\Http\Controllers\ContactedEmployeeController@getContactRequests');
    Route::post('/contact_requests', 'App\Http\Controllers\ContactedEmployeeController@employeeContactResponse');
    Route::get('/suggested-jobs', 'App\Http\Controllers\EmployeeController@getSuggestedJobs');
});

Route::group(['prefix' => 'employer', 'middleware' => ['auth:api', "user-role:employeradmin,employer"]], function () {
    Route::get('/', 'App\Http\Controllers\EmployerController@show');
    Route::put('/', 'App\Http\Controllers\EmployerController@update');
    Route::delete('/', 'App\Http\Controllers\EmployerController@destroy');
    Route::post('/upload-image', 'App\Http\Controllers\EmployerController@uploadProfileImage');
    Route::get('/get-file', 'App\Http\Controllers\EmployerController@getEmployerFile');
    Route::post('/create-employer', 'App\Http\Controllers\EmployerController@createEmployer');
    Route::get('/get-employer-list', 'App\Http\Controllers\EmployerController@getCorrespondingEmployers');
    Route::delete('/delete-employer/{id}', 'App\Http\Controllers\EmployerController@deleteCorrespondingEmployerAccount');
    
    // jobs
    Route::get('/jobs', 'App\Http\Controllers\JobController@getEmployerJobs');
    Route::post('/jobs', 'App\Http\Controllers\JobController@store');
    Route::post('/jobs/{id}', 'App\Http\Controllers\JobController@update');
    Route::get('/jobs/{id}', 'App\Http\Controllers\JobController@show');
    Route::delete('/jobs/{id}', 'App\Http\Controllers\JobController@destroy');

    Route::get('/job/employee-annonymous-resume/{id}', 'App\Http\Controllers\EmployerController@getAnnonymousEmployeeProfile');
    // uopdate job soft skills
    Route::put('/jobs/{id}/soft-skills', 'App\Http\Controllers\JobSoftSkillController@update');
    //update job contract types
    Route::put('/jobs/{id}/contract-types', 'App\Http\Controllers\JobContractTypeController@update');
    //update job languages
    Route::put('/jobs/{id}/languages', 'App\Http\Controllers\JobLanguageController@update');
    //update job educations
    Route::put('/jobs/{id}/educations', 'App\Http\Controllers\JobEducationController@update');
    //contact employee
    Route::post('/contact-employee', 'App\Http\Controllers\ContactedEmployeeController@store');
    Route::get('/years-costs', 'App\Http\Controllers\JobCostController@getEmployerYearsCosts');
    Route::post('/support-email', 'App\Http\Controllers\SupportEmailsController@create');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:api']], function () {
    Route::post('/reset-password', 'App\Http\Controllers\UserController@resetPassword');
    Route::get('/logout', 'App\Http\Controllers\UserController@logout');
});
