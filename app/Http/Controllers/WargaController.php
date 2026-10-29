<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $query = Warga::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%")
                    ->orWhere('no_kk', 'like', "%{$q}%");
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'unique:warga,nik'],
            'no_kk' => ['required', 'string', 'size:16'],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'agama' => ['required', 'string', 'max:50'],
            'pekerjaan' => ['required', 'string', 'max:100'],
            'status_perkawinan' => ['required', 'in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati'],
            'alamat' => ['required', 'string'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);

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

    public function update(Request $request, Warga $warga)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'unique:warga,nik,' . $warga->id],
            'no_kk' => ['required', 'string', 'size:16'],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'agama' => ['required', 'string', 'max:50'],
            'pekerjaan' => ['required', 'string', 'max:100'],
            'status_perkawinan' => ['required', 'in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati'],
            'alamat' => ['required', 'string'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);

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

    public function apiSearch(Request $request)
    {
        $q = $request->input('q', '');
        $warga = Warga::where('nama', 'like', "%{$q}%")
            ->orWhere('nik', 'like', "%{$q}%")
            ->limit(10)
            ->get();

        return response()->json($warga);
    }
}
