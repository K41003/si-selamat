{{-- Pemilih warga asinkron (combobox ARIA + Alpine). Tidak memuat daftar warga; mencari ke server saat mengetik.
     Pakai: <x-warga-picker :url="route('warga.cari')" :selected="$selectedWarga" />
     Mengirim <input name="warga_id"> berisi id warga. NIK yang tampil hanya NIK tersamar dari server. --}}
@props(['name' => 'warga_id', 'url', 'selected' => null, 'label' => 'Warga pemohon'])
@php
    $id = 'picker-' . $name;
    // Setelah validasi gagal, pilihan sebelumnya (old input) didahulukan.
    $w = old($name) ? \App\Models\Warga::find(old($name)) : $selected;
    $initial = $w ? [
        'id' => $w->id,
        'nama' => $w->nama,
        'nik_masked' => $w->masked_nik,
        'ttl' => $w->tempat_lahir . ', ' . date('d/m/Y', strtotime($w->tanggal_lahir)),
        'pekerjaan' => $w->pekerjaan,
        'alamat' => $w->alamat,
    ] : null;
    $serverError = $errors->first($name);
@endphp

@once
<style>[x-cloak]{display:none !important}</style>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('wargaPicker', (cfg) => ({
        url: cfg.url,
        selected: cfg.selected || null,
        query: '', results: [], open: false, loading: false,
        searched: false, failed: false, active: -1, error: '',
        timer: null, controller: null,

        init() {
            const form = this.$el.closest('form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    if (!this.selected) {
                        e.preventDefault();
                        this.error = 'Pilih warga pemohon terlebih dahulu.';
                        this.$refs.input && this.$refs.input.focus();
                    }
                });
            }
        },
        onInput() {
            this.error = ''; this.active = -1;
            clearTimeout(this.timer);
            const q = this.query.trim();
            if (q.length < 2) { this.results = []; this.searched = false; this.open = false; return; }
            this.timer = setTimeout(() => this.search(q), 250);
        },
        async search(q) {
            if (this.controller) this.controller.abort();
            this.controller = new AbortController();
            this.loading = true; this.failed = false; this.open = true;
            try {
                const res = await fetch(this.url + '?q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                    signal: this.controller.signal,
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                this.results = await res.json();
            } catch (e) {
                if (e.name === 'AbortError') return;
                this.results = []; this.failed = true;
            }
            this.searched = true; this.loading = false;
        },
        move(step) {
            if (!this.results.length) return;
            this.open = true;
            this.active = (this.active + step + this.results.length) % this.results.length;
            this.$nextTick(() => {
                const el = document.getElementById(this.$refs.list.dataset.prefix + this.active);
                if (el) el.scrollIntoView({ block: 'nearest' });
            });
        },
        choose(i) {
            const r = this.results[i];
            if (!r) return;
            this.selected = r; this.open = false; this.query = '';
            this.results = []; this.active = -1; this.error = ''; this.searched = false;
        },
        clear() {
            this.selected = null;
            this.$nextTick(() => this.$refs.input && this.$refs.input.focus());
        },
    }));
});
</script>
@endonce

