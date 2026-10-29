@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Permohonan Surat</h1>
            <p class="text-xs sm:text-sm text-slate-500">Kelola dan pantau seluruh permohonan surat administrasi warga desa</p>
        </div>
        <a href="{{ route('surat.create') }}" class="px-4 py-2 rounded-xl bg-[#1E3A8A] hover:bg-[#172554] text-white text-xs sm:text-sm font-semibold transition shadow-xs flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Surat Baru
        </a>
    </div>

    <!-- Status Tabs & Filter -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-100 pb-3 text-xs font-semibold">
            <a href="{{ route('surat.index', ['status' => 'all', 'q' => $search]) }}"
                class="px-3 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ $status === 'all' ? 'bg-[#1E3A8A] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Semua</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('surat.index', ['status' => 'draft', 'q' => $search]) }}"
                class="px-3 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ $status === 'draft' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Draf</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'draft' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['draft'] }}</span>
            </a>
            <a href="{{ route('surat.index', ['status' => 'diajukan', 'q' => $search]) }}"
                class="px-3 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ $status === 'diajukan' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Menunggu Validasi</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'diajukan' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['diajukan'] }}</span>
            </a>
            <a href="{{ route('surat.index', ['status' => 'disetujui', 'q' => $search]) }}"
                class="px-3 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ $status === 'disetujui' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Disetujui / Selesai</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'disetujui' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['disetujui'] }}</span>
            </a>
            <a href="{{ route('surat.index', ['status' => 'ditolak', 'q' => $search]) }}"
                class="px-3 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ $status === 'ditolak' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Ditolak</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'ditolak' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['ditolak'] }}</span>
            </a>
        </div>

        <form method="GET" action="{{ route('surat.index') }}" class="flex gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari berdasarkan nomor surat, nama warga pemohon, jenis surat..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-medium transition">
                Filter
            </button>
            @if(!empty($search))
            <a href="{{ route('surat.index', ['status' => $status]) }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-medium transition flex items-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Surat List Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3">No. Surat & Jenis</th>
                        <th class="px-5 py-3">Pemohon</th>
                        <th class="px-5 py-3">Tanggal Pengajuan</th>
                        <th class="px-5 py-3">Petugas Pembuat</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($suratList as $s)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-slate-900 block font-mono">{{ $s->nomor_surat }}</span>
                            <span class="text-[11px] text-blue-700 font-medium">{{ $s->jenisSurat->nama_jenis ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-slate-800 block">{{ $s->warga->nama ?? '-' }}</span>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $s->warga->masked_nik ?? '' }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-600">
                            {{ $s->tanggal_pengajuan ? $s->tanggal_pengajuan->format('d/m/Y') : $s->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-600">
                            {{ $s->created_by_name ?: ($s->pembuat->name ?? '-') }}
                        </td>
                        <td class="px-5 py-3.5">
                            @if($s->status === 'draft')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Draf</span>
                            @elseif($s->status === 'diajukan')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Diajukan</span>
                            @elseif($s->status === 'disetujui')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                            @if($s->status === 'draft')
                            <form method="POST" action="{{ route('surat.ajukan', $s) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Ajukan draf surat ini ke Kepala Desa?')" class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-semibold transition inline-flex items-center gap-1 shadow-2xs">
                                    Ajukan
                                </button>
                            </form>
                            @endif

                            <a href="{{ route('surat.show', $s) }}" class="p-1.5 text-slate-600 hover:text-blue-700 rounded-lg hover:bg-slate-100 transition inline-block" title="Lihat Rincian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>

                            @if($s->status !== 'disetujui')
                            <a href="{{ route('surat.edit', $s) }}" class="p-1.5 text-slate-600 hover:text-amber-700 rounded-lg hover:bg-slate-100 transition inline-block" title="Edit Surat">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            @endif

                            @if($s->status === 'disetujui')
                            <a href="{{ route('surat.print', $s) }}" target="_blank" class="p-1.5 text-emerald-600 hover:text-emerald-800 rounded-lg hover:bg-emerald-50 transition inline-block" title="Cetak Surat Resmi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </a>
                            @endif

                            @if($s->status !== 'disetujui')
                            <form method="POST" action="{{ route('surat.destroy', $s) }}" class="inline" onsubmit="return confirm('Hapus surat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition inline-block" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                            Tidak ada surat yang ditemukan dalam filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suratList->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $suratList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
