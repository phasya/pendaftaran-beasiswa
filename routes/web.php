<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BeasiswaController as AdminBeasiswaController;
use App\Http\Controllers\Admin\PendaftarController as AdminPendaftarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes (Guest)
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/persyaratan', 'persyaratan')->name('persyaratan');
    Route::get('/status', 'checkStatus')->name('status');
    // Di routes/web.php
    Route::get('/admin/pendaftaran/{id}', [BeasiswaController::class, 'show'])->name('beasiswa.show');
});

// PDF route
Route::get('/pdf/{filename}', function ($filename) {
    $path = storage_path('app/public/pdf/' . $filename);

    if (!file_exists($path)) {
        abort(404, 'File not found.');
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
    ]);
})->name('pdf.show');

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

// Test Route
Route::get('/test-404', function () {
    abort(404);
})->name('test.404');

// Protected Routes (Authenticated Users)
Route::middleware('auth')->group(function () {

    // Pendaftar Routes
    Route::controller(PendaftarController::class)->group(function () {
        Route::get('/beasiswa/{beasiswa}/daftar', 'create')->name('pendaftar.create');
        Route::post('/beasiswa/{beasiswa}/daftar', 'store')->name('pendaftar.store');
        Route::get('/pendaftar/{pendaftar}', 'show')->name('pendaftar.show');
        Route::get('/pendaftar/{pendaftar}/document/{documentKey}', 'downloadDocument')
            ->name('pendaftar.download-document');
    });

    // Resubmit Routes (Home Controller)
    Route::controller(HomeController::class)->group(function () {
        Route::get('/resubmit/{pendaftar}', 'editForResubmit')->name('pendaftar.resubmit');
        Route::put('/resubmit/{pendaftar}', 'resubmit')->name('pendaftar.resubmit.store');
        Route::get('/pendaftar/{pendaftar}/resubmit', [PendaftarController::class, 'resubmit'])->name('pendaftar.resubmit');
        Route::put('/pendaftar/{pendaftar}/resubmit', [PendaftarController::class, 'resubmitStore'])->name('pendaftar.resubmit.store');
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Beasiswa Management
    Route::resource('beasiswa', AdminBeasiswaController::class);

    // Additional Beasiswa Routes
    Route::prefix('beasiswa')->name('beasiswa.')->group(function () {
        Route::post('{beasiswa}/fix-data', [AdminBeasiswaController::class, 'fixData'])->name('fix-data');
        Route::post('{beasiswa}/clean', [AdminBeasiswaController::class, 'clean'])->name('clean');
        Route::get('{beasiswa}/analyze', [AdminBeasiswaController::class, 'analyze'])->name('analyze');
        Route::post('bulk-clean', [AdminBeasiswaController::class, 'bulkClean'])->name('bulk-clean');
    });

    // Admin Pendaftar Management
    Route::resource('pendaftar', AdminPendaftarController::class)->except(['create', 'store']);
    Route::controller(AdminPendaftarController::class)->prefix('pendaftar')->name('pendaftar.')->group(function () {
        Route::get('{pendaftar}/rejection-history', 'getRejectionHistory')->name('rejection-history');
        Route::patch('{pendaftar}/status', 'updateStatus')->name('update-status');
    });

    // User Pendaftar Routes (di luar grup admin)
    Route::controller(PendaftarController::class)->group(function () {
        Route::get('beasiswa/{beasiswa}/daftar', 'create')->name('pendaftar.create');
        Route::post('beasiswa/{beasiswa}/daftar', 'store')->name('pendaftar.store');
        Route::get('pendaftar/{pendaftar}/resubmit', 'resubmit')->name('pendaftar.resubmit');
        Route::put('pendaftar/{pendaftar}/resubmit', 'resubmitStore')->name('pendaftar.resubmit.store');
    });

    // Temporary Fix Routes (untuk development/debugging)
    Route::get('/fix-beasiswa/{id}', function ($id) {
        $beasiswa = \App\Models\Beasiswa::find($id);
        if ($beasiswa) {
            $beasiswa->autoFixCorruptedData();
            return "Fixed beasiswa ID: " . $id;
        }
        return "Beasiswa not found";
    })->name('fix-beasiswa-temp');
    
});