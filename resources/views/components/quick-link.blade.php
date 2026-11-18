{{-- Kartu aksi cepat bergaya "Biru Langit Balai Desa": ikon kiri, judul, tombol bulat berpanah.
     Taruh di dalam <section class="pita"> (lihat dashboard).
     Pakai: <x-quick-link :href="route('surat.create')" title="Buat surat" icon="plus" hint="Mulai permohonan baru" :badge="3" /> --}}
@props(['href', 'title', 'icon', 'hint' => null, 'badge' => null])
<a href="{{ $href }}"
   class="group flex min-h-16 items-center gap-4 rounded-card bg-surface p-4 shadow-card transition-all duration-150 hover:-translate-y-0.5 hover:shadow-soft">
    <span class="flex size-12 shrink-0 items-center justify-center rounded-control bg-brand-100 text-brand-700">
        <x-icon :name="$icon" class="size-7"/>
    </span>
    <span class="min-w-0 flex-1">
        <span class="block font-semibold text-ink-900">{{ $title }}</span>
        @if($hint)<span class="block text-sm text-ink-600">{{ $hint }}</span>@endif
    </span>
    @if($badge)
    <span class="rounded-full bg-warning-100 px-2 py-0.5 text-xs font-semibold tabular-nums text-warning-800">{{ $badge }}<span class="sr-only"> perlu tindakan</span></span>
    @endif
    <span aria-hidden="true"
          class="flex size-8 shrink-0 items-center justify-center rounded-full border border-brand-600 text-brand-600 transition-colors group-hover:bg-brand-600 group-hover:text-white">
        <x-icon name="arrow-right" class="size-4"/>
    </span>
</a>
