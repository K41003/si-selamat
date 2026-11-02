{{-- Judul halaman + aksi. Pakai:
     <x-page-header title="Daftar Permohonan Surat" subtitle="..." :back="route('dashboard')">
         <x-slot:actions><x-button href="{{ route('surat.create') }}">Buat surat</x-button></x-slot:actions>
     </x-page-header> --}}
@props(['title', 'subtitle' => null, 'back' => null])
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-start gap-3">
        @if($back)
        <a href="{{ $back }}" class="btn btn-secondary shrink-0 px-3" aria-label="Kembali">
            <x-icon name="arrow-left" class="size-5"/>
        </a>
        @endif
        <div>
            <h1 class="text-title font-bold text-ink-900">{{ $title }}</h1>
            @if($subtitle)
            <p class="mt-0.5 text-sm text-ink-600">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @isset($actions)
    <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
