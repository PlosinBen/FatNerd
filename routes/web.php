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
    return redirect()->route('invest.index');

    return \Inertia\Inertia::render('Welcome');
});


Route::resource('/invest', \App\Http\Controllers\InvestController::class);

Route::resource('/futures', \App\Http\Controllers\Invest\FuturesController::class)
    ->parameter('futures', 'investFutures');

Route::group(['prefix' => '/about'], function () {
    Route::get('privacy', [\App\Http\Controllers\AboutController::class, 'privacy']);
});
