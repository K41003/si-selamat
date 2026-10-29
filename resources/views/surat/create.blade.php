@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('surat.index') }}" class="p-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Buat Permohonan Surat</h1>
            <p class="text-xs text-slate-500">Pilih warga pemohon dan lengkapi data isian sesuai jenis surat</p>
        </div>
    </div>

    <form method="POST" action="{{ route('surat.store') }}" class="space-y-6">
        @csrf

        <!-- Langkah 1: Pilih Warga Pemohon -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">1</span>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Pilih Warga Pemohon</h2>
            </div>

            <div>
                <label for="warga_id" class="block text-xs font-semibold text-slate-700 mb-1">
                    Pilih Warga Terdaftar <span class="text-rose-500">*</span>
                </label>
                <select id="warga_id" name="warga_id" required onchange="updateWargaPreview(this.value)"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                    <option value="">-- Cari / Pilih Warga Pemohon --</option>
                    @foreach($wargaList as $w)
                    <option value="{{ $w->id }}"
                        data-nik="{{ $w->nik }}"
                        data-nama="{{ $w->nama }}"
                        data-alamat="{{ $w->alamat }}"
                        data-ttl="{{ $w->tempat_lahir }}, {{ date('d/m/Y', strtotime($w->tanggal_lahir)) }}"
                        data-gender="{{ $w->jenis_kelamin }}"
                        data-pekerjaan="{{ $w->pekerjaan }}"
                        {{ (old('warga_id', $selectedWarga->id ?? '') == $w->id) ? 'selected' : '' }}>
                        {{ $w->nama }} (NIK: {{ $w->nik }}) - {{ $w->pekerjaan }}
                    </option>
                    @endforeach
                </select>
                <span class="text-[10px] text-slate-400 mt-1 block">Warga belum terdaftar? <a href="{{ route('warga.create') }}" class="text-blue-700 font-semibold underline">Daftarkan data warga baru dahulu &rarr;</a></span>
            </div>

            <!-- Preview Data Warga Terpilih -->
            <div id="wargaPreviewBox" class="{{ $selectedWarga ? '' : 'hidden' }} p-4 rounded-xl bg-blue-50/60 border border-blue-100 text-xs space-y-2">
                <p class="font-bold text-blue-950 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    Ringkasan Pemohon:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-700">
                    <p><strong>Nama:</strong> <span id="prevNama">{{ $selectedWarga->nama ?? '-' }}</span></p>
                    <p><strong>NIK:</strong> <span id="prevNik" class="font-mono">{{ $selectedWarga->nik ?? '-' }}</span></p>
                    <p><strong>Tempat/Tgl Lahir:</strong> <span id="prevTtl">{{ $selectedWarga ? $selectedWarga->tempat_lahir . ', ' . date('d/m/Y', strtotime($selectedWarga->tanggal_lahir)) : '-' }}</span></p>
                    <p><strong>Pekerjaan:</strong> <span id="prevPekerjaan">{{ $selectedWarga->pekerjaan ?? '-' }}</span></p>
                    <p class="sm:col-span-2"><strong>Alamat:</strong> <span id="prevAlamat">{{ $selectedWarga->alamat ?? '-' }}</span></p>
                </div>
            </div>
        </div>

        <!-- Langkah 2: Pilih Jenis Surat -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">2</span>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Pilih Jenis Surat</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($jenisSuratList as $jenis)
                <label class="cursor-pointer border rounded-2xl p-4 transition flex flex-col justify-between hover:border-blue-500 hover:bg-blue-50/30 has-[:checked]:border-blue-700 has-[:checked]:bg-blue-50/70 has-[:checked]:shadow-xs">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">{{ $jenis->kode }}</span>
                            <input type="radio" name="jenis_surat_id" value="{{ $jenis->id }}"
                                {{ (old('jenis_surat_id', $selectedJenis->id ?? 1) == $jenis->id) ? 'checked' : '' }}
                                onchange="renderDynamicFields({{ json_encode($jenis) }})"
                                class="text-blue-600 focus:ring-blue-500">
                        </div>
                        <p class="text-xs font-bold text-slate-900">{{ $jenis->nama_jenis }}</p>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">{{ $jenis->deskripsi }}</p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Langkah 3: Formulir Isian Tambahan Surat -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">3</span>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Data Isian Keterangan Surat</h2>
            </div>

            <div id="dynamicFieldsContainer" class="space-y-4">
                <!-- Di-generate otomatis oleh script di bawah -->
            </div>
        </div>

        <!-- Tombol Aksi Simpan / Ajukan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="{{ route('surat.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                &larr; Batalkan
            </a>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="submit" name="action" value="draft"
                    class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition shadow-xs">
                    Simpan sebagai Draf
                </button>
                <button type="submit" name="action" value="ajukan"
                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-[#1E3A8A] hover:bg-[#172554] text-white text-xs sm:text-sm font-semibold transition shadow-md shadow-blue-900/10 flex items-center justify-center gap-1.5">
                    <span>Ajukan ke Kades</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const jenisSuratData = @json($jenisSuratList);

    function updateWargaPreview(id) {
        const box = document.getElementById('wargaPreviewBox');
        if (!id) {
            box.classList.add('hidden');
            return;
        }
        const select = document.getElementById('warga_id');
        const opt = select.options[select.selectedIndex];
        document.getElementById('prevNama').innerText = opt.dataset.nama || '-';
        document.getElementById('prevNik').innerText = opt.dataset.nik || '-';
        document.getElementById('prevTtl').innerText = opt.dataset.ttl || '-';
        document.getElementById('prevPekerjaan').innerText = opt.dataset.pekerjaan || '-';
        document.getElementById('prevAlamat').innerText = opt.dataset.alamat || '-';
        box.classList.remove('hidden');
    }

    function renderDynamicFields(jenis) {
        const container = document.getElementById('dynamicFieldsContainer');
        container.innerHTML = '';

        if (!jenis || !jenis.template_field || jenis.template_field.length === 0) {
            container.innerHTML = '<p class="text-xs text-slate-400">Tidak ada isian khusus tambahan untuk jenis surat ini.</p>';
            return;
        }

        jenis.template_field.forEach(field => {
            const div = document.createElement('div');
            const label = document.createElement('label');
            label.className = 'block text-xs font-semibold text-slate-700 mb-1';
            label.innerHTML = field.label + ' <span class="text-rose-500">*</span>';
            div.appendChild(label);

            let input;
            if (field.type === 'textarea') {
                input = document.createElement('textarea');
                input.rows = 3;
            } else {
                input = document.createElement('input');
                input.type = field.type || 'text';
            }

            input.name = `data_isian[${field.key}]`;
            input.required = true;
            input.placeholder = field.placeholder || '';
            input.className = 'w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none';
            div.appendChild(input);
            container.appendChild(div);
        });
    }

    // Inisialisasi saat load
    document.addEventListener('DOMContentLoaded', () => {
        const checkedRadio = document.querySelector('input[name="jenis_surat_id"]:checked');
        if (checkedRadio) {
            const currentJenis = jenisSuratData.find(j => j.id == checkedRadio.value);
            renderDynamicFields(currentJenis);
        }
    });
</script>
@endsection
