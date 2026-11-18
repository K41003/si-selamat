{{-- Sapaan hero dashboard: subjudul tipis + nama besar kapital (hanya untuk dashboard, bukan halaman kerja).
     Pakai: <x-greeting eyebrow="Selamat datang," :title="$namaUser" subtitle="..." /> --}}
@props(['eyebrow' => null, 'title', 'subtitle' => null])
<div>
    @if($eyebrow)<p class="text-base text-brand-600">{{ $eyebrow }}</p>@endif
    <h1 class="mt-0.5 break-words text-display font-extrabold tracking-tight text-brand-700 sm:text-[2.25rem] sm:leading-[2.75rem]">{{ $title }}</h1>
    @if($subtitle)<p class="mt-1.5 max-w-2xl text-sm text-ink-600">{{ $subtitle }}</p>@endif
</div>
