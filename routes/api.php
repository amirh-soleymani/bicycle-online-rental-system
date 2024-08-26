<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BicycleController;
use App\Http\Controllers\RentController;
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

// Authentication Routes
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('logout', [AuthenticationController::class, 'logout'])
    ->middleware('auth:api');

Route::get('bicycleSearch', [RentController::class, 'bicycleSearch']);

Route::middleware('auth:api')->group(function(){
    Route::apiResource('bicycles', BicycleController::class);
    Route::post('rentBicycle', [RentController::class, 'rent']);
    Route::get('memberReport', [RentController::class, 'rentReportUser']);
    Route::get('adminReport', [RentController::class, 'rentReportAdmin']);
});



