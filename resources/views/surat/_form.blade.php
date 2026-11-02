{{-- Form surat (dipakai create & edit).
     Variabel: $jenisSuratList, $wargaTerpilih (Warga|null), $jenisTerpilihId, $isianAwal (array), $batalUrl --}}
@php
    $jenisTerpilih = old('jenis_surat_id', $jenisTerpilihId ?? null);
    $isianErrors = collect($errors->messages())
        ->filter(fn ($m, $k) => str_starts_with($k, 'data_isian.'))
        ->map(fn ($m) => $m[0]);
@endphp

<x-card :padded="false">
    <div class="space-y-8 p-5 sm:p-6">
        <section aria-labelledby="langkah-1" class="space-y-4">
            <h2 id="langkah-1" class="text-base font-semibold text-ink-900">1. Warga pemohon</h2>
            <x-warga-picker :url="route('warga.cari')" :selected="$wargaTerpilih ?? null" />
            <p class="text-sm text-ink-600">
                Warga belum terdaftar?
                <a href="{{ route('warga.create') }}" class="font-semibold text-brand-600 underline hover:text-brand-900">Daftarkan data warga baru dahulu</a>
            </p>
        </section>

        <section aria-labelledby="langkah-2" class="space-y-4 border-t border-line pt-6">
            <h2 id="langkah-2" class="text-base font-semibold text-ink-900">2. Jenis surat</h2>
            <fieldset>
                <legend class="sr-only">Pilih jenis surat</legend>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($jenisSuratList as $jenis)
                    <label class="flex cursor-pointer flex-col gap-1 rounded-control border border-line p-4 transition-colors hover:border-ink-500 has-[:checked]:border-brand-700 has-[:checked]:bg-brand-50 has-[:checked]:ring-1 has-[:checked]:ring-brand-700">
                        <span class="flex items-center justify-between">
                            <span class="font-mono text-xs font-semibold text-ink-600">{{ $jenis->kode }}</span>
                            <input type="radio" name="jenis_surat_id" value="{{ $jenis->id }}" required
                                   @checked($jenisTerpilih == $jenis->id) class="size-4 accent-brand-700">
                        </span>
                        <span class="font-semibold text-ink-900">{{ $jenis->nama_jenis }}</span>
                        <span class="text-sm text-ink-600">{{ $jenis->deskripsi }}</span>
                    </label>
                    @endforeach
                </div>
            </fieldset>
        </section>

        <section aria-labelledby="langkah-3" class="space-y-4 border-t border-line pt-6">
            <h2 id="langkah-3" class="text-base font-semibold text-ink-900">3. Data isian surat</h2>
            <div id="isian-container" class="space-y-5" aria-live="polite"></div>
        </section>
    </div>

    <div class="flex flex-col-reverse gap-2 border-t border-line rounded-b-card bg-canvas px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <x-button variant="ghost" href="{{ $batalUrl }}">Batal</x-button>
        <div class="flex flex-col gap-2 sm:flex-row">
            <button type="submit" name="action" value="draft" class="btn btn-secondary">Simpan sebagai draf</button>
            <button type="submit" name="action" value="ajukan" class="btn btn-primary">
                Ajukan ke Kades <x-icon name="arrow-right" class="size-4"/>
            </button>
        </div>
    </div>
</x-card>

<script>
(() => {
    const jenisData = @json($jenisSuratList);
    const awal = @json(old('data_isian', $isianAwal ?? []));
    const galat = @json($isianErrors);
    const container = document.getElementById('isian-container');
    const cache = Object.assign({}, awal);

    function simpanNilai() {
        container.querySelectorAll('[data-key]').forEach((el) => { cache[el.dataset.key] = el.value; });
    }

    function render(jenisId) {
        simpanNilai();
        container.replaceChildren();
        const jenis = jenisData.find((j) => String(j.id) === String(jenisId));
        const fields = jenis && Array.isArray(jenis.template_field) ? jenis.template_field : [];

        if (!fields.length) {
            const p = document.createElement('p');
            p.className = 'text-sm text-ink-600';
            p.textContent = jenis ? 'Tidak ada isian khusus tambahan untuk jenis surat ini.' : 'Pilih jenis surat untuk menampilkan isiannya.';
            container.appendChild(p);
            return;
        }

        fields.forEach((f) => {
            const id = 'isian-' + f.key;
            const wrap = document.createElement('div');
            wrap.className = 'space-y-1.5';

            const label = document.createElement('label');
            label.htmlFor = id;
            label.className = 'block text-sm font-medium text-ink-900';
            label.textContent = f.label + ' ';
            const wajib = document.createElement('span');
            wajib.className = 'font-normal text-danger-700';
            wajib.textContent = '(wajib)';
            label.appendChild(wajib);

            const input = f.type === 'textarea' ? document.createElement('textarea') : document.createElement('input');
            if (f.type === 'textarea') { input.rows = 3; } else { input.type = f.type || 'text'; }
            input.id = id;
            input.name = 'data_isian[' + f.key + ']';
            input.required = true;
            input.className = 'field';
            input.placeholder = f.placeholder || '';
            input.dataset.key = f.key;
            if (cache[f.key] !== undefined && cache[f.key] !== null) input.value = cache[f.key];

            wrap.append(label, input);

            const pesan = galat['data_isian.' + f.key];
            if (pesan) {
                input.setAttribute('aria-invalid', 'true');
                input.setAttribute('aria-describedby', id + '-error');
                const err = document.createElement('p');
                err.id = id + '-error';
                err.className = 'text-sm font-medium text-danger-700';
                err.textContent = pesan;
                wrap.appendChild(err);
            }
            container.appendChild(wrap);
        });
    }

    document.querySelectorAll('input[name="jenis_surat_id"]').forEach((radio) => {
        radio.addEventListener('change', () => render(radio.value));
    });
    const dipilih = document.querySelector('input[name="jenis_surat_id"]:checked');
    render(dipilih ? dipilih.value : null);
})();
</script>
