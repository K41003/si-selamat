<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-900">Update Data Warga</h1>
    <p class="text-sm text-slate-500">Perbarui data <?php echo e($warga->nama); ?>.</p>
</div>

<div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <form method="POST" action="<?php echo e(route('warga.update', $warga)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <?php echo $__env->make('warga._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="<?php echo e(route('warga.index')); ?>" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">
                Batal
            </a>
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\TUGAS AKHIR\si-selamat\si-selamat\resources\views/warga/edit.blade.php ENDPATH**/ ?>