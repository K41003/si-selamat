@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Kependudukan</h1>
            <p class="text-xs sm:text-sm text-slate-500">Master data warga penduduk Desa Tanjung Selamat, Kecamatan Sunggal</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="toggleNikMask()" id="btnMask" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50 transition shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span id="btnMaskText">Buka Sensor NIK</span>
            </button>
            <a href="{{ route('warga.create') }}" class="px-4 py-2 rounded-xl bg-[#1E3A8A] hover:bg-[#172554] text-white text-xs sm:text-sm font-semibold transition shadow-xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Warga
            </a>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('warga.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari berdasarkan Nama, NIK 16 digit, atau No. KK..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-medium transition">
                Cari
            </button>
            @if(!empty($q))
            <a href="{{ route('warga.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-medium transition flex items-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Warga Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3">Nama & Jenis Kelamin</th>
                        <th class="px-5 py-3">NIK & No. KK</th>
                        <th class="px-5 py-3">Tempat / Tgl Lahir</th>
                        <th class="px-5 py-3">Pekerjaan</th>
                        <th class="px-5 py-3">Alamat</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($warga as $w)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-slate-900 block text-sm">{{ $w->nama }}</span>
                            <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-semibold {{ $w->jenis_kelamin === 'Laki-laki' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                {{ $w->jenis_kelamin }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-slate-700">
                            <span class="nik-masked block font-medium">{{ $w->masked_nik }}</span>
                            <span class="nik-real hidden font-medium text-blue-900">{{ $w->nik }}</span>
                            <span class="text-[11px] text-slate-400 block">KK: {{ $w->no_kk }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-600">
                            {{ $w->tempat_lahir }}, {{ date('d/m/Y', strtotime($w->tanggal_lahir)) }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-600">
                            {{ $w->pekerjaan }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-600 max-w-xs truncate" title="{{ $w->alamat }}">
                            {{ $w->alamat }}
                            @if($w->rt || $w->rw)
                            <span class="block text-[11px] text-slate-400">RT {{ $w->rt ?? '-' }} / RW {{ $w->rw ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                            <a href="{{ route('surat.create', ['warga_id' => $w->id]) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-800 text-[11px] font-semibold transition inline-flex items-center gap-1" title="Buat surat permohonan untuk warga ini">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Surat
                            </a>
                            <a href="{{ route('warga.show', $w) }}" class="p-1.5 text-slate-600 hover:text-blue-700 rounded-lg hover:bg-slate-100 transition inline-block" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('warga.edit', $w) }}" class="p-1.5 text-slate-600 hover:text-amber-700 rounded-lg hover:bg-slate-100 transition inline-block" title="Edit Data">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form method="POST" action="{{ route('warga.destroy', $w) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kependudukan warga ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition inline-block" title="Hapus Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                            Tidak ditemukan data warga yang sesuai dengan pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($warga->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $warga->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    let isMasked = true;
    function toggleNikMask() {
        isMasked = !isMasked;
        const maskedEls = document.querySelectorAll('.nik-masked');
        const realEls = document.querySelectorAll('.nik-real');
        const btnText = document.getElementById('btnMaskText');

        if (isMasked) {
            maskedEls.forEach(el => el.classList.remove('hidden'));
            realEls.forEach(el => el.classList.add('hidden'));
            btnText.innerText = 'Buka Sensor NIK';
        } else {
            maskedEls.forEach(el => el.classList.add('hidden'));
            realEls.forEach(el => el.classList.remove('hidden'));
            btnText.innerText = 'Sensor NIK';
        }
    }
</script>
@endsection
