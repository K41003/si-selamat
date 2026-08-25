@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Detail Warga</h1>
        <p class="text-sm text-slate-500">Informasi lengkap data kependudukan.</p>
    </div>
    <a href="{{ route('warga.edit', $warga) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
        Update Data
    </a>
</div>

<div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">NIK</dt>
            <dd class="mt-1 font-mono text-sm text-slate-900">{{ $warga->nik }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nomor KK</dt>
            <dd class="mt-1 font-mono text-sm text-slate-900">{{ $warga->no_kk ?: '-' }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nama Lengkap</dt>
            <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $warga->nama }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Tempat, Tanggal Lahir</dt>
            <dd class="mt-1 text-sm text-slate-900">
                {{ $warga->tempat_lahir ?: '-' }}, {{ $warga->tanggal_lahir?->format('d-m-Y') ?: '-' }}
            </dd>
        </div>
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Jenis Kelamin</dt>
            <dd class="mt-1 text-sm text-slate-900">{{ $warga->jenis_kelamin ?: '-' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Agama</dt>
            <dd class="mt-1 text-sm text-slate-900">{{ $warga->agama ?: '-' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Pekerjaan</dt>
            <dd class="mt-1 text-sm text-slate-900">{{ $warga->pekerjaan ?: '-' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Status Perkawinan</dt>
            <dd class="mt-1 text-sm text-slate-900">{{ $warga->status_perkawinan ?: '-' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nomor HP</dt>
            <dd class="mt-1 text-sm text-slate-900">{{ $warga->no_hp ?: '-' }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Alamat</dt>
            <dd class="mt-1 text-sm text-slate-900">
                {{ $warga->alamat ?: '-' }}
                @if($warga->rt || $warga->rw)
                    (RT {{ $warga->rt ?: '-' }} / RW {{ $warga->rw ?: '-' }})
                @endif
            </dd>
        </div>
    </dl>
</div>

<div class="mt-4">
    <a href="{{ route('warga.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
        &larr; Kembali ke daftar warga
    </a>
</div>
@endsection
