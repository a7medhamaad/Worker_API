<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\WorkerAuthController;
use App\Http\Controllers\ClientAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('DbBackup')
    ->prefix('auth/admin')
    ->controller(AdminController::class)
    ->group(function () {

        Route::post('login', 'login');
        Route::post('register', 'register');

        Route::middleware('auth:admin')->group(function () {
            Route::post('logout', 'logout');
            Route::post('refresh', 'refresh');
            Route::post('user-profile', 'userProfile');
        });
    });

/*
|--------------------------------------------------------------------------
| WORKER AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('DbBackup')
    ->prefix('auth/worker')
    ->controller(WorkerAuthController::class)
    ->group(function () {

        Route::post('login', 'login');
        Route::post('register', 'register');

        Route::middleware('auth:worker')->group(function () {
            Route::post('logout', 'logout');
            Route::post('refresh', 'refresh');
            Route::post('user-profile', 'userProfile');
            Route::get('verifiy/{token}', 'verifiy');
        });
    });

/*
|--------------------------------------------------------------------------
| CLIENT AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('DbBackup')
    ->prefix('auth/client')
    ->controller(ClientAuthController::class)
    ->group(function () {

        Route::post('login', 'login');
        Route::post('register', 'register');

        Route::middleware('auth:client')->group(function () {
            Route::post('logout', 'logout');
            Route::post('refresh', 'refresh');
            Route::post('user-profile', 'userProfile');
        });
    });

    Route::get('/unauthorize',function(){
        return response()->json([
            "message"=>"unauthorize"
        ],401);
    })->name('login');
