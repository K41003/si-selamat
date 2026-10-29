@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('warga.index') }}" class="p-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">{{ $warga->nama }}</h1>
                <p class="text-xs text-slate-500 font-mono">NIK: {{ $warga->nik }} &bull; No. KK: {{ $warga->no_kk }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('surat.create', ['warga_id' => $warga->id]) }}" class="px-3.5 py-2 rounded-xl bg-[#1E3A8A] hover:bg-[#172554] text-white text-xs font-semibold transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Surat
            </a>
            <a href="{{ route('warga.edit', $warga) }}" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold transition shadow-xs">
                Edit
            </a>
        </div>
    </div>

    <!-- Profile Details Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Biodata Kependudukan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-xs">
            <div>
                <span class="text-slate-400 block mb-0.5">Nama Lengkap</span>
                <span class="font-bold text-slate-800 text-sm">{{ $warga->nama }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Jenis Kelamin</span>
                <span class="font-semibold text-slate-800">{{ $warga->jenis_kelamin }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Tempat & Tanggal Lahir</span>
                <span class="font-semibold text-slate-800">{{ $warga->tempat_lahir }}, {{ date('d F Y', strtotime($warga->tanggal_lahir)) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Agama</span>
                <span class="font-semibold text-slate-800">{{ $warga->agama }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Pekerjaan</span>
                <span class="font-semibold text-slate-800">{{ $warga->pekerjaan }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Status Perkawinan</span>
                <span class="font-semibold text-slate-800">{{ $warga->status_perkawinan }}</span>
            </div>
            <div class="md:col-span-2">
                <span class="text-slate-400 block mb-0.5">Alamat Tempat Tinggal</span>
                <span class="font-semibold text-slate-800">{{ $warga->alamat }} (RT {{ $warga->rt ?? '-' }} / RW {{ $warga->rw ?? '-' }})</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">No. Telepon / HP</span>
                <span class="font-semibold text-slate-800">{{ $warga->no_hp ?: '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Terdaftar Sejak</span>
                <span class="text-slate-600">{{ $warga->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Riwayat Permohonan Surat -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Riwayat Permohonan Surat</h3>
                <p class="text-xs text-slate-500">Daftar arsip dan permohonan surat atas nama warga ini</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3">No. Surat</th>
                        <th class="px-5 py-3">Jenis Surat</th>
                        <th class="px-5 py-3">Tanggal Pengajuan</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($warga->surat as $s)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-3 font-mono font-semibold text-slate-900">{{ $s->nomor_surat }}</td>
                        <td class="px-5 py-3 text-slate-700">{{ $s->jenisSurat->nama_jenis ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $s->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            @if($s->status === 'draft')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Draf</span>
                            @elseif($s->status === 'diajukan')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Diajukan</span>
                            @elseif($s->status === 'disetujui')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right space-x-1">
                            <a href="{{ route('surat.show', $s) }}" class="p-1.5 text-slate-600 hover:text-blue-700 rounded-lg hover:bg-slate-100 inline-block" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            @if($s->status === 'disetujui')
                            <a href="{{ route('surat.print', $s) }}" target="_blank" class="p-1.5 text-emerald-600 hover:text-emerald-800 rounded-lg hover:bg-emerald-50 inline-block" title="Cetak Surat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-6 text-center text-slate-400">Belum ada riwayat permohonan surat untuk warga ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
