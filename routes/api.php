<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/companies/show/{company}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/companies/{ids?}', [CompanyController::class, 'index'])->name('companies.index');
Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.modify');
Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');

Route::get('/activity-query', [CompanyController::class, 'activityQuery'])->name('companies.activity-qery');
Route::get('/creation-date-query', [CompanyController::class, 'creationDateQuery'])->name('companies.creation-date-qery');

Route::fallback(function (){
    abort(404, 'API resource not found');
});
