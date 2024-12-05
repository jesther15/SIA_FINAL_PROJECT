<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogInController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LogInController::class, 'login']);
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [LogInController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/{id}', [DashboardController::class, 'show']);
    Route::post('/dashboard', [DashboardController::class, 'create']);
    Route::delete('/dashboard/{id}', [DashboardController::class, 'delete']);
    Route::post('/dashboard/{id}', [DashboardController::class, 'update']);
    Route::get('/update_resume/{id}', [DashboardController::class, 'show']);
    Route::post('/add_application', [DashboardController::class, 'add_application']);
    Route::post('/delete_application', [DashboardController::class, 'delete_application']);

    Route::get('/', function () {
        return Auth::check() ? redirect('/dashboard') : redirect('/login');
    });

    Route::get('/add_resume', function () {
        $user = Auth::user()->name;

        return view('add_resume2', compact('user'));
    });
});
