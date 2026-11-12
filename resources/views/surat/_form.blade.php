{{-- Form Surat Live Sheet (Editor langsung di atas format kertas surat fisik) --}}
@php
    $intro = [
        'SKD' => [
            ['t' => 'Adalah benar yang namanya tersebut di atas adalah warga penduduk yang berdomisili dan bertempat tinggal di wilayah Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang.'],
        ],
        'SKTM' => [
            ['t' => 'Adalah benar yang namanya tersebut di atas adalah warga penduduk Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang yang berdasarkan data dan pengamatan di lapangan tergolong dalam keluarga yang '],
            ['t' => 'BERPENGHASILAN RENDAH / TIDAK MAMPU', 'b' => true],
            ['t' => '.'],
        ],
        'SKU' => [
            ['t' => 'Menerangkan dengan sebenarnya bahwa orang tersebut di atas adalah benar memiliki dan menjalankan usaha di wilayah Desa Tanjung Selamat dengan rincian sebagai berikut:'],
        ],
        'SKIK' => [
            ['t' => 'Menerangkan dengan sebenarnya bahwa yang bersangkutan bermaksud menyelenggarakan kegiatan / acara keramaian di wilayah Desa Tanjung Selamat dengan rincian sebagai berikut:'],
        ],
        'SKM' => [
            ['t' => 'Menerangkan dengan sebenarnya bahwa telah berpulang ke rahmatullah warga penduduk Desa Tanjung Selamat dengan rincian sebagai berikut:'],
        ],
        'DEFAULT' => [
            ['t' => 'Menerangkan dengan sebenarnya bahwa yang bersangkutan adalah warga Desa Tanjung Selamat dengan data keterangan pendukung:'],
        ],
    ];

    $initialWarga = old('warga_id') ? \App\Models\Warga::find(old('warga_id')) : ($wargaTerpilih ?? null);
    $initialWargaData = null;
    if ($initialWarga) {
        $initialWargaData = [
            'id' => $initialWarga->id,
            'nik' => $initialWarga->nik,
            'nama' => $initialWarga->nama,
            'no_kk' => $initialWarga->no_kk,
            'tempat_lahir' => $initialWarga->tempat_lahir,
            'tanggal_lahir' => $initialWarga->tanggal_lahir,
            'jenis_kelamin' => $initialWarga->jenis_kelamin,
            'agama' => $initialWarga->agama,
            'pekerjaan' => $initialWarga->pekerjaan,
            'status_perkawinan' => $initialWarga->status_perkawinan,
            'alamat' => $initialWarga->alamat,
            'rt' => $initialWarga->rt ?? '-',
            'rw' => $initialWarga->rw ?? '-',
        ];
    }

    $BULAN = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $todayStr = date('j') . ' ' . ($BULAN[(int)date('n')] ?? date('F')) . ' ' . date('Y');

    $logoFile = public_path('images/logo-deli-serdang.png');
    $logoSrc = file_exists($logoFile)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile))
        : asset('images/logo-deli-serdang.png');
@endphp

