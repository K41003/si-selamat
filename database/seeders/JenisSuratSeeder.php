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
                'deskripsi' => 'Surat keterangan tempat tinggal warga.',
                'template_field' => [
                    ['key' => 'keperluan', 'label' => 'Keperluan', 'type' => 'textarea'],
                ],
            ],
            [
                'kode' => 'SKTM',
                'nama_jenis' => 'Surat Keterangan Tidak Mampu',
                'deskripsi' => 'Surat keterangan status ekonomi kurang mampu.',
                'template_field' => [
                    ['key' => 'keperluan', 'label' => 'Keperluan', 'type' => 'textarea'],
                ],
            ],
            [
                'kode' => 'SKU',
                'nama_jenis' => 'Surat Keterangan Usaha',
                'deskripsi' => 'Surat keterangan kepemilikan usaha warga.',
                'template_field' => [
                    ['key' => 'nama_usaha', 'label' => 'Nama Usaha', 'type' => 'text'],
                    ['key' => 'jenis_usaha', 'label' => 'Jenis Usaha', 'type' => 'text'],
                    ['key' => 'alamat_usaha', 'label' => 'Alamat Usaha', 'type' => 'textarea'],
                ],
            ],
            [
                'kode' => 'SKKL',
                'nama_jenis' => 'Surat Keterangan Kelahiran',
                'deskripsi' => 'Surat pengantar keterangan kelahiran anak.',
                'template_field' => [
                    ['key' => 'nama_anak', 'label' => 'Nama Anak', 'type' => 'text'],
                    ['key' => 'tanggal_lahir_anak', 'label' => 'Tanggal Lahir Anak', 'type' => 'date'],
                ],
            ],
            [
                'kode' => 'SPPK',
                'nama_jenis' => 'Surat Pengantar Pindah/Kematian',
                'deskripsi' => 'Surat pengantar untuk keperluan pindah domisili atau laporan kematian.',
                'template_field' => [
                    ['key' => 'keperluan', 'label' => 'Keperluan', 'type' => 'textarea'],
                ],
            ],
        ];

        foreach ($jenisList as $item) {
            JenisSurat::updateOrCreate(
                ['kode' => $item['kode']],
                $item + ['is_active' => true]
            );
        }
    }
}
