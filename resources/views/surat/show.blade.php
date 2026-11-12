@extends('layouts.app')

@section('content')
@php
    $warga = $surat->warga;
    $isKades = auth()->check() && auth()->user()->isKades();
    $bisaValidasi = $isKades && $surat->status === 'diajukan';
    $keterangan = [
        'draft'     => 'Belum diajukan ke Kepala Desa.',
        'diajukan'  => 'Menunggu peninjauan Kepala Desa.',
        'disetujui' => 'Disetujui dan e-signature sudah diterbitkan.',
        'ditolak'   => 'Ditolak. Surat perlu diperbaiki lalu diajukan kembali.',
    ][$surat->status] ?? '';
    $tglLahir = $warga->tanggal_lahir ? \Carbon\Carbon::parse($warga->tanggal_lahir)->locale('id')->translatedFormat('d F Y') : '-';
    $catatanTolak = $surat->status === 'ditolak' && $surat->validasi ? $surat->validasi->catatan : null;
@endphp

<div class="mx-auto max-w-4xl space-y-6">
    <x-page-header :title="$surat->jenisSurat->nama_jenis" :subtitle="'No. Surat: ' . $surat->nomor_surat" :back="route('surat.index')">
        <x-slot:actions>
            @if($surat->status === 'disetujui')
                <x-button href="{{ route('surat.print', $surat) }}" target="_blank" rel="noopener">
                    <x-icon name="printer" class="size-4"/> Cetak surat resmi
                </x-button>
            @elseif($surat->status === 'draft')
                <x-button variant="secondary" href="{{ route('surat.edit', $surat) }}">
                    <x-icon name="pencil" class="size-4"/> Edit draf
                </x-button>
                <x-button type="button" onclick="openDialog('ajukan-{{ $surat->id }}')">
                    Ajukan ke Kades <x-icon name="arrow-right" class="size-4"/>
                </x-button>
            @elseif($surat->status === 'ditolak')
                <x-button href="{{ route('surat.edit', $surat) }}">
                    <x-icon name="pencil" class="size-4"/> Revisi surat
                </x-button>
            @endif
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-ink-600">Status dokumen</p>
                <div class="mt-1.5 flex flex-wrap items-center gap-3">
                    <x-status-badge :status="$surat->status" />
                    <span class="text-sm text-ink-700">{{ $keterangan }}</span>
                </div>
            </div>
            @if($bisaValidasi)
            <div class="flex flex-wrap gap-2">
                <x-button type="button" variant="secondary" onclick="openDialog('tolak-{{ $surat->id }}')" class="text-danger-800">Tolak</x-button>
                <x-button type="button" variant="success" onclick="openDialog('setujui-{{ $surat->id }}')">
                    <x-icon name="check-circle" class="size-4"/> Setujui surat
                </x-button>
            </div>
            @endif
        </div>

        @if($catatanTolak)
        <div role="note" class="mt-4 rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800">
            <p class="font-semibold">Catatan dari Kepala Desa</p>
            <p class="mt-1">{{ $catatanTolak }}</p>
        </div>
        @endif
    </x-card>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <x-card>
            <h2 class="text-title-sm font-semibold text-ink-900">Warga pemohon</h2>
            <dl class="mt-4 space-y-4">
                <div><dt class="text-sm text-ink-600">Nama lengkap</dt><dd class="mt-0.5 font-medium text-ink-900">{{ $warga->nama }}</dd></div>
                <div><dt class="text-sm text-ink-600">NIK</dt><dd class="mt-1"><x-nik-reveal :warga="$warga" /></dd></div>
                <div><dt class="text-sm text-ink-600">No. KK</dt><dd class="mt-0.5 font-mono text-ink-900">{{ $warga->no_kk }}</dd></div>
                <div><dt class="text-sm text-ink-600">Tempat, tanggal lahir</dt><dd class="mt-0.5 font-medium text-ink-900">{{ $warga->tempat_lahir }}, {{ $tglLahir }}</dd></div>
                <div><dt class="text-sm text-ink-600">Jenis kelamin / agama</dt><dd class="mt-0.5 font-medium text-ink-900">{{ $warga->jenis_kelamin }} &bull; {{ $warga->agama }}</dd></div>
                <div><dt class="text-sm text-ink-600">Pekerjaan</dt><dd class="mt-0.5 font-medium text-ink-900">{{ $warga->pekerjaan }}</dd></div>
                <div><dt class="text-sm text-ink-600">Alamat</dt><dd class="mt-0.5 font-medium text-ink-900">{{ $warga->alamat }} @if($warga->rt || $warga->rw)<span class="font-normal text-ink-700">(RT {{ $warga->rt ?? '-' }} / RW {{ $warga->rw ?? '-' }})</span>@endif</dd></div>
            </dl>
        </x-card>

        <x-card>
            <h2 class="text-title-sm font-semibold text-ink-900">Data isian surat</h2>
            @if(!empty($surat->data_isian))
            <dl class="mt-4 space-y-4">
                @foreach($surat->isianUntukTampil() as $r)
                <div>
                    <dt class="text-sm text-ink-600">{{ $r['label'] }}</dt>
                    @if(isset($r['list']))
                    <dd class="mt-0.5 font-medium text-ink-900"><ol class="list-decimal space-y-0.5 pl-5">@foreach($r['list'] as $li)<li class="break-words">{{ $li }}</li>@endforeach</ol></dd>
                    @else
                    <dd class="mt-0.5 break-words font-medium text-ink-900">{{ $r['text'] }}</dd>
                    @endif
                </div>
                @endforeach
            </dl>
            @else
            <p class="mt-4 text-sm text-ink-600">Tidak ada data isian tambahan untuk surat ini.</p>
            @endif
        </x-card>
    </div>

    @if($surat->validasi)
    <x-card>
        <h2 class="text-title-sm font-semibold text-ink-900">Catatan keabsahan &amp; e-signature</h2>
        <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
            <dl class="space-y-3">
                <div><dt class="text-sm text-ink-600">Divalidasi oleh</dt><dd class="mt-0.5 font-medium text-ink-900">{{ $surat->validasi->validator_name }}</dd></div>
                <div><dt class="text-sm text-ink-600">Waktu validasi</dt><dd class="mt-0.5 tabular-nums text-ink-900">{{ $surat->validasi->validated_at->format('d/m/Y H:i:s') }}</dd></div>
                @if($surat->validasi->catatan && $surat->status !== 'ditolak')
                <div><dt class="text-sm text-ink-600">Catatan</dt><dd class="mt-0.5 text-ink-900">{{ $surat->validasi->catatan }}</dd></div>
                @endif
            </dl>
            @if($surat->validasi->qr_code_token)
            <div class="shrink-0 rounded-control border border-line bg-canvas px-4 py-3 text-center">
                <p class="text-sm text-ink-600">Token QR resmi</p>
                <p class="mt-0.5 font-mono text-sm font-semibold text-brand-900">{{ $surat->validasi->qr_code_token }}</p>
            </div>
            @endif
        </div>
    </x-card>
    @endif
