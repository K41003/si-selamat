<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
        'nomor_surat',
        'jenis_surat_id',
        'warga_id',
        'dibuat_oleh',
        'data_surat',
        'status',
        'tanggal_pengajuan',
    ];

    protected function casts(): array
    {
        return [
            'data_surat' => 'array',
            'tanggal_pengajuan' => 'datetime',
        ];
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function validasi()
    {
        return $this->hasOne(SuratValidasi::class, 'surat_id')->latestOfMany();
    }

    public function riwayatValidasi()
    {
        return $this->hasMany(SuratValidasi::class, 'surat_id');
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isDiajukan(): bool
    {
        return $this->status === 'diajukan';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    public function scopeMenungguValidasi($query)
    {
        return $query->where('status', 'diajukan');
    }
}
