<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Staff Desa',
            'username' => 'staff',
            'email' => 'staff@tanjungselamat.desa.id',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kepala Desa',
            'username' => 'kades',
            'email' => 'kades@tanjungselamat.desa.id',
            'password' => bcrypt('password'),
            'role' => 'kades',
            'is_active' => true,
        ]);

        $this->call([
            JenisSuratSeeder::class,
        ]);
    }
}
