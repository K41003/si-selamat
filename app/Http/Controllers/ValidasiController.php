<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\SuratValidasi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiController extends Controller
{
    public function index()
    {
        $pendingSurat = Surat::with(['warga', 'jenisSurat', 'pembuat'])
            ->where('status', 'diajukan')
            ->latest('tanggal_pengajuan')
            ->paginate(15);

        return view('surat.validasi', compact('pendingSurat'));
    }

    public function approve(Request $request, Surat $surat)
    {
        $user = Auth::user();
        if (!$user->isKades()) {
            abort(403, 'Hanya Kepala Desa yang berwenang menyetujui surat.');
        }

        $cleanNomor = str_replace('/', '-', $surat->nomor_surat);
        $dateFormatted = date('Ymd');
        $qrToken = "QR-TS-{$cleanNomor}-{$dateFormatted}";

        $surat->update(['status' => 'disetujui']);

        SuratValidasi::updateOrCreate(
            ['surat_id' => $surat->id],
            [
                'validated_by' => $user->id,
                'validator_name' => $user->name,
                'status' => 'disetujui',
                'qr_code_token' => $qrToken,
                'catatan' => $request->input('catatan', 'Disetujui dan diterbitkan e-signature resmi.'),
                'validated_at' => now(),
            ]
        );

        ActivityLog::log(
            'Validasi Surat Disetujui',
            "Menyetujui & menerbitkan e-signature surat {$surat->jenisSurat->nama_jenis} No. {$surat->nomor_surat}",
            'Surat',
            $surat->id
        );

        return redirect()->back()->with('success', "Surat No. {$surat->nomor_surat} berhasil disetujui dan diterbitkan.");
    }

    public function reject(Request $request, Surat $surat)
    {
        $user = Auth::user();
        if (!$user->isKades()) {
            abort(403, 'Hanya Kepala Desa yang berwenang menolak surat.');
        }

        $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ]);

        $surat->update(['status' => 'ditolak']);

        SuratValidasi::updateOrCreate(
            ['surat_id' => $surat->id],
            [
                'validated_by' => $user->id,
                'validator_name' => $user->name,
                'status' => 'ditolak',
                'catatan' => $request->catatan,
                'validated_at' => now(),
            ]
        );

        ActivityLog::log(
            'Validasi Surat Ditolak',
            "Menolak surat {$surat->jenisSurat->nama_jenis} No. {$surat->nomor_surat} (Catatan: {$request->catatan})",
            'Surat',
            $surat->id
        );

        return redirect()->back()->with('success', "Surat No. {$surat->nomor_surat} ditolak dengan catatan perbaikan.");
    }
}
