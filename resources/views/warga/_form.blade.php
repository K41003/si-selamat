@php
    $w = $warga ?? null;
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="nik" class="mb-1 block text-sm font-medium text-slate-700">NIK <span class="text-red-500">*</span></label>
        <input type="text" name="nik" id="nik" maxlength="16" inputmode="numeric"
               value="{{ old('nik', $w?->nik) }}"
               class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 @error('nik') border-red-400 focus:ring-red-400 @else border-slate-300 focus:ring-slate-500 @enderror">
        @error('nik') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="no_kk" class="mb-1 block text-sm font-medium text-slate-700">Nomor KK</label>
        <input type="text" name="no_kk" id="no_kk" maxlength="16" inputmode="numeric"
               value="{{ old('no_kk', $w?->no_kk) }}"
               class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 @error('no_kk') border-red-400 focus:ring-red-400 @else border-slate-300 focus:ring-slate-500 @enderror">
        @error('no_kk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="nama" class="mb-1 block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="nama" id="nama"
               value="{{ old('nama', $w?->nama) }}"
               class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-1 @error('nama') border-red-400 focus:ring-red-400 @else border-slate-300 focus:ring-slate-500 @enderror">
        @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="tempat_lahir" class="mb-1 block text-sm font-medium text-slate-700">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" id="tempat_lahir"
               value="{{ old('tempat_lahir', $w?->tempat_lahir) }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="tanggal_lahir" class="mb-1 block text-sm font-medium text-slate-700">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
               value="{{ old('tanggal_lahir', $w?->tanggal_lahir?->format('Y-m-d')) }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
        @error('tanggal_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="jenis_kelamin" class="mb-1 block text-sm font-medium text-slate-700">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
            <option value="">Pilih</option>
            <option value="Laki-laki" @selected(old('jenis_kelamin', $w?->jenis_kelamin) === 'Laki-laki')>Laki-laki</option>
            <option value="Perempuan" @selected(old('jenis_kelamin', $w?->jenis_kelamin) === 'Perempuan')>Perempuan</option>
        </select>
    </div>

    <div>
        <label for="agama" class="mb-1 block text-sm font-medium text-slate-700">Agama</label>
        <input type="text" name="agama" id="agama"
               value="{{ old('agama', $w?->agama) }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="pekerjaan" class="mb-1 block text-sm font-medium text-slate-700">Pekerjaan</label>
        <input type="text" name="pekerjaan" id="pekerjaan"
               value="{{ old('pekerjaan', $w?->pekerjaan) }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="status_perkawinan" class="mb-1 block text-sm font-medium text-slate-700">Status Perkawinan</label>
        <select name="status_perkawinan" id="status_perkawinan"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
            <option value="">Pilih</option>
            @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $status)
                <option value="{{ $status }}" @selected(old('status_perkawinan', $w?->status_perkawinan) === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="no_hp" class="mb-1 block text-sm font-medium text-slate-700">Nomor HP</label>
        <input type="text" name="no_hp" id="no_hp"
               value="{{ old('no_hp', $w?->no_hp) }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="rt" class="mb-1 block text-sm font-medium text-slate-700">RT</label>
        <input type="text" name="rt" id="rt" maxlength="5"
               value="{{ old('rt', $w?->rt) }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div>
        <label for="rw" class="mb-1 block text-sm font-medium text-slate-700">RW</label>
        <input type="text" name="rw" id="rw" maxlength="5"
               value="{{ old('rw', $w?->rw) }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">
    </div>

    <div class="sm:col-span-2">
        <label for="alamat" class="mb-1 block text-sm font-medium text-slate-700">Alamat Lengkap</label>
        <textarea name="alamat" id="alamat" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-500">{{ old('alamat', $w?->alamat) }}</textarea>
    </div>
</div>
