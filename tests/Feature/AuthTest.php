<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SI SELAMAT');
        $response->assertSee('name="_token"', false);
    }

    public function test_staff_can_login_successfully(): void
    {
        $response = $this->post('/login', [
            'username' => 'staff',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        // Verify Activity Log was recorded without database column errors
        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'Login Sistem',
            'user_role' => 'Staff Pelayanan',
        ]);
    }

    public function test_kades_can_login_successfully(): void
    {
        $response = $this->post('/login', [
            'username' => 'kades',
            'password' => 'kades123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'Login Sistem',
            'user_role' => 'Kepala Desa',
        ]);
    }
}
