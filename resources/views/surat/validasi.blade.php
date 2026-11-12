@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <x-page-header title="Validasi & Tanda Tangan Surat" subtitle="Permohonan surat yang menunggu peninjauan dan persetujuan Kepala Desa." />

    <x-card :padded="false" class="overflow-hidden">
        <div class="flex items-center justify-between gap-4 px-5 py-4 sm:px-6">
            <h2 class="text-title-sm font-semibold text-ink-900">Antrean surat masuk</h2>
            <span class="text-sm tabular-nums text-ink-600">{{ $pendingSurat->total() }} surat</span>
        </div>

        @if($pendingSurat->isEmpty())
            <div class="border-t border-line">
                <x-empty-state icon="check-circle" title="Tidak ada permohonan yang tertunda" description="Semua permohonan surat warga sudah divalidasi." />
            </div>
        @else
        <ul class="divide-y divide-line border-t border-line">
            @foreach($pendingSurat as $s)
            <li class="flex flex-col gap-4 px-5 py-5 sm:px-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 flex-1 space-y-3">
                    <div>
                        <p class="font-semibold text-ink-900">{{ $s->jenisSurat->nama_jenis }}</p>
                        <p class="font-mono text-xs text-ink-600">{{ $s->nomor_surat }}</p>
                    </div>

                    <dl class="grid gap-x-6 gap-y-1 text-sm sm:grid-cols-3">
                        <div><dt class="text-ink-600">Pemohon</dt><dd class="font-medium text-ink-900">{{ $s->warga->nama }} <span class="font-mono text-xs font-normal text-ink-600">{{ $s->warga->masked_nik }}</span></dd></div>
                        <div><dt class="text-ink-600">Diajukan oleh</dt><dd class="font-medium text-ink-900">{{ $s->created_by_name ?: ($s->pembuat->name ?? '-') }}</dd></div>
                        <div><dt class="text-ink-600">Tanggal</dt><dd class="font-medium tabular-nums text-ink-900">{{ $s->tanggal_pengajuan ? $s->tanggal_pengajuan->format('d/m/Y') : $s->created_at->format('d/m/Y') }}</dd></div>
                    </dl>

                    @if(!empty($s->data_isian))
                    <div class="rounded-control border border-line bg-canvas p-3">
                        <p class="text-sm font-medium text-ink-900">Keterangan khusus</p>
                        <dl class="mt-2 grid gap-x-6 gap-y-1 text-sm sm:grid-cols-2">
                            @foreach($s->isianUntukTampil() as $r)
                            <div>
                                <dt class="text-ink-600">{{ $r['label'] }}</dt>
                                <dd class="break-words text-ink-900">{{ isset($r['list']) ? implode('; ', $r['list']) : $r['text'] }}</dd>
                            </div>
                            @endforeach
                        </dl>
                    </div>
                    @endif
                </div>

                <div class="flex shrink-0 flex-wrap gap-2">
                    <x-button variant="secondary" size="sm" href="{{ route('surat.show', $s) }}">Detail</x-button>
                    <x-button type="button" variant="secondary" size="sm" class="text-danger-800" onclick="openDialog('tolak-{{ $s->id }}')">Tolak</x-button>
                    <x-button type="button" variant="success" size="sm" onclick="openDialog('setujui-{{ $s->id }}')">
                        <x-icon name="check-circle" class="size-4"/> Setujui
                    </x-button>
                </div>
            </li>
            @endforeach
        </ul>

        @if($pendingSurat->hasPages())
        <div class="border-t border-line p-4">{{ $pendingSurat->links() }}</div>
        @endif
        @endif
    </x-card>
</div>

@foreach($pendingSurat as $s)
<x-confirm-dialog id="setujui-{{ $s->id }}" :action="route('validasi.approve', $s)"
    title="Setujui dan tanda tangani surat ini?" confirm-label="Setujui & tanda tangani" variant="success">
    <p>Surat <span class="font-mono">{{ $s->nomor_surat }}</span> untuk <strong>{{ $s->warga->nama }}</strong> akan disetujui dan e-signature diterbitkan atas nama Anda.</p>
</x-confirm-dialog>

<x-confirm-dialog id="tolak-{{ $s->id }}" :action="route('validasi.reject', $s)"
    title="Tolak permohonan surat?" confirm-label="Tolak permohonan" variant="danger">
    <p class="mb-4">Berikan catatan perbaikan agar staf dapat memperbaiki surat <span class="font-mono">{{ $s->nomor_surat }}</span>.</p>
    <x-field label="Alasan penolakan" name="catatan" as="textarea" rows="3" required id="catatan-{{ $s->id }}" placeholder="Contoh: Alamat usaha belum lengkap." />
</x-confirm-dialog>
@endforeach
@endsection
