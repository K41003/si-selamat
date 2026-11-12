<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DINONAKTIFKAN: file SQL lama melakukan DROP TABLE warga dan membuat skema berbeda
 * (nama_lengkap, status_kawin, dst) sehingga merusak aplikasi.
 * Gunakan:  php artisan warga:import-csv --fresh
 */
class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->warn('WargaSeeder dinonaktifkan. Pakai: php artisan warga:import-csv --fresh');
    }
}
