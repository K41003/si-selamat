@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('surat.index') }}" class="p-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">{{ $surat->jenisSurat->nama_jenis }}</h1>
                <p class="text-xs text-slate-500 font-mono">No. Surat: {{ $surat->nomor_surat }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($surat->status === 'disetujui')
            <a href="{{ route('surat.print', $surat) }}" target="_blank"
                class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold transition shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Surat Resmi</span>
            </a>
            @endif

            @if($surat->status === 'draft')
            <form method="POST" action="{{ route('surat.ajukan', $surat) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Ajukan surat ini ke Kepala Desa?')"
                    class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    <span>Ajukan ke Kades</span>
                </button>
            </form>
            <a href="{{ route('surat.edit', $surat) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold transition shadow-xs">
                Edit Draf
            </a>
            @endif

            @if($surat->status === 'ditolak')
            <a href="{{ route('surat.edit', $surat) }}" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs">
                Revisi / Edit
            </a>
            @endif
        </div>
    </div>

    <!-- Status Badge Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            @if($surat->status === 'draft')
            <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </span>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Status Dokumen</p>
                <p class="text-sm font-bold text-amber-700">Draf Surat (Belum Diajukan)</p>
            </div>
            @elseif($surat->status === 'diajukan')
            <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Status Dokumen</p>
                <p class="text-sm font-bold text-blue-700">Menunggu Validasi Kepala Desa</p>
            </div>
            @elseif($surat->status === 'disetujui')
            <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Status Dokumen</p>
                <p class="text-sm font-bold text-emerald-700">Disetujui & E-Signature Diterbitkan</p>
            </div>
            @else
            <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </span>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Status Dokumen</p>
                <p class="text-sm font-bold text-rose-700">Permohonan Ditolak</p>
            </div>
            @endif
        </div>

        @if(auth()->check() && auth()->user()->isKades() && $surat->status === 'diajukan')
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('validasi.approve', $surat) }}" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Setujui dan tanda tangani surat ini?')" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs">
                    Setujui Surat
                </button>
            </form>
            <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden'); document.getElementById('rejectModal').classList.add('flex');"
                class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold">
                Tolak...
            </button>
        </div>
        @endif
    </div>

    <!-- Data Pemohon & Isian Surat -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Biodata Pemohon -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
                Biodata Warga Pemohon
            </h2>
            <div class="space-y-2.5 text-xs">
                <div>
                    <span class="text-slate-400 block text-[11px]">Nama Lengkap:</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $surat->warga->nama }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">NIK / No. KK:</span>
                    <span class="font-mono text-slate-800">{{ $surat->warga->nik }} / {{ $surat->warga->no_kk }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Tempat, Tanggal Lahir:</span>
                    <span class="text-slate-800 font-medium">{{ $surat->warga->tempat_lahir }}, {{ date('d F Y', strtotime($surat->warga->tanggal_lahir)) }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Jenis Kelamin / Agama:</span>
                    <span class="text-slate-800 font-medium">{{ $surat->warga->jenis_kelamin }} &bull; {{ $surat->warga->agama }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Pekerjaan:</span>
                    <span class="text-slate-800 font-medium">{{ $surat->warga->pekerjaan }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Alamat:</span>
                    <span class="text-slate-800 font-medium">{{ $surat->warga->alamat }} (RT {{ $surat->warga->rt ?? '-' }} / RW {{ $surat->warga->rw ?? '-' }})</span>
                </div>
            </div>
        </div>

        <!-- Detail Keterangan Surat -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
                Data Isian Keterangan Khusus
            </h2>
            <div class="space-y-3 text-xs">
                @if(!empty($surat->data_isian))
                    @foreach($surat->data_isian as $key => $val)
                    <div>
                        <span class="text-slate-400 block text-[11px] capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                        <span class="font-semibold text-slate-800 block bg-slate-50 p-2.5 rounded-xl border border-slate-100 mt-0.5">
                            {{ $val }}
                        </span>
                    </div>
                    @endforeach
                @else
                    <p class="text-slate-400 italic">Tidak ada data isian spesifik tambahan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Riwayat Validasi & E-Signature -->
    @if($surat->validasi)
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
            Catatan Keabsahan & E-Signature
        </h2>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
            <div class="space-y-1">
                <p><strong>Divalidasi oleh:</strong> {{ $surat->validasi->validator_name }}</p>
                <p><strong>Waktu Validasi:</strong> {{ $surat->validasi->validated_at->format('d/m/Y H:i:s') }}</p>
                @if($surat->validasi->catatan)
                <p><strong>Catatan:</strong> <span class="text-slate-600">{{ $surat->validasi->catatan }}</span></p>
                @endif
            </div>

            @if($surat->validasi->qr_code_token)
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-center shrink-0">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Token QR Resmi:</span>
                <span class="font-mono text-xs font-bold text-blue-900">{{ $surat->validasi->qr_code_token }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Modal Tolak Surat (Kades) -->
@if(auth()->check() && auth()->user()->isKades() && $surat->status === 'diajukan')
<div id="rejectModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 mb-1">Tolak Permohonan Surat</h3>
        <p class="text-xs text-slate-500 mb-4">Berikan catatan perbaikan agar staff dapat memperbaikinya:</p>
        
        <form method="POST" action="{{ route('validasi.reject', $surat) }}">
            @csrf
            <textarea name="catatan" required rows="3"
                class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500 focus:outline-none"
                placeholder="Alasan penolakan surat..."></textarea>
            
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden'); document.getElementById('rejectModal').classList.remove('flex');"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold">Tolak Surat</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
