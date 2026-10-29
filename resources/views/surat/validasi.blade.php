@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Validasi & Tanda Tangan Surat</h1>
            <p class="text-xs sm:text-sm text-slate-500">Daftar permohonan surat yang memerlukan peninjauan dan persetujuan Kepala Desa</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Antrean Surat Masuk ({{ $pendingSurat->total() }})</h2>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($pendingSurat as $s)
            <div class="p-5 hover:bg-slate-50/60 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1.5 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-mono font-bold text-slate-900">{{ $s->nomor_surat }}</span>
                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[11px] font-semibold">
                            {{ $s->jenisSurat->nama_jenis }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-600 flex flex-wrap gap-x-4 gap-y-1">
                        <span><strong>Pemohon:</strong> {{ $s->warga->nama }} (NIK: {{ $s->warga->masked_nik }})</span>
                        <span><strong>Diajukan oleh:</strong> {{ $s->created_by_name ?: ($s->pembuat->name ?? '-') }}</span>
                        <span><strong>Tanggal:</strong> {{ $s->tanggal_pengajuan ? $s->tanggal_pengajuan->format('d M Y') : $s->created_at->format('d M Y') }}</span>
                    </div>

                    @if(!empty($s->data_isian))
                    <div class="mt-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="font-semibold text-slate-800 block mb-0.5">Keterangan Khusus:</span>
                        @foreach($s->data_isian as $k => $v)
                            <span class="mr-3 text-slate-600"><strong class="capitalize">{{ str_replace('_', ' ', $k) }}:</strong> {{ $v }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('surat.show', $s) }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Detail Lengkap
                    </a>

                    <form method="POST" action="{{ route('validasi.approve', $s) }}" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui dan menandatangani surat ini secara digital?')"
                            class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Setujui
                        </button>
                    </form>

                    <button type="button" onclick="promptReject('{{ route('validasi.reject', $s) }}', '{{ $s->nomor_surat }}')"
                        class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold transition">
                        Tolak
                    </button>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-semibold text-slate-700">Tidak ada permohonan surat yang tertunda.</p>
                <p class="text-xs text-slate-400 mt-0.5">Semua permohonan surat warga telah divalidasi.</p>
            </div>
            @endforelse
        </div>

        @if($pendingSurat->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pendingSurat->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tolak Surat -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 mb-1">Tolak Permohonan Surat</h3>
        <p class="text-xs text-slate-500 mb-4" id="rejectSubtitle">Masukkan alasan atau catatan perbaikan:</p>
        
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="catatan" required rows="3"
                class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none"
                placeholder="Alasan penolakan surat..."></textarea>
            
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
