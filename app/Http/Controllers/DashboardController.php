<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Warga;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isKades()) {
            return $this->kadesDashboard();
        }

        return $this->staffDashboard();
    }

    private function staffDashboard()
    {
        $stats = [
            'total_warga' => Warga::count(),
            'total_surat' => Surat::count(),
            'total_draft' => Surat::where('status', 'draft')->count(),
            'total_diajukan' => Surat::where('status', 'diajukan')->count(),
            'total_disetujui' => Surat::where('status', 'disetujui')->count(),
        ];

        $recentSurat = Surat::with(['warga', 'jenisSurat'])
            ->latest()
            ->take(6)
            ->get();

        $recentLogs = ActivityLog::latest()->take(5)->get();

        return view('dashboard.staff', compact('stats', 'recentSurat', 'recentLogs'));
    }

    private function kadesDashboard()
    {
        $stats = [
            'perlu_validasi' => Surat::where('status', 'diajukan')->count(),
            'disetujui' => Surat::where('status', 'disetujui')->count(),
            'ditolak' => Surat::where('status', 'ditolak')->count(),
            'total_surat' => Surat::count(),
            'total_warga' => Warga::count(),
        ];

        $pendingSurat = Surat::with(['warga', 'jenisSurat', 'pembuat'])
            ->where('status', 'diajukan')
            ->latest('tanggal_pengajuan')
            ->get();

        $recentSurat = Surat::with(['warga', 'jenisSurat', 'validasi'])
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.kades', compact('stats', 'pendingSurat', 'recentSurat'));
    }
}
