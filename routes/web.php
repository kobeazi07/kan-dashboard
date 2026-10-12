<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;



Route::get('/', [DashboardController::class, 'HalamanLogin'])->name('HalamanLogin');
Route::post('/login', [DashboardController::class, 'login'])->name('login');
Route::post('/logout', [DashboardController::class, 'user_logout'])->name('Logout');


Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/HalamanDashboard', [DashboardController::class, 'index'])->name('HalamanDashboard');
    Route::get('/dashboard/progress-data', [
        DashboardController::class,
        'getProgressData'
    ])->name('dashboard.progress-data');
});
