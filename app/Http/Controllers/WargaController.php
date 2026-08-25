<?php

namespace App\Http\Controllers;

use App\Http\Requests\WargaRequest;
use App\Models\ActivityLog;
use App\Models\Warga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WargaController extends Controller
{
    /**
     * Menampilkan daftar lengkap data warga, dengan pencarian sederhana.
     */
    public function index(Request $request): View
    {
        $query = Warga::query();

        if ($search = $request->string('q')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $warga = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('warga.index', compact('warga'));
    }

    /**
     * Form tambah data warga baru.
     */
    public function create(): View
    {
        return view('warga.create');
    }

    /**
     * Simpan data warga baru (Tambah Data).
     */
    public function store(WargaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $warga = Warga::create($data);

        ActivityLog::catat('Tambah Data Warga', "Menambahkan data warga: {$warga->nama} (NIK: {$warga->nik})", $warga);

        return redirect()
            ->route('warga.index')
            ->with('success', 'Data berhasil ditambah.');
    }

    /**
     * Tampilkan detail satu warga.
     */
    public function show(Warga $warga): View
    {
        return view('warga.show', compact('warga'));
    }

    /**
     * Form update data warga.
     */
    public function edit(Warga $warga): View
    {
        return view('warga.edit', compact('warga'));
    }

    /**
     * Simpan perubahan data warga (Update Data).
     */
    public function update(WargaRequest $request, Warga $warga): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        $warga->update($data);

        ActivityLog::catat('Update Data Warga', "Memperbarui data warga: {$warga->nama} (NIK: {$warga->nik})", $warga);

        return redirect()
            ->route('warga.index')
            ->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Hapus data warga.
     */
    public function destroy(Request $request, Warga $warga): RedirectResponse
    {
        // Cegah hapus warga yang masih punya surat terkait, agar histori surat tidak rusak.
        if ($warga->suratList()->exists()) {
            return redirect()
                ->route('warga.index')
                ->with('error', 'Data warga tidak dapat dihapus karena memiliki riwayat permohonan surat.');
        }

        $namaWarga = $warga->nama;
        $nikWarga = $warga->nik;

        $warga->delete();

        ActivityLog::catat('Hapus Data Warga', "Menghapus data warga: {$namaWarga} (NIK: {$nikWarga})");

        return redirect()
            ->route('warga.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
