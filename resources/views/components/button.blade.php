{{-- Tombol / tautan-tombol. Tinggi minimum 44px (md) agar mudah disentuh.
     Pakai: <x-button variant="primary" type="submit">Simpan</x-button>
            <x-button href="{{ route('surat.create') }}">Buat surat</x-button>
     variant: primary | secondary | danger | success | ghost   size: md | sm --}}
@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'type' => 'button'])
@php
    $cls = 'btn btn-' . $variant . ($size === 'sm' ? ' btn-sm' : '');
@endphp
@if($href)
<a href="{{ $href }}" {{ $attributes->class([$cls]) }}>{{ $slot }}</a>
@else
<button type="{{ $type }}" {{ $attributes->class([$cls]) }}>{{ $slot }}</button>
@endif
