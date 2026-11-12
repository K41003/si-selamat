@extends('layouts.app')

@section('content')
@php
    $iconBtn = 'inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-canvas hover:text-ink-900';
@endphp

<div class="space-y-6">
    {{-- Header Halaman --}}
    <x-page-header title="Dashboard Kepala Desa"
                   :subtitle="'Selamat datang, ' . auth()->user()->name . '. Tinjau dan validasi permohonan surat administrasi warga.'">
        <x-slot:actions>
            <x-button variant="secondary" href="{{ route('validasi.index') }}">
                <x-icon name="check-circle" class="size-4"/> Buka halaman validasi
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Strip Statistik (1 Kartu Datar, 4 Kolom) --}}
    <section aria-label="Ringkasan validasi surat">
        <x-card :padded="false" class="overflow-hidden">
            <div class="grid grid-cols-2 gap-px bg-line lg:grid-cols-4">
                <x-stat label="Perlu validasi" :value="$stats['perlu_validasi']" hint="Menunggu persetujuan Anda" :emphasis="$stats['perlu_validasi'] > 0" />
                <x-stat label="Surat disetujui" :value="$stats['disetujui']" hint="Telah terbit & bertanda tangan" />
                <x-stat label="Surat ditolak" :value="$stats['ditolak']" hint="Memerlukan revisi staf" />
                <x-stat label="Total penduduk" :value="number_format($stats['total_warga'])" hint="Warga terdaftar" />
            </div>
        </x-card>
    </section>

    {{-- Grid 2 Kolom Asimetris --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">

        {{-- Kolom Utama: Antrean Permohonan Menunggu Validasi (Satu Kartu Terpadu) --}}
        <x-card :padded="false" class="overflow-hidden">
            <div class="flex items-center justify-between gap-4 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="text-title-sm font-semibold text-ink-900">Permohonan menunggu validasi</h2>
                    <p class="mt-0.5 text-sm text-ink-600">Periksa berkas dan tentukan persetujuan tanda tangan digital.</p>
                </div>
                @if($pendingSurat->isNotEmpty())
                <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-warning-100 px-2.5 py-1 text-xs font-semibold text-warning-800">
                    <span class="size-1.5 rounded-full bg-current"></span>
                    {{ $pendingSurat->count() }} perlu tindakan
                </span>
                @endif
            </div>

            @if($pendingSurat->isEmpty())
                <div class="border-t border-line">
                    <x-empty-state icon="check-circle"
                                   title="Semua permohonan telah ditindaklanjuti"
                                   description="Tidak ada permohonan surat warga yang menunggu validasi saat ini." />
                </div>
            @else
                <ul class="divide-y divide-line border-t border-line">
                    @foreach($pendingSurat as $s)
                    <li class="p-5 sm:px-6 hover:bg-canvas/40 transition-colors space-y-3">
                        {{-- Baris 1: Judul Surat, Nomor Surat, & Tanggal --}}
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <span class="font-semibold text-ink-900">{{ $s->jenisSurat->nama_jenis }}</span>
                                <span class="ml-2 font-mono text-xs text-ink-600">{{ $s->nomor_surat }}</span>
                            </div>
                            <span class="text-xs tabular-nums text-ink-500">
                                Diajukan {{ $s->tanggal_pengajuan ? $s->tanggal_pengajuan->format('d/m/Y') : $s->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        {{-- Baris 2: Pemohon & Staf Pengaju --}}
                        <div class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                            <div>
                                <span class="text-xs text-ink-500 block">Pemohon</span>
                                <span class="font-medium text-ink-900">{{ $s->warga->nama }}</span>
                                <span class="font-mono text-xs text-ink-600 ml-1">({{ $s->warga->masked_nik }})</span>
                            </div>
                            <div>
                                <span class="text-xs text-ink-500 block">Diajukan oleh Staf</span>
                                <span class="font-medium text-ink-900">{{ $s->created_by_name ?: ($s->pembuat->name ?? '-') }}</span>
                            </div>
                        </div>

                        {{-- Baris 3: Keterangan Khusus (Kompak & Elegan, Bukan Kotak Raksasa) --}}
                        @if(!empty($s->data_isian))
                        <div class="rounded-control bg-canvas/70 border border-line/70 px-3.5 py-2 text-xs">
                            <span class="font-semibold text-ink-700 mr-2">Keterangan:</span>
                            <span class="text-ink-800">
                                @php
                                    $keteranganPairs = [];
                                    foreach($s->isianUntukTampil() as $r) {
                                        $nilai = isset($r['list']) ? implode('; ', $r['list']) : $r['text'];
                                        if (is_numeric($nilai) && preg_match('/penghasilan|gaji|biaya/i', $r['label'])) {
                                            $nilai = 'Rp ' . number_format((float) $nilai, 0, ',', '.');
                                        }
                                        $keteranganPairs[] = "<strong class='font-medium text-ink-600'>" . e($r['label']) . ":</strong> " . e($nilai);
                                    }
                                @endphp
                                {!! implode(' &bull; ', $keteranganPairs) !!}
                            </span>
                        </div>
                        @endif

                        {{-- Baris 4: Footer Aksi --}}
                        <div class="flex items-center justify-between pt-1">
                            <a href="{{ route('surat.show', $s) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-brand-600 hover:text-brand-900">
                                <x-icon name="eye" class="size-3.5"/> Lihat detail berkas
                            </a>
                            <div class="flex items-center gap-2">
                                <x-button type="button" variant="secondary" size="sm" class="text-danger-800 hover:bg-danger-100/50 hover:border-danger-700" onclick="openDialog('tolak-{{ $s->id }}')">
                                    Tolak
                                </x-button>
                                <x-button type="button" variant="success" size="sm" onclick="openDialog('setujui-{{ $s->id }}')">
                                    <x-icon name="check-circle" class="size-4"/> Setujui &amp; tanda tangani
                                </x-button>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </x-card>

        {{-- Kolom Samping (20rem): Riwayat Keputusan Terkini & Info Otoritas --}}
        <div class="space-y-6">

            {{-- Riwayat Keputusan Terkini --}}
            <x-card :padded="false" class="overflow-hidden">
                <div class="flex items-center justify-between gap-2 px-5 py-4 border-b border-line">
                    <h3 class="text-title-sm font-semibold text-ink-900">Keputusan terbaru</h3>
                    <a href="{{ route('surat.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-900">
                        Semua <x-icon name="arrow-right" class="size-4"/>
                    </a>
                </div>

                @if($recentSurat->isEmpty())
                    <x-empty-state icon="clock" title="Belum ada keputusan" description="Surat yang disetujui atau ditolak akan dicatat di sini." />
                @else
                    <ul class="divide-y divide-line">
                        @foreach($recentSurat as $s)
                        <li class="px-5 py-3.5 hover:bg-canvas/50 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-ink-900">{{ $s->jenisSurat->nama_jenis ?? '-' }}</p>
                                    <p class="font-mono text-xs text-ink-600">{{ $s->nomor_surat }}</p>
                                </div>
                                <x-status-badge :status="$s->status" />
                            </div>

                            <div class="mt-2 flex items-center justify-between text-xs text-ink-600">
                                <span class="truncate font-medium text-ink-700">{{ $s->warga->nama ?? '-' }}</span>
                                <span class="tabular-nums shrink-0">
                                    {{ $s->validasi ? $s->validasi->created_at->format('d/m/Y') : $s->updated_at->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="mt-2.5 flex items-center justify-end gap-1 border-t border-line/60 pt-2">
                                <a href="{{ route('surat.show', $s) }}" class="{{ $iconBtn }}" title="Lihat detail" aria-label="Lihat detail surat {{ $s->nomor_surat }}">
                                    <x-icon name="eye" class="size-4"/>
                                </a>
                                @if($s->status === 'disetujui')
                                <a href="{{ route('surat.print', $s) }}" target="_blank" rel="noopener" class="{{ $iconBtn }}" title="Cetak surat" aria-label="Cetak surat {{ $s->nomor_surat }}">
                                    <x-icon name="printer" class="size-4"/>
                                </a>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>

            {{-- Kartu Info Otoritas Digital Kades --}}
            <x-card class="bg-brand-50/60 border-brand-100">
                <div class="flex items-start gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-brand-100 text-brand-700">
                        <x-icon name="check-circle" class="size-5"/>
                    </span>
                    <div>
                        <h4 class="text-sm font-semibold text-brand-900">Otoritas Tanda Tangan Digital</h4>
                        <p class="mt-1 text-xs text-ink-700 leading-relaxed">
                            Persetujuan Kades langsung menerbitkan tanda tangan elektronik dengan kode QR otentikasi resmi yang tercatat di audit trail sistem.
                        </p>
                    </div>
                </div>
            </x-card>

        </div>
    </div>
</div>

{{-- Dialog Konfirmasi Validasi (Native <dialog>) --}}
@foreach($pendingSurat as $s)
<x-confirm-dialog id="setujui-{{ $s->id }}" :action="route('validasi.approve', $s)"
    title="Setujui dan tanda tangani surat ini?" confirm-label="Setujui & tanda tangani" variant="success">
    <p>Surat <span class="font-mono">{{ $s->nomor_surat }}</span> untuk <strong>{{ $s->warga->nama }}</strong> akan disetujui dan tanda tangan digital (QR Code) diterbitkan resmi atas nama Kepala Desa.</p>
</x-confirm-dialog>

<x-confirm-dialog id="tolak-{{ $s->id }}" :action="route('validasi.reject', $s)"
    title="Tolak permohonan surat?" confirm-label="Tolak permohonan" variant="danger">
    <p class="mb-4">Berikan catatan perbaikan agar staf dapat memperbaiki atau melengkapi surat <span class="font-mono">{{ $s->nomor_surat }}</span>.</p>
    <x-field label="Alasan penolakan" name="catatan" as="textarea" rows="3" required id="catatan-{{ $s->id }}" placeholder="Contoh: Keterangan alamat usaha kurang spesifik, mohon verifikasi kembali." />
</x-confirm-dialog>
@endforeach
@endsection
