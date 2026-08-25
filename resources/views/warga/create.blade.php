@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-900">Tambah Data Warga</h1>
    <p class="text-sm text-slate-500">Lengkapi form berikut untuk menambahkan data warga baru.</p>
</div>

<div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <form method="POST" action="{{ route('warga.store') }}">
        @csrf

        @include('warga._form')

        <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('warga.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">
                Batal
            </a>
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
