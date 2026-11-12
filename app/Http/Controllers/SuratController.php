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

        $tahun = date('Y');
        $count = Surat::whereYear('created_at', $tahun)->count() + 1;
        $nextNomorUrut = str_pad((string)$count, 2, '0', STR_PAD_LEFT);

        return view('surat.create', compact('jenisSuratList', 'selectedWarga', 'selectedJenis', 'nextNomorUrut', 'tahun'));
    }

    /**
     * Pencarian warga untuk pemilih asinkron dan autofill NIK di form surat.
     * - Mendukung pencarian via 'nik' langsung atau 'q' (nama / NIK / No. KK).
     * - Mengembalikan profil lengkap warga untuk preview otomatis.
     */
    public function cariWarga(Request $request)
    {
        $nik = trim((string) $request->query('nik', ''));
        if ($nik !== '') {
            $warga = Warga::where('nik', $nik)->first();
            if ($warga) {
                return response()->json([
                    'found' => true,
                    'warga' => $this->formatWargaData($warga),
                ]);
            }
            return response()->json(['found' => false]);
        }

        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        // Escape karakter khusus LIKE agar "%" dan "_" dicari apa adanya.
        $like = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q);

        $hasil = Warga::query()
            ->where(function ($w) use ($like) {
                $w->where('nama', 'like', "%{$like}%")
                  ->orWhere('nik', 'like', "{$like}%")
                  ->orWhere('no_kk', 'like', "{$like}%");
            })
            ->orderBy('nama')
            ->limit(10)
            ->get()
            ->map(fn (Warga $w) => $this->formatWargaData($w));

        return response()->json($hasil);
    }

    private function formatWargaData(Warga $w): array
    {
        return [
            'id' => $w->id,
            'nik' => $w->nik,
            'nik_masked' => $w->masked_nik,
            'nama' => $w->nama,
            'no_kk' => $w->no_kk,
            'tempat_lahir' => $w->tempat_lahir,
            'tanggal_lahir' => date('d/m/Y', strtotime($w->tanggal_lahir)),
            'ttl' => $w->tempat_lahir . ', ' . date('d/m/Y', strtotime($w->tanggal_lahir)),
            'jenis_kelamin' => $w->jenis_kelamin,
            'agama' => $w->agama,
            'pekerjaan' => $w->pekerjaan,
            'status_perkawinan' => $w->status_perkawinan,
            'alamat' => $w->alamat,
            'rt' => $w->rt ?? '-',
            'rw' => $w->rw ?? '-',
            'alamat_lengkap' => $w->alamat . ($w->rt || $w->rw ? " (RT {$w->rt} / RW {$w->rw})" : ''),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat,nomor_surat'],
            'warga_id' => ['required', 'exists:warga,id'],
            'jenis_surat_id' => ['required', 'exists:jenis_surat,id'],
            'action' => ['required', 'in:draft,ajukan'],
            'data_isian' => ['nullable', 'array'],
        ]);

        $user = Auth::user();
        $jenis = JenisSurat::findOrFail($request->jenis_surat_id);
        $warga = Warga::findOrFail($request->warga_id);

        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';
        $nomorSurat = trim($request->nomor_surat);

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

        $surat->load(['warga', 'validasi']);
        $jenisSuratList = JenisSurat::where('is_active', true)->get();

        $tahun = date('Y');
        $count = Surat::whereYear('created_at', $tahun)->count() + 1;
        $nextNomorUrut = str_pad((string)$count, 2, '0', STR_PAD_LEFT);

        return view('surat.edit', compact('surat', 'jenisSuratList', 'nextNomorUrut', 'tahun'));
    }

    public function update(Request $request, Surat $surat)
    {
        if ($surat->status === 'disetujui') {
            return redirect()->route('surat.show', $surat)->with('error', 'Surat yang telah disetujui tidak dapat diedit.');
        }

        $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255', 'unique:surat,nomor_surat,' . $surat->id],
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
            'nomor_surat' => trim($request->nomor_surat),
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
