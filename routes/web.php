<?php

use Illuminate\Support\Facades\Route;

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
    return \Inertia\Inertia::render('Welcome');
});


Route::get('/invest', [\App\Http\Controllers\InvestController::class, 'index']);
Route::get('/invest/history', [\App\Http\Controllers\InvestController::class, 'history']);
Route::resource('/invest/futures', \App\Http\Controllers\Invest\FuturesController::class);
