@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Log Aktivitas & Audit Trail</h1>
            <p class="text-xs sm:text-sm text-slate-500">Rekam jejak seluruh aktivitas staf dan kepala desa pada sistem</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('logs.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="sm:w-48">
                <select name="role" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                    <option value="">Semua Peran</option>
                    <option value="Staff Pelayanan" {{ ($role ?? '') === 'Staff Pelayanan' ? 'selected' : '' }}>Staff Pelayanan</option>
                    <option value="Kepala Desa" {{ ($role ?? '') === 'Kepala Desa' ? 'selected' : '' }}>Kepala Desa</option>
                </select>
            </div>
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari aksi, nama petugas, deskripsi log..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-medium transition">
                Filter
            </button>
            @if(!empty($search) || !empty($role))
            <a href="{{ route('logs.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-medium transition flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3">Waktu Kejadian</th>
                        <th class="px-5 py-3">Petugas Pelaksana</th>
                        <th class="px-5 py-3">Jenis Tindakan</th>
                        <th class="px-5 py-3">Deskripsi Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-3.5 whitespace-nowrap text-slate-500 font-mono">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                            <span class="block text-[10px] text-slate-400 font-sans">{{ $log->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="font-bold text-slate-900 block">{{ $log->user_name }}</span>
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold mt-0.5 {{ $log->user_role === 'Kepala Desa' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                {{ $log->user_role }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="font-semibold text-slate-800">{{ $log->aksi }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-600 leading-relaxed">
                            {{ $log->deskripsi }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                            Tidak ditemukan log aktivitas yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
