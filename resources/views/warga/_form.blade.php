{{-- Form warga (dipakai create & edit). Nama kolom tidak berubah, jadi validasi controller tetap cocok.
     Pada create, $warga tidak ada -> semua nilai awal kosong. --}}
<div class="space-y-8">
    <fieldset class="space-y-5">
        <legend class="mb-4 text-base font-semibold text-ink-900">Identitas</legend>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-field label="NIK (Nomor Induk Kependudukan)" name="nik" required
                     :value="$warga->nik ?? ''" hint="16 digit angka, harus unik."
                     inputmode="numeric" maxlength="16" autocomplete="off"
                     placeholder="16 digit angka sesuai KTP" class="font-mono" />
            <x-field label="Nomor Kartu Keluarga (No. KK)" name="no_kk" required
                     :value="$warga->no_kk ?? ''" hint="16 digit angka."
                     inputmode="numeric" maxlength="16" autocomplete="off"
                     placeholder="16 digit angka No. KK" class="font-mono" />
        </div>
        <x-field label="Nama lengkap (sesuai KTP)" name="nama" required
                 :value="$warga->nama ?? ''" placeholder="Contoh: AHMAD FAHRI" class="uppercase" />
    </fieldset>

    <fieldset class="space-y-5 border-t border-line pt-6">
        <legend class="mb-4 text-base font-semibold text-ink-900">Data pribadi</legend>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-field label="Tempat lahir" name="tempat_lahir" required
                     :value="$warga->tempat_lahir ?? ''" placeholder="Kota/Kabupaten lahir" />
            <x-field label="Tanggal lahir" name="tanggal_lahir" type="date" required
                     :value="isset($warga) && $warga->tanggal_lahir ? date('Y-m-d', strtotime($warga->tanggal_lahir)) : ''" />
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <x-field label="Jenis kelamin" name="jenis_kelamin" as="select" required>
                <option value="">Pilih jenis kelamin</option>
                @foreach(['Laki-laki', 'Perempuan'] as $jk)
                <option value="{{ $jk }}" @selected(old('jenis_kelamin', $warga->jenis_kelamin ?? '') === $jk)>{{ $jk }}</option>
                @endforeach
            </x-field>
            <x-field label="Agama" name="agama" as="select" required>
                <option value="">Pilih agama</option>
                @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $ag)
                <option value="{{ $ag }}" @selected(old('agama', $warga->agama ?? '') === $ag)>{{ $ag }}</option>
                @endforeach
            </x-field>
            <x-field label="Status perkawinan" name="status_perkawinan" as="select" required>
                <option value="">Pilih status</option>
                @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $sp)
                <option value="{{ $sp }}" @selected(old('status_perkawinan', $warga->status_perkawinan ?? '') === $sp)>{{ $sp }}</option>
                @endforeach
            </x-field>
        </div>

        <x-field label="Pekerjaan" name="pekerjaan" required
                 :value="$warga->pekerjaan ?? ''" placeholder="Contoh: Wiraswasta, Buruh Harian Lepas, PNS" />
    </fieldset>

    <fieldset class="space-y-5 border-t border-line pt-6">
        <legend class="mb-4 text-base font-semibold text-ink-900">Alamat dan kontak</legend>
        <x-field label="Alamat tempat tinggal" name="alamat" as="textarea" rows="2" required
                 :value="$warga->alamat ?? ''" placeholder="Jalan, gang, dusun di Desa Tanjung Selamat" />
        <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
            <x-field label="RT" name="rt" :value="$warga->rt ?? ''" inputmode="numeric" placeholder="01" class="font-mono" />
            <x-field label="RW" name="rw" :value="$warga->rw ?? ''" inputmode="numeric" placeholder="02" class="font-mono" />
            <div class="col-span-2">
                <x-field label="Nomor HP / WhatsApp" name="no_hp" type="tel"
                         :value="$warga->no_hp ?? ''" inputmode="tel" placeholder="08xxxxxxxxxx" />
            </div>
        </div>
    </fieldset>
</div>
