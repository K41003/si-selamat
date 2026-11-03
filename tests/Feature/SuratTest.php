<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Warga;
use App\Models\JenisSurat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratTest extends TestCase
{
    public function test_cari_warga_by_nik(): void
    {
        $staff = User::where('role', 'staff')->first() ?? User::factory()->create(['role' => 'staff']);
        $warga = Warga::first();

        if (!$warga) {
            $this->markTestSkipped('Belum ada data warga di DB.');
        }

        $res = $this->actingAs($staff)->getJson(route('warga.cari', ['nik' => $warga->nik]));
        $res->assertStatus(200);
        $res->assertJsonPath('found', true);
        $res->assertJsonPath('warga.id', $warga->id);
        $res->assertJsonPath('warga.nama', $warga->nama);
    }

    public function test_create_surat_view_loads_successfully(): void
    {
        $staff = User::where('role', 'staff')->first() ?? User::factory()->create(['role' => 'staff']);

        $res = $this->actingAs($staff)->get(route('surat.create'));
        $res->assertStatus(200);
        $res->assertSee('Pilih Jenis Surat');
        $res->assertSee('Nomor Induk Kependudukan (NIK)');
    }
}

