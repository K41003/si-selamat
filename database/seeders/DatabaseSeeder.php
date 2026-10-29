<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warga;
use App\Models\Surat;
use App\Models\SuratValidasi;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Jenis Surat
        $this->call(JenisSuratSeeder::class);

        // 2. Seed Users
        $staff = User::updateOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Petugas Pelayanan Staff',
                'email' => 'staff@tanjungselamat.desa.id',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'active_staff_name' => 'Amanda Rahmawati',
                'is_active' => true,
            ]
        );

        $kades = User::updateOrCreate(
            ['username' => 'kades'],
            [
                'name' => 'H. AHMAD FAUZI, S.Sos',
                'email' => 'kades@tanjungselamat.desa.id',
                'password' => Hash::make('kades123'),
                'role' => 'kades',
                'is_active' => true,
            ]
        );

        // 3. Seed Warga Samples
        $wargaData = [
            [
                'nik' => '1207261508920001',
                'no_kk' => '1207260102030005',
                'nama' => 'BAMBANG HERMANTO',
                'tempat_lahir' => 'Deli Serdang',
                'tanggal_lahir' => '1992-08-15',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'pekerjaan' => 'Wiraswasta',
                'status_perkawinan' => 'Kawin',
                'alamat' => 'Jl. Flamboyan Raya Dusun II, Desa Tanjung Selamat',
                'rt' => '02',
                'rw' => '01',
                'no_hp' => '081234567890',
            ],
            [
                'nik' => '1207264812950002',
                'no_kk' => '1207260102030005',
                'nama' => 'SITI NURHALIZA',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1995-12-08',
                'jenis_kelamin' => 'Perempuan',
                'agama' => 'Islam',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'status_perkawinan' => 'Kawin',
                'alamat' => 'Jl. Flamboyan Raya Dusun II, Desa Tanjung Selamat',
                'rt' => '02',
                'rw' => '01',
                'no_hp' => '081378901234',
            ],
            [
                'nik' => '1207262104880003',
                'no_kk' => '1207260405060008',
                'nama' => 'DEDI SURYADI',
                'tempat_lahir' => 'Sunggal',
                'tanggal_lahir' => '1988-04-21',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'pekerjaan' => 'Pedagang',
                'status_perkawinan' => 'Kawin',
                'alamat' => 'Jl. Melati Dusun III, Desa Tanjung Selamat',
                'rt' => '01',
                'rw' => '02',
                'no_hp' => '082165439871',
            ],
            [
                'nik' => '1207266509010004',
                'no_kk' => '1207260708090012',
                'nama' => 'RINA ANGGRAENI',
                'tempat_lahir' => 'Binjai',
                'tanggal_lahir' => '2001-09-25',
                'jenis_kelamin' => 'Perempuan',
                'agama' => 'Islam',
                'pekerjaan' => 'Pelajar/Mahasiswa',
                'status_perkawinan' => 'Belum Kawin',
                'alamat' => 'Jl. Mawar Dusun I No. 45, Desa Tanjung Selamat',
                'rt' => '03',
                'rw' => '01',
                'no_hp' => '085298761234',
            ],
        ];

        foreach ($wargaData as $w) {
            Warga::updateOrCreate(['nik' => $w['nik']], $w);
        }

        // 4. Seed Initial Surat
        $bambang = Warga::where('nik', '1207261508920001')->first();
        $rina = Warga::where('nik', '1207266509010004')->first();
        $dedi = Warga::where('nik', '1207262104880003')->first();

        if ($bambang) {
            $surat1 = Surat::updateOrCreate(
                ['nomor_surat' => '470/01/SKD/TS/2026'],
                [
                    'warga_id' => $bambang->id,
                    'jenis_surat_id' => 1,
                    'status' => 'disetujui',
                    'data_isian' => ['keperluan' => 'Pengurusan kelengkapan berkas administrasi kredit perbankan'],
                    'tanggal_pengajuan' => '2026-10-25',
                    'created_by' => $staff->id,
                    'created_by_name' => 'Amanda Rahmawati (Staff Pelayanan)',
                ]
            );

            SuratValidasi::updateOrCreate(
                ['surat_id' => $surat1->id],
                [
                    'validated_by' => $kades->id,
                    'validator_name' => $kades->name,
                    'status' => 'disetujui',
                    'qr_code_token' => 'QR-TS-470-01-SKD-TS-2026-20261026',
                    'catatan' => 'Berkas dan identitas kependudukan terverifikasi lengkap.',
                    'validated_at' => '2026-10-26 10:15:00',
                ]
            );
        }

        if ($rina) {
            Surat::updateOrCreate(
                ['nomor_surat' => '470/02/SKTM/TS/2026'],
                [
                    'warga_id' => $rina->id,
                    'jenis_surat_id' => 2,
                    'status' => 'diajukan',
                    'data_isian' => [
                        'keperluan' => 'Permohonan Keringanan Uang Kuliah Tunggal (UKT) Semester Genap',
                        'penghasilan_per_bulan' => '1200000',
                        'jumlah_tanggungan' => '3',
                    ],
                    'tanggal_pengajuan' => '2026-10-28',
                    'created_by' => $staff->id,
                    'created_by_name' => 'Amanda Rahmawati (Staff Pelayanan)',
                ]
            );
        }

        if ($dedi) {
            Surat::updateOrCreate(
                ['nomor_surat' => '470/03/SKU/TS/2026'],
                [
                    'warga_id' => $dedi->id,
                    'jenis_surat_id' => 3,
                    'status' => 'draft',
                    'data_isian' => [
                        'nama_usaha' => 'Toko Kelontong Berkah Suryadi',
                        'jenis_usaha' => 'Perdagangan Sembako & Kebutuhan Harian',
                        'alamat_usaha' => 'Jl. Melati Dusun III No. 8 Tanjung Selamat',
                        'tahun_berdiri' => '2019',
                    ],
                    'tanggal_pengajuan' => '2026-10-29',
                    'created_by' => $staff->id,
                    'created_by_name' => 'Amanda Rahmawati (Staff Pelayanan)',
                ]
            );
        }

        // 5. Seed Activity Logs
        ActivityLog::create([
            'user_name' => 'Amanda Rahmawati (Staff Pelayanan)',
            'user_role' => 'Staff Pelayanan',
            'aksi' => 'Buat Permohonan Surat',
            'deskripsi' => 'Membuat permohonan surat Surat Keterangan Tidak Mampu untuk warga RINA ANGGRAENI',
            'model_type' => 'Surat',
            'model_id' => 2,
        ]);

        ActivityLog::create([
            'user_name' => 'H. AHMAD FAUZI, S.Sos',
            'user_role' => 'Kepala Desa',
            'aksi' => 'Validasi Surat Disetujui',
            'deskripsi' => 'Menyetujui & menerbitkan e-signature surat Surat Keterangan Domisili No. 470/01/SKD/TS/2026',
            'model_type' => 'Surat',
            'model_id' => 1,
        ]);
    }
}
