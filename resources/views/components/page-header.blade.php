{{-- Blok judul halaman bergaya acuan: latar biru pucat, judul besar biru, satu sudut kiri-atas sangat bulat.
     Pakai: <x-page-header title="Daftar Permohonan Surat" subtitle="..." :back="route('dashboard')">
                <x-slot:actions><x-button href="{{ route('surat.create') }}">Buat surat</x-button></x-slot:actions>
            </x-page-header> --}}
@props(['title', 'subtitle' => null, 'back' => null])
<div class="hero-block flex flex-col gap-5 px-6 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-10 sm:py-8">
    <div class="flex items-start gap-4">
        @if($back)
        <a href="{{ $back }}" class="btn btn-secondary mt-1 size-11 shrink-0 !px-0" aria-label="Kembali">
            <x-icon name="arrow-left" class="size-5"/>
        </a>
        @endif
        <div>
            <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-brand-700 sm:text-4xl">{{ $title }}</h1>
            @if($subtitle)
            <p class="mt-1.5 max-w-2xl text-base text-ink-700">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @isset($actions)
    <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
