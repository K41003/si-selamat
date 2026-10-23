<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\User;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportWargaCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warga:import-csv 
                            {path? : Path file CSV relatif terhadap root aplikasi}
                            {--fresh : Kosongkan tabel warga sebelum import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data kependudukan desa dari file CSV ke tabel warga';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $csvPath = $this->argument('path') 
            ? base_path($this->argument('path'))
            : public_path('assets/aistudio/DATA_DESA_TANJUNG_SELAMAT_SUNGGAL_1_0d0e08be.csv');

        if (!file_exists($csvPath)) {
            $this->error("File CSV tidak ditemukan pada path: {$csvPath}");
            return 1;
        }

        if ($this->option('fresh')) {
            $this->warn('Mengosongkan tabel warga...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('warga')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->info("Membaca dan memproses CSV: " . basename($csvPath));

        $staffUser = User::where('role', 'staff')->first();
        $staffId = $staffUser?->id ?? 1;

        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            $this->error("Gagal membuka file CSV.");
            return 1;
        }

        // Baca header
        $header = fgetcsv($handle, 0, ';', '"', '\\');

        $seenNiks = [];
        $batch = [];
        $batchSize = 500;
        $totalProcessed = 0;
        $totalImported = 0;
        $skipped = 0;
        $now = now();

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, ';', '"', '\\')) !== false) {
                // Lewati baris kosong
                if (count(array_filter($row)) === 0) {
                    continue;
                }

                $totalProcessed++;

                $noKk = trim($row[2] ?? '');
                $nik = trim($row[3] ?? '');
                $nama = trim($row[4] ?? '');
                $jkRaw = strtoupper(trim($row[5] ?? ''));
                $tmptLahir = trim($row[6] ?? '');
                $tglLahirRaw = trim($row[7] ?? '');
                $agama = trim($row[8] ?? '');
                $pekerjaan = trim($row[10] ?? '');
                $stKawinRaw = strtoupper(trim($row[11] ?? ''));
                $alamat = trim($row[20] ?? '');

                // Validasi NIK
                if (empty($nik) || strlen($nik) !== 16 || !ctype_digit($nik)) {
                    $skipped++;
                    continue;
                }

                // Cek duplikasi dalam file CSV
                if (isset($seenNiks[$nik])) {
                    $skipped++;
                    continue;
                }
                $seenNiks[$nik] = true;

                // Format Jenis Kelamin
                $jenisKelamin = null;
                if (str_contains($jkRaw, 'LAKI')) {
                    $jenisKelamin = 'Laki-laki';
                } elseif (str_contains($jkRaw, 'PEREMPUAN')) {
                    $jenisKelamin = 'Perempuan';
                }

                // Format Tanggal Lahir (d-m-Y -> Y-m-d)
                $tanggalLahir = null;
                if (!empty($tglLahirRaw)) {
                    $d = DateTime::createFromFormat('d-m-Y', $tglLahirRaw);
                    if ($d && $d->format('d-m-Y') === $tglLahirRaw) {
                        $tanggalLahir = $d->format('Y-m-d');
                    }
                }

                // Format Status Perkawinan
                $statusPerkawinan = null;
                if ($stKawinRaw === 'KAWIN') {
                    $statusPerkawinan = 'Kawin';
                } elseif (str_contains($stKawinRaw, 'BELUM') || str_contains($stKawinRaw, 'BELUN')) {
                    $statusPerkawinan = 'Belum Kawin';
                } elseif (str_contains($stKawinRaw, 'CERAI MATI')) {
                    $statusPerkawinan = 'Cerai Mati';
                } elseif (str_contains($stKawinRaw, 'CERAI HIDUP')) {
                    $statusPerkawinan = 'Cerai Hidup';
                }

                // Ekstrak RT / RW jika ada di teks alamat
                $rt = null;
                $rw = null;
                if (preg_match('/RT[\.\s:]*([0-9]{1,3})/i', $alamat, $mRt)) {
                    $rt = str_pad($mRt[1], 2, '0', STR_PAD_LEFT);
                }
                if (preg_match('/RW[\.\s:]*([0-9]{1,3})/i', $alamat, $mRw)) {
                    $rw = str_pad($mRw[1], 2, '0', STR_PAD_LEFT);
                }

                $batch[] = [
                    'nik' => $nik,
                    'no_kk' => !empty($noKk) ? substr($noKk, 0, 16) : null,
                    'nama' => $nama ?: 'Tanpa Nama',
                    'tempat_lahir' => $tmptLahir ?: null,
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => $jenisKelamin,
                    'agama' => $agama ? ucwords(strtolower($agama)) : null,
                    'pekerjaan' => $pekerjaan ? ucwords(strtolower($pekerjaan)) : null,
                    'status_perkawinan' => $statusPerkawinan,
                    'alamat' => $alamat ?: null,
                    'rt' => $rt,
                    'rw' => $rw,
                    'no_hp' => null,
                    'created_by' => $staffId,
                    'updated_by' => $staffId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batch) >= $batchSize) {
                    $inserted = DB::table('warga')->insertOrIgnore($batch);
                    $totalImported += $inserted;
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                $inserted = DB::table('warga')->insertOrIgnore($batch);
                $totalImported += $inserted;
            }

            fclose($handle);

            // Simpan log aktivitas
            ActivityLog::create([
                'user_id' => $staffId,
                'aktivitas' => 'Import Data Warga CSV',
                'deskripsi' => "Berhasil mengimpor {$totalImported} data kependudukan dari file CSV {$csvPath}.",
                'subject_type' => null,
                'subject_id' => null,
                'ip_address' => '127.0.0.1',
                'created_at' => $now,
            ]);

            DB::commit();

            $this->newLine();
            $this->info("=== HASIL IMPORT DATA WARGA ===");
            $this->line("Total baris diproses : {$totalProcessed}");
            $this->line("Berhasil diimpor     : {$totalImported} warga");
            $this->line("Dilewati (invalid/duplikat): {$skipped}");
            $this->line("Total data warga saat ini di database: " . DB::table('warga')->count());

            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            if (is_resource($handle)) {
                fclose($handle);
            }
            $this->error("Terjadi error saat import: " . $e->getMessage());
            return 1;
        }
    }
}
