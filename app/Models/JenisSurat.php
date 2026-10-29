<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat';

    protected $fillable = [
        'kode',
        'nama_jenis',
        'deskripsi',
        'template_field',
        'is_active',
    ];

    protected $casts = [
        'template_field' => 'array',
        'is_active' => 'boolean',
    ];

    public function surat()
    {
        return $this->hasMany(Surat::class, 'jenis_surat_id');
    }
}
