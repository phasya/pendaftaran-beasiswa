<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BeasiswaController as AdminBeasiswaController;
use App\Http\Controllers\Admin\PendaftarController as AdminPendaftarController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/persyaratan', 'persyaratan')->name('persyaratan');
    Route::get('/status', 'checkStatus')->name('status');
    Route::get('/resubmit/{pendaftar}', 'editForResubmit')
        ->name('pendaftar.resubmit')
        ->middleware('auth');
    Route::put('/resubmit/{pendaftar}', 'resubmit')
        ->name('pendaftar.resubmit.store')
        ->middleware('auth');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(PendaftarController::class)->group(function () {
    Route::get('/beasiswa/{beasiswa}/daftar', 'create')->name('pendaftar.create');
    Route::post('/beasiswa/{beasiswa}/daftar', 'store')->name('pendaftar.store');
    Route::resource('beasiswa', BeasiswaController::class);

    // Protected routes for logged-in users
    Route::middleware('auth')->group(function () {
        Route::get('/pendaftar/{pendaftar}', 'show')->name('pendaftar.show');
        Route::get('/pendaftar/{pendaftar}/document/{documentKey}', 'downloadDocument')
            ->name('pendaftar.download-document');
    });
});

Route::get('/test-404', function () {
    abort(404);
})->name('test.404');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // DashboardController
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AdminBeasiswaController
    Route::resource('beasiswa', AdminBeasiswaController::class);

    // AdminPendaftarController
    Route::controller(AdminPendaftarController::class)->group(function () {
        Route::resource('pendaftar', AdminPendaftarController::class)->except(['create', 'store', 'edit', 'update']);
        Route::get('/pendaftar/{pendaftar}/rejection-history', 'getRejectionHistory')
            ->name('pendaftar.rejection-history');
        Route::patch('/pendaftar/{pendaftar}/status', 'updateStatus')->name('pendaftar.update-status');
    });

    Route::post('admin/beasiswa/{beasiswa}/fix-data', [BeasiswaController::class, 'fixData'])
        ->name('admin.beasiswa.fix-data');

    // Route sementara untuk fix data
    Route::get('admin/fix-beasiswa/{id}', function ($id) {
        $beasiswa = \App\Models\Beasiswa::find($id);
        if ($beasiswa) {
            $beasiswa->autoFixCorruptedData();
            return "Fixed beasiswa ID: " . $id;
        }
        return "Beasiswa not found";
    });

    // Route untuk beasiswa cleaning (tambahkan setelah resource routes beasiswa)
    Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {

        // Existing beasiswa resource routes
        Route::resource('beasiswa', 'App\Http\Controllers\Admin\BeasiswaController');

        // NEW: Additional routes untuk cleaning data
        Route::prefix('beasiswa')->group(function () {
            // Clean duplicate data untuk beasiswa tertentu
            Route::post('{beasiswa}/clean', [App\Http\Controllers\Admin\BeasiswaController::class, 'clean'])
                ->name('admin.beasiswa.clean');

            // Analyze duplicate data untuk beasiswa tertentu (untuk debugging)
            Route::get('{beasiswa}/analyze', [App\Http\Controllers\Admin\BeasiswaController::class, 'analyze'])
                ->name('admin.beasiswa.analyze');

            // Bulk clean semua beasiswa
            Route::post('bulk-clean', [App\Http\Controllers\Admin\BeasiswaController::class, 'bulkClean'])
                ->name('admin.beasiswa.bulk-clean');
            // Di routes/web.php, tambahkan:
            Route::post('admin/beasiswa/{beasiswa}/clean', [BeasiswaController::class, 'clean'])->name('admin.beasiswa.clean');
            Route::get('admin/beasiswa/{beasiswa}/analyze', [BeasiswaController::class, 'analyze'])->name('admin.beasiswa.analyze');
            Route::post('admin/beasiswa/bulk-clean', [BeasiswaController::class, 'bulkClean'])->name('admin.beasiswa.bulk-clean');
        });
    });
});