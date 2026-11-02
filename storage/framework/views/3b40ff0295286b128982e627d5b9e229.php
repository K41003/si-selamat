
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

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

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    [$label, $tone] = match ($status) {
        'draft'     => ['Draf',              'bg-warning-100 text-warning-800'],
        'diajukan'  => ['Menunggu validasi', 'bg-info-100 text-info-800'],
        'disetujui' => ['Disetujui',         'bg-success-100 text-success-800'],
        default     => ['Ditolak',           'bg-danger-100 text-danger-800'],
    };
?>
<span <?php echo e($attributes->class(['inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold', $tone])); ?>>
    <span class="size-1.5 rounded-full bg-current" aria-hidden="true"></span><?php echo e($label); ?>

</span>
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/components/status-badge.blade.php ENDPATH**/ ?>