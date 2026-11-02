@extends('layouts.app')

@section('content')
@php
    // [kunci status, label tab]
    $tabs = [
        'all'       => 'Semua',
        'draft'     => 'Draf',
        'diajukan'  => 'Menunggu validasi',
        'disetujui' => 'Disetujui',
        'ditolak'   => 'Ditolak',
    ];
    $iconBtn = 'inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-canvas hover:text-ink-900';
@endphp

<div class="space-y-6">
    <x-page-header title="Daftar Surat" subtitle="Kelola dan pantau seluruh permohonan surat administrasi warga.">
        <x-slot:actions>
            <x-button href="{{ route('surat.create') }}">
                <x-icon name="plus" class="size-4"/> Buat surat
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card :padded="false" class="overflow-hidden">
        {{-- Tab status: garis bawah, jumlah sebagai teks biasa --}}
        <nav aria-label="Filter status surat" class="flex gap-1 overflow-x-auto overflow-y-hidden border-b border-line px-3 sm:px-4">
            @foreach($tabs as $key => $label)
            @php $aktif = $status === $key; @endphp
            <a href="{{ route('surat.index', ['status' => $key, 'q' => $search]) }}" @if($aktif) aria-current="page" @endif
               class="inline-flex h-11 shrink-0 items-center gap-2 border-b-2 px-3 text-sm transition-colors {{ $aktif ? 'border-brand-700 font-semibold text-brand-700' : 'border-transparent font-medium text-ink-600 hover:border-line hover:text-ink-900' }}">
                {{ $label }}
                <span class="font-normal tabular-nums text-ink-600">{{ $counts[$key] }}</span>
            </a>
            @endforeach
        </nav>

        {{-- Pencarian --}}
        <form method="GET" action="{{ route('surat.index') }}" role="search" class="flex flex-col gap-2 border-b border-line p-4 sm:flex-row">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="relative flex-1">
                <label for="cari-surat" class="sr-only">Cari surat</label>
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-ink-600"/>
                <input id="cari-surat" type="search" name="q" value="{{ $search ?? '' }}"
                       placeholder="Cari nomor surat, nama pemohon, atau jenis surat"
                       class="field pl-10">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(!empty($search))
                <a href="{{ route('surat.index', ['status' => $status]) }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>

        @if($suratList->isEmpty())
            <x-empty-state icon="search" title="Tidak ada surat yang cocok"
                :description="!empty($search) ? 'Coba kata kunci lain atau hapus pencarian.' : 'Belum ada surat pada status ini.'">
                @if(!empty($search))
                <x-button variant="secondary" href="{{ route('surat.index', ['status' => $status]) }}">Hapus pencarian</x-button>
                @else
                <x-button href="{{ route('surat.create') }}"><x-icon name="plus" class="size-4"/> Buat surat</x-button>
                @endif
            </x-empty-state>
        @else
            <x-data-table caption="Daftar permohonan surat">
                <x-slot:head>
                    <tr>
                        <th scope="col">Surat</th>
                        <th scope="col">Pemohon</th>
                        <th scope="col">Tanggal pengajuan</th>
                        <th scope="col" class="hidden lg:table-cell">Petugas</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right"><span class="sr-only">Aksi</span></th>
                    </tr>
                </x-slot:head>
                @foreach($suratList as $s)
                <tr class="hover:bg-canvas/60">
                    <td>
                        <p class="whitespace-nowrap font-medium text-ink-900">{{ $s->jenisSurat->nama_jenis ?? '-' }}</p>
                        <p class="font-mono text-xs text-ink-600">{{ $s->nomor_surat }}</p>
                    </td>
                    <td>
                        <p class="whitespace-nowrap font-medium text-ink-900">{{ $s->warga->nama ?? '-' }}</p>
                        <p class="font-mono text-xs text-ink-600">{{ $s->warga->masked_nik ?? '' }}</p>
                    </td>
                    <td class="whitespace-nowrap tabular-nums text-ink-700">
                        {{ $s->tanggal_pengajuan ? $s->tanggal_pengajuan->format('d/m/Y') : $s->created_at->format('d/m/Y') }}
                    </td>
                    <td class="hidden text-ink-700 lg:table-cell">{{ $s->created_by_name ?: ($s->pembuat->name ?? '-') }}</td>
                    <td><x-status-badge :status="$s->status" /></td>
                    <td class="whitespace-nowrap text-right">
                        @if($s->status === 'draft')
                        <button type="button" class="btn btn-primary btn-sm mr-1" onclick="openDialog('ajukan-{{ $s->id }}')">Ajukan</button>
                        @endif

                        <a href="{{ route('surat.show', $s) }}" class="{{ $iconBtn }}" title="Lihat rincian" aria-label="Lihat rincian surat {{ $s->nomor_surat }}">
                            <x-icon name="eye" class="size-5"/>
                        </a>

                        @if($s->status !== 'disetujui')
                        <a href="{{ route('surat.edit', $s) }}" class="{{ $iconBtn }}" title="Edit surat" aria-label="Edit surat {{ $s->nomor_surat }}">
                            <x-icon name="pencil" class="size-5"/>
                        </a>
                        @else
                        <a href="{{ route('surat.print', $s) }}" target="_blank" rel="noopener" class="{{ $iconBtn }}" title="Cetak surat" aria-label="Cetak surat {{ $s->nomor_surat }}">
                            <x-icon name="printer" class="size-5"/>
                        </a>
                        @endif

                        @if($s->status !== 'disetujui')
                        <button type="button" onclick="openDialog('hapus-{{ $s->id }}')" title="Hapus surat" aria-label="Hapus surat {{ $s->nomor_surat }}"
                                class="inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-danger-100 hover:text-danger-800">
                            <x-icon name="trash" class="size-5"/>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </x-data-table>

            @if($suratList->hasPages())
            <div class="border-t border-line p-4">
                {{ $suratList->links() }}
            </div>
            @endif
        @endif
    </x-card>
</div>

{{-- Dialog konfirmasi (<dialog> native), satu per surat yang relevan --}}
@foreach($suratList as $s)
    @if($s->status === 'draft')
    <x-confirm-dialog id="ajukan-{{ $s->id }}" :action="route('surat.ajukan', $s)"
        title="Ajukan surat ke Kepala Desa?" confirm-label="Ajukan">
        <p>Surat <span class="font-mono">{{ $s->nomor_surat }}</span> untuk <strong>{{ $s->warga->nama ?? '-' }}</strong> akan dikirim ke Kepala Desa untuk divalidasi.</p>
    </x-confirm-dialog>
    @endif

    @if($s->status !== 'disetujui')
    <x-confirm-dialog id="hapus-{{ $s->id }}" :action="route('surat.destroy', $s)" method="DELETE"
        title="Hapus surat ini?" confirm-label="Hapus surat" variant="danger">
        <p>Surat <span class="font-mono">{{ $s->nomor_surat }}</span> untuk <strong>{{ $s->warga->nama ?? '-' }}</strong> akan dihapus dari daftar.</p>
    </x-confirm-dialog>
    @endif
@endforeach
@endsection
