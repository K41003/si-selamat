@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <x-page-header title="Perbarui Surat" :subtitle="'No. Surat: ' . $surat->nomor_surat" :back="route('surat.show', $surat)" />

    @if($surat->status === 'ditolak' && $surat->validasi && $surat->validasi->catatan)
    <div role="note" class="flex items-start gap-3 rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800">
        <x-icon name="exclamation" class="mt-0.5 size-5 shrink-0"/>
        <div>
            <p class="font-semibold">Catatan perbaikan dari Kepala Desa</p>
            <p class="mt-1">{{ $surat->validasi->catatan }}</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('surat.update', $surat) }}">
        @csrf
        @method('PUT')
        @include('surat._form', [
            'wargaTerpilih'   => $surat->warga,
            'jenisTerpilihId' => $surat->jenis_surat_id,
            'isianAwal'       => $surat->data_isian ?? [],
            'batalUrl'        => route('surat.show', $surat),
        ])
    </form>
</div>
@endsection
