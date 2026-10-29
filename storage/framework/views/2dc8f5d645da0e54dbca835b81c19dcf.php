<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Welcome Header Banner -->
    <div class="bg-gradient-to-r from-[#1E3A8A] to-blue-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-blue-200 mb-3 border border-white/10">
                Dashboard Pelayanan Masyarakat
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Selamat Datang, <?php echo e(auth()->user()->active_staff_name ?: auth()->user()->name); ?>

            </h1>
            <p class="mt-2 text-sm text-blue-100 leading-relaxed">
                Kelola data kependudukan dan proses permohonan surat administrasi warga Desa Tanjung Selamat dengan cepat, tertib, dan transparan.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="<?php echo e(route('surat.create')); ?>" class="px-4 py-2.5 bg-white text-[#1E3A8A] font-semibold text-xs sm:text-sm rounded-xl shadow hover:bg-blue-50 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Surat Baru
                </a>
                <a href="<?php echo e(route('warga.create')); ?>" class="px-4 py-2.5 bg-blue-700/60 text-white font-medium text-xs sm:text-sm rounded-xl border border-white/20 hover:bg-blue-700 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Tambah Data Warga
                </a>
            </div>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 translate-x-8 translate-y-8 pointer-events-none hidden lg:block">
            <svg class="w-72 h-72 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Penduduk</span>
                <span class="p-2 bg-blue-50 text-blue-700 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-2 tabular-nums"><?php echo e(number_format($stats['total_warga'])); ?></p>
            <p class="text-xs text-slate-400 mt-1">Warga terdaftar di sistem</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Draf Surat</span>
                <span class="p-2 bg-amber-50 text-amber-700 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-2 tabular-nums"><?php echo e($stats['total_draft']); ?></p>
            <p class="text-xs text-amber-600 mt-1">Belum diajukan ke Kades</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Menunggu Validasi</span>
                <span class="p-2 bg-indigo-50 text-indigo-700 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-2 tabular-nums"><?php echo e($stats['total_diajukan']); ?></p>
            <p class="text-xs text-indigo-600 mt-1">Sedang ditinjau Kades</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Surat Disetujui</span>
                <span class="p-2 bg-emerald-50 text-emerald-700 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-2 tabular-nums"><?php echo e($stats['total_disetujui']); ?></p>
            <p class="text-xs text-emerald-600 mt-1">Siap dicetak & ber-QR e-sign</p>
        </div>
    </div>

    <!-- Recent Letters & Recent Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Permohonan Surat Terkini -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Permohonan Surat Terbaru</h2>
                    <p class="text-xs text-slate-500">Daftar permohonan surat administrasi terkini</p>
                </div>
                <a href="<?php echo e(route('surat.index')); ?>" class="text-xs font-semibold text-blue-700 hover:text-blue-900">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">No. Surat & Jenis</th>
                            <th class="px-5 py-3">Pemohon</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $recentSurat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-slate-900 block font-mono"><?php echo e($s->nomor_surat); ?></span>
                                <span class="text-[11px] text-slate-500"><?php echo e($s->jenisSurat->nama_jenis ?? '-'); ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-slate-800 block"><?php echo e($s->warga->nama ?? '-'); ?></span>
                                <span class="text-[11px] text-slate-400 font-mono"><?php echo e($s->warga->masked_nik ?? ''); ?></span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">
                                <?php echo e($s->created_at->format('d/m/Y')); ?>

                            </td>
                            <td class="px-5 py-3.5">
                                <?php if($s->status === 'draft'): ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Draf</span>
                                <?php elseif($s->status === 'diajukan'): ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Diajukan</span>
                                <?php elseif($s->status === 'disetujui'): ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                <?php else: ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="<?php echo e(route('surat.show', $s)); ?>" class="p-1.5 text-slate-600 hover:text-blue-700 rounded-lg hover:bg-slate-100 transition inline-block" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <?php if($s->status === 'disetujui'): ?>
                                <a href="<?php echo e(route('surat.print', $s)); ?>" target="_blank" class="p-1.5 text-emerald-600 hover:text-emerald-800 rounded-lg hover:bg-emerald-50 transition inline-block" title="Cetak Surat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada permohonan surat yang diajukan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Log Aktivitas Terkini -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h2 class="text-base font-bold text-slate-900">Aktivitas Terkini</h2>
                <a href="<?php echo e(route('logs.index')); ?>" class="text-xs font-semibold text-blue-700 hover:text-blue-900">Semua</a>
            </div>
            <div class="space-y-4 flex-1">
                <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="text-xs border-l-2 border-blue-500 pl-3 py-0.5">
                    <p class="font-semibold text-slate-800"><?php echo e($log->aksi); ?></p>
                    <p class="text-slate-500 mt-0.5 text-[11px] leading-relaxed"><?php echo e($log->deskripsi); ?></p>
                    <div class="flex items-center gap-2 mt-1 text-[10px] text-slate-400">
                        <span><?php echo e($log->user_name); ?></span>
                        <span>&bull;</span>
                        <span><?php echo e($log->created_at->diffForHumans()); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-xs text-slate-400 text-center py-6">Belum ada catatan log aktivitas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/dashboard/staff.blade.php ENDPATH**/ ?>