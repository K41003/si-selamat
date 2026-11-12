<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Kelompok aksi → kategori filter
    private const KATEGORI = [
        'surat' => ['Simpan Draft Surat', 'Buat Permohonan Surat', 'Perbarui Draft Surat', 'Pengajuan Surat ke Kades', 'Hapus Surat', 'Validasi Surat Disetujui', 'Validasi Surat Ditolak'],
        'warga' => ['Tambah Data Warga', 'Update Data Warga', 'Hapus Data Warga', 'Import Data Warga CSV'],
        'nik'   => ['Buka NIK Warga'],
        'akses' => ['Login Sistem', 'Logout Sistem'],
    ];

    public function index(Request $request)
    {
        $role   = $request->input('role');
        $search = $request->input('q');
        $jenis  = $request->input('jenis'); // surat | warga | nik | akses | '' (semua)

        $query = ActivityLog::query();

        if ($role) {
            $query->where('user_role', $role);
        }

        // Filter kategori jenis tindakan
        if ($jenis && isset(self::KATEGORI[$jenis])) {
            $query->whereIn('aksi', self::KATEGORI[$jenis]);
        }

        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->where('aksi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(25)->withQueryString();

        return view('logs.index', compact('logs', 'role', 'search', 'jenis'));
    }
}
