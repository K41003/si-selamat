{{-- Kartu dasar. Pakai: <x-card>...</x-card>   atau tanpa padding: <x-card :padded="false"> --}}
@props(['padded' => true])
<div {{ $attributes->class(['rounded-card border border-line bg-surface shadow-sm', 'p-5 sm:p-6' => $padded]) }}>
    {{ $slot }}
</div>
