<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratValidasi extends Model
{
    use HasFactory;

    protected $table = 'surat_validasi';

    protected $fillable = [
        'surat_id',
        'validated_by',
        'validator_name',
        'status', // disetujui, ditolak
        'qr_code_token',
        'catatan',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
