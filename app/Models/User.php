<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'active_staff_name',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isKades(): bool
    {
        return $this->role === 'kades';
    }

    public function suratDibuat()
    {
        return $this->hasMany(Surat::class, 'created_by');
    }

    public function suratDivalidasi()
    {
        return $this->hasMany(SuratValidasi::class, 'validated_by');
    }
}