<div x-data="wargaPicker({ url: @js($url), selected: @js($initial) })" @click.outside="open = false" class="space-y-3">
    <input type="hidden" name="{{ $name }}" :value="selected ? selected.id : ''">

    {{-- Mode mencari --}}
    <div x-show="!selected" @if($initial) x-cloak @endif>
        <label for="{{ $id }}-input" class="block text-sm font-medium text-ink-900">
            {{ $label }} <span class="font-normal text-danger-700">(wajib)</span>
        </label>
        <div class="relative mt-1.5">
            <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-ink-600"/>
            <input x-ref="input" id="{{ $id }}-input" type="text" autocomplete="off"
                   role="combobox" aria-autocomplete="list" aria-controls="{{ $id }}-list"
                   :aria-expanded="open ? 'true' : 'false'"
                   :aria-activedescendant="active >= 0 ? '{{ $id }}-opt-' + active : null"
                   :aria-invalid="(error || @js($serverError !== '')) ? 'true' : null"
                   aria-describedby="{{ $id }}-hint"
                   x-model="query" @input="onInput()"
                   @keydown.arrow-down.prevent="move(1)"
                   @keydown.arrow-up.prevent="move(-1)"
                   @keydown.enter="if (open && active >= 0) { $event.preventDefault(); choose(active); }"
                   @keydown.escape="open = false"
                   placeholder="Ketik nama, NIK, atau No. KK"
                   class="field pl-10">

            <ul x-show="open" x-cloak x-ref="list" data-prefix="{{ $id }}-opt-" id="{{ $id }}-list" role="listbox" aria-label="Hasil pencarian warga"
                class="absolute z-20 mt-1 max-h-72 w-full overflow-auto rounded-control border border-line bg-surface shadow-lg">
                <li role="presentation" x-show="loading" class="px-4 py-3 text-sm text-ink-600">Mencari…</li>
                <template x-for="(r, i) in results" :key="r.id">
                    <li role="option" :id="'{{ $id }}-opt-' + i" :aria-selected="active === i ? 'true' : 'false'"
                        @click="choose(i)" @mousemove="active = i"
                        :class="active === i ? 'bg-brand-50' : ''" class="cursor-pointer px-4 py-2.5">
                        <p class="text-sm font-medium text-ink-900" x-text="r.nama"></p>
                        <p class="text-sm text-ink-600"><span class="font-mono" x-text="r.nik_masked"></span> &bull; <span x-text="r.pekerjaan"></span></p>
                    </li>
                </template>
                <li role="presentation" x-show="!loading && searched && !failed && results.length === 0" class="px-4 py-3 text-sm text-ink-600">Warga tidak ditemukan. Periksa ejaan, atau coba NIK / No. KK lengkap.</li>
                <li role="presentation" x-show="failed" class="px-4 py-3 text-sm font-medium text-danger-800">Pencarian gagal. Periksa koneksi lalu coba lagi.</li>
            </ul>
        </div>
        <p id="{{ $id }}-hint" class="mt-1.5 text-sm text-ink-600">Ketik minimal 2 huruf. Hanya 10 hasil teratas yang ditampilkan.</p>
        <p x-show="error" x-text="error" x-cloak class="mt-1.5 text-sm font-medium text-danger-700"></p>
        @if($serverError)
        <p class="mt-1.5 text-sm font-medium text-danger-700">{{ $serverError }}</p>
        @endif
        <p class="sr-only" role="status" aria-live="polite" x-text="loading ? 'Mencari' : (searched ? (results.length + ' hasil ditemukan') : '')"></p>
    </div>

    {{-- Mode terpilih: ringkasan pemohon --}}
    <div x-show="selected" @if(!$initial) x-cloak @endif class="rounded-control border border-line bg-canvas p-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-sm text-ink-600">{{ $label }}</p>
                <p class="font-semibold text-ink-900" x-text="selected && selected.nama"></p>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" @click="clear()">Ganti</button>
        </div>
        <dl class="mt-3 grid gap-x-6 gap-y-2 text-sm sm:grid-cols-2">
            <div><dt class="text-ink-600">NIK (tersamar)</dt><dd class="font-mono text-ink-900" x-text="selected && selected.nik_masked"></dd></div>
            <div><dt class="text-ink-600">Tempat / tgl lahir</dt><dd class="text-ink-900" x-text="selected && selected.ttl"></dd></div>
            <div><dt class="text-ink-600">Pekerjaan</dt><dd class="text-ink-900" x-text="selected && selected.pekerjaan"></dd></div>
            <div class="sm:col-span-2"><dt class="text-ink-600">Alamat</dt><dd class="text-ink-900" x-text="selected && selected.alamat"></dd></div>
        </dl>
    </div>
</div>
