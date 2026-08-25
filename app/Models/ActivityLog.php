<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false; // hanya pakai created_at

    protected $fillable = [
        'user_id',
        'aktivitas',
        'deskripsi',
        'subject_type',
        'subject_id',
        'ip_address',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk mencatat log aktivitas dari mana saja di aplikasi.
     *
     * Contoh: ActivityLog::catat('Validasi Surat', "Menyetujui surat #{$surat->id}", $surat);
     */
    public static function catat(string $aktivitas, ?string $deskripsi = null, ?Model $subject = null): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}
