
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['caption']));

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

foreach (array_filter((['caption']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="overflow-x-auto" role="region" aria-label="<?php echo e($caption); ?>" tabindex="0">
    <table class="w-full text-left text-sm [&_td]:px-4 [&_td]:py-3.5 [&_th]:px-4 [&_th]:py-3 [&_th]:font-medium">
        <caption class="sr-only"><?php echo e($caption); ?></caption>
        <thead class="border-b border-line bg-canvas text-ink-600"><?php echo e($head); ?></thead>
        <tbody class="divide-y divide-line"><?php echo e($slot); ?></tbody>
    </table>
</div>
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/components/data-table.blade.php ENDPATH**/ ?>