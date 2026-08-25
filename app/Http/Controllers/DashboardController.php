<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isKades()) {
            return view('dashboard.kades', [
                'totalMenungguValidasi' => Surat::menungguValidasi()->count(),
                'totalDisetujuiBulanIni' => Surat::where('status', 'disetujui')
                    ->whereMonth('updated_at', now()->month)
                    ->count(),
                'totalDitolakBulanIni' => Surat::where('status', 'ditolak')
                    ->whereMonth('updated_at', now()->month)
                    ->count(),
            ]);
        }

        // Dashboard Staff (default)
        return view('dashboard.staff', [
            'totalWarga' => Warga::count(),
            'totalSuratDraft' => Surat::where('status', 'draft')->count(),
            'totalSuratDiajukan' => Surat::where('status', 'diajukan')->count(),
            'totalSuratDisetujui' => Surat::where('status', 'disetujui')->count(),
        ]);
    }
}
