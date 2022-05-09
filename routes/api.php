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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/process-payroll/{cutoff}', [App\Api\PayrollController::class, 'processPayroll']);
Route::post('/check-status/{cutoff}', [App\Api\PayrollController::class, 'checkStatus']);
Route::post('/capture-log/{employeeNo}/{action}', [App\Api\FaceRecognition::class, 'captureLogs']);
Route::get('/payroll-summary/{cutoff}', [App\Http\Controllers\ReportController::class, 'payrollSummary']);
Route::get('/payslip/{payslip}', [App\Http\Controllers\ReportController::class, 'payslip']);
