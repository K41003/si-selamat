<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratValidasi extends Model
{
    use HasFactory;

    protected $table = 'surat_validasi';

    public $timestamps = true;

    protected $fillable = [
        'surat_id',
        'kades_id',
        'status',
        'catatan',
        'qr_code_path',
        'qr_code_hash',
        'tanggal_validasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_validasi' => 'datetime',
        ];
    }

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function kades()
    {
        return $this->belongsTo(User::class, 'kades_id');
    }
}
