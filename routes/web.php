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

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
    Route::get('/login/{provider}', [\App\Http\Controllers\LoginController::class, 'show']);
    Route::get('/login/{provider}/callback', [\App\Http\Controllers\LoginController::class, 'callback'])
        ->name('login.callback');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('invest.history.index');
//        return \Inertia\Inertia::render('Welcome');
    });

    Route::group(['prefix' => 'invest', 'as' => 'invest.'], function () {

        Route::resource('/history', \App\Http\Controllers\Invest\HistoryController::class);

        Route::resource('/futures', \App\Http\Controllers\Invest\FuturesController::class)
            ->parameter('futures', 'investFutures')
            ->middleware('can:admin');
    });

});

Route::group(['prefix' => '/about'], function () {
    Route::get('privacy', [\App\Http\Controllers\AboutController::class, 'privacy']);
});
