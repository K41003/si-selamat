<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tentukan path ke file SQL
        $path = database_path('seeders/sql/seeder_warga_tanjung_selamat.sql');

        if (File::exists($path)) {
            $this->command->info('Memulai seeding data warga...');
            
            // Eksekusi seluruh script SQL sekaligus (sangat cepat untuk 9000+ data)
            DB::unprepared(File::get($path));
            
            $this->command->info('Seeding data warga berhasil!');
        } else {
            $this->command->error("File SQL tidak ditemukan di: {$path}");
        }
    }
}