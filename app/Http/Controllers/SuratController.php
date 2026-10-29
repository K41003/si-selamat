<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Warga;
use App\Models\JenisSurat;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('q');

        $query = Surat::with(['warga', 'jenisSurat', 'validasi']);

        if ($status === 'draft') {
            $query->where('status', 'draft');
        } elseif ($status === 'diajukan') {
            $query->where('status', 'diajukan');
        } elseif ($status === 'disetujui') {
            $query->where('status', 'disetujui');
        } elseif ($status === 'ditolak') {
            $query->where('status', 'ditolak');
        } elseif ($status === 'arsip') {
            $query->whereIn('status', ['disetujui', 'ditolak']);
        }

        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhereHas('warga', function ($w) use ($search) {
                        $w->where('nama', 'like', "%{$search}%")
                          ->orWhere('nik', 'like', "%{$search}%");
                    })
                    ->orWhereHas('jenisSurat', function ($j) use ($search) {
                        $j->where('nama_jenis', 'like', "%{$search}%");
                    });
            });
        }

        $suratList = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => Surat::count(),
            'draft' => Surat::where('status', 'draft')->count(),
            'diajukan' => Surat::where('status', 'diajukan')->count(),
            'disetujui' => Surat::where('status', 'disetujui')->count(),
            'ditolak' => Surat::where('status', 'ditolak')->count(),
        ];

        return view('surat.index', compact('suratList', 'status', 'search', 'counts'));
    }

    public function create(Request $request)
    {
        $jenisSuratList = JenisSurat::where('is_active', true)->get();
        $selectedWarga = null;
        if ($request->has('warga_id')) {
            $selectedWarga = Warga::find($request->warga_id);
        }
        $selectedJenis = null;
        if ($request->has('jenis_id')) {
            $selectedJenis = JenisSurat::find($request->jenis_id);
        }

        $wargaList = Warga::orderBy('nama')->take(50)->get();

        return view('surat.create', compact('jenisSuratList', 'selectedWarga', 'selectedJenis', 'wargaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warga_id' => ['required', 'exists:warga,id'],
            'jenis_surat_id' => ['required', 'exists:jenis_surat,id'],
            'action' => ['required', 'in:draft,ajukan'],
            'data_isian' => ['nullable', 'array'],
        ]);

        $user = Auth::user();
        $jenis = JenisSurat::findOrFail($request->jenis_surat_id);
        $warga = Warga::findOrFail($request->warga_id);

        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';
        $nomorSurat = Surat::generateNomorSurat($jenis->kode);

        $creatorName = $user->isStaff() && $user->active_staff_name
            ? "{$user->active_staff_name} (Staff Pelayanan)"
            : $user->name;

        $surat = Surat::create([
            'nomor_surat' => $nomorSurat,
            'warga_id' => $warga->id,
            'jenis_surat_id' => $jenis->id,
            'status' => $status,
            'data_isian' => $request->data_isian ?: [],
            'tanggal_pengajuan' => now()->toDateString(),
            'created_by' => $user->id,
            'created_by_name' => $creatorName,
        ]);

        ActivityLog::log(
            $status === 'draft' ? 'Simpan Draft Surat' : 'Buat Permohonan Surat',
            ($status === 'draft' ? 'Menyimpan draft' : 'Membuat permohonan') . " surat {$jenis->nama_jenis} untuk warga {$warga->nama}",
            'Surat',
            $surat->id
        );

        $message = $status === 'draft'
            ? "Draft surat {$jenis->nama_jenis} berhasil disimpan."
            : "Permohonan surat {$jenis->nama_jenis} berhasil diajukan ke Kepala Desa.";

        return redirect()->route('surat.show', $surat)->with('success', $message);
    }

    public function show(Surat $surat)
    {
        $surat->load(['warga', 'jenisSurat', 'pembuat', 'validasi.validator']);
        return view('surat.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        if ($surat->status === 'disetujui') {
            return redirect()->route('surat.show', $surat)->with('error', 'Surat yang telah disetujui tidak dapat diedit kembali.');
        }

        $jenisSuratList = JenisSurat::where('is_active', true)->get();
        $wargaList = Warga::orderBy('nama')->take(50)->get();

        return view('surat.edit', compact('surat', 'jenisSuratList', 'wargaList'));
    }

    public function update(Request $request, Surat $surat)
    {
        if ($surat->status === 'disetujui') {
            return redirect()->route('surat.show', $surat)->with('error', 'Surat yang telah disetujui tidak dapat diedit.');
        }

        $request->validate([
            'warga_id' => ['required', 'exists:warga,id'],
            'jenis_surat_id' => ['required', 'exists:jenis_surat,id'],
            'action' => ['required', 'in:draft,ajukan'],
            'data_isian' => ['nullable', 'array'],
        ]);

        $user = Auth::user();
        $jenis = JenisSurat::findOrFail($request->jenis_surat_id);
        $warga = Warga::findOrFail($request->warga_id);

        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        $surat->update([
            'warga_id' => $warga->id,
            'jenis_surat_id' => $jenis->id,
            'status' => $status,
            'data_isian' => $request->data_isian ?: [],
        ]);

        ActivityLog::log(
            $status === 'diajukan' ? 'Pengajuan Surat ke Kades' : 'Perbarui Draft Surat',
            ($status === 'diajukan' ? 'Mengajukan permohonan' : 'Memperbarui draft') . " surat {$jenis->nama_jenis} No. {$surat->nomor_surat}",
            'Surat',
            $surat->id
        );

        return redirect()->route('surat.show', $surat)->with('success', 'Data permohonan surat berhasil diperbarui.');
    }

    public function destroy(Surat $surat)
    {
        $nomor = $surat->nomor_surat;
        $id = $surat->id;
        $surat->delete();

        ActivityLog::log(
            'Hapus Surat',
            "Menghapus surat No. {$nomor}",
            'Surat',
            $id
        );

        return redirect()->route('surat.index')->with('success', "Surat No. {$nomor} berhasil dihapus.");
    }

    public function ajukan(Surat $surat)
    {
        if ($surat->status !== 'draft') {
            return back()->with('error', 'Hanya surat berstatus draft yang dapat diajukan.');
        }

        $surat->update(['status' => 'diajukan', 'tanggal_pengajuan' => now()->toDateString()]);

        ActivityLog::log(
            'Pengajuan Surat ke Kades',
            "Mengajukan surat {$surat->jenisSurat->nama_jenis} No. {$surat->nomor_surat} ke Kepala Desa",
            'Surat',
            $surat->id
        );

        return back()->with('success', 'Surat berhasil diajukan ke Kepala Desa untuk ditinjau.');
    }

    public function print(Surat $surat)
    {
        $surat->load(['warga', 'jenisSurat', 'validasi.validator']);
        return view('surat.print', compact('surat'));
    }
}
