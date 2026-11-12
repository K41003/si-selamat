{{-- Baris isian: "Label : [input]" (param: label, key, type?, placeholder?) --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
    <span class="w-44 sm:w-52 shrink-0 flex items-center justify-between"><span>{{ $label }}</span><span class="hidden sm:inline">:</span></span>
    <div class="flex-1 font-sans">
        <input type="{{ $type ?? 'text' }}" name="data_isian[{{ $key }}]" x-model="isian['{{ $key }}']"
               placeholder="{{ $placeholder ?? '' }}" class="field bg-white">
    </div>
</div>
