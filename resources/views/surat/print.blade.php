<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat - {{ $surat->nomor_surat }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #f1f5f9;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm 25mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            box-sizing: border-box;
            position: relative;
        }
        .double-border {
            border-bottom: 3px double #000;
        }
        @media print {
            body {
                background: transparent;
                margin: 0;
                padding: 0;
            }
            .page {
                box-shadow: none;
                margin: 0;
                width: 100%;
                min-height: 100vh;
                padding: 15mm 20mm;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="py-6">
    <!-- Floating Print Control Bar (Hidden on Print) -->
    <div class="fixed top-4 right-4 z-50 flex items-center gap-2 bg-slate-900/90 backdrop-blur-md p-2 rounded-2xl shadow-xl text-white no-print">
        <a href="{{ route('surat.show', $surat) }}" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-sans transition">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="px-4 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-xs font-semibold font-sans transition flex items-center gap-1.5 shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    <!-- Halaman Kertas Surat (A4 Standard) -->
    <div class="page text-[12pt] leading-normal">
        <!-- KOP SURAT RESMI -->
        <div class="text-center relative pb-3 double-border mb-6">
            <div class="absolute left-2 top-1 w-20 h-24 flex items-center justify-center">
                <svg class="w-16 h-20 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="px-16">
                <h4 class="text-[13pt] font-bold tracking-wider leading-tight uppercase">Pemerintah Kabupaten Deli Serdang</h4>
                <h3 class="text-[14pt] font-bold tracking-wider leading-tight uppercase">Kecamatan Sunggal</h3>
                <h2 class="text-[16pt] font-extrabold tracking-wider leading-tight uppercase mt-0.5">Kantor Kepala Desa Tanjung Selamat</h2>
                <p class="text-[9.5pt] italic text-slate-700 mt-1">
                    Alamat: Jl. Tanjung Selamat No. 1, Kec. Sunggal, Kab. Deli Serdang &bull; Kode Pos 20353
                </p>
            </div>
        </div>

        <!-- JUDUL & NOMOR SURAT -->
        <div class="text-center mb-6">
            <h1 class="text-[13pt] font-bold uppercase underline tracking-wide">{{ $surat->jenisSurat->nama_jenis }}</h1>
            <p class="text-[11pt] mt-0.5 font-bold">Nomor : {{ $surat->nomor_surat }}</p>
        </div>

        <!-- PARAGRAF PEMBUKA -->
        <p class="text-justify mb-4 indent-8 leading-relaxed">
            Yang bertanda tangan di bawah ini, Kepala Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang, Provinsi Sumatera Utara, dengan ini menerangkan bahwa:
        </p>

        <!-- BIODATA PEMOHON -->
        <div class="pl-8 mb-4 space-y-1 text-[11.5pt] leading-relaxed">
            <table class="w-full">
                <tr>
                    <td class="w-48 align-top py-0.5">Nama Lengkap</td>
                    <td class="w-4 align-top py-0.5">:</td>
                    <td class="font-bold py-0.5 uppercase">{{ $surat->warga->nama }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">NIK</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="py-0.5 font-bold">{{ $surat->warga->nik }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">Nomor Kartu Keluarga</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="py-0.5">{{ $surat->warga->no_kk }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">Tempat / Tanggal Lahir</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="py-0.5">{{ $surat->warga->tempat_lahir }}, {{ date('d F Y', strtotime($surat->warga->tanggal_lahir)) }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">Jenis Kelamin</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="py-0.5">{{ $surat->warga->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">Agama</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="py-0.5">{{ $surat->warga->agama }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">Pekerjaan</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="py-0.5">{{ $surat->warga->pekerjaan }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">Status Perkawinan</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="py-0.5">{{ $surat->warga->status_perkawinan }}</td>
                </tr>
                <tr>
                    <td class="align-top py-0.5">Alamat Tempat Tinggal</td>
                    <td class="align-top py-0.5">:</td>
                    <td class="py-0.5">{{ $surat->warga->alamat }} @if($surat->warga->rt || $surat->warga->rw) RT {{ $surat->warga->rt ?? '-' }} / RW {{ $surat->warga->rw ?? '-' }} @endif, Desa Tanjung Selamat</td>
                </tr>
            </table>
        </div>

        <!-- ISI KETERANGAN SESUAI JENIS SURAT -->
        <div class="mb-4 text-justify leading-relaxed">
            @if($surat->jenisSurat->kode === 'SKD')
                <p class="indent-8 mb-2">
                    Adalah benar yang namanya tersebut di atas adalah warga penduduk yang berdomisili dan bertempat tinggal di wilayah Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang.
                </p>
                @if(!empty($surat->data_isian['keperluan']))
                <p class="indent-8">
                    Surat keterangan ini diberikan kepada yang bersangkutan untuk keperluan: <strong>{{ $surat->data_isian['keperluan'] }}</strong>.
                </p>
                @endif

            @elseif($surat->jenisSurat->kode === 'SKTM')
                <p class="indent-8 mb-2">
                    Adalah benar yang namanya tersebut di atas adalah warga penduduk Desa Tanjung Selamat, Kecamatan Sunggal, Kabupaten Deli Serdang yang berdasarkan data dan pengamatan di lapangan tergolong dalam keluarga yang <strong>BERPENGHASILAN RENDAH / TIDAK MAMPU</strong>.
                </p>
                <div class="pl-8 my-2">
                    <p>&bull; Estimasi Penghasilan : Rp {{ number_format((int)($surat->data_isian['penghasilan_per_bulan'] ?? 0), 0, ',', '.') }} / bulan</p>
                    <p>&bull; Tanggungan Keluarga : {{ $surat->data_isian['jumlah_tanggungan'] ?? '-' }} orang</p>
                </div>
                <p class="indent-8">
                    Surat keterangan ini dipergunakan untuk keperluan: <strong>{{ $surat->data_isian['keperluan'] ?? 'Persyaratan bantuan sosial / beasiswa pendidikan' }}</strong>.
                </p>

            @elseif($surat->jenisSurat->kode === 'SKU')
                <p class="indent-8 mb-2">
                    Menerangkan dengan sebenarnya bahwa orang tersebut di atas adalah benar memiliki dan menjalankan usaha di wilayah Desa Tanjung Selamat dengan rincian sebagai berikut:
                </p>
                <div class="pl-8 my-2 space-y-0.5">
                    <p>&bull; <strong>Nama Usaha</strong> : {{ $surat->data_isian['nama_usaha'] ?? '-' }}</p>
                    <p>&bull; <strong>Bidang / Jenis Usaha</strong> : {{ $surat->data_isian['jenis_usaha'] ?? '-' }}</p>
                    <p>&bull; <strong>Alamat Lokasi Usaha</strong> : {{ $surat->data_isian['alamat_usaha'] ?? '-' }}</p>
                    <p>&bull; <strong>Mulai Berdiri</strong> : Tahun {{ $surat->data_isian['tahun_berdiri'] ?? '-' }}</p>
                </div>

            @else
                <p class="indent-8 mb-2">
                    Menerangkan dengan sebenarnya bahwa yang bersangkutan adalah warga Desa Tanjung Selamat dengan data keterangan pendukung:
                </p>
                <div class="pl-8 my-2 space-y-0.5">
                    @foreach($surat->data_isian ?: [] as $k => $v)
                    <p>&bull; <strong class="capitalize">{{ str_replace('_', ' ', $k) }}</strong> : {{ $v }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- PARAGRAF PENUTUP -->
        <p class="text-justify indent-8 leading-relaxed mb-8">
            Demikian surat keterangan ini kami perbuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya oleh yang berkepentingan.
        </p>

        <!-- KOLOM TANDA TANGAN KEPALA DESA & STEMPEL DIGITAL -->
        <div class="flex justify-end pt-4">
            <div class="w-72 text-center text-[11.5pt] leading-normal relative">
                <p>Tanjung Selamat, {{ $surat->validasi ? $surat->validasi->validated_at->translatedFormat('d F Y') : date('d F Y') }}</p>
                <p class="font-bold uppercase mt-1">Kepala Desa Tanjung Selamat</p>

                <!-- AREA STEMPEL & QR CODE TOKEN -->
                <div class="my-3 flex items-center justify-center relative min-h-[90px]">
                    @if($surat->validasi && $surat->validasi->qr_code_token)
                    <div class="flex flex-col items-center justify-center p-2 border border-slate-300 rounded bg-white shadow-2xs">
                        <!-- Simulated QR Code SVG -->
                        <svg class="w-16 h-16 text-slate-900" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm8-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm11 1h2v2h-2v-2zm-3-3h2v2h-2v-2zm6 0h2v2h-2v-2zm-3 3h2v2h-2v-2zm3 3h2v2h-2v-2zm-6 0h2v2h-2v-2zm3-3h2v2h-2v-2z"></path>
                        </svg>
                        <span class="text-[7pt] font-mono font-bold tracking-tighter text-slate-600 mt-1">
                            {{ $surat->validasi->qr_code_token }}
                        </span>
                    </div>
                    @else
                    <div class="h-16 flex items-center justify-center text-xs italic text-slate-400">
                        (Belum divalidasi)
                    </div>
                    @endif
                </div>

                <p class="font-bold underline text-[12pt] uppercase tracking-wide">
                    {{ $surat->validasi ? $surat->validasi->validator_name : 'H. AHMAD FAUZI, S.Sos' }}
                </p>
                <p class="text-[10pt] text-slate-700">NIP. 19740512 200212 1 003</p>
            </div>
        </div>

        <!-- FOOTER KEABSAHAN ELEKTRONIK -->
        <div class="absolute bottom-6 left-25 right-25 text-center border-t border-slate-300 pt-2 text-[8pt] italic text-slate-500 font-sans">
            Dokumen ini diterbitkan secara sah dan tersertifikasi secara digital melalui Sistem Informasi Administrasi Desa (SI SELAMAT).
            Keaslian naskah dapat diverifikasi melalui pemindaian kode QR di atas.
        </div>
    </div>
</body>
</html>
