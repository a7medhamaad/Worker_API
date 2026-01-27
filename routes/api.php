<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboard\AdminNotificationController;
use App\Http\Controllers\AdminDashboard\PostStatusController;
use App\Http\Controllers\WorkerAuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\PostController;
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

Route::get('/unauthorize', function () {
    return response()->json([
        "message" => "unauthorize"
    ], 401);
})->name('login');

Route::controller(PostController::class)->prefix('worker/post')->group(function () {
    Route::post('/add', 'store')->middleware('auth:worker');
    Route::get('/show', 'index')->middleware('auth:admin');
    Route::get('/approved', 'approved')->middleware('auth:admin');
    Route::get('/rejected', 'rejected')->middleware('auth:admin');
    Route::get('/pending', 'pending')->middleware('auth:admin');
});

Route::controller(AdminNotificationController::class)->middleware('auth:admin')->prefix('admin/notifications')->group(function () {
    Route::get('/all', 'index');
    Route::get('/unread', 'unread');
    Route::post('/markReadAll', 'markreadall');
    Route::delete('/deleteall', 'deletedAll');
    Route::delete('/delete/{id}',  'delete');
});

Route::middleware('auth:admin')->controller(PostStatusController::class)->prefix('admin/post')->group(function () {
    Route::post('/changestatus', 'changeStatus');
});
