@extends('layouts.app')

@section('content')
@php
    $namaUser = auth()->user()->active_staff_name ?: auth()->user()->name;

    // Dashboard hanya menampilkan peristiwa surat. Login/logout tetap ada di halaman Log Aktivitas.
    $aktivitas = $recentLogs
        ->reject(fn ($log) => str_contains(strtolower($log->aksi), 'login') || str_contains(strtolower($log->aksi), 'logout'))
        ->take(6);
@endphp

<div class="space-y-6">
    <x-page-header title="Dashboard" :subtitle="'Selamat datang, ' . $namaUser . '. Berikut ringkasan pelayanan surat desa.'">
        <x-slot:actions>
            <x-button variant="secondary" href="{{ route('warga.create') }}">
                <x-icon name="user-plus" class="size-4"/> Tambah warga
            </x-button>
            <x-button href="{{ route('surat.create') }}">
                <x-icon name="plus" class="size-4"/> Buat surat
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Strip statistik: satu kartu, pemisah tipis, tanpa ikon warna-warni --}}
    <section aria-label="Ringkasan">
        <x-card :padded="false" class="overflow-hidden">
            <div class="grid grid-cols-2 gap-px bg-line lg:grid-cols-4">
                <x-stat label="Total penduduk" :value="number_format($stats['total_warga'])" hint="Warga terdaftar" />
                <x-stat label="Draf surat" :value="$stats['total_draft']" hint="Belum diajukan ke Kades" emphasis />
                <x-stat label="Menunggu validasi" :value="$stats['total_diajukan']" hint="Sedang ditinjau Kades" />
                <x-stat label="Surat disetujui" :value="$stats['total_disetujui']" hint="Siap dicetak" />
            </div>
        </x-card>
    </section>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        {{-- Permohonan terbaru --}}
        <x-card :padded="false" class="overflow-hidden">
            <div class="flex items-center justify-between gap-4 px-5 py-4 sm:px-6">
                <h2 class="text-title-sm font-semibold text-ink-900">Permohonan surat terbaru</h2>
                <a href="{{ route('surat.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-900">
                    Lihat semua <x-icon name="arrow-right" class="size-4"/>
                </a>
            </div>

            @if($recentSurat->isEmpty())
                <div class="border-t border-line">
                    <x-empty-state title="Belum ada permohonan surat" description="Permohonan yang dibuat akan muncul di sini.">
                        <x-button href="{{ route('surat.create') }}"><x-icon name="plus" class="size-4"/> Buat surat</x-button>
                    </x-empty-state>
                </div>
            @else
                <x-data-table caption="Permohonan surat terbaru">
                    <x-slot:head>
                        <tr>
                            <th scope="col">Surat</th>
                            <th scope="col">Pemohon</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </x-slot:head>
                    @foreach($recentSurat as $s)
                    <tr class="hover:bg-canvas/60">
                        <td>
                            <p class="whitespace-nowrap font-medium text-ink-900">{{ $s->jenisSurat->nama_jenis ?? '-' }}</p>
                            <p class="font-mono text-xs text-ink-600">{{ $s->nomor_surat }}</p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap font-medium text-ink-900">{{ $s->warga->nama ?? '-' }}</p>
                            <p class="font-mono text-xs text-ink-600">{{ $s->warga->masked_nik ?? '' }}</p>
                        </td>
                        <td class="whitespace-nowrap tabular-nums text-ink-700">{{ $s->created_at->format('d/m/Y') }}</td>
                        <td><x-status-badge :status="$s->status" /></td>
                        <td class="whitespace-nowrap text-right">
                            <a href="{{ route('surat.show', $s) }}" title="Lihat detail" aria-label="Lihat detail surat {{ $s->nomor_surat }}"
                               class="inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-canvas hover:text-ink-900">
                                <x-icon name="eye" class="size-5"/>
                            </a>
                            @if($s->status === 'disetujui')
                            <a href="{{ route('surat.print', $s) }}" target="_blank" rel="noopener" title="Cetak surat" aria-label="Cetak surat {{ $s->nomor_surat }}"
                               class="inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-canvas hover:text-ink-900">
                                <x-icon name="printer" class="size-5"/>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </x-data-table>
            @endif
        </x-card>

        {{-- Aktivitas surat --}}
        <x-card :padded="false" class="overflow-hidden">
            <div class="flex items-center justify-between gap-4 px-5 py-4">
                <h2 class="text-title-sm font-semibold text-ink-900">Aktivitas terkini</h2>
                <a href="{{ route('logs.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-900">Semua</a>
            </div>
            @forelse($aktivitas as $log)
                @if($loop->first)<ul class="divide-y divide-line border-t border-line">@endif
                <li class="px-5 py-3.5">
                    <p class="text-sm font-medium text-ink-900">{{ $log->aksi }}</p>
                    <p class="mt-0.5 line-clamp-2 text-sm text-ink-600">{{ $log->deskripsi }}</p>
                    <p class="mt-1 text-xs text-ink-600">{{ $log->user_name }} &bull; {{ $log->created_at->diffForHumans() }}</p>
                </li>
                @if($loop->last)</ul>@endif
            @empty
                <div class="border-t border-line">
                    <x-empty-state title="Belum ada aktivitas surat" icon="clock" />
                </div>
            @endforelse
        </x-card>
    </div>
</div>
@endsection
