<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $jenisList = [
            [
                'kode' => 'SKD',
                'nama_jenis' => 'Surat Keterangan Domisili',
                'deskripsi' => 'Surat keterangan resmi tempat tinggal warga di wilayah Desa Tanjung Selamat.',
                'template_field' => [
                    ['key' => 'keperluan', 'label' => 'Keperluan Permohonan', 'type' => 'textarea', 'placeholder' => 'Contoh: Melamar pekerjaan, pengurusan rekening bank, dll.'],
                ],
                'is_active' => true,
            ],
            [
                'kode' => 'SKTM',
                'nama_jenis' => 'Surat Keterangan Tidak Mampu',
                'deskripsi' => 'Surat keterangan status ekonomi kurang mampu untuk keperluan beasiswa, bantuan sosial, atau keringanan biaya medis.',
                'template_field' => [
                    ['key' => 'keperluan', 'label' => 'Keperluan / Tujuan SKTM', 'type' => 'textarea', 'placeholder' => 'Contoh: Persyaratan beasiswa KIP Kuliah anak kandung'],
                    ['key' => 'penghasilan_per_bulan', 'label' => 'Estimasi Penghasilan per Bulan (Rp)', 'type' => 'number', 'placeholder' => '1000000'],
                    ['key' => 'jumlah_tanggungan', 'label' => 'Jumlah Tanggungan Keluarga', 'type' => 'number', 'placeholder' => '3'],
                ],
                'is_active' => true,
            ],
            [
                'kode' => 'SKU',
                'nama_jenis' => 'Surat Keterangan Usaha',
                'deskripsi' => 'Surat keterangan kepemilikan dan legalitas izin usaha mikro warga di Desa Tanjung Selamat.',
                'template_field' => [
                    ['key' => 'nama_usaha', 'label' => 'Nama Usaha', 'type' => 'text', 'placeholder' => 'Contoh: Warung Berkah Jaya'],
                    ['key' => 'jenis_usaha', 'label' => 'Bidang / Jenis Usaha', 'type' => 'text', 'placeholder' => 'Contoh: Perdagangan Sembako & Makanan'],
                    ['key' => 'alamat_usaha', 'label' => 'Alamat Lokasi Usaha', 'type' => 'textarea', 'placeholder' => 'Jl. Tanjung Selamat Dusun II No. 15'],
                    ['key' => 'tahun_berdiri', 'label' => 'Tahun Mulai Berdiri', 'type' => 'number', 'placeholder' => '2021'],
                ],
                'is_active' => true,
            ],
            [
                'kode' => 'SKIK',
                'nama_jenis' => 'Surat Keterangan Izin Keramaian',
                'deskripsi' => 'Surat pengantar untuk rekomendasi penyelenggaraan acara hajatan atau keramaian warga.',
                'template_field' => [
                    ['key' => 'nama_kegiatan', 'label' => 'Nama Kegiatan / Acara', 'type' => 'text', 'placeholder' => 'Contoh: Resepsi Pernikahan / Syukuran'],
                    ['key' => 'tanggal_kegiatan', 'label' => 'Tanggal Penyelenggaraan', 'type' => 'text', 'placeholder' => 'Contoh: 15 November 2026'],
                    ['key' => 'lokasi_kegiatan', 'label' => 'Tempat / Lokasi Acara', 'type' => 'textarea', 'placeholder' => 'Halaman Kediaman Pemohon'],
                ],
                'is_active' => true,
            ],
            [
                'kode' => 'SKM',
                'nama_jenis' => 'Surat Keterangan Kematian',
                'deskripsi' => 'Surat keterangan resmi peristiwa kematian warga desa.',
                'template_field' => [
                    ['key' => 'hari_tanggal_meninggal', 'label' => 'Hari & Tanggal Meninggal', 'type' => 'text', 'placeholder' => 'Contoh: Senin, 12 Oktober 2026'],
                    ['key' => 'tempat_meninggal', 'label' => 'Tempat Meninggal', 'type' => 'text', 'placeholder' => 'Contoh: RS Umum Daerah Deli Serdang / Di Rumah'],
                    ['key' => 'penyebab_kematian', 'label' => 'Penyebab Kematian', 'type' => 'text', 'placeholder' => 'Contoh: Sakit Wajar / Usia Lanjut'],
                    ['key' => 'tempat_pemakaman', 'label' => 'Tempat Pemakaman Umum (TPU)', 'type' => 'text', 'placeholder' => 'TPU Muslim Dusun IV Tanjung Selamat'],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($jenisList as $item) {
            JenisSurat::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
