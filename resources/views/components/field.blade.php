{{-- Kolom form lengkap: label terhubung (for/id), petunjuk, galat inline, aria-invalid & aria-describedby.
     Nama kolom (`name`) dipakai apa adanya, jadi kontrak dengan controller/validasi tidak berubah.
     Pakai: <x-field label="NIK" name="nik" required hint="16 digit" inputmode="numeric" maxlength="16" />
            <x-field label="Alamat" name="alamat" as="textarea" rows="3" />
            <x-field label="Agama" name="agama" as="select"><option value="">Pilih</option>...</x-field> --}}
@props(['label', 'name', 'as' => 'input', 'type' => 'text', 'hint' => null, 'required' => false, 'value' => null])
@php
    $id = $attributes->get('id', $name);
    $err = $errors->first($name);
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($err ? $id . '-error' : ''));
    $control = $attributes->merge(array_filter([
        'id' => $id,
        'name' => $name,
        'class' => 'field',
        'required' => $required ? true : null,
        'aria-invalid' => $err ? 'true' : null,
        'aria-describedby' => $describedBy !== '' ? $describedBy : null,
    ]));
@endphp
<div class="space-y-1.5">
    <label for="{{ $id }}" class="block text-sm font-medium text-ink-900">
        {{ $label }}@if($required) <span class="font-normal text-danger-700">(wajib)</span>@endif
    </label>

    @if($as === 'textarea')
        <textarea {{ $control }}>{{ old($name, $value) }}</textarea>
    @elseif($as === 'select')
        <select {{ $control }}>{{ $slot }}</select>
    @else
        <input type="{{ $type }}" value="{{ old($name, $value) }}" {{ $control }}>
    @endif

    @if($hint)
        <p id="{{ $id }}-hint" class="text-sm text-ink-600">{{ $hint }}</p>
    @endif
    @if($err)
        <p id="{{ $id }}-error" class="text-sm font-medium text-danger-700">{{ $err }}</p>
    @endif
</div>
