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
        'warga_id',
        'jenis_surat_id',
        'status', // draft, diajukan, disetujui, ditolak
        'data_isian',
        'tanggal_pengajuan',
        'created_by',
        'created_by_name',
    ];

    protected $casts = [
        'data_isian' => 'array',
        'tanggal_pengajuan' => 'date',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validasi()
    {
        return $this->hasOne(SuratValidasi::class, 'surat_id');
    }

    public static function generateNomorSurat(string $kode = ''): string
    {
        $romawi = [1=>'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        $urut = static::whereYear('created_at', now()->year)->count() + 1;
        do {
            $nomor = sprintf('470/%03d/%s/TS/%d', $urut, $romawi[now()->month], now()->year);
            $urut++;
        } while (static::where('nomor_surat', $nomor)->exists());
        return $nomor;
    }
}
