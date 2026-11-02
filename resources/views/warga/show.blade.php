@extends('layouts.app')

@section('content')
@php
    $iconBtn = 'inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-canvas hover:text-ink-900';
    $biodata = [
        'NIK'                     => ['v' => $warga->nik, 'mono' => true],
        'No. KK'                  => ['v' => $warga->no_kk, 'mono' => true],
        'Jenis kelamin'           => ['v' => $warga->jenis_kelamin],
        'Agama'                   => ['v' => $warga->agama],
        'Tempat & tanggal lahir'  => ['v' => $warga->tempat_lahir . ', ' . \Carbon\Carbon::parse($warga->tanggal_lahir)->locale('id')->translatedFormat('d F Y')],
        'Status perkawinan'       => ['v' => $warga->status_perkawinan],
        'Pekerjaan'               => ['v' => $warga->pekerjaan],
        'No. telepon / HP'        => ['v' => $warga->no_hp ?: '-'],
    ];
@endphp

<div class="mx-auto max-w-4xl space-y-6">
    <x-page-header :title="$warga->nama" subtitle="Data kependudukan warga Desa Tanjung Selamat." :back="route('warga.index')">
        <x-slot:actions>
            <x-button variant="secondary" href="{{ route('warga.edit', $warga) }}">
                <x-icon name="pencil" class="size-4"/> Edit
            </x-button>
            <x-button href="{{ route('surat.create', ['warga_id' => $warga->id]) }}">
                <x-icon name="plus" class="size-4"/> Buat surat
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <h2 class="text-title-sm font-semibold text-ink-900">Biodata kependudukan</h2>
        <dl class="mt-5 grid grid-cols-1 gap-x-8 gap-y-5 sm:grid-cols-2">
            @foreach($biodata as $label => $row)
            <div>
                <dt class="text-sm text-ink-600">{{ $label }}</dt>
                <dd @class(['mt-0.5 font-medium text-ink-900', 'font-mono' => $row['mono'] ?? false])>{{ $row['v'] }}</dd>
            </div>
            @endforeach
            <div class="sm:col-span-2">
                <dt class="text-sm text-ink-600">Alamat tempat tinggal</dt>
                <dd class="mt-0.5 font-medium text-ink-900">{{ $warga->alamat }} <span class="font-normal text-ink-700">(RT {{ $warga->rt ?? '-' }} / RW {{ $warga->rw ?? '-' }})</span></dd>
            </div>
            <div>
                <dt class="text-sm text-ink-600">Terdaftar sejak</dt>
                <dd class="mt-0.5 tabular-nums text-ink-700">{{ $warga->created_at->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </x-card>

    <x-card :padded="false" class="overflow-hidden">
        <div class="px-5 py-4 sm:px-6">
            <h2 class="text-title-sm font-semibold text-ink-900">Riwayat permohonan surat</h2>
            <p class="mt-0.5 text-sm text-ink-600">Arsip dan permohonan surat atas nama warga ini.</p>
        </div>

        @if($warga->surat->isEmpty())
            <div class="border-t border-line">
                <x-empty-state title="Belum ada riwayat surat" description="Permohonan surat untuk warga ini akan muncul di sini.">
                    <x-button href="{{ route('surat.create', ['warga_id' => $warga->id]) }}"><x-icon name="plus" class="size-4"/> Buat surat</x-button>
                </x-empty-state>
            </div>
        @else
            <x-data-table caption="Riwayat permohonan surat">
                <x-slot:head>
                    <tr>
                        <th scope="col">Surat</th>
                        <th scope="col">Tanggal pengajuan</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right"><span class="sr-only">Aksi</span></th>
                    </tr>
                </x-slot:head>
                @foreach($warga->surat as $s)
                <tr class="hover:bg-canvas/60">
                    <td>
                        <p class="font-medium text-ink-900">{{ $s->jenisSurat->nama_jenis ?? '-' }}</p>
                        <p class="font-mono text-xs text-ink-600">{{ $s->nomor_surat }}</p>
                    </td>
                    <td class="whitespace-nowrap tabular-nums text-ink-700">{{ $s->created_at->format('d/m/Y') }}</td>
                    <td><x-status-badge :status="$s->status" /></td>
                    <td class="whitespace-nowrap text-right">
                        <a href="{{ route('surat.show', $s) }}" class="{{ $iconBtn }}" title="Lihat detail" aria-label="Lihat detail surat {{ $s->nomor_surat }}">
                            <x-icon name="eye" class="size-5"/>
                        </a>
                        @if($s->status === 'disetujui')
                        <a href="{{ route('surat.print', $s) }}" target="_blank" rel="noopener" class="{{ $iconBtn }}" title="Cetak surat" aria-label="Cetak surat {{ $s->nomor_surat }}">
                            <x-icon name="printer" class="size-5"/>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </x-data-table>
        @endif
    </x-card>
</div>
@endsection
