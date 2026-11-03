@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <x-page-header title="Buat Surat" subtitle="Pilih warga pemohon dan lengkapi data isian sesuai jenis surat." :back="route('surat.index')" />

    <form method="POST" action="{{ route('surat.store') }}">
        @csrf
        @include('surat._form', [
            'wargaTerpilih'   => $selectedWarga,
            'jenisTerpilihId' => $selectedJenis->id ?? null,
            'isianAwal'       => [],
            'batalUrl'        => route('surat.index'),
            'nextNomorUrut'   => $nextNomorUrut ?? '01',
            'tahun'           => $tahun ?? date('Y'),
        ])
    </form>
</div>
@endsection
