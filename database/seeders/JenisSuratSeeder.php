<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $f = fn ($key, $label, $type = 'text', $ph = '') => compact('key', 'label', 'type') + ['placeholder' => $ph];

        $data = [
            ['SKTM', 'Surat Keterangan Tidak Mampu', 'Keterangan keluarga kurang mampu/miskin.', [
                $f('keperluan', 'Untuk melengkapi persyaratan', 'text', 'cth: beasiswa / BPJS'),
            ]],
            ['SKD', 'Surat Keterangan Domisili', 'Keterangan berdomisili di desa.', [
                $f('bertempat', 'Berdomisili/Bertempat', 'text', 'cth: sejak tahun 2020'),
                $f('dusun', 'Dusun', 'text', 'IV'),
            ]],
            ['MMS', 'Surat Keterangan Mandah Masuk', 'Pendatang sementara yang masuk ke desa.', [
                $f('no_mandah', 'No. Surat Mandah asal'),
                $f('penerbit', 'Dikeluarkan oleh'),
                $f('dusun', 'Dusun tujuan'),
                $f('alasan', 'Alasan mandah'),
                $f('lamanya', 'Lamanya'),
                $f('berlaku_bulan', 'Berlaku (bulan)', 'number'),
                $f('berlaku_dari', 'Berlaku dari', 'date'),
                $f('berlaku_sampai', 'Berlaku sampai', 'date'),
                $f('diperiksa_oleh', 'Diketahui/Diperiksa oleh (jabatan)'),
                $f('kadus_nama', 'Nama Kepala Dusun'),
            ]],
            ['MDJ', 'Surat Keterangan Mandah/Jalan', 'Warga yang bepergian/pindah sementara keluar desa.', [
                $f('tujuan', 'Mandah/Jalan ke'),
                $f('desa', 'Desa/Kelurahan'),
                $f('kecamatan', 'Kecamatan'),
                $f('kabupaten', 'Kota/Kabupaten'),
                $f('provinsi', 'Provinsi'),
                $f('keperluan', 'Keperluan'),
                $f('lamanya', 'Lamanya'),
            ]],
        ];

        $kodeAktif = array_column($data, 0);

        // Hapus jenis surat selain 4 di atas. Yang sudah dipakai surat lama dinonaktifkan (tidak bisa dihapus karena FK).
        foreach (JenisSurat::whereNotIn('kode', $kodeAktif)->get() as $j) {
            \App\Models\Surat::where('jenis_surat_id', $j->id)->exists()
                ? $j->update(['is_active' => false])
                : $j->delete();
        }

        foreach ($data as [$kode, $nama, $desk, $fields]) {
            JenisSurat::updateOrCreate(
                ['kode' => $kode],
                ['nama_jenis' => $nama, 'deskripsi' => $desk, 'template_field' => $fields, 'is_active' => true]
            );
        }
    }
}
