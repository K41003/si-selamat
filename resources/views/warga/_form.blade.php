<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- NIK -->
        <div>
            <label for="nik" class="block text-xs font-semibold text-slate-700 mb-1">NIK (Nomor Induk Kependudukan) <span class="text-rose-500">*</span></label>
            <input type="text" id="nik" name="nik" maxlength="16" required
                value="{{ old('nik', $warga->nik ?? '') }}"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-mono focus:ring-2 focus:ring-blue-600 focus:outline-none"
                placeholder="16 digit angka sesuai KTP">
            <span class="text-[10px] text-slate-400 mt-1 block">Wajib 16 digit angka unik</span>
        </div>

        <!-- No. KK -->
        <div>
            <label for="no_kk" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Kartu Keluarga (No. KK) <span class="text-rose-500">*</span></label>
            <input type="text" id="no_kk" name="no_kk" maxlength="16" required
                value="{{ old('no_kk', $warga->no_kk ?? '') }}"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-mono focus:ring-2 focus:ring-blue-600 focus:outline-none"
                placeholder="16 digit angka No. KK">
        </div>
    </div>

    <!-- Nama Lengkap -->
    <div>
        <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span></label>
        <input type="text" id="nama" name="nama" required
            value="{{ old('nama', $warga->nama ?? '') }}"
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none uppercase"
            placeholder="Contoh: AHMAD FAHRI">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Tempat Lahir -->
        <div>
            <label for="tempat_lahir" class="block text-xs font-semibold text-slate-700 mb-1">Tempat Lahir <span class="text-rose-500">*</span></label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" required
                value="{{ old('tempat_lahir', $warga->tempat_lahir ?? '') }}"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none"
                placeholder="Kota/Kabupaten Lahir">
        </div>

        <!-- Tanggal Lahir -->
        <div>
            <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir <span class="text-rose-500">*</span></label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required
                value="{{ old('tanggal_lahir', isset($warga) && $warga->tanggal_lahir ? date('Y-m-d', strtotime($warga->tanggal_lahir)) : '') }}"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Jenis Kelamin -->
        <div>
            <label for="jenis_kelamin" class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
            <select id="jenis_kelamin" name="jenis_kelamin" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', $warga->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', $warga->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <!-- Agama -->
        <div>
            <label for="agama" class="block text-xs font-semibold text-slate-700 mb-1">Agama <span class="text-rose-500">*</span></label>
            <select id="agama" name="agama" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                <option value="">-- Pilih Agama --</option>
                @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $ag)
                <option value="{{ $ag }}" {{ old('agama', $warga->agama ?? '') === $ag ? 'selected' : '' }}>{{ $ag }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status Perkawinan -->
        <div>
            <label for="status_perkawinan" class="block text-xs font-semibold text-slate-700 mb-1">Status Perkawinan <span class="text-rose-500">*</span></label>
            <select id="status_perkawinan" name="status_perkawinan" required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none bg-white">
                <option value="">-- Pilih Status --</option>
                @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $sp)
                <option value="{{ $sp }}" {{ old('status_perkawinan', $warga->status_perkawinan ?? '') === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Pekerjaan -->
        <div>
            <label for="pekerjaan" class="block text-xs font-semibold text-slate-700 mb-1">Pekerjaan <span class="text-rose-500">*</span></label>
            <input type="text" id="pekerjaan" name="pekerjaan" required
                value="{{ old('pekerjaan', $warga->pekerjaan ?? '') }}"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none"
                placeholder="Contoh: Wiraswasta, Buruh Harian Lepas, PNS, dll.">
        </div>

        <!-- No HP / WA -->
        <div>
            <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP / WhatsApp</label>
            <input type="text" id="no_hp" name="no_hp"
                value="{{ old('no_hp', $warga->no_hp ?? '') }}"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none"
                placeholder="08xxxxxxxxxx">
        </div>
    </div>

    <!-- Alamat Lengkap -->
    <div>
        <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Tempat Tinggal <span class="text-rose-500">*</span></label>
        <textarea id="alamat" name="alamat" rows="2" required
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none"
            placeholder="Jalan, Gang, Dusun di Desa Tanjung Selamat">{{ old('alamat', $warga->alamat ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4 max-w-xs">
        <div>
            <label for="rt" class="block text-xs font-semibold text-slate-700 mb-1">RT</label>
            <input type="text" id="rt" name="rt" value="{{ old('rt', $warga->rt ?? '') }}"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none font-mono" placeholder="01">
        </div>
        <div>
            <label for="rw" class="block text-xs font-semibold text-slate-700 mb-1">RW</label>
            <input type="text" id="rw" name="rw" value="{{ old('rw', $warga->rw ?? '') }}"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none font-mono" placeholder="02">
        </div>
    </div>
</div>
