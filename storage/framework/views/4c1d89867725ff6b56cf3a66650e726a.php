<?php
    $w = $warga ?? null;
?>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="nik" class="mb-1 block text-sm font-medium text-slate-700">NIK <span class="text-red-500">*</span></label>
        <input type="text" name="nik" id="nik" maxlength="16" inputmode="numeric"
               value="<?php echo e(old('nik', $w?->nik)); ?>"
               class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 focus:ring-red-400 <?php else: ?> border-slate-300 focus:ring-slate-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label for="no_kk" class="mb-1 block text-sm font-medium text-slate-700">Nomor KK</label>
        <input type="text" name="no_kk" id="no_kk" maxlength="16" inputmode="numeric"
               value="<?php echo e(old('no_kk', $w?->no_kk)); ?>"
               class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 <?php $__errorArgs = ['no_kk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 focus:ring-red-400 <?php else: ?> border-slate-300 focus:ring-slate-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['no_kk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="sm:col-span-2">
        <label for="nama" class="mb-1 block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="nama" id="nama"
               value="<?php echo e(old('nama', $w?->nama)); ?>"
               class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 focus:ring-red-400 <?php else: ?> border-slate-300 focus:ring-slate-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label for="tempat_lahir" class="mb-1 block text-sm font-medium text-slate-700">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" id="tempat_lahir"
               value="<?php echo e(old('tempat_lahir', $w?->tempat_lahir)); ?>"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="tanggal_lahir" class="mb-1 block text-sm font-medium text-slate-700">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
               value="<?php echo e(old('tanggal_lahir', $w?->tanggal_lahir?->format('Y-m-d'))); ?>"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
        <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label for="jenis_kelamin" class="mb-1 block text-sm font-medium text-slate-700">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
            <option value="">Pilih</option>
            <option value="Laki-laki" <?php if(old('jenis_kelamin', $w?->jenis_kelamin) === 'Laki-laki'): echo 'selected'; endif; ?>>Laki-laki</option>
            <option value="Perempuan" <?php if(old('jenis_kelamin', $w?->jenis_kelamin) === 'Perempuan'): echo 'selected'; endif; ?>>Perempuan</option>
        </select>
    </div>

    <div>
        <label for="agama" class="mb-1 block text-sm font-medium text-slate-700">Agama</label>
        <input type="text" name="agama" id="agama"
               value="<?php echo e(old('agama', $w?->agama)); ?>"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="pekerjaan" class="mb-1 block text-sm font-medium text-slate-700">Pekerjaan</label>
        <input type="text" name="pekerjaan" id="pekerjaan"
               value="<?php echo e(old('pekerjaan', $w?->pekerjaan)); ?>"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="status_perkawinan" class="mb-1 block text-sm font-medium text-slate-700">Status Perkawinan</label>
        <select name="status_perkawinan" id="status_perkawinan"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
            <option value="">Pilih</option>
            <?php $__currentLoopData = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php if(old('status_perkawinan', $w?->status_perkawinan) === $status): echo 'selected'; endif; ?>><?php echo e($status); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div>
        <label for="no_hp" class="mb-1 block text-sm font-medium text-slate-700">Nomor HP</label>
        <input type="text" name="no_hp" id="no_hp"
               value="<?php echo e(old('no_hp', $w?->no_hp)); ?>"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="rt" class="mb-1 block text-sm font-medium text-slate-700">RT</label>
        <input type="text" name="rt" id="rt" maxlength="5"
               value="<?php echo e(old('rt', $w?->rt)); ?>"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="rw" class="mb-1 block text-sm font-medium text-slate-700">RW</label>
        <input type="text" name="rw" id="rw" maxlength="5"
               value="<?php echo e(old('rw', $w?->rw)); ?>"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div class="sm:col-span-2">
        <label for="alamat" class="mb-1 block text-sm font-medium text-slate-700">Alamat Lengkap</label>
        <textarea name="alamat" id="alamat" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500"><?php echo e(old('alamat', $w?->alamat)); ?></textarea>
    </div>
</div>
<?php /**PATH C:\TUGAS AKHIR\si-selamat\si-selamat\resources\views/warga/_form.blade.php ENDPATH**/ ?>