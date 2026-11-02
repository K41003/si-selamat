{{-- Keadaan kosong yang memberi arah (bukan sekadar teks abu-abu).
     Pakai: <x-empty-state title="Belum ada surat" description="Buat surat pertama...">
                <x-button href="{{ route('surat.create') }}">Buat surat</x-button>
            </x-empty-state> --}}
@props(['title', 'description' => null, 'icon' => 'check-circle'])
<div class="px-6 py-10 text-center">
    <span class="mx-auto flex size-12 items-center justify-center rounded-full bg-brand-50 text-brand-700">
        <x-icon :name="$icon" class="size-6"/>
    </span>
    <p class="mt-3 font-semibold text-ink-900">{{ $title }}</p>
    @if($description)
    <p class="mx-auto mt-1 max-w-sm text-sm text-ink-600">{{ $description }}</p>
    @endif
    @if($slot->isNotEmpty())
    <div class="mt-4 flex justify-center">{{ $slot }}</div>
    @endif
</div>
