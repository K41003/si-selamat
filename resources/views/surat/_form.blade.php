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
@endphp

<div x-data="suratBuilder({
    searchUrl: @js(route('warga.cari')),
    jenis: @js($jenisSuratList),
    jenisId: @js((int) old('jenis_surat_id', $jenisTerpilihId ?? ($jenisSuratList->first()->id ?? 1))),
    intro: @js($intro),
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

        {{-- KOP SURAT RESMI --}}
        <div class="relative pb-4 border-b-[3px] border-double border-ink-900 text-center font-serif text-ink-900">
            {{-- Logo / Lambang Desa Lingkaran di Kiri --}}
            <div class="sm:absolute sm:left-2 sm:top-1 flex justify-center mb-2 sm:mb-0">
                <div class="size-16 sm:size-20 rounded-full border-2 border-ink-900 flex items-center justify-center p-2 text-ink-900">
                    <svg class="size-10 sm:size-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>

            <div class="sm:px-20 leading-tight">
                <h4 class="text-xs sm:text-sm font-bold tracking-wider uppercase">PEMERINTAH KABUPATEN DELI SERDANG</h4>
                <h3 class="text-sm sm:text-base font-bold tracking-wider uppercase mt-0.5">KECAMATAN SUNGGAL</h3>
                <h2 class="text-base sm:text-lg font-extrabold tracking-wider uppercase mt-0.5">KANTOR KEPALA DESA TANJUNG SELAMAT</h2>
                <p class="text-[10px] sm:text-xs italic text-ink-700 mt-1">
                    Jl. Tanjung Selamat No. 1, Kec. Sunggal, Kab. Deli Serdang &bull; Kode Pos 20353
                </p>
            </div>
        </div>

        {{-- JUDUL & NOMOR SURAT --}}
        <div class="text-center font-serif my-4">
            <h1 class="text-base sm:text-lg font-bold uppercase underline tracking-wide" x-text="jenis?.nama_jenis"></h1>
            <p class="text-xs italic text-ink-600 mt-0.5">Nomor : {{ $nomorSuratEksis ?? 'dibuat otomatis saat surat disimpan' }}</p>
        </div>

        {{-- PARAGRAF PEMBUKA --}}
        <p class="font-serif text-justify text-sm sm:text-[15px] leading-relaxed indent-8 text-ink-900">
            Yang bertanda tangan di bawah ini, Kepala Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang, Provinsi Sumatera Utara, dengan ini menerangkan bahwa:
        </p>

        {{-- BANNER STATUS WARGA (DALAM SURAT) --}}
        <div class="rounded-control border border-brand-200 bg-brand-50/70 px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs sm:text-sm font-sans">
            <div class="flex items-center gap-2">
                <template x-if="warga">
                    <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-700">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        Data warga terisi dari database
                    </span>
                </template>
                <template x-if="!warga">
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
                <a href="{{ route('warga.create') }}" target="_blank" class="text-xs font-semibold text-brand-700 underline hover:text-brand-900">
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
                <div class="relative flex flex-col sm:flex-row sm:items-center gap-2">
                    <label :for="'f-' + fld.key" class="w-44 sm:w-52 shrink-0 flex items-center justify-between">
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
                               @keydown.arrow-down.prevent="move(1)"
                               @keydown.arrow-up.prevent="move(-1)"
                               @keydown.enter.prevent="choose()"
                               class="field bg-white font-medium">

                        {{-- Dropdown Autocomplete Pencarian Warga --}}
                        <div x-show="open && activeField === fld.key && results.length > 0"
                             @click.outside="open = false"
                             x-cloak
                             class="absolute left-0 top-full z-30 mt-1 max-h-56 w-full overflow-auto rounded-control border border-line bg-surface shadow-xl text-left">
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
                        </div>

                        {{-- Status Searching/Empty --}}
                        <div x-show="loading && activeField === fld.key" x-cloak class="absolute right-3 top-2.5">
                            <svg class="size-4 animate-spin text-brand-600" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
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

        {{-- PARAGRAF KETERANGAN DINAMIS SESUAI JENIS SURAT --}}
        <p class="font-serif text-justify text-sm sm:text-[15px] leading-relaxed my-4 text-ink-900">
            <template x-for="(part, pIdx) in introParts" :key="pIdx">
                <span :class="{'font-bold': part.b}" x-text="part.t"></span>
            </template>
        </p>

        {{-- FORM ISIAN KHUSUS SURAT (TEMPLATE FIELDS) LANGSUNG DI BADAN SURAT --}}
        <div class="space-y-3 my-4 font-serif text-ink-900">
            <template x-for="f in fields" :key="f.key">
                <div class="flex flex-col sm:flex-row sm:items-start gap-2 text-sm sm:text-[15px]">
                    <label :for="'field-' + f.key" class="w-56 shrink-0 pt-2 font-medium">
                        &bull; <span x-text="f.label"></span> :
                    </label>
                    <div class="flex-1 font-sans">
                        <template x-if="f.type === 'textarea'">
                            <textarea :id="'field-' + f.key"
                                      :name="'data_isian[' + f.key + ']'"
                                      x-model="isian[f.key]"
                                      :placeholder="f.placeholder || ''"
                                      rows="3"
                                      required
                                      class="field bg-white"></textarea>
                        </template>
                        <template x-if="f.type !== 'textarea'">
                            <input :type="f.type || 'text'"
                                   :id="'field-' + f.key"
                                   :name="'data_isian[' + f.key + ']'"
                                   x-model="isian[f.key]"
                                   :placeholder="f.placeholder || ''"
                                   required
                                   class="field bg-white">
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- PARAGRAF PENUTUP --}}
        <p class="font-serif text-justify text-sm sm:text-[15px] leading-relaxed indent-8 my-4 text-ink-900">
            Demikian surat keterangan ini kami perbuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya oleh yang berkepentingan.
        </p>

        {{-- KOLOM TANDA TANGAN KEPALA DESA --}}
        <div class="flex justify-end pt-4 font-serif">
            <div class="w-64 sm:w-72 text-center text-sm sm:text-[15px] space-y-1">
                <p>Tanjung Selamat, <span x-text="today"></span></p>
                <p class="font-bold uppercase tracking-wide">KEPALA DESA TANJUNG SELAMAT</p>
                <div class="my-3 rounded-control border border-dashed border-ink-400 p-4 font-sans text-xs text-ink-600 bg-canvas/40 min-h-[85px] flex items-center justify-center">
                    Tanda tangan digital dan QR terbit setelah surat disetujui Kepala Desa
                </div>
            </div>
        </div>

    </div>

    {{-- KARTU 3: TOMBOL TINDAKAN (BATAL, SIMPAN SEBAGAI DRAF, AJUKAN KE KADES) --}}
    <div class="flex flex-col-reverse gap-3 rounded-card border border-line bg-surface p-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <x-button variant="ghost" href="{{ $batalUrl }}">Batal</x-button>
        <div class="flex flex-col gap-2 sm:flex-row">
            <button type="submit" name="action" value="draft" class="btn btn-secondary">Simpan sebagai draf</button>
            <button type="submit" name="action" value="ajukan" class="btn btn-primary flex items-center gap-2">
                <span>Ajukan ke Kades</span>
                <span class="flex size-4 items-center justify-center rounded-full border border-current text-[10px]">&#9658;</span>
            </button>
        </div>
    </div>
</div>
