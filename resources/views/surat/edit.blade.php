@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('surat.show', $surat) }}" class="p-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Perbarui Permohonan Surat</h1>
            <p class="text-xs text-slate-500 font-mono">No. Surat: {{ $surat->nomor_surat }}</p>
        </div>
    </div>

    @if($surat->status === 'ditolak' && $surat->validasi)
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
        <span class="font-bold flex items-center gap-1.5 text-rose-900">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            Catatan Perbaikan dari Kepala Desa:
        </span>
        <p class="pl-5 text-slate-700 italic">"{{ $surat->validasi->catatan }}"</p>
    </div>
    @endif

    <form method="POST" action="{{ route('surat.update', $surat) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">1</span>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Warga Pemohon</h2>
            </div>

            <div>
                <label for="warga_id" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Warga</label>
                <select id="warga_id" name="warga_id" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                    @foreach($wargaList as $w)
                    <option value="{{ $w->id }}" {{ old('warga_id', $surat->warga_id) == $w->id ? 'selected' : '' }}>
                        {{ $w->nama }} (NIK: {{ $w->nik }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">2</span>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Jenis Surat</h2>
            </div>

            <div>
                <select name="jenis_surat_id" id="jenis_surat_id" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                    @foreach($jenisSuratList as $j)
                    <option value="{{ $j->id }}" {{ old('jenis_surat_id', $surat->jenis_surat_id) == $j->id ? 'selected' : '' }}>
                        [{{ $j->kode }}] {{ $j->nama_jenis }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">3</span>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Data Isian Keterangan Surat</h2>
            </div>

            <div class="space-y-4">
                @if($surat->jenisSurat && !empty($surat->jenisSurat->template_field))
                    @foreach($surat->jenisSurat->template_field as $field)
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">{{ $field['label'] }} <span class="text-rose-500">*</span></label>
                        @php
                            $val = old("data_isian.{$field['key']}", $surat->data_isian[$field['key']] ?? '');
                        @endphp
                        @if(($field['type'] ?? '') === 'textarea')
                        <textarea name="data_isian[{{ $field['key'] }}]" rows="3" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ $val }}</textarea>
                        @else
                        <input type="{{ $field['type'] ?? 'text' }}" name="data_isian[{{ $field['key'] }}]" value="{{ $val }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        @endif
                    </div>
                    @endforeach
                @else
                    <p class="text-xs text-slate-400">Tidak ada isian form khusus.</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex items-center justify-between">
            <a href="{{ route('surat.show', $surat) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                Batal
            </a>
            <div class="flex items-center gap-3">
                <button type="submit" name="action" value="draft"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition shadow-xs">
                    Simpan Draf
                </button>
                <button type="submit" name="action" value="ajukan"
                    class="px-6 py-2.5 rounded-xl bg-[#1E3A8A] hover:bg-[#172554] text-white text-xs sm:text-sm font-semibold transition shadow-md shadow-blue-900/10">
                    Ajukan ke Kades
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
