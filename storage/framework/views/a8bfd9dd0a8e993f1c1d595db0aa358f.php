
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'name', 'as' => 'input', 'type' => 'text', 'hint' => null, 'required' => false, 'value' => null]));

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

foreach (array_filter((['label', 'name', 'as' => 'input', 'type' => 'text', 'hint' => null, 'required' => false, 'value' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $id = $attributes->get('id', $name);
    $err = $errors->first($name);
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($err ? $id . '-error' : ''));
    $control = $attributes->merge(array_filter([
        'id' => $id,
        'name' => $name,
        'class' => 'field',
        'required' => $required ? true : null,
        'aria-invalid' => $err ? 'true' : null,
        'aria-describedby' => $describedBy !== '' ? $describedBy : null,
    ]));
?>
<div class="space-y-1.5">
    <label for="<?php echo e($id); ?>" class="block text-sm font-medium text-ink-900">
        <?php echo e($label); ?><?php if($required): ?> <span class="font-normal text-danger-700">(wajib)</span><?php endif; ?>
    </label>

    <?php if($as === 'textarea'): ?>
        <textarea <?php echo e($control); ?>><?php echo e(old($name, $value)); ?></textarea>
    <?php elseif($as === 'select'): ?>
        <select <?php echo e($control); ?>><?php echo e($slot); ?></select>
    <?php else: ?>
        <input type="<?php echo e($type); ?>" value="<?php echo e(old($name, $value)); ?>" <?php echo e($control); ?>>
    <?php endif; ?>

    <?php if($hint): ?>
        <p id="<?php echo e($id); ?>-hint" class="text-sm text-ink-600"><?php echo e($hint); ?></p>
    <?php endif; ?>
    <?php if($err): ?>
        <p id="<?php echo e($id); ?>-error" class="text-sm font-medium text-danger-700"><?php echo e($err); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/components/field.blade.php ENDPATH**/ ?>