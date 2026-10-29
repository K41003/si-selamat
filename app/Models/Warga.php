<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'warga';

    protected $fillable = [
        'nik',
        'no_kk',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pekerjaan',
        'status_perkawinan',
        'alamat',
        'rt',
        'rw',
        'no_hp',
    ];

    public function surat()
    {
        return $this->hasMany(Surat::class, 'warga_id');
    }

    public function getMaskedNikAttribute(): string
    {
        if (strlen($this->nik) >= 8) {
            return substr($this->nik, 0, 4) . '********' . substr($this->nik, -4);
        }
        return $this->nik;
    }
}
