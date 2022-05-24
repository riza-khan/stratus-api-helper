<?php

use App\Http\Controllers\PfizerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/save-pfizer-token', [PfizerController::class, 'token'])->name('save-pfizer-token');

    Route::prefix('configuration')->group(function () {
        Route::get('/', [PfizerController::class, 'getConfiguration'])
            ->name('get-configuration');
        Route::put('/', [PfizerController::class, 'updateConfiguration'])
            ->name('update-configuration');
    });

    Route::prefix('email-template')->group(function () {
        Route::get('/', [PfizerController::class, 'getEmailTemplate'])
            ->name('get-email-template');
        Route::put('/', [PfizerController::class, 'updateEmailTemplate'])
            ->name('update-email-template');
    });
});