<div x-data="suratBuilder({
    searchUrl: @js(route('warga.cari')),
    jenis: @js($jenisSuratList),
    jenisId: @js((int) old('jenis_surat_id', $jenisTerpilihId ?? ($jenisSuratList->first()->id ?? 1))),
    today: @js($todayStr),
    isian: @js(old('data_isian', $isianAwal ?? [])),
    warga: @js($initialWargaData),
})" class="space-y-6">

    {{-- Hidden Input untuk Form Submission --}}
    <input type="hidden" name="warga_id" :value="warga ? warga.id : ''" required>
    <input type="hidden" name="jenis_surat_id" :value="jenisId" required>

    {{-- KARTU 1: PILIH JENIS SURAT --}}
    <x-card>
        <div class="space-y-1.5">
            <label for="pilih_jenis_surat" class="block text-sm font-medium text-ink-900">
                Jenis surat <span class="font-normal text-danger-700">(wajib)</span>
            </label>
            <select id="pilih_jenis_surat" x-model.number="jenisId" class="field">
                @foreach($jenisSuratList as $j)
                <option value="{{ $j->id }}">{{ $j->nama_jenis }} ({{ $j->kode }})</option>
                @endforeach
            </select>
            <p class="text-xs text-ink-600 mt-1" x-text="jenis?.deskripsi"></p>
        </div>
    </x-card>

    {{-- KARTU 2: KERTAS SURAT RESMI (LIVE SHEET EDITOR) --}}
    <div class="rounded-card border border-line bg-surface p-6 sm:p-10 shadow-sm space-y-6">

        {{-- KOP SURAT RESMI (sama dengan surat cetak) --}}
        <div class="font-serif text-ink-900">
            <div class="relative text-center">
                <img src="{{ $logoSrc }}" alt="Logo Kabupaten Deli Serdang" class="hidden sm:block absolute left-2 top-0 w-16 h-auto object-contain">
                <div class="sm:px-20 leading-tight">
                    <h4 class="text-sm sm:text-base font-bold uppercase">PEMERINTAH KABUPATEN DELI SERDANG</h4>
                    <h3 class="text-sm sm:text-base font-bold uppercase mt-0.5">KECAMATAN SUNGGAL</h3>
                    <h2 class="text-lg sm:text-xl font-bold uppercase">DESA TANJUNG SELAMAT</h2>
                </div>
            </div>
            <div class="mt-3 border-y-4 border-ink-900 py-0.5 text-center text-[10px] sm:text-xs font-bold">
                Alamat Kantor : Jl. Besar Tanjung Selamat Dusun III. Desa Tanjung Selamat. Kode Pos 20352
            </div>
        </div>

        {{-- JUDUL & NOMOR SURAT --}}
        <div class="text-center font-serif my-4">
            <h1 class="text-base sm:text-lg font-bold uppercase underline tracking-wide" x-text="jenis?.nama_jenis"></h1>
            @php
            $romawi = [1=>'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
            $nomorDefault = $nomorSuratEksis ?? ('470//'.$romawi[(int) date('n')].'/TS/'.date('Y'));
        @endphp
        <div class="mt-1 flex items-center justify-center gap-2 font-sans">
            <label for="nomor_surat" class="text-sm font-bold font-serif">Nomor :</label>
            <input type="text" id="nomor_surat" name="nomor_surat" required maxlength="255"
                   value="{{ old('nomor_surat', $nomorDefault) }}"
                   placeholder="470/001/XI/TS/2026" class="field bg-white w-64 text-center font-medium">
        </div>
        @error('nomor_surat')<p class="text-xs text-danger-700 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- PARAGRAF PEMBUKA --}}
        <p class="font-serif text-justify text-sm sm:text-[15px] leading-relaxed text-ink-900">
            Kepala Desa Tanjung Selamat Kecamatan Sunggal Kabupaten Deli Serdang dengan ini menerangkan bahwa :
        </p>

        {{-- BANNER STATUS WARGA (DALAM SURAT) --}}
        <div class="rounded-control border px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs sm:text-sm font-sans transition-colors"
             :class="{
                 'border-emerald-300 bg-emerald-50/80 text-emerald-900': warga,
                 'border-rose-300 bg-rose-50/90 text-rose-900': !warga && hasNotFound,
                 'border-brand-200 bg-brand-50/70 text-ink-600': !warga && !hasNotFound
             }">
            <div class="flex items-center gap-2">
                <template x-if="warga">
                    <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-700">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        Data warga terisi dari database
                    </span>
                </template>
                <template x-if="!warga && hasNotFound">
                    <span class="inline-flex items-center gap-1.5 font-semibold text-rose-700">
                        <x-icon name="exclamation" class="size-4 shrink-0 text-rose-600" />
                        <span>Catatan: Data tidak ada di database (<span class="font-normal" x-text="notFoundSummary"></span>)</span>
                    </span>
                </template>
                <template x-if="!warga && !hasNotFound">
                    <span class="text-ink-600">
                        Ketik Nama, NIK, atau No. KK di bawah untuk memilih data warga:
                    </span>
                </template>
            </div>
            <div class="flex items-center gap-3">
                <template x-if="warga">
                    <button type="button" @click="clearWarga()" class="btn btn-secondary btn-sm text-xs">
                        Ganti warga
                    </button>
                </template>
                <a href="{{ route('warga.create') }}" target="_blank"
                   class="text-xs font-semibold underline transition-colors"
                   :class="hasNotFound ? 'text-rose-700 hover:text-rose-900 font-bold' : 'text-brand-700 hover:text-brand-900'">
                    Warga belum terdaftar? Daftarkan
                </a>
            </div>
        </div>

        {{-- PESAN ERROR JIKA WARGA BELUM DIPILIH SAAT SUBMIT --}}
        <template x-if="error">
            <div class="rounded-control border border-danger-300 bg-danger-50 p-3 text-xs sm:text-sm text-danger-800 flex items-center gap-2">
                <x-icon name="exclamation" class="size-4 shrink-0 text-danger-700"/>
                <span x-text="error"></span>
            </div>
        </template>

        {{-- FORM INPUT IDENTITAS WARGA (LANGSUNG DI FORMAT BARIS SURAT) --}}
        <div class="space-y-2.5 font-serif text-sm sm:text-[15px] text-ink-900">
            {{-- 3 Kolom Input Langsung: Nama, NIK, No. KK --}}
            <template x-for="fld in searchFields" :key="fld.key">
                <div class="relative flex flex-col sm:flex-row sm:items-start gap-2">
                    <label :for="'f-' + fld.key" class="w-44 sm:w-52 shrink-0 flex items-center justify-between sm:pt-2.5">
                        <span x-text="fld.label"></span>
                        <span class="hidden sm:inline">:</span>
                    </label>
                    <div class="relative flex-1 font-sans">
                        <input :type="fld.key === 'nama' ? 'text' : 'text'"
                               :id="'f-' + fld.key"
                               :name="fld.key"
                               :inputmode="fld.inputmode"
                               :maxlength="fld.maxlength"
                               :value="form[fld.key]"
                               :placeholder="fld.placeholder"
                               @input="onInput(fld.key, $el)"
                               @blur="onBlur(fld.key)"
                               @keydown.arrow-down.prevent="move(1)"
                               @keydown.arrow-up.prevent="move(-1)"
                               @keydown.enter.prevent="choose()"
                               class="field bg-white font-medium transition-colors"
                               :class="{'border-rose-400 bg-rose-50/20 focus:border-rose-600 focus:ring-rose-500/30': !warga && notFound[fld.key]}">

                        {{-- Dropdown Autocomplete Pencarian Warga --}}
                        <div x-show="open && activeField === fld.key && (results.length > 0 || (searched && !loading && !warga && form[fld.key].trim().length >= 3))"
                             @click.outside="open = false"
                             x-cloak
                             class="absolute left-0 top-full z-30 mt-1 max-h-56 w-full overflow-auto rounded-control border border-line bg-surface shadow-xl text-left">
                            
                            {{-- Jika ada hasil --}}
                            <template x-if="results.length > 0">
                                <ul class="divide-y divide-line text-xs sm:text-sm">
                                    <template x-for="(r, idx) in results" :key="r.id">
                                        <li :id="'warga-opt-' + idx"
                                            @click="pick(r)"
                                            :class="{'bg-brand-50 text-brand-900 font-semibold': active === idx, 'hover:bg-canvas': active !== idx}"
                                            class="cursor-pointer px-4 py-2.5 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span x-text="r.nama"></span>
                                                <span class="font-mono text-xs text-ink-600" x-text="mask(r.nik)"></span>
                                            </div>
                                            <p class="text-xs text-ink-500 font-normal mt-0.5" x-text="r.alamat_lengkap || r.alamat"></p>
                                        </li>
                                    </template>
                                </ul>
                            </template>

                            {{-- Jika TIDAK ADA hasil --}}
                            <template x-if="results.length === 0 && searched && !loading && !warga">
                                <div class="p-3.5 text-xs sm:text-sm bg-rose-50/90 text-rose-800">
                                    <div class="flex items-start gap-2.5">
                                        <x-icon name="exclamation" class="size-4 shrink-0 text-rose-600 mt-0.5"/>
                                        <div class="flex-1 space-y-1">
                                            <p class="font-semibold text-rose-900">
                                                Data tidak ditemukan di database
                                            </p>
                                            <p class="text-xs text-rose-700 leading-relaxed">
                                                Tidak ditemukan warga dengan <span class="lowercase" x-text="fld.label"></span> "<strong x-text="form[fld.key]"></strong>" di database desa.
                                            </p>
                                            <div class="pt-1 flex items-center gap-2">
                                                <a href="{{ route('warga.create') }}" target="_blank"
                                                   class="inline-flex items-center gap-1 font-semibold text-brand-700 hover:text-brand-900 underline">
                                                    <span>Daftarkan warga baru</span>
                                                    <x-icon name="arrow-right" class="size-3"/>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Status Searching/Loading --}}
                        <div x-show="loading && activeField === fld.key" x-cloak class="absolute right-3 top-2.5">
                            <svg class="size-4 animate-spin text-brand-600" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </div>

                        {{-- Catatan di bawah input box jika tidak ditemukan di database --}}
                        <div x-show="!warga && notFound[fld.key] && (!open || activeField !== fld.key) && !loading"
                             x-cloak
                             class="mt-1.5 flex items-center gap-1.5 text-xs text-rose-600 font-medium">
                            <x-icon name="exclamation" class="size-3.5 shrink-0 text-rose-500" />
                            <span>
                                <span x-text="fld.label"></span> "<strong x-text="notFound[fld.key]"></strong>" tidak ada di database warga.
                            </span>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Baris Data Otomatis dengan Garis Titik-Titik Bawah --}}
            <template x-for="row in autoRows" :key="row.label">
                <div class="flex items-baseline gap-2 py-0.5">
                    <span class="w-44 sm:w-52 shrink-0 flex items-center justify-between">
                        <span x-text="row.label"></span>
                        <span>:</span>
                    </span>
                    <span class="flex-1 border-b border-dotted border-ink-400 text-ink-900 pb-0.5 min-h-[1.5rem]"
                          x-text="row.value || ''"></span>
                </div>
            </template>
        </div>

        {{-- ISI SURAT PER JENIS (sama dengan surat cetak) --}}
        <div class="font-serif text-sm sm:text-[15px] text-ink-900 leading-relaxed space-y-4">

            {{-- SKTM --}}
            <template x-if="kode === 'SKTM'">
                <div class="space-y-4">
                    <p class="text-justify">Nama tersebut di atas benar Penduduk Desa Tanjung Selamat Kecamatan Sunggal Kabupaten Deli Serdang, dan menurut sepengetahuan kami memang benar nama tersebut di atas berasal dari <b>Keluarga Kurang Mampu/Miskin</b>.</p>
                    <p class="text-justify">Surat Keterangan ini dibuat atas permintaan yang bersangkutan guna untuk melengkapi persyaratan administrasi :</p>
                    @include('surat._row', ['label' => 'Untuk keperluan', 'key' => 'keperluan', 'placeholder' => 'cth: beasiswa / BPJS'])
                    <p class="text-justify">Demikianlah Surat Keterangan ini diperbuat dengan sebenarnya untuk dapat dipergunakan seperlunya.</p>
                </div>
            </template>

            {{-- DOMISILI --}}
            <template x-if="kode === 'SKD'">
                <div class="space-y-4">
                    <p class="text-justify">Nama tersebut di atas menurut sepengetahuan kami pada saat ini memang benar <b>Berdomisili/Bertempat</b> :</p>
                    @include('surat._row', ['label' => 'Berdomisili/Bertempat', 'key' => 'bertempat', 'placeholder' => 'cth: sejak tahun 2020'])
                    @include('surat._row', ['label' => 'di Dusun', 'key' => 'dusun', 'placeholder' => 'IV'])
                    <p class="text-justify">Desa Tanjung Selamat Kec. Sunggal Kab. Deli Serdang.</p>
                    <p class="text-justify">Surat Keterangan ini diberikan atas permintaan yang bersangkutan adalah bermaksud untuk kelengkapan administrasi.</p>
                    <p class="text-justify">Demikianlah Surat Keterangan ini diperbuat dengan sebenarnya untuk dapat dipergunakan seperlunya dan apabila dikemudian hari terdapat kekeliruan maka kami akan memperbaiki sebagaimana mestinya.</p>
                </div>
            </template>

            {{-- MANDAH MASUK --}}
            <template x-if="kode === 'MMS'">
                <div class="space-y-4">
                    <p class="text-justify">Memang benar nama tersebut di atas berdasarkan Surat Keterangan Jalan/Mandah :</p>
                    @include('surat._row', ['label' => 'Dengan Nomor', 'key' => 'no_mandah'])
                    @include('surat._row', ['label' => 'Dikeluarkan Oleh', 'key' => 'penerbit'])
                    <p>Mandah Masuk ke :</p>
                    @include('surat._row', ['label' => 'Dusun', 'key' => 'dusun'])
                    <div class="space-y-1">
                        <div class="flex gap-2"><span class="w-44 sm:w-52 shrink-0 flex justify-between"><span>Desa/Kelurahan</span><span>:</span></span><span>Tanjung Selamat</span></div>
                        <div class="flex gap-2"><span class="w-44 sm:w-52 shrink-0 flex justify-between"><span>Kecamatan</span><span>:</span></span><span>Sunggal</span></div>
                        <div class="flex gap-2"><span class="w-44 sm:w-52 shrink-0 flex justify-between"><span>Kabupaten</span><span>:</span></span><span>Deli Serdang</span></div>
                        <div class="flex gap-2"><span class="w-44 sm:w-52 shrink-0 flex justify-between"><span>Provinsi</span><span>:</span></span><span>Sumatera Utara</span></div>
                    </div>
                    @include('surat._row', ['label' => 'Alasan Mandah', 'key' => 'alasan'])
                    @include('surat._row', ['label' => 'Lamanya', 'key' => 'lamanya'])
                    <div class="flex gap-2"><span class="w-44 sm:w-52 shrink-0 flex justify-between"><span>Banyaknya Pengikut</span><span>:</span></span><span x-text="pengikut.length"></span></div>
                    @include('surat._pengikut_form')
                    <p class="text-justify">Surat Keterangan Mandah ini berlaku hanya beberapa bulan sejak tanggal s/d tanggal berikut:</p>
                    @include('surat._row', ['label' => 'Berlaku (bulan)', 'key' => 'berlaku_bulan', 'type' => 'number'])
                    @include('surat._row', ['label' => 'Sejak tanggal', 'key' => 'berlaku_dari', 'type' => 'date'])
                    @include('surat._row', ['label' => 's/d tanggal', 'key' => 'berlaku_sampai', 'type' => 'date'])
                    <p class="text-justify">Yang bersangkutan harap segera meminta Surat Keterangan Mandah dari alamat asal selambat-lambatnya seminggu sebelum berakhirnya Surat Keterangan Mandah ini agar dapat memperpanjang berlakunya Surat Keterangan Mandah ini dan diketahui oleh Kepala Dusun.</p>
                    <p class="text-justify">Demikian Surat Keterangan Mandah Masuk ini diperbuat dengan sebenarnya untuk dapat dipergunakan seperlunya. Apabila terdapat kekeliruan maka akan kami perbaiki sebagaimana mestinya.</p>
                    <p class="text-sm">Diketahui, Diperiksa dan Diawasi Oleh :</p>
                    @include('surat._row', ['label' => 'Jabatan/Instansi', 'key' => 'diperiksa_oleh'])
                    @include('surat._row', ['label' => 'Nama Kepala Dusun', 'key' => 'kadus_nama'])
                </div>
            </template>

            {{-- MANDAH / JALAN --}}
            <template x-if="kode === 'MDJ'">
                <div class="space-y-4">
                    <p>Memang benar nama tersebut di atas :</p>
                    @include('surat._row', ['label' => 'Mandah /Jalan ke', 'key' => 'tujuan'])
                    @include('surat._row', ['label' => 'Desa/Kelurahan', 'key' => 'desa'])
                    @include('surat._row', ['label' => 'Kecamatan', 'key' => 'kecamatan'])
                    @include('surat._row', ['label' => 'Kota/Kabupaten', 'key' => 'kabupaten'])
                    @include('surat._row', ['label' => 'Provinsi', 'key' => 'provinsi'])
                    @include('surat._row', ['label' => 'Keperluan', 'key' => 'keperluan'])
                    @include('surat._row', ['label' => 'Lamanya', 'key' => 'lamanya'])
                    <div class="flex gap-2"><span class="w-44 sm:w-52 shrink-0 flex justify-between"><span>Pengikut</span><span>:</span></span><span x-text="pengikut.length"></span></div>
                    @include('surat._pengikut_form')
                    <p class="text-justify">Sesampainya di tempat tujuan, yang bersangkutan selambat-lambatnya 2 x 24 jam harus segera melaporkan diri kepada Aparat Pemerintahan setempat.</p>
                    <p class="text-justify">Demikianlah Surat Keterangan Mandah/Jalan ini diperbuat dengan sebenarnya untuk dapat dipergunakan seperlunya.</p>
                </div>
            </template>
        </div>
    </div>

    {{-- KARTU 3: TOMBOL TINDAKAN (BATAL, SIMPAN SEBAGAI DRAF, AJUKAN KE KADES) --}}
    <div class="flex flex-col-reverse gap-3 rounded-card border border-line bg-surface p-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <x-button variant="ghost" href="{{ $batalUrl }}">Batal</x-button>
        <div class="flex flex-col gap-2 sm:flex-row">
            <button type="submit" name="action" value="draft" class="btn btn-secondary" @click="onSubmit($event)">Simpan sebagai draf</button>
            <button type="submit" name="action" value="ajukan" class="btn btn-primary flex items-center gap-2" @click="onSubmit($event)">
                <span>Ajukan ke Kades</span>
                <span class="flex size-4 items-center justify-center rounded-full border border-current text-[10px]">&#9658;</span>
            </button>
        </div>
    </div>
</div>
