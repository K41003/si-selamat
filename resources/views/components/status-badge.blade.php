{{-- Badge status surat. Menggantikan blok @if 4 cabang yang tadinya disalin di banyak view.
     Status selalu berupa TEKS (bukan hanya warna). Pakai: <x-status-badge :status="$s->status" /> --}}
@props(['status'])
@php
    [$label, $tone] = match ($status) {
        'draft'     => ['Draf',              'bg-warning-100 text-warning-800'],
        'diajukan'  => ['Menunggu validasi', 'bg-info-100 text-info-800'],
        'disetujui' => ['Disetujui',         'bg-success-100 text-success-800'],
        default     => ['Ditolak',           'bg-danger-100 text-danger-800'],
    };
@endphp
<span {{ $attributes->class(['inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold', $tone]) }}>
    <span class="size-1.5 rounded-full bg-current" aria-hidden="true"></span>{{ $label }}
</span>
