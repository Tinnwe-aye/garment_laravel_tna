<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Raw\RawMaterialController;
use App\Http\Controllers\API\Tailor\TailorListController;
use App\Http\Controllers\API\Tailor\TailorRegistrationController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('tailor-list')->group(function () {
    Route::get('search-tailor',[TailorListController::class, 'search']);
    Route::post('delete-tailor',[TailorListController::class, 'destroy']);
});
Route::prefix('tailor-register')->group(function () {
    Route::post('register-tailor',[TailorRegistrationController::class, 'store']);
    Route::get('edit-tailor/{id}',[TailorRegistrationController::class, 'show']);
    Route::put('update-tailor/{id}',[TailorRegistrationController::class, 'update']);
});

Route::post('raw-register',[RawMaterialController::class, 'store']);



