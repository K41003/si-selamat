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

    /**
     * Isian surat siap tampil: urut sesuai template jenis surat (kolom JSON MySQL mengacak urutan key),
     * label dari template, tanggal diformat, dan daftar pengikut menjadi baris teks.
     * Tiap baris: ['label' => string, 'text' => string] atau ['label' => string, 'list' => string[]].
     */
    public function isianUntukTampil(): array
    {
        $data = $this->data_isian ?? [];
        $fmtTgl = fn ($t) => $t && strtotime((string) $t) ? date('d/m/Y', strtotime((string) $t)) : (string) $t;
        $rows = [];

        $tambah = function (string $key, string $label, $val, ?string $type = null) use (&$rows, $fmtTgl) {
            if ($val === null || $val === '' || $val === []) {
                return;
            }
            if (is_array($val)) {
                $list = [];
                foreach (array_values($val) as $p) {
                    $baris = is_array($p)
                        ? implode(', ', array_filter([
                            $p['nama'] ?? null,
                            $p['jenis_kelamin'] ?? null,
                            !empty($p['tanggal_lahir']) ? $fmtTgl($p['tanggal_lahir']) : null,
                            $p['hubungan'] ?? null,
                        ]))
                        : (string) $p;
                    if ($baris !== '') {
                        $list[] = $baris;
                    }
                }
                if ($list) {
                    $rows[] = ['label' => $label, 'list' => $list];
                }
                return;
            }
            $rows[] = ['label' => $label, 'text' => $type === 'date' ? $fmtTgl($val) : (string) $val];
        };

        foreach ($this->jenisSurat?->template_field ?? [] as $f) {
            $k = $f['key'] ?? null;
            if ($k !== null && array_key_exists($k, $data)) {
                $tambah($k, $f['label'] ?? ucfirst(str_replace('_', ' ', $k)), $data[$k], $f['type'] ?? null);
                unset($data[$k]);
            }
        }
        foreach ($data as $k => $v) {
            $tambah((string) $k, ucfirst(str_replace('_', ' ', (string) $k)), $v);
        }

        return $rows;
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
