<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RideController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/drivers', [DriverController::class, 'store']);
Route::delete('/drivers/{id}', [DriverController::class, 'destroy']);
Route::get('/drivers/{id}/get-rides', [DriverController::class, 'getOpenRides']);

Route::get('/rides/{id}', [RideController::class, 'show']);
Route::delete('/rides/{id}', [RideController::class, 'destroy']);
Route::post('/rides/request-driver', [RideController::class, 'requestDriver']);
Route::post('/rides/cancel-ride', [RideController::class, 'cancelRide']);
Route::post('/rides/accept-ride', [RideController::class, 'acceptRide']);
Route::post('/rides/refuse-ride', [RideController::class, 'refuseRide']);
Route::post('/rides/finish-ride', [RideController::class, 'finishRide']);
