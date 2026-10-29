<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\ActivityLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Warga Management
    Route::get('/api/warga/search', [WargaController::class, 'apiSearch'])->name('warga.api.search');
    Route::resource('warga', WargaController::class);

    // Surat Management
    Route::post('/surat/{surat}/ajukan', [SuratController::class, 'ajukan'])->name('surat.ajukan');
    Route::get('/surat/{surat}/print', [SuratController::class, 'print'])->name('surat.print');
    Route::resource('surat', SuratController::class);

    // Activity Logs
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

    // Kades Only: Validation
    Route::middleware('role:kades')->prefix('validasi')->name('validasi.')->group(function () {
        Route::get('/', [ValidasiController::class, 'index'])->name('index');
        Route::post('/{surat}/approve', [ValidasiController::class, 'approve'])->name('approve');
        Route::post('/{surat}/reject', [ValidasiController::class, 'reject'])->name('reject');
    });
});
