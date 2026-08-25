<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik (Autentikasi)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| Rute Terproteksi (harus login)
|--------------------------------------------------------------------------
| Modul-modul lain (Warga, Surat, Validasi, Arsip, Log Aktivitas) akan
| ditambahkan di sini secara bertahap pada langkah-langkah berikutnya,
| masing-masing dengan middleware role:staff / role:kades sesuai aktor.
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:staff')->group(function () {
        Route::resource('warga', WargaController::class);

        // --- Placeholder modul berikut (Step selanjutnya) ---
        // Route::resource('surat', SuratController::class);
    });

    // Route::middleware('role:kades')->group(function () {
    //     Route::get('/validasi-surat', [ValidasiSuratController::class, 'index']);
    // });
});
