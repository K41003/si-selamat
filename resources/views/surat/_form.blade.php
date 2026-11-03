{{-- Form surat (dipakai create & edit).
     Variabel: $jenisSuratList, $wargaTerpilih (Warga|null), $jenisTerpilihId, $isianAwal (array), $batalUrl, $nextNomorUrut, $tahun, $nomorSuratEksis (string|null) --}}
@php
    $jenisTerpilih = old('jenis_surat_id', $jenisTerpilihId ?? null);
    $isianErrors = collect($errors->messages())
        ->filter(fn ($m, $k) => str_starts_with($k, 'data_isian.'))
        ->map(fn ($m) => $m[0]);
    $serverErrorWarga = $errors->first('warga_id');

    $wargaData = null;
    $initialWarga = old('warga_id') ? \App\Models\Warga::find(old('warga_id')) : ($wargaTerpilih ?? null);
    if ($initialWarga) {
        $wargaData = [
            'id' => $initialWarga->id,
            'nik' => $initialWarga->nik,
            'nik_masked' => $initialWarga->masked_nik,
            'nama' => $initialWarga->nama,
            'no_kk' => $initialWarga->no_kk,
            'ttl' => $initialWarga->tempat_lahir . ', ' . date('d/m/Y', strtotime($initialWarga->tanggal_lahir)),
            'jenis_kelamin' => $initialWarga->jenis_kelamin,
            'agama' => $initialWarga->agama,
            'status_perkawinan' => $initialWarga->status_perkawinan,
            'pekerjaan' => $initialWarga->pekerjaan,
            'alamat_lengkap' => $initialWarga->alamat . ($initialWarga->rt || $initialWarga->rw ? " (RT {$initialWarga->rt} / RW {$initialWarga->rw})" : ''),
        ];
    }
@endphp

