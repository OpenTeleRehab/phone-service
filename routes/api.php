<?php

use App\Http\Controllers\PhoneController;
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

Route::apiResource('phone', PhoneController::class);
Route::get('get-phone', [PhoneController::class, 'getPhone']);
Route::get('get-phone-by-org', [PhoneController::class, 'getPhoneByOrg']);
Route::post('phone/delete/by-clinic', [PhoneController::class, 'deleteByClinicId']);
