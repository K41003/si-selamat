<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\ActivityLog;
use App\Http\Requests\WargaRequest;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $like = $q ? str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) : null;

        $query = Warga::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$like}%")
                    ->orWhere('nik', 'like', "%{$like}%")
                    ->orWhere('no_kk', 'like', "%{$like}%");
            });
        }

        $warga = $query->latest()->paginate(10)->withQueryString();
        $totalWarga = Warga::count();

        return view('warga.index', compact('warga', 'q', 'totalWarga'));
    }

    public function create()
    {
        return view('warga.create');
    }

    public function store(WargaRequest $request)
    {
        $validated = $request->validated();

        $warga = Warga::create($validated);

        ActivityLog::log(
            'Tambah Data Warga',
            "Menambahkan data warga: {$warga->nama} (NIK: {$warga->nik})",
            'Warga',
            $warga->id
        );

        return redirect()->route('warga.index')->with('success', "Data warga {$warga->nama} berhasil ditambahkan.");
    }

    public function show(Warga $warga)
    {
        $warga->load(['surat' => function ($q) {
            $q->with('jenisSurat')->latest();
        }]);

        return view('warga.show', compact('warga'));
    }

    public function edit(Warga $warga)
    {
        return view('warga.edit', compact('warga'));
    }

    public function update(WargaRequest $request, Warga $warga)
    {
        $validated = $request->validated();

        $warga->update($validated);

        ActivityLog::log(
            'Update Data Warga',
            "Memperbarui data warga: {$warga->nama} (NIK: {$warga->nik})",
            'Warga',
            $warga->id
        );

        return redirect()->route('warga.index')->with('success', "Data warga {$warga->nama} berhasil diperbarui.");
    }

    public function destroy(Warga $warga)
    {
        if ($warga->surat()->exists()) {
            return back()->with('error', 'Data warga ini tidak dapat dihapus karena memiliki riwayat permohonan surat.');
        }

        $nama = $warga->nama;
        $nik = $warga->nik;
        $id = $warga->id;
        $warga->delete();

        ActivityLog::log(
            'Hapus Data Warga',
            "Menghapus data kependudukan warga: {$nama} (NIK: {$nik})",
            'Warga',
            $id
        );

        return redirect()->route('warga.index')->with('success', "Data warga {$nama} berhasil dihapus.");
    }


    /**
     * Tampilkan NIK lengkap (dicatat di log aktivitas). Dipanggil via POST dari komponen nik-reveal.
     */
    public function tampilkanNik(Warga $warga)
    {
        ActivityLog::log(
            'Buka NIK Warga',
            "Membuka NIK lengkap warga: {$warga->nama}",
            'Warga',
            $warga->id
        );

        return response()->json(['nik' => $warga->nik]);
    }
}
