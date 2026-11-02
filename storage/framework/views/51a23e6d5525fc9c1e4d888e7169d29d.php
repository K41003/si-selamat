
<?php
    $jenisTerpilih = old('jenis_surat_id', $jenisTerpilihId ?? null);
    $isianErrors = collect($errors->messages())
        ->filter(fn ($m, $k) => str_starts_with($k, 'data_isian.'))
        ->map(fn ($m) => $m[0]);
?>

<?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['padded' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padded' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <div class="space-y-8 p-5 sm:p-6">
        <section aria-labelledby="langkah-1" class="space-y-4">
            <h2 id="langkah-1" class="text-base font-semibold text-ink-900">1. Warga pemohon</h2>
            <?php if (isset($component)) { $__componentOriginalc5fda7314b72846a0a0ac9121145103e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5fda7314b72846a0a0ac9121145103e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.warga-picker','data' => ['url' => route('warga.cari'),'selected' => $wargaTerpilih ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('warga-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('warga.cari')),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wargaTerpilih ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5fda7314b72846a0a0ac9121145103e)): ?>
<?php $attributes = $__attributesOriginalc5fda7314b72846a0a0ac9121145103e; ?>
<?php unset($__attributesOriginalc5fda7314b72846a0a0ac9121145103e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5fda7314b72846a0a0ac9121145103e)): ?>
<?php $component = $__componentOriginalc5fda7314b72846a0a0ac9121145103e; ?>
<?php unset($__componentOriginalc5fda7314b72846a0a0ac9121145103e); ?>
<?php endif; ?>
            <p class="text-sm text-ink-600">
                Warga belum terdaftar?
                <a href="<?php echo e(route('warga.create')); ?>" class="font-semibold text-brand-600 underline hover:text-brand-900">Daftarkan data warga baru dahulu</a>
            </p>
        </section>

        <section aria-labelledby="langkah-2" class="space-y-4 border-t border-line pt-6">
            <h2 id="langkah-2" class="text-base font-semibold text-ink-900">2. Jenis surat</h2>
            <fieldset>
                <legend class="sr-only">Pilih jenis surat</legend>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <?php $__currentLoopData = $jenisSuratList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex cursor-pointer flex-col gap-1 rounded-control border border-line p-4 transition-colors hover:border-ink-500 has-[:checked]:border-brand-700 has-[:checked]:bg-brand-50 has-[:checked]:ring-1 has-[:checked]:ring-brand-700">
                        <span class="flex items-center justify-between">
                            <span class="font-mono text-xs font-semibold text-ink-600"><?php echo e($jenis->kode); ?></span>
                            <input type="radio" name="jenis_surat_id" value="<?php echo e($jenis->id); ?>" required
                                   <?php if($jenisTerpilih == $jenis->id): echo 'checked'; endif; ?> class="size-4 accent-brand-700">
                        </span>
                        <span class="font-semibold text-ink-900"><?php echo e($jenis->nama_jenis); ?></span>
                        <span class="text-sm text-ink-600"><?php echo e($jenis->deskripsi); ?></span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </fieldset>
        </section>

        <section aria-labelledby="langkah-3" class="space-y-4 border-t border-line pt-6">
            <h2 id="langkah-3" class="text-base font-semibold text-ink-900">3. Data isian surat</h2>
            <div id="isian-container" class="space-y-5" aria-live="polite"></div>
        </section>
    </div>

    <div class="flex flex-col-reverse gap-2 border-t border-line rounded-b-card bg-canvas px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'ghost','href' => ''.e($batalUrl).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ghost','href' => ''.e($batalUrl).'']); ?>Batal <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
        <div class="flex flex-col gap-2 sm:flex-row">
            <button type="submit" name="action" value="draft" class="btn btn-secondary">Simpan sebagai draf</button>
            <button type="submit" name="action" value="ajukan" class="btn btn-primary">
                Ajukan ke Kades <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'size-4']); ?>
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
            </button>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

<script>
(() => {
    const jenisData = <?php echo json_encode($jenisSuratList, 15, 512) ?>;
    const awal = <?php echo json_encode(old('data_isian', $isianAwal ?? []), 512) ?>;
    const galat = <?php echo json_encode($isianErrors, 15, 512) ?>;
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
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/surat/_form.blade.php ENDPATH**/ ?>