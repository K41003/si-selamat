{{-- Sel statistik datar (tanpa ikon/kartu sendiri). Dirangkai dalam satu strip oleh pemanggil.
     emphasis = angka diberi warna aksen untuk pekerjaan yang perlu ditindaklanjuti.
     Pakai: <x-stat label="Draf surat" :value="$stats['total_draft']" hint="Belum diajukan" emphasis /> --}}
@props(['label', 'value', 'hint' => null, 'emphasis' => false])
<div {{ $attributes->class(['bg-surface px-5 py-4 sm:px-6 sm:py-5']) }}>
    <p class="text-sm text-ink-600">{{ $label }}</p>
    <p @class(['mt-1 text-display font-semibold tabular-nums', 'text-brand-700' => $emphasis, 'text-ink-900' => ! $emphasis])>{{ $value }}</p>
    @if($hint)
    <p class="mt-0.5 text-sm text-ink-600">{{ $hint }}</p>
    @endif
</div>
