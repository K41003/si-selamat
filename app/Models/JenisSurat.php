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
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'template_field' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function suratList()
    {
        return $this->hasMany(Surat::class, 'jenis_surat_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
