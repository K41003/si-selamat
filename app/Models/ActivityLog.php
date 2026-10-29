<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_name',
        'user_role',
        'aksi',
        'deskripsi',
        'model_type',
        'model_id',
    ];

    public static function log(string $aksi, string $deskripsi, ?string $modelType = null, ?int $modelId = null): self
    {
        $user = auth()->user();
        return self::create([
            'user_name' => $user ? ($user->active_staff_name ?: $user->name) : 'Sistem',
            'user_role' => $user ? ($user->role === 'kades' ? 'Kepala Desa' : 'Staff Pelayanan') : 'Sistem',
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'model_type' => $modelType,
            'model_id' => $modelId,
        ]);
    }
}
