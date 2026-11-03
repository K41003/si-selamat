@extends('layouts.app')

@section('content')
@php
    $tabs = [
        ''      => 'Semua',
        'surat' => 'Surat',
        'warga' => 'Warga',
        'nik'   => 'Akses NIK',
        'akses' => 'Login / Logout',
    ];

    // Hitung per kategori untuk label tab
    $counts = [
        ''      => \App\Models\ActivityLog::count(),
        'surat' => \App\Models\ActivityLog::whereIn('aksi', ['Buat Surat','Ajukan Surat','Validasi Surat','Tolak Surat','Hapus Surat','Cetak Surat'])->count(),
        'warga' => \App\Models\ActivityLog::whereIn('aksi', ['Tambah Warga','Ubah Warga','Hapus Warga'])->count(),
        'nik'   => \App\Models\ActivityLog::whereIn('aksi', ['Buka NIK Warga'])->count(),
        'akses' => \App\Models\ActivityLog::whereIn('aksi', ['Login Sistem','Logout Sistem'])->count(),
    ];
@endphp

<div class="space-y-6">
    <x-page-header title="Log Aktivitas &amp; Audit Trail"
                   subtitle="Rekam jejak seluruh tindakan staf dan kepala desa pada sistem." />

    <x-card :padded="false" class="overflow-hidden">

        {{-- Tab kategori — pola sama dengan surat/index --}}
        <nav aria-label="Filter kategori log" class="flex gap-1 overflow-x-auto overflow-y-hidden border-b border-line px-3 sm:px-4">
            @foreach($tabs as $val => $label)
            @php $aktif = ($jenis ?? '') === $val; @endphp
            <a href="{{ route('logs.index', array_merge(request()->only('q', 'role'), ['jenis' => $val])) }}"
               @if($aktif) aria-current="page" @endif
               class="inline-flex h-11 shrink-0 items-center gap-2 border-b-2 px-3 text-sm transition-colors
                      {{ $aktif
                          ? 'border-brand-700 font-semibold text-brand-700'
                          : 'border-transparent font-medium text-ink-600 hover:border-line hover:text-ink-900' }}">
                {{ $label }}
                <span class="font-normal tabular-nums text-ink-600">{{ $counts[$val] }}</span>
            </a>
            @endforeach
        </nav>

        {{-- Filter pencarian + peran --}}
        <form method="GET" action="{{ route('logs.index') }}" role="search"
              class="flex flex-col gap-2 border-b border-line p-4 sm:flex-row">
            <input type="hidden" name="jenis" value="{{ $jenis ?? '' }}">

            {{-- Dropdown peran --}}
            <div class="sm:w-52">
                <label class="sr-only" for="filter-role">Filter peran</label>
                <select id="filter-role" name="role" class="field">
                    <option value="">Semua peran</option>
                    <option value="Staff Pelayanan" {{ ($role ?? '') === 'Staff Pelayanan' ? 'selected' : '' }}>Staff Pelayanan</option>
                    <option value="Kepala Desa"     {{ ($role ?? '') === 'Kepala Desa'     ? 'selected' : '' }}>Kepala Desa</option>
                </select>
            </div>

            {{-- Input pencarian --}}
            <div class="relative flex-1">
                <label for="cari-log" class="sr-only">Cari log aktivitas</label>
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-ink-600" />
                <input id="cari-log" type="search" name="q" value="{{ $search ?? '' }}"
                       placeholder="Cari nama petugas, jenis tindakan, atau deskripsi…"
                       class="field pl-10">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(!empty($search) || !empty($role))
                <a href="{{ route('logs.index', ['jenis' => $jenis ?? '']) }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>

        {{-- Tabel log --}}
        @if($logs->isEmpty())
            <x-empty-state icon="clock"
                           title="Tidak ada log yang ditemukan"
                           :description="!empty($search) ? 'Coba kata kunci lain atau hapus pencarian.' : 'Belum ada aktivitas pada kategori ini.'" >
                @if(!empty($search))
                <a href="{{ route('logs.index', ['jenis' => $jenis ?? '']) }}" class="btn btn-secondary">Hapus pencarian</a>
                @endif
            </x-empty-state>
        @else
            <x-data-table caption="Log aktivitas dan audit trail">
                <x-slot:head>
                    <tr>
                        <th scope="col" class="w-44">Waktu</th>
                        <th scope="col">Petugas</th>
                        <th scope="col">Tindakan</th>
                        <th scope="col">Deskripsi</th>
                    </tr>
                </x-slot:head>

                @foreach($logs as $log)
                <tr class="hover:bg-canvas/60">

                    {{-- Waktu --}}
                    <td class="whitespace-nowrap align-top">
                        <time datetime="{{ $log->created_at->toIso8601String() }}"
                              class="block tabular-nums text-ink-900">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </time>
                        <span class="text-xs text-ink-600">{{ $log->created_at->diffForHumans() }}</span>
                    </td>

                    {{-- Petugas --}}
                    <td class="whitespace-nowrap align-top">
                        <p class="font-medium text-ink-900">{{ $log->user_name }}</p>
                        @php $isKades = $log->user_role === 'Kepala Desa'; @endphp
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold
                                     {{ $isKades ? 'bg-warning-100 text-warning-800' : 'bg-info-100 text-info-800' }}">
                            <span class="size-1.5 rounded-full bg-current" aria-hidden="true"></span>
                            {{ $log->user_role }}
                        </span>
                    </td>

                    {{-- Tindakan --}}
                    <td class="whitespace-nowrap align-top">
                        @php
                            $aksiLower = strtolower($log->aksi);
                            $tone = match(true) {
                                str_contains($aksiLower, 'nik')                                      => 'bg-danger-100 text-danger-800',
                                str_contains($aksiLower, 'warga')                                    => 'bg-info-100 text-info-800',
                                str_contains($aksiLower, 'surat')                                    => 'bg-success-100 text-success-800',
                                str_contains($aksiLower, 'login') || str_contains($aksiLower, 'logout') => 'bg-canvas text-ink-700',
                                default                                                              => 'bg-canvas text-ink-700',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold {{ $tone }}">
                            <span class="size-1.5 rounded-full bg-current" aria-hidden="true"></span>
                            {{ $log->aksi }}
                        </span>
                    </td>

                    {{-- Deskripsi --}}
                    <td class="align-top text-ink-700">
                        {{ $log->deskripsi }}
                    </td>

                </tr>
                @endforeach
            </x-data-table>

            @if($logs->hasPages())
            <div class="border-t border-line p-4">
                {{ $logs->links() }}
            </div>
            @endif
        @endif

    </x-card>
</div>
@endsection
