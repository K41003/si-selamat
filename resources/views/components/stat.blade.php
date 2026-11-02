{{-- Kartu statistik. tone: brand | warning | info | success | danger
     Pakai: <x-stat label="Draf surat" :value="$stats['total_draft']" hint="Belum diajukan" tone="warning" icon="pencil" /> --}}
@props(['label', 'value', 'hint' => null, 'tone' => 'brand', 'icon' => null])
@php
    $chip = match ($tone) {
        'warning' => 'bg-warning-100 text-warning-800',
        'info'    => 'bg-info-100 text-info-800',
        'success' => 'bg-success-100 text-success-800',
        'danger'  => 'bg-danger-100 text-danger-800',
        default   => 'bg-brand-100 text-brand-700',
    };
@endphp
<x-card {{ $attributes }}>
    <div class="flex items-start justify-between gap-3">
        <p class="text-sm font-medium text-ink-700">{{ $label }}</p>
        @if($icon)
        <span class="rounded-control p-2 {{ $chip }}"><x-icon :name="$icon" class="size-5"/></span>
        @endif
    </div>
    <p class="mt-2 text-display font-bold tabular-nums text-ink-900">{{ $value }}</p>
    @if($hint)
    <p class="mt-1 text-sm text-ink-600">{{ $hint }}</p>
    @endif
</x-card>
