@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <x-page-header title="Tambah Data Warga" subtitle="Formulir identitas kependudukan warga Desa Tanjung Selamat." :back="route('warga.index')" />

    <x-card>
        <form method="POST" action="{{ route('warga.store') }}" class="space-y-6">
            @csrf
            @include('warga._form')

            <div class="flex flex-col-reverse gap-2 border-t border-line pt-5 sm:flex-row sm:justify-end">
                <x-button variant="secondary" href="{{ route('warga.index') }}">Batal</x-button>
                <x-button type="submit">Simpan data warga</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
