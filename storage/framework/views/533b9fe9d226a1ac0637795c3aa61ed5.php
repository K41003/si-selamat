<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-900">Dashboard Kades</h1>
    <p class="text-sm text-slate-500">Ringkasan validasi surat dan aktivitas sistem.</p>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-500">Menunggu Validasi</p>
        <p class="mt-2 text-3xl font-bold text-blue-600"><?php echo e($totalMenungguValidasi); ?></p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-500">Disetujui Bulan Ini</p>
        <p class="mt-2 text-3xl font-bold text-emerald-600"><?php echo e($totalDisetujuiBulanIni); ?></p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <p class="text-sm text-slate-500">Ditolak Bulan Ini</p>
        <p class="mt-2 text-3xl font-bold text-red-600"><?php echo e($totalDitolakBulanIni); ?></p>
    </div>
</div>

<div class="mt-6 rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
    <p class="text-sm text-slate-500">
        Modul Validasi Surat (E-Signature QR Code), Arsip Surat, dan Log Aktivitas akan tersedia pada langkah pengembangan berikutnya.
    </p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\TUGAS AKHIR\si-selamat\si-selamat\resources\views/dashboard/kades.blade.php ENDPATH**/ ?>