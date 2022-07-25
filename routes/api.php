<?php

use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\PfizerController;
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


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/save-pfizer-token', [PfizerController::class, 'token'])
        ->name('save-pfizer-token');

    Route::prefix('configuration')->group(function () {
        Route::get('/', [ConfigurationController::class, 'getConfiguration'])
            ->name('get-configuration');
        Route::post('/', [ConfigurationController::class, 'createConfiguration'])
            ->name('create-configuration');
        Route::put('/', [ConfigurationController::class, 'updateConfiguration'])
            ->name('update-configuration');
    });

    Route::prefix('email-template')->group(function () {
        Route::get('/', [EmailTemplateController::class, 'getEmailTemplate'])
            ->name('get-email-template');
        Route::put('/', [EmailTemplateController::class, 'updateEmailTemplate'])
            ->name('update-email-template');
    });

    Route::prefix('form')->group(function () {
        Route::get('/', [FormController::class, 'index'])
            ->name('get-form');
        Route::post('/', [FormController::class, 'submit'])
            ->name('submit-form');
    });
});
