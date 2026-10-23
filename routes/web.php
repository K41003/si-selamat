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

/*
|--------------------------------------------------------------------------
| API Endpoints (untuk Frontend React / Mobile / SPA)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    Route::get('/warga', function (\Illuminate\Http\Request $request) {
        $q = trim($request->get('q', ''));
        $nik = trim($request->get('nik', ''));
        $perPage = min(max((int) $request->get('per_page', 8), 1), 100);

        $query = \App\Models\Warga::query();

        if ($nik !== '') {
            $query->where('nik', $nik);
        } elseif ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nik', 'like', "%{$q}%")
                  ->orWhere('no_kk', 'like', "%{$q}%")
                  ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        $paginated = $query->orderBy('nama')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $paginated->items(),
            'total' => $paginated->total(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'per_page' => $paginated->perPage(),
        ]);
    });

    Route::get('/warga/{id}', function ($id) {
        $warga = \App\Models\Warga::find($id);
        if (!$warga) {
            return response()->json(['status' => 'error', 'message' => 'Warga tidak ditemukan'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $warga]);
    });

    Route::post('/warga', function (\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'nik' => 'required|string|size:16|unique:warga,nik',
            'no_kk' => 'nullable|string|max:16',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $data['created_by'] = 1;
        $data['updated_by'] = 1;
        $warga = \App\Models\Warga::create($data);

        \App\Models\ActivityLog::create([
            'user_id' => 1,
            'aktivitas' => 'Tambah Data Warga',
            'deskripsi' => "Menambahkan data warga: {$warga->nama} (NIK: {$warga->nik}) via Frontend",
            'subject_type' => null,
            'subject_id' => null,
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'created_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'data' => $warga], 201);
    });

    Route::put('/warga/{id}', function (\Illuminate\Http\Request $request, $id) {
        $warga = \App\Models\Warga::find($id);
        if (!$warga) {
            return response()->json(['status' => 'error', 'message' => 'Warga tidak ditemukan'], 404);
        }

        $data = $request->validate([
            'nik' => 'sometimes|required|string|size:16|unique:warga,nik,'.$id,
            'no_kk' => 'nullable|string|max:16',
            'nama' => 'sometimes|required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $data['updated_by'] = 1;
        $warga->update($data);

        \App\Models\ActivityLog::create([
            'user_id' => 1,
            'aktivitas' => 'Update Data Warga',
            'deskripsi' => "Memperbarui data warga: {$warga->nama} (NIK: {$warga->nik}) via Frontend",
            'subject_type' => null,
            'subject_id' => null,
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'created_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'data' => $warga]);
    });

    Route::delete('/warga/{id}', function (\Illuminate\Http\Request $request, $id) {
        $warga = \App\Models\Warga::find($id);
        if (!$warga) {
            return response()->json(['status' => 'error', 'message' => 'Warga tidak ditemukan'], 404);
        }

        $nama = $warga->nama;
        $nik = $warga->nik;
        $warga->delete();

        \App\Models\ActivityLog::create([
            'user_id' => 1,
            'aktivitas' => 'Hapus Data Warga',
            'deskripsi' => "Menghapus data warga: {$nama} (NIK: {$nik}) via Frontend",
            'subject_type' => null,
            'subject_id' => null,
            'ip_address' => $request->ip() ?? '127.0.0.1',
            'created_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data warga berhasil dihapus']);
    });

    Route::get('/stats', function () {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_warga' => \App\Models\Warga::count(),
                'total_surat' => \App\Models\Surat::count(),
                'total_draft' => \App\Models\Surat::where('status', 'draft')->count(),
                'total_diajukan' => \App\Models\Surat::where('status', 'diajukan')->count(),
                'total_disetujui' => \App\Models\Surat::where('status', 'disetujui')->count(),
            ]
        ]);
    });
});
