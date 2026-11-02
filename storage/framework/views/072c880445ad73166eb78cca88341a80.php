
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id', 'title', 'action', 'method' => 'POST', 'confirmLabel' => 'Ya, lanjutkan', 'cancelLabel' => 'Batal', 'variant' => 'primary']));

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

foreach (array_filter((['id', 'title', 'action', 'method' => 'POST', 'confirmLabel' => 'Ya, lanjutkan', 'cancelLabel' => 'Batal', 'variant' => 'primary']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<dialog id="<?php echo e($id); ?>" aria-labelledby="<?php echo e($id); ?>-title"
        onclick="if (event.target === this) this.close()"
        class="m-auto w-[min(28rem,92vw)] rounded-card bg-surface p-0 text-ink-900 shadow-lg backdrop:bg-ink-900/50">
    <form method="POST" action="<?php echo e($action); ?>" class="p-6">
        <?php echo csrf_field(); ?>
        <?php if(strtoupper($method) !== 'POST'): ?> <?php echo method_field($method); ?> <?php endif; ?>
        <h2 id="<?php echo e($id); ?>-title" class="text-title-sm font-semibold"><?php echo e($title); ?></h2>
        <div class="mt-3 space-y-3 text-sm text-ink-700"><?php echo e($slot); ?></div>
        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" class="btn btn-secondary" onclick="this.closest('dialog').close()"><?php echo e($cancelLabel); ?></button>
            <button type="submit" class="btn btn-<?php echo e($variant); ?>"><?php echo e($confirmLabel); ?></button>
        </div>
    </form>
</dialog>
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/components/confirm-dialog.blade.php ENDPATH**/ ?>