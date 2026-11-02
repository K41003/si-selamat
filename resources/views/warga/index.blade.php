@extends('layouts.app')

@section('content')
@php
    $iconBtn = 'inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-canvas hover:text-ink-900';
@endphp

<div class="space-y-6">
    <x-page-header title="Data Warga" subtitle="Master data penduduk Desa Tanjung Selamat, Kecamatan Sunggal.">
        <x-slot:actions>
            <x-button href="{{ route('warga.create') }}">
                <x-icon name="user-plus" class="size-4"/> Tambah warga
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card :padded="false" class="overflow-hidden">
        <form method="GET" action="{{ route('warga.index') }}" role="search" class="flex flex-col gap-2 border-b border-line p-4 sm:flex-row">
            <div class="relative flex-1">
                <label for="cari-warga" class="sr-only">Cari warga</label>
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-ink-600"/>
                <input id="cari-warga" type="search" name="q" value="{{ $q ?? '' }}"
                       placeholder="Cari nama, NIK 16 digit, atau No. KK"
                       class="field pl-10">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(!empty($q))
                <a href="{{ route('warga.index') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>

        @if($warga->isEmpty())
            <x-empty-state icon="search" title="Data warga tidak ditemukan"
                :description="!empty($q) ? 'Periksa ejaan nama, atau coba NIK / No. KK lengkap.' : 'Belum ada data warga yang terdaftar.'">
                @if(!empty($q))
                <x-button variant="secondary" href="{{ route('warga.index') }}">Hapus pencarian</x-button>
                @else
                <x-button href="{{ route('warga.create') }}"><x-icon name="user-plus" class="size-4"/> Tambah warga</x-button>
                @endif
            </x-empty-state>
        @else
            <x-data-table caption="Daftar warga">
                <x-slot:head>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">NIK &amp; No. KK</th>
                        <th scope="col">Tempat / tgl lahir</th>
                        <th scope="col" class="hidden lg:table-cell">Alamat</th>
                        <th scope="col" class="text-right"><span class="sr-only">Aksi</span></th>
                    </tr>
                </x-slot:head>
                @foreach($warga as $w)
                <tr class="hover:bg-canvas/60">
                    <td>
                        <p class="whitespace-nowrap font-medium text-ink-900">{{ $w->nama }}</p>
                        <p class="text-xs text-ink-600">{{ $w->jenis_kelamin }}@if($w->pekerjaan) &bull; {{ $w->pekerjaan }}@endif</p>
                    </td>
                    <td>
                        {{-- NIK hanya tampil tersamar. NIK lengkap TIDAK dikirim ke browser. --}}
                        <p class="whitespace-nowrap font-mono text-sm text-ink-900">{{ $w->masked_nik }}</p>
                        <p class="whitespace-nowrap font-mono text-xs text-ink-600">KK: {{ $w->no_kk }}</p>
                    </td>
                    <td class="text-ink-700">
                        {{ $w->tempat_lahir }}, <span class="tabular-nums">{{ date('d/m/Y', strtotime($w->tanggal_lahir)) }}</span>
                    </td>
                    <td class="hidden text-ink-700 lg:table-cell">
                        <p class="max-w-[14rem] truncate" title="{{ $w->alamat }}">{{ $w->alamat }}</p>
                        @if($w->rt || $w->rw)
                        <p class="text-xs text-ink-600">RT {{ $w->rt ?? '-' }} / RW {{ $w->rw ?? '-' }}</p>
                        @endif
                    </td>
                    <td class="whitespace-nowrap text-right">
                        <a href="{{ route('surat.create', ['warga_id' => $w->id]) }}" class="btn btn-secondary btn-sm mr-1"
                           title="Buat surat permohonan untuk warga ini" aria-label="Buat surat untuk {{ $w->nama }}">
                            <x-icon name="plus" class="size-4"/> Surat
                        </a>
                        <a href="{{ route('warga.show', $w) }}" class="{{ $iconBtn }}" title="Lihat detail" aria-label="Lihat detail {{ $w->nama }}">
                            <x-icon name="eye" class="size-5"/>
                        </a>
                        <a href="{{ route('warga.edit', $w) }}" class="{{ $iconBtn }}" title="Edit data" aria-label="Edit data {{ $w->nama }}">
                            <x-icon name="pencil" class="size-5"/>
                        </a>
                        <button type="button" onclick="openDialog('hapus-warga-{{ $w->id }}')" title="Hapus data" aria-label="Hapus data {{ $w->nama }}"
                                class="inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-danger-100 hover:text-danger-800">
                            <x-icon name="trash" class="size-5"/>
                        </button>
                    </td>
                </tr>
                @endforeach
            </x-data-table>

            @if($warga->hasPages())
            <div class="border-t border-line p-4">
                {{ $warga->links() }}
            </div>
            @endif
        @endif
    </x-card>
</div>

@foreach($warga as $w)
<x-confirm-dialog id="hapus-warga-{{ $w->id }}" :action="route('warga.destroy', $w)" method="DELETE"
    title="Hapus data warga ini?" confirm-label="Hapus data" variant="danger">
    <p>Data kependudukan <strong>{{ $w->nama }}</strong> ({{ $w->masked_nik }}) akan dihapus.</p>
</x-confirm-dialog>
@endforeach
@endsection