<x-card :padded="false">
    <div class="space-y-8 p-5 sm:p-6" x-data="suratForm({
        jenisList: @js($jenisSuratList),
        jenisTerpilihId: @js($jenisTerpilih),
        wargaDataAwal: @js($wargaData),
        nomorEksis: @js($nomorSuratEksis ?? null),
        nextUrut: @js($nextNomorUrut ?? '01'),
        tahun: @js($tahun ?? date('Y')),
        cariUrl: @js(route('warga.cari')),
        isianAwal: @js(old('data_isian', $isianAwal ?? [])),
        galatIsian: @js($isianErrors)
    })">

        {{-- LANGKAH 1: PILIH JENIS SURAT & NOMOR SURAT OTOMATIS --}}
        <section aria-labelledby="langkah-1" class="space-y-4">
            <div class="flex items-center justify-between border-b border-line pb-2">
                <h2 id="langkah-1" class="text-base font-semibold text-ink-900 flex items-center gap-2">
                    <span class="flex size-6 items-center justify-center rounded-full bg-brand-700 text-xs font-bold text-white">1</span>
                    <span>Pilih Jenis Surat &amp; Nomor Surat</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Dropdown Jenis Surat --}}
                <div class="space-y-1.5">
                    <label for="jenis_surat_id" class="block text-sm font-medium text-ink-900">
                        Jenis Surat <span class="font-normal text-danger-700">(wajib)</span>
                    </label>
                    <select id="jenis_surat_id" name="jenis_surat_id"
                            x-model="selectedJenisId"
                            @change="onJenisChange()"
                            required
                            class="field">
                        <option value="">-- Pilih Jenis Surat --</option>
                        @foreach($jenisSuratList as $j)
                        <option value="{{ $j->id }}" @selected($jenisTerpilih == $j->id)>
                            {{ $j->nama_jenis }} ({{ $j->kode }})
                        </option>
                        @endforeach
                    </select>
                    <template x-if="selectedJenis">
                        <p class="text-xs text-ink-600 mt-1" x-text="selectedJenis.deskripsi"></p>
                    </template>
                </div>

                {{-- Nomor Surat Otomatis --}}
                <div class="space-y-1.5">
                    <label for="nomor_surat_preview" class="block text-sm font-medium text-ink-900 flex items-center justify-between">
                        <span>Nomor Surat</span>
                        <span class="inline-flex items-center gap-1 rounded bg-brand-50 px-2 py-0.5 text-xs font-semibold text-brand-700 border border-brand-200">
                            <x-icon name="check" class="size-3"/> Otomatis
                        </span>
                    </label>
                    <div class="relative">
                        <input type="text" id="nomor_surat_preview" readonly
                               :value="nomorSuratDisplay"
                               class="field bg-canvas font-mono font-semibold text-ink-900 cursor-not-allowed">
                    </div>
                    <p class="text-xs text-ink-500">Nomor registrasi resmi yang digenerate otomatis berdasarkan format Desa Tanjung Selamat.</p>
                </div>
            </div>
        </section>

        {{-- LANGKAH 2: INPUT NIK & DATA WARGA OTOMATIS --}}
        <section aria-labelledby="langkah-2" class="space-y-4 border-t border-line pt-6">
            <div class="flex items-center justify-between border-b border-line pb-2">
                <h2 id="langkah-2" class="text-base font-semibold text-ink-900 flex items-center gap-2">
                    <span class="flex size-6 items-center justify-center rounded-full bg-brand-700 text-xs font-bold text-white">2</span>
                    <span>Data Warga Pemohon (Input NIK)</span>
                </h2>
                <a href="{{ route('warga.create') }}" target="_blank" class="text-xs font-semibold text-brand-600 hover:text-brand-900 inline-flex items-center gap-1">
                    <x-icon name="user-plus" class="size-3.5"/> + Daftarkan Warga Baru
                </a>
            </div>

            {{-- Hidden input warga_id untuk submit form --}}
            <input type="hidden" name="warga_id" :value="selectedWarga ? selectedWarga.id : ''" required>

            {{-- Input NIK / Pencarian --}}
            <div class="space-y-3">
                <div>
                    <label for="input-nik" class="block text-sm font-medium text-ink-900">
                        Nomor Induk Kependudukan (NIK) <span class="font-normal text-danger-700">(wajib 16 digit)</span>
                    </label>
                    <div class="relative mt-1.5 flex gap-2">
                        <div class="relative flex-1">
                            <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-ink-600"/>
                            <input type="text" id="input-nik"
                                   x-ref="nikInput"
                                   x-model="nikQuery"
                                   @input="onNikInput()"
                                   @keydown.enter.prevent="cariByNik()"
                                   maxlength="16"
                                   inputmode="numeric"
                                   placeholder="Ketik 16 digit NIK warga (atau ketik nama untuk mencari)..."
                                   :class="{'border-danger-700 ring-danger-700': errorWarga}"
                                   class="field pl-10 font-mono tracking-wide">

                            {{-- Autocomplete dropdown saat mencari --}}
                            <div x-show="autocompleteOpen && searchResults.length > 0"
                                 @click.outside="autocompleteOpen = false"
                                 x-cloak
                                 class="absolute z-30 mt-1 max-h-60 w-full overflow-auto rounded-control border border-line bg-surface shadow-xl">
                                <ul class="divide-y divide-line text-sm">
                                    <template x-for="r in searchResults" :key="r.id">
                                        <li @click="pilihWarga(r)"
                                            class="cursor-pointer px-4 py-2.5 hover:bg-brand-50 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <p class="font-semibold text-ink-900" x-text="r.nama"></p>
                                                <span class="font-mono text-xs text-ink-600" x-text="r.nik"></span>
                                            </div>
                                            <p class="text-xs text-ink-500 mt-0.5">
                                                <span x-text="r.pekerjaan || '-'"></span> &bull; <span x-text="r.alamat_lengkap"></span>
                                            </p>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <button type="button" @click="cariByNik()" :disabled="loadingWarga" class="btn btn-secondary shrink-0">
                            <template x-if="!loadingWarga">
                                <span>Cek NIK</span>
                            </template>
                            <template x-if="loadingWarga">
                                <span class="flex items-center gap-1.5 text-xs">
                                    <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Mengecek...
                                </span>
                            </template>
                        </button>
                    </div>

                    <p class="mt-1.5 text-xs text-ink-500">
                        Ketik 16 digit NIK sesuai KTP, data warga akan terdeteksi dan terisi otomatis ke formulir di bawah.
                    </p>
                </div>

                {{-- Status Pesan Error jika NIK tidak ditemukan --}}
                <template x-if="errorWarga">
                    <div class="rounded-control border border-danger-200 bg-danger-50 p-3.5 text-sm text-danger-800 flex items-start gap-2.5">
                        <x-icon name="exclamation" class="size-5 shrink-0 text-danger-700 mt-0.5"/>
                        <div class="flex-1">
                            <p class="font-semibold" x-text="errorWarga"></p>
                            <p class="mt-1 text-xs text-danger-700">
                                Pastikan 16 digit NIK sudah sesuai KTP warga. Jika belum terdaftar, silakan tambahkan data warga terlebih dahulu.
                            </p>
                            <a href="{{ route('warga.create') }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-brand-700 underline hover:text-brand-900">
                                <x-icon name="user-plus" class="size-3.5"/> Daftarkan Warga Baru Sekarang &rarr;
                            </a>
                        </div>
                    </div>
                </template>

                @if($serverErrorWarga)
                <p class="text-sm font-medium text-danger-700">{{ $serverErrorWarga }}</p>
                @endif

                {{-- PANEL DATA WARGA OTOMATIS (MUNCUL OTOMATIS KETIKA NIK DITEMUKAN) --}}
                <div x-show="selectedWarga" x-cloak class="rounded-control border border-emerald-200 bg-emerald-50/50 p-4.5 sm:p-5 transition-all">
                    <div class="flex items-start justify-between gap-3 border-b border-emerald-200/80 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex size-7 items-center justify-center rounded-full bg-emerald-600 text-white">
                                <x-icon name="check" class="size-4"/>
                            </span>
                            <div>
                                <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider block">Data Warga Ditemukan (Otomatis)</span>
                                <h3 class="text-base font-bold text-ink-900" x-text="selectedWarga ? selectedWarga.nama : ''"></h3>
                            </div>
                        </div>
                        <button type="button" @click="resetWarga()" class="btn btn-secondary btn-sm text-xs">
                            Ganti Warga
                        </button>
                    </div>

                    <dl class="mt-4 grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-2 lg:grid-cols-3 text-sm">
                        <div>
                            <dt class="text-xs text-ink-600">NIK (KTP)</dt>
                            <dd class="font-mono font-semibold text-ink-900" x-text="selectedWarga ? selectedWarga.nik : ''"></dd>
                        </div>
                        <div>
                            <dt class="text-xs text-ink-600">Nomor Kartu Keluarga (KK)</dt>
                            <dd class="font-mono text-ink-900" x-text="(selectedWarga && selectedWarga.no_kk) ? selectedWarga.no_kk : '-'"></dd>
                        </div>
                        <div>
                            <dt class="text-xs text-ink-600">Tempat, Tanggal Lahir</dt>
                            <dd class="font-medium text-ink-900" x-text="selectedWarga ? selectedWarga.ttl : ''"></dd>
                        </div>
                        <div>
                            <dt class="text-xs text-ink-600">Jenis Kelamin</dt>
                            <dd class="text-ink-900" x-text="selectedWarga ? selectedWarga.jenis_kelamin : ''"></dd>
                        </div>
                        <div>
                            <dt class="text-xs text-ink-600">Agama &amp; Status</dt>
                            <dd class="text-ink-900">
                                <span x-text="selectedWarga ? selectedWarga.agama : ''"></span> &bull; 
                                <span x-text="selectedWarga ? selectedWarga.status_perkawinan : ''"></span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-ink-600">Pekerjaan</dt>
                            <dd class="text-ink-900" x-text="(selectedWarga && selectedWarga.pekerjaan) ? selectedWarga.pekerjaan : '-'"></dd>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-3">
                            <dt class="text-xs text-ink-600">Alamat Lengkap</dt>
                            <dd class="text-ink-900 font-medium" x-text="selectedWarga ? selectedWarga.alamat_lengkap : ''"></dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        {{-- LANGKAH 3: TEMPLATE DATA ISIAN KHUSUS SURAT --}}
        <section aria-labelledby="langkah-3" class="space-y-4 border-t border-line pt-6">
            <div class="flex items-center justify-between border-b border-line pb-2">
                <h2 id="langkah-3" class="text-base font-semibold text-ink-900 flex items-center gap-2">
                    <span class="flex size-6 items-center justify-center rounded-full bg-brand-700 text-xs font-bold text-white">3</span>
                    <span>Template Formulir Isian Surat</span>
                </h2>
                <template x-if="selectedJenis">
                    <span class="text-xs font-semibold text-brand-700 bg-brand-50 border border-brand-200 px-2.5 py-0.5 rounded-full"
                          x-text="'Template: ' + selectedJenis.nama_jenis"></span>
                </template>
            </div>

            <template x-if="!selectedJenis">
                <div class="rounded-control border border-dashed border-line bg-canvas/60 p-6 text-center text-ink-600 text-sm">
                    <x-icon name="building" class="size-8 mx-auto text-ink-400 mb-2"/>
                    <p class="font-semibold text-ink-800">Silakan pilih Jenis Surat di Langkah 1 terlebih dahulu</p>
                    <p class="text-xs text-ink-500 mt-1">Formulir isian khusus akan muncul secara otomatis sesuai template jenis surat yang dipilih.</p>
                </div>
            </template>

            <div id="isian-container" class="space-y-5" aria-live="polite"></div>
        </section>
    </div>

    {{-- Tombol Tindakan Form --}}
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
document.addEventListener('alpine:init', () => {
    Alpine.data('suratForm', (cfg) => ({
        jenisList: cfg.jenisList || [],
        selectedJenisId: cfg.jenisTerpilihId ? String(cfg.jenisTerpilihId) : '',
        selectedJenis: null,
        nomorEksis: cfg.nomorEksis,
        nextUrut: cfg.nextUrut,
        tahun: cfg.tahun,
        cariUrl: cfg.cariUrl,
        selectedWarga: cfg.wargaDataAwal || null,
        nikQuery: (cfg.wargaDataAwal && cfg.wargaDataAwal.nik) ? cfg.wargaDataAwal.nik : '',
        loadingWarga: false,
        errorWarga: '',
        autocompleteOpen: false,
        searchResults: [],
        searchTimer: null,
        cacheIsian: Object.assign({}, cfg.isianAwal || {}),
        galatIsian: cfg.galatIsian || {},

        init() {
            if (this.selectedJenisId) {
                this.updateSelectedJenis();
            }
            this.$nextTick(() => {
                this.renderTemplateFields();
            });

            // Validasi client-side sebelum submit
            const form = this.$el.closest('form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    if (!this.selectedWarga) {
                        e.preventDefault();
                        this.errorWarga = 'Warga pemohon belum dipilih. Masukkan NIK warga yang valid.';
                        this.$refs.nikInput && this.$refs.nikInput.focus();
                        return;
                    }
                    if (!this.selectedJenisId) {
                        e.preventDefault();
                        alert('Silakan pilih jenis surat terlebih dahulu.');
                        document.getElementById('jenis_surat_id').focus();
                        return;
                    }
                });
            }
        },

        get nomorSuratDisplay() {
            if (this.nomorEksis) {
                return this.nomorEksis;
            }
            const kode = this.selectedJenis ? this.selectedJenis.kode : '___';
            return `470/${this.nextUrut}/${kode}/TS/${this.tahun}`;
        },

        onJenisChange() {
            this.updateSelectedJenis();
            this.renderTemplateFields();
        },

        updateSelectedJenis() {
            this.selectedJenis = this.jenisList.find(j => String(j.id) === String(this.selectedJenisId)) || null;
        },

        onNikInput() {
            this.errorWarga = '';
            const q = this.nikQuery.trim();

            // Jika pas 16 digit angka, langsung picu pengecekan otomatis
            if (/^\d{16}$/.test(q)) {
                this.autocompleteOpen = false;
                this.cariByNik();
                return;
            }

            // Jika kurang dari 16 digit tetapi minimal 2 karakter, berikan autocomplete
            clearTimeout(this.searchTimer);
            if (q.length >= 2) {
                this.searchTimer = setTimeout(() => {
                    this.fetchAutocomplete(q);
                }, 300);
            } else {
                this.searchResults = [];
                this.autocompleteOpen = false;
            }
        },

        async cariByNik() {
            const nik = this.nikQuery.trim();
            if (!nik) {
                this.errorWarga = 'Silakan masukkan 16 digit NIK terlebih dahulu.';
                return;
            }
            this.loadingWarga = true;
            this.errorWarga = '';
            this.autocompleteOpen = false;

            try {
                const res = await fetch(`${this.cariUrl}?nik=${encodeURIComponent(nik)}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.found && data.warga) {
                    this.pilihWarga(data.warga);
                } else {
                    // Coba fallback pencarian fleksibel jika user input nama / sebagian
                    const fallbackRes = await fetch(`${this.cariUrl}?q=${encodeURIComponent(nik)}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const fallbackData = await fallbackRes.json();
                    if (Array.isArray(fallbackData) && fallbackData.length === 1) {
                        this.pilihWarga(fallbackData[0]);
                    } else if (Array.isArray(fallbackData) && fallbackData.length > 1) {
                        this.searchResults = fallbackData;
                        this.autocompleteOpen = true;
                    } else {
                        this.selectedWarga = null;
                        this.errorWarga = `NIK / Warga "${nik}" tidak ditemukan dalam database.`;
                    }
                }
            } catch (err) {
                this.errorWarga = 'Terjadi kesalahan saat memeriksa data warga. Periksa koneksi Anda.';
            } finally {
                this.loadingWarga = false;
            }
        },

        async fetchAutocomplete(q) {
            try {
                const res = await fetch(`${this.cariUrl}?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                this.searchResults = Array.isArray(data) ? data : [];
                this.autocompleteOpen = this.searchResults.length > 0;
            } catch (e) {
                this.searchResults = [];
            }
        },

        pilihWarga(w) {
            this.selectedWarga = w;
            this.nikQuery = w.nik || w.nik_masked || '';
            this.autocompleteOpen = false;
            this.errorWarga = '';
        },

        resetWarga() {
            this.selectedWarga = null;
            this.nikQuery = '';
            this.$nextTick(() => {
                this.$refs.nikInput && this.$refs.nikInput.focus();
            });
        },

        simpanNilaiIsian() {
            const container = document.getElementById('isian-container');
            if (container) {
                container.querySelectorAll('[data-key]').forEach((el) => {
                    this.cacheIsian[el.dataset.key] = el.value;
                });
            }
        },

        renderTemplateFields() {
            const container = document.getElementById('isian-container');
            if (!container) return;

            this.simpanNilaiIsian();
            container.replaceChildren();

            if (!this.selectedJenis) {
                return;
            }

            const fields = Array.isArray(this.selectedJenis.template_field) ? this.selectedJenis.template_field : [];

            if (!fields.length) {
                const p = document.createElement('p');
                p.className = 'text-sm text-ink-600 bg-canvas p-4 rounded-control border border-line';
                p.textContent = 'Tidak ada formulir isian khusus tambahan untuk jenis surat ini.';
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
                if (f.type === 'textarea') {
                    input.rows = 3;
                } else {
                    input.type = f.type || 'text';
                }
                input.id = id;
                input.name = 'data_isian[' + f.key + ']';
                input.required = true;
                input.className = 'field';
                input.placeholder = f.placeholder || '';
                input.dataset.key = f.key;
                if (this.cacheIsian[f.key] !== undefined && this.cacheIsian[f.key] !== null) {
                    input.value = this.cacheIsian[f.key];
                }

                wrap.append(label, input);

                const pesan = this.galatIsian['data_isian.' + f.key];
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
    }));
});
</script>