</div>

{{-- Dialog konfirmasi --}}
@if($surat->status === 'draft')
<x-confirm-dialog id="ajukan-{{ $surat->id }}" :action="route('surat.ajukan', $surat)"
    title="Ajukan surat ke Kepala Desa?" confirm-label="Ajukan">
    <p>Surat <span class="font-mono">{{ $surat->nomor_surat }}</span> untuk <strong>{{ $warga->nama }}</strong> akan dikirim ke Kepala Desa untuk divalidasi.</p>
</x-confirm-dialog>
@endif

@if($bisaValidasi)
<x-confirm-dialog id="setujui-{{ $surat->id }}" :action="route('validasi.approve', $surat)"
    title="Setujui dan tanda tangani surat ini?" confirm-label="Setujui & tanda tangani" variant="success">
    <p>Surat <span class="font-mono">{{ $surat->nomor_surat }}</span> untuk <strong>{{ $warga->nama }}</strong> akan disetujui dan e-signature diterbitkan atas nama Anda.</p>
</x-confirm-dialog>

<x-confirm-dialog id="tolak-{{ $surat->id }}" :action="route('validasi.reject', $surat)"
    title="Tolak permohonan surat?" confirm-label="Tolak permohonan" variant="danger">
    <p class="mb-4">Berikan catatan perbaikan agar staf dapat memperbaiki surat <span class="font-mono">{{ $surat->nomor_surat }}</span>.</p>
    <x-field label="Alasan penolakan" name="catatan" as="textarea" rows="3" required id="catatan-{{ $surat->id }}" placeholder="Contoh: Alamat usaha belum lengkap." />
</x-confirm-dialog>
@endif
@endsection
