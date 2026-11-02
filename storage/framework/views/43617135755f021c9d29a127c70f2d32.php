<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl space-y-6">
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Buat Surat','subtitle' => 'Pilih warga pemohon dan lengkapi data isian sesuai jenis surat.','back' => route('surat.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Buat Surat','subtitle' => 'Pilih warga pemohon dan lengkapi data isian sesuai jenis surat.','back' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('surat.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $attributes = $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $component = $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>

    <form method="POST" action="<?php echo e(route('surat.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('surat._form', [
            'wargaTerpilih'   => $selectedWarga,
            'jenisTerpilihId' => $selectedJenis->id ?? ($jenisSuratList->first()->id ?? null),
            'isianAwal'       => [],
            'batalUrl'        => route('surat.index'),
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/surat/create.blade.php ENDPATH**/ ?>