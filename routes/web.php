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

    Route::get('admin_kawasan', [DashboardController::class, 'admin_kawasan'])->name('HalamanAdminkawasan');
    Route::post('/tambah_kawasan', [DashboardController::class, 'tambah_kawasan'])->name('Tambah_kawasan');
    Route::post('/edit_kawasan/{id}', [DashboardController::class, 'edit_kawasan'])->name('Edit_kawasan');
    Route::delete('/kawasan/{kawasan}', [DashboardController::class, 'kawasan_destroy'])->name('kawasan.destroy');



    Route::get('admin_bangunan', [DashboardController::class, 'admin_bangunan'])->name('HalamanAdminbangunan');
    Route::post('/tambah_bangunan', [DashboardController::class, 'tambah_bangunan'])->name('Tambah_bangunan');
    Route::post('/edit_bangunan/{id}', [DashboardController::class, 'edit_bangunan'])->name('Edit_bangunan');
    Route::delete('/bangunan/{bangunan}', [DashboardController::class, 'bangunan_destroy'])->name('bangunan.destroy');
    Route::delete('/bangunan/detail-picture/{id}', [DashboardController::class, 'deletePicturebangunan'])->name('bangunan.delete-picture');



    // Route::get(
    //     '/admin/kawasan',
    //     [DashboardController::class, 'kawasanIndex']
    // )->name('kawasan.index');

    // Route::get(
    //     '/admin/kawasan/create',
    //     [DashboardController::class, 'kawasanCreate']
    // )->name('kawasan.create');

    // Route::post(
    //     '/admin/kawasan',
    //     [DashboardController::class, 'kawasanStore']
    // )->name('kawasan.store');

    // Route::get(
    //     '/admin/kawasan/{kawasan}/edit',
    //     [DashboardController::class, 'kawasanEdit']
    // )->name('kawasan.edit');

    // Route::put(
    //     '/admin/kawasan/{kawasan}',
    //     [DashboardController::class, 'kawasanUpdate']
    // )->name('kawasan.update');

    // Route::delete(
    //     '/admin/kawasan/{kawasan}',
    //     [DashboardController::class, 'kawasanDestroy']
    // )->name('kawasan.destroy');


    /*
|--------------------------------------------------------------------------
| ADMIN - BANGUNAN
|--------------------------------------------------------------------------
*/

    Route::get(
        '/admin/kawasan/{kawasan}/bangunan',
        [DashboardController::class, 'bangunanIndex']
    )->name('bangunan.index');

    Route::get(
        '/admin/kawasan/{kawasan}/bangunan/create',
        [DashboardController::class, 'bangunanCreate']
    )->name('bangunan.create');

    Route::post(
        '/admin/kawasan/{kawasan}/bangunan',
        [DashboardController::class, 'bangunanStore']
    )->name('bangunan.store');

    Route::get(
        '/admin/bangunan/{bangunan}/edit',
        [DashboardController::class, 'bangunanEdit']
    )->name('bangunan.edit');

    Route::put(
        '/admin/bangunan/{bangunan}',
        [DashboardController::class, 'bangunanUpdate']
    )->name('bangunan.update');

    Route::delete(
        '/admin/bangunan/{bangunan}',
        [DashboardController::class, 'bangunanDestroy']
    )->name('bangunan.destroy');


    /*
|--------------------------------------------------------------------------
| ADMIN - POSISI PIN BANGUNAN
|--------------------------------------------------------------------------
*/

    Route::put(
        '/admin/bangunan/{bangunan}/position',
        [DashboardController::class, 'updatePosition']
    )->name('bangunan.position');


    /*
|--------------------------------------------------------------------------
| ADMIN - FOTO BANGUNAN
|--------------------------------------------------------------------------
*/

    Route::post(
        '/admin/bangunan/{bangunan}/foto',
        [DashboardController::class, 'storeFoto']
    )->name('bangunan.foto.store');

    Route::delete(
        '/admin/foto-bangunan/{fotoBangunan}',
        [DashboardController::class, 'destroyFoto']
    )->name('bangunan.foto.destroy');
});
