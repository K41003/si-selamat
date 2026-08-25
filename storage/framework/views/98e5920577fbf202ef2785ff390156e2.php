<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? 'SI SELAMAT'); ?> - Sistem Informasi Surat Elektronik & Layanan Administrasi Masyarakat</title>
    <style>[x-cloak] { display: none !important; }</style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <?php if(auth()->guard()->check()): ?>
        <div class="flex min-h-screen">
            
            <aside
                class="fixed inset-y-0 left-0 z-30 w-64 transform bg-slate-900 text-white transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="flex h-16 items-center gap-2 border-b border-slate-800 px-6">
                    <span class="text-lg font-bold tracking-tight">SI SELAMAT</span>
                </div>

                <nav class="mt-4 space-y-1 px-3">
                    <a href="<?php echo e(route('dashboard')); ?>"
                       class="block rounded-lg px-3 py-2 text-sm font-medium <?php echo e(request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                        Dashboard
                    </a>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->isStaff()): ?>
                            <a href="<?php echo e(route('warga.index')); ?>"
                               class="block rounded-lg px-3 py-2 text-sm font-medium <?php echo e(request()->routeIs('warga.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                                Kelola Data Warga
                            </a>
                            
                        <?php endif; ?>

                        <?php if(auth()->user()->isKades()): ?>
                            
                        <?php endif; ?>
                    <?php endif; ?>
                </nav>
            </aside>

            <div class="flex flex-1 flex-col lg:pl-0">
                
                <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-6">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden">
                        <span class="text-slate-600">☰</span>
                    </button>

                    <div class="ml-auto flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-800"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-slate-500 capitalize"><?php echo e(auth()->user()->role); ?></p>
                        </div>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-200">
                                Keluar
                            </button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 p-4 lg:p-6">
                    <?php if(session('success')): ?>
                        <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php echo $__env->yieldContent('content'); ?>
                </main>
            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->yieldContent('content'); ?>
    <?php endif; ?>

</body>
</html>
<?php /**PATH C:\TUGAS AKHIR\si-selamat\si-selamat\resources\views/layouts/app.blade.php ENDPATH**/ ?>