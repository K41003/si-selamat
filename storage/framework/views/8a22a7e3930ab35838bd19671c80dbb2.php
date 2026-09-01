<?php $__env->startSection('content'); ?>
<div class="flex min-h-screen items-center justify-center bg-slate-100 px-4">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-slate-900">SI SELAMAT</h1>
            <p class="mt-1 text-sm text-slate-500">Sistem Informasi Surat Elektronik &amp; Layanan Administrasi Masyarakat Tanjung Selamat</p>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-6 text-lg font-semibold text-slate-800">Masuk ke Akun Anda</h2>

            <?php if($errors->any()): ?>
                <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.perform')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="username" class="mb-1 block text-sm font-medium text-slate-700">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?php echo e(old('username')); ?>"
                        required
                        autofocus
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                    >
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                    <div class="relative">
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password"
                            name="password"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 pr-10 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                        >
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center px-3 text-xs text-slate-400">
                            <span x-text="show ? 'Sembunyikan' : 'Lihat'"></span>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    Ingat saya
                </label>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-slate-900 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    Masuk
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            &copy; <?php echo e(date('Y')); ?> Desa Tanjung Selamat
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\TUGAS AKHIR\si-selamat\si-selamat\resources\views/auth/login.blade.php ENDPATH**/ ?>