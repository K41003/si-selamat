@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <x-page-header title="Buat Surat" subtitle="Pilih warga pemohon dan lengkapi data isian sesuai jenis surat." :back="route('surat.index')" />

    <form method="POST" action="{{ route('surat.store') }}">
        @csrf
        @include('surat._form', [
            'wargaTerpilih'   => $selectedWarga,
            'jenisTerpilihId' => $selectedJenis->id ?? ($jenisSuratList->first()->id ?? null),
            'isianAwal'       => [],
            'batalUrl'        => route('surat.index'),
        ])
    </form>
</div>
@endsection
