
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name' => 'warga_id', 'url', 'selected' => null, 'label' => 'Warga pemohon']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name' => 'warga_id', 'url', 'selected' => null, 'label' => 'Warga pemohon']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
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
?>

<?php if (! $__env->hasRenderedOnce('6d9e5f1a-a387-4c8c-ae3d-cfe13799f9d7')): $__env->markAsRenderedOnce('6d9e5f1a-a387-4c8c-ae3d-cfe13799f9d7'); ?>
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
<?php endif; ?>

<div x-data="wargaPicker({ url: <?php echo \Illuminate\Support\Js::from($url)->toHtml() ?>, selected: <?php echo \Illuminate\Support\Js::from($initial)->toHtml() ?> })" @click.outside="open = false" class="space-y-3">
    <input type="hidden" name="<?php echo e($name); ?>" :value="selected ? selected.id : ''">

    
    <div x-show="!selected" <?php if($initial): ?> x-cloak <?php endif; ?>>
        <label for="<?php echo e($id); ?>-input" class="block text-sm font-medium text-ink-900">
            <?php echo e($label); ?> <span class="font-normal text-danger-700">(wajib)</span>
        </label>
        <div class="relative mt-1.5">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-ink-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-ink-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
            <input x-ref="input" id="<?php echo e($id); ?>-input" type="text" autocomplete="off"
                   role="combobox" aria-autocomplete="list" aria-controls="<?php echo e($id); ?>-list"
                   :aria-expanded="open ? 'true' : 'false'"
                   :aria-activedescendant="active >= 0 ? '<?php echo e($id); ?>-opt-' + active : null"
                   :aria-invalid="(error || <?php echo \Illuminate\Support\Js::from($serverError !== '')->toHtml() ?>) ? 'true' : null"
                   aria-describedby="<?php echo e($id); ?>-hint"
                   x-model="query" @input="onInput()"
                   @keydown.arrow-down.prevent="move(1)"
                   @keydown.arrow-up.prevent="move(-1)"
                   @keydown.enter="if (open && active >= 0) { $event.preventDefault(); choose(active); }"
                   @keydown.escape="open = false"
                   placeholder="Ketik nama, NIK, atau No. KK"
                   class="field pl-10">

            <ul x-show="open" x-cloak x-ref="list" data-prefix="<?php echo e($id); ?>-opt-" id="<?php echo e($id); ?>-list" role="listbox" aria-label="Hasil pencarian warga"
                class="absolute z-20 mt-1 max-h-72 w-full overflow-auto rounded-control border border-line bg-surface shadow-lg">
                <li role="presentation" x-show="loading" class="px-4 py-3 text-sm text-ink-600">Mencari…</li>
                <template x-for="(r, i) in results" :key="r.id">
                    <li role="option" :id="'<?php echo e($id); ?>-opt-' + i" :aria-selected="active === i ? 'true' : 'false'"
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
        <p id="<?php echo e($id); ?>-hint" class="mt-1.5 text-sm text-ink-600">Ketik minimal 2 huruf. Hanya 10 hasil teratas yang ditampilkan.</p>
        <p x-show="error" x-text="error" x-cloak class="mt-1.5 text-sm font-medium text-danger-700"></p>
        <?php if($serverError): ?>
        <p class="mt-1.5 text-sm font-medium text-danger-700"><?php echo e($serverError); ?></p>
        <?php endif; ?>
        <p class="sr-only" role="status" aria-live="polite" x-text="loading ? 'Mencari' : (searched ? (results.length + ' hasil ditemukan') : '')"></p>
    </div>

    
    <div x-show="selected" <?php if(!$initial): ?> x-cloak <?php endif; ?> class="rounded-control border border-line bg-canvas p-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-sm text-ink-600"><?php echo e($label); ?></p>
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
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/components/warga-picker.blade.php ENDPATH**/ ?>