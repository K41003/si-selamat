<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-900">Dashboard Staff</h1>
    <p class="text-sm text-slate-500">Ringkasan data warga dan permohonan surat.</p>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-500">Total Data Warga</p>
        <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e($totalWarga); ?></p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-500">Draft Surat</p>
        <p class="mt-2 text-3xl font-bold text-amber-600"><?php echo e($totalSuratDraft); ?></p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-500">Menunggu Validasi</p>
        <p class="mt-2 text-3xl font-bold text-blue-600"><?php echo e($totalSuratDiajukan); ?></p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-500">Surat Disetujui</p>
        <p class="mt-2 text-3xl font-bold text-emerald-600"><?php echo e($totalSuratDisetujui); ?></p>
    </div>
</div>

<div class="mt-6 rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
    <p class="text-sm text-slate-500">
        Modul Kelola Data Warga, Permohonan Surat, Arsip, dan Cetak Surat akan tersedia pada langkah pengembangan berikutnya.
    </p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\TUGAS AKHIR\si-selamat\si-selamat\resources\views/dashboard/staff.blade.php ENDPATH**/ ?>