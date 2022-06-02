<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/user-token', function () {
        return Inertia::render('UserToken');
    })->name('user-token');

    Route::get('/form-configuration', function () {
        return Inertia::render('FormConfiguration');
    })->name('form-configuration');

    Route::get('/form-submission', function () {
        return Inertia::render('FormSubmission');
    })->name('form-submission');

    Route::get('/email-template', function () {
        return Inertia::render('EmailTemplate');
    })->name('email-template');
});

require __DIR__ . '/auth.php';
