@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Kelola Data Warga</h1>
        <p class="text-sm text-slate-500">Total {{ $warga->total() }} warga terdaftar.</p>
    </div>
    <a href="{{ route('warga.create') }}"
       class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
        + Tambah Data Warga
    </a>
</div>

<div class="mb-4 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
    <form method="GET" action="{{ route('warga.index') }}" class="flex gap-2">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari nama atau NIK..."
            class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
        >
        <button type="submit" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">
            Cari
        </button>
        @if(request('q'))
            <a href="{{ route('warga.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700">
                Reset
            </a>
        @endif
    </form>
</div>

<div x-data="{ deleteTarget: null }" @keydown.escape.window="deleteTarget = null">

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-600">NIK</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-600">Nama</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-600">Jenis Kelamin</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-600">Alamat</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($warga as $item)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-slate-700">{{ $item->nik }}</td>
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $item->nama }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $item->jenis_kelamin ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ \Illuminate\Support\Str::limit($item->alamat, 40) ?: '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('warga.show', $item) }}" class="rounded-md px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-100">
                                Detail
                            </a>
                            <a href="{{ route('warga.edit', $item) }}" class="rounded-md px-2 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50">
                                Update
                            </a>
                            <button
                                @click="deleteTarget = { id: {{ $item->id }}, nama: @js($item->nama), nik: @js($item->nik), url: @js(route('warga.destroy', $item)) }"
                                type="button"
                                class="rounded-md px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-400">
                        @if(request('q'))
                            Tidak ada data warga yang cocok dengan pencarian "{{ request('q') }}".
                        @else
                            Belum ada data warga. Klik "Tambah Data Warga" untuk mulai.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal konfirmasi hapus, dipisah dari struktur tabel supaya form submit tidak terganggu --}}
<div
    x-show="deleteTarget !== null"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
>
    <div @click.outside="deleteTarget = null" class="w-full max-w-sm rounded-xl bg-white p-6 shadow-lg" x-show="deleteTarget !== null">
        <template x-if="deleteTarget">
            <div>
                <h3 class="text-base font-semibold text-slate-900">Hapus Data Warga?</h3>
                <p class="mt-2 text-sm text-slate-500">
                    Data <strong x-text="deleteTarget?.nama"></strong> (NIK: <span x-text="deleteTarget?.nik"></span>) akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="mt-5 flex justify-end gap-2">
                    <button @click="deleteTarget = null" type="button" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>
                    <form method="POST" :action="deleteTarget?.url">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>

</div>

<div class="mt-4">
    {{ $warga->links() }}
</div>
@endsection
