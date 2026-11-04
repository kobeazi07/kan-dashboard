<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetaController;


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



    Route::get('admin_maps', [DashboardController::class, 'admin_maps'])->name('HalamanAdminmaps');


    Route::get('/peta', [PetaController::class, 'index'])
        ->name('peta.index');

    Route::get('/kawasan/{kawasan}', [PetaController::class, 'show'])
        ->name('kawasan.show');

    Route::get('/bangunan/{bangunan}', [PetaController::class, 'detail'])
        ->name('bangunan.detail');

    // sementara masukkan admin kesini 
    Route::resource('kawasan', DashboardController::class);

    Route::resource(
        'kawasan.bangunan',
        DashboardController::class
    );

    Route::put(
        'bangunan/{bangunan}/position',
        [DashboardController::class, 'updatePosition']
    );

    Route::post(
        'bangunan/{bangunan}/foto',
        [DashboardController::class, 'storeFoto']
    );

    Route::delete(
        'foto-bangunan/{fotoBangunan}',
        [DashboardController::class, 'destroyFoto']
    );
});
