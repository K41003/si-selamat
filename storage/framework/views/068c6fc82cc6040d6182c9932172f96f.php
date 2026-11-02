<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? 'SI SELAMAT'); ?> - Sistem Informasi Surat Desa Tanjung Selamat</title>

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="h-full flex flex-col">
    <a href="#konten-utama" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-control focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-brand-700 focus:shadow-lg no-print">Lewati ke konten utama</a>

    <?php
        $isKades = auth()->check() && auth()->user()->isKades();
        $pendingCount = $isKades ? \App\Models\Surat::where('status', 'diajukan')->count() : 0;

        // [label, url, aktif?]
        $menu = [
            ['Dashboard',     route('dashboard'),     request()->routeIs('dashboard')],
            ['Data Warga',    route('warga.index'),   request()->routeIs('warga.*')],
            ['Daftar Surat',  route('surat.index'),   request()->routeIs('surat.index') || request()->routeIs('surat.show')],
            ['Buat Surat',    route('surat.create'),  request()->routeIs('surat.create')],
        ];
        if ($isKades) {
            $menu[] = ['Validasi Kades', route('validasi.index'), request()->routeIs('validasi.*')];
        }
        $menu[] = ['Log Aktivitas', route('logs.index'), request()->routeIs('logs.*')];
    ?>

    <div class="min-h-full flex flex-col">
        <header class="sticky top-0 z-40 border-b border-line bg-surface no-print">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 rounded-control">
                        <span class="flex size-9 items-center justify-center rounded-control bg-brand-700 text-white">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'building','class' => 'size-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'building','class' => 'size-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                        </span>
                        <span class="leading-tight">
                            <span class="block text-base font-bold tracking-tight text-ink-900">SI SELAMAT</span>
                            <span class="block text-xs text-ink-600">Desa Tanjung Selamat &bull; Kec. Sunggal</span>
                        </span>
                    </a>

                    <?php if(auth()->guard()->check()): ?>
                    <div class="flex items-center gap-4">
                        <div class="hidden text-right leading-tight md:block">
                            <p class="text-sm font-semibold text-ink-900"><?php echo e(auth()->user()->active_staff_name ?: auth()->user()->name); ?></p>
                            <p class="text-xs text-ink-600"><?php echo e(auth()->user()->role === 'kades' ? 'Kepala Desa Tanjung Selamat' : 'Staf Pelayanan Administrasi'); ?></p>
                        </div>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'logout','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logout','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                                Keluar
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>

                <nav aria-label="Menu utama" class="-mb-px flex gap-1 overflow-x-auto">
                    <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $url, $active]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($url); ?>" <?php if($active): ?> aria-current="page" <?php endif; ?>
                       class="inline-flex h-11 shrink-0 items-center gap-2 border-b-2 px-3 text-sm transition-colors <?php echo e($active ? 'border-brand-700 font-semibold text-brand-700' : 'border-transparent font-medium text-ink-600 hover:border-line hover:text-ink-900'); ?>">
                        <?php echo e($label); ?>

                        <?php if($label === 'Validasi Kades' && $pendingCount > 0): ?>
                        <span class="rounded-full bg-warning-100 px-1.5 text-xs font-semibold text-warning-800"><?php echo e($pendingCount); ?><span class="sr-only"> surat menunggu</span></span>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </nav>
            </div>
        </header>

        <main id="konten-utama" tabindex="-1" class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 focus:outline-none sm:px-6 lg:px-8 lg:py-8">
            <?php if(session('success')): ?>
            <div role="status" class="mb-6 flex items-start gap-3 rounded-control border border-success-700/30 bg-success-100/60 p-4 text-sm text-success-800 no-print">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check-circle','class' => 'mt-0.5 size-5 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check-circle','class' => 'mt-0.5 size-5 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                <span><?php echo e(session('success')); ?></span>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div role="alert" class="mb-6 flex items-start gap-3 rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800 no-print">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'exclamation','class' => 'mt-0.5 size-5 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'exclamation','class' => 'mt-0.5 size-5 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                <span><?php echo e(session('error')); ?></span>
            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div role="alert" class="mb-6 rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800 no-print">
                <p class="mb-1 font-semibold">Terdapat kesalahan pada isian form:</p>
                <ul class="list-disc space-y-0.5 pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="mt-auto border-t border-line py-5 text-center text-xs text-ink-600 no-print">
            <div class="mx-auto max-w-7xl px-4">
                &copy; <?php echo e(date('Y')); ?> SI SELAMAT &bull; Kantor Kepala Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang.
            </div>
        </footer>
    </div>
</body>
</html>
<?php /**PATH C:\7SIA3\Project_Web\si-selamat\si-selamat\resources\views/layouts/app.blade.php ENDPATH**/ ?>