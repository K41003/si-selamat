@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('warga.index') }}" class="p-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Edit Data Warga</h1>
            <p class="text-xs text-slate-500">Perbarui data identitas kependudukan {{ $warga->nama }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('warga.update', $warga) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('warga._form')

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('warga.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs sm:text-sm font-semibold shadow-xs transition">
                    Perbarui Data Warga
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
