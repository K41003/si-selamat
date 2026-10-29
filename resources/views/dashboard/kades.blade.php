@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Executive Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-950 rounded-2xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <div class="flex items-center gap-2 mb-3">
                <span class="px-3 py-1 bg-amber-500/20 text-amber-300 rounded-full text-xs font-semibold border border-amber-500/30 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    Portal Verifikasi Kepala Desa
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                {{ auth()->user()->name }}
            </h1>
            <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                Tinjau dan validasi permohonan surat administrasi warga Desa Tanjung Selamat. Surat yang disetujui akan langsung diterbitkan dengan tanda tangan digital resmi dan kode QR otentikasi.
            </p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-amber-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Perlu Validasi</span>
                <span class="p-2 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-amber-600 mt-2 tabular-nums">{{ $stats['perlu_validasi'] }}</p>
            <p class="text-xs text-amber-700/80 mt-1 font-medium">Menunggu persetujuan Kades</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Surat Disetujui</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2 tabular-nums">{{ $stats['disetujui'] }}</p>
            <p class="text-xs text-emerald-600 mt-1">Telah diterbitkan resmi</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Surat Ditolak</span>
                <span class="p-2 bg-rose-50 text-rose-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2 tabular-nums">{{ $stats['ditolak'] }}</p>
            <p class="text-xs text-rose-600 mt-1">Dikembalikan untuk revisi</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Penduduk</span>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2 tabular-nums">{{ number_format($stats['total_warga']) }}</p>
            <p class="text-xs text-slate-400 mt-1">Warga Desa Tanjung Selamat</p>
        </div>
    </div>

    <!-- Antrean Validasi Surat Masuk -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span>Permohonan Menunggu Validasi</span>
                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">{{ $pendingSurat->count() }}</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Periksa keabsahan berkas permohonan sebelum menyetujui</p>
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($pendingSurat as $surat)
            <div class="p-5 hover:bg-slate-50/60 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1.5 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-mono font-bold text-slate-900">{{ $surat->nomor_surat }}</span>
                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[11px] font-semibold">
                            {{ $surat->jenisSurat->nama_jenis }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-600 flex flex-wrap gap-x-4 gap-y-1">
                        <span><strong>Pemohon:</strong> {{ $surat->warga->nama }} (NIK: {{ $surat->warga->masked_nik }})</span>
                        <span><strong>Diajukan oleh:</strong> {{ $surat->created_by_name }}</span>
                        <span><strong>Tanggal:</strong> {{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y') : $surat->created_at->format('d M Y') }}</span>
                    </div>
                    @if(!empty($surat->data_isian))
                    <div class="mt-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="font-semibold text-slate-800 block mb-0.5">Ringkasan Isian Permohonan:</span>
                        @foreach($surat->data_isian as $k => $v)
                            <span class="mr-3 text-slate-600"><strong class="capitalize">{{ str_replace('_', ' ', $k) }}:</strong> {{ $v }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('surat.show', $surat) }}" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Detail
                    </a>

                    <!-- Tombol Approve Form -->
                    <form method="POST" action="{{ route('validasi.approve', $surat) }}" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui dan menandatangani surat ini secara digital?')"
                            class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Setujui
                        </button>
                    </form>

                    <!-- Tombol Tolak dengan prompt catatan -->
                    <button type="button" onclick="promptReject('{{ route('validasi.reject', $surat) }}', '{{ $surat->nomor_surat }}')"
                        class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold transition">
                        Tolak
                    </button>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-semibold text-slate-700">Semua surat telah ditindaklanjuti!</p>
                <p class="text-xs text-slate-400 mt-0.5">Tidak ada permohonan surat yang tertunda saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Form Tolak Surat -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 mb-1">Tolak Permohonan Surat</h3>
        <p class="text-xs text-slate-500 mb-4" id="rejectSubtitle">Masukkan alasan atau catatan perbaikan untuk staff:</p>
        
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="catatan" required rows="3"
                class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none"
                placeholder="Contoh: Lampiran belum lengkap, harap konfirmasi ulang NIK warga..."></textarea>
            
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold">Tolak Permohonan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function promptReject(actionUrl, nomorSurat) {
        document.getElementById('rejectForm').action = actionUrl;
        document.getElementById('rejectSubtitle').innerText = 'Alasan penolakan surat No. ' + nomorSurat + ':';
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
</script>
@endsection
