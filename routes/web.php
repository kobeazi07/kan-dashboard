<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;



Route::get('/', [DashboardController::class, 'HalamanLogin'])->name('HalamanLogin');
Route::post('/login', [DashboardController::class, 'login'])->name('login');
Route::post('/logout', [DashboardController::class, 'user_logout'])->name('Logout');


Route::middleware(['auth', 'role:Admin|Owner'])->group(function () {
    Route::get('/HalamanDashboard', [DashboardController::class, 'index'])->name('HalamanDashboard');
    Route::get('/dashboard/progress-data', [
        DashboardController::class,
        'getProgressData'
    ])->name('dashboard.progress-data');

    // Route::get('sidebar', [DashboardController::class, 'sidebar'])->name('side');
    Route::get('admin_maps', [DashboardController::class, 'admin_maps'])->name('HalamanAdminmaps');
    Route::post('/tambah_maps', [DashboardController::class, 'tambah_maps'])->name('Tambah_maps');
    Route::post('/edit_maps/{id}', [DashboardController::class, 'edit_maps'])->name('Edit_maps');
    Route::delete('/maps/{maps}', [DashboardController::class, 'maps_destroy'])->name('maps.destroy');

    Route::get('admin_settings', [DashboardController::class, 'admin_setting'])->name('HalamanAdminsettings');
    Route::post('/edit_setting/{id}', [DashboardController::class, 'edit_setting'])->name('Edit_setting');
});
