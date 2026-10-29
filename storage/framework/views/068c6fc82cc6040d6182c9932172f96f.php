<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? 'SI SELAMAT'); ?> - Sistem Informasi Surat Desa Tanjung Selamat</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN for instant styling in Laravel -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: {
                            DEFAULT: '#1E3A8A',
                            hover: '#172554',
                            light: '#eff6ff',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Poppins', system-ui, sans-serif; }
        .tabular-nums { font-variant-numeric: tabular-nums; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="h-full flex flex-col text-slate-800 antialiased">
    <div class="min-h-full flex flex-col">
        <!-- Top Navbar -->
        <header class="bg-[#1E3A8A] text-white shadow-md sticky top-0 z-40 no-print">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/10 p-2 rounded-xl backdrop-blur-sm border border-white/20">
                            <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-bold tracking-tight text-white flex items-center gap-2">
                                SI SELAMAT
                                <span class="text-[10px] uppercase tracking-wider font-semibold bg-blue-500/30 text-blue-100 px-2 py-0.5 rounded-full border border-blue-400/30">Desa</span>
                            </span>
                            <p class="text-xs text-blue-200">Desa Tanjung Selamat &bull; Kec. Sunggal</p>
                        </div>
                    </div>

                    <!-- User profile & Logout -->
                    <div class="flex items-center space-x-4">
                        <?php if(auth()->guard()->check()): ?>
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-sm font-semibold text-white">
                                <?php echo e(auth()->user()->active_staff_name ?: auth()->user()->name); ?>

                            </span>
                            <span class="text-xs text-blue-200">
                                <?php echo e(auth()->user()->role === 'kades' ? 'Kepala Desa Tanjung Selamat' : 'Staff Pelayanan Administrasi'); ?>

                            </span>
                        </div>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-medium transition border border-white/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Keluar
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Navigation Bar -->
            <div class="bg-[#172554] border-t border-blue-800/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex space-x-1 sm:space-x-3 overflow-x-auto py-2 text-xs sm:text-sm font-medium">
                        <a href="<?php echo e(route('dashboard')); ?>" class="px-3 py-1.5 rounded-lg transition <?php echo e(request()->routeIs('dashboard') ? 'bg-blue-600 text-white font-semibold' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white'); ?>">
                            Dashboard
                        </a>
                        <a href="<?php echo e(route('warga.index')); ?>" class="px-3 py-1.5 rounded-lg transition <?php echo e(request()->routeIs('warga.*') ? 'bg-blue-600 text-white font-semibold' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white'); ?>">
                            Data Warga
                        </a>
                        <a href="<?php echo e(route('surat.index')); ?>" class="px-3 py-1.5 rounded-lg transition <?php echo e(request()->routeIs('surat.index') || request()->routeIs('surat.show') ? 'bg-blue-600 text-white font-semibold' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white'); ?>">
                            Daftar Surat
                        </a>
                        <a href="<?php echo e(route('surat.create')); ?>" class="px-3 py-1.5 rounded-lg transition <?php echo e(request()->routeIs('surat.create') ? 'bg-blue-600 text-white font-semibold' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white'); ?>">
                            + Buat Surat
                        </a>
                        <?php if(auth()->check() && auth()->user()->isKades()): ?>
                        <a href="<?php echo e(route('validasi.index')); ?>" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 <?php echo e(request()->routeIs('validasi.*') ? 'bg-amber-600 text-white font-semibold' : 'text-amber-200 hover:bg-blue-900/50 hover:text-white'); ?>">
                            <span>Validasi Kades</span>
                            <?php
                                $pendingCount = \App\Models\Surat::where('status', 'diajukan')->count();
                            ?>
                            <?php if($pendingCount > 0): ?>
                            <span class="bg-amber-500 text-slate-900 text-[10px] font-bold px-1.5 py-0.2 rounded-full"><?php echo e($pendingCount); ?></span>
                            <?php endif; ?>
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('logs.index')); ?>" class="px-3 py-1.5 rounded-lg transition <?php echo e(request()->routeIs('logs.*') ? 'bg-blue-600 text-white font-semibold' : 'text-blue-100 hover:bg-blue-900/50 hover:text-white'); ?>">
                            Log Aktivitas
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content Body -->
        <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
            <!-- Flash Notifications -->
            <?php if(session('success')): ?>
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm no-print">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span><?php echo e(session('success')); ?></span>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm no-print">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span><?php echo e(session('error')); ?></span>
            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm no-print">
                <p class="font-semibold mb-1">Terdapat kesalahan pada isian form:</p>
                <ul class="list-disc pl-5 space-y-0.5 text-xs">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 mt-auto py-4 text-center text-xs text-slate-500 no-print">
            <div class="max-w-7xl mx-auto px-4">
                &copy; <?php echo e(date('Y')); ?> SI SELAMAT &bull; Kantor Kepala Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang.
            </div>
        </footer>
    </div>
</body>
</html>
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/layouts/app.blade.php ENDPATH**/ ?>