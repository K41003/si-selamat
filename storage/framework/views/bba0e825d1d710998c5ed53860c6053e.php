
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'value', 'hint' => null, 'emphasis' => false]));

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

foreach (array_filter((['label', 'value', 'hint' => null, 'emphasis' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div <?php echo e($attributes->class(['bg-surface px-5 py-4 sm:px-6 sm:py-5'])); ?>>
    <p class="text-sm text-ink-600"><?php echo e($label); ?></p>
    <p class="<?php echo \Illuminate\Support\Arr::toCssClasses(['mt-1 text-display font-semibold tabular-nums', 'text-brand-700' => $emphasis, 'text-ink-900' => ! $emphasis]); ?>"><?php echo e($value); ?></p>
    <?php if($hint): ?>
    <p class="mt-0.5 text-sm text-ink-600"><?php echo e($hint); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/components/stat.blade.php ENDPATH**/ ?>