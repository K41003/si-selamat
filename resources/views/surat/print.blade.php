@php
    $w = $surat->warga;
    $d = $surat->data_isian ?: [];
    $kode = $surat->jenisSurat->kode;
    $BLN = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $fmt = fn($t) => $t ? \Carbon\Carbon::parse($t)->day.' '.$BLN[\Carbon\Carbon::parse($t)->month].' '.\Carbon\Carbon::parse($t)->year : '';
    $tglSurat = $fmt($surat->validasi?->validated_at ?? now());
    $namaKades = $surat->validasi?->validator_name ?? 'SAPII ANDINATA';
    $ttl = $w->tempat_lahir.', '.$fmt($w->tanggal_lahir);
    $v = fn($k, $def = '') => e($d[$k] ?? $def);
    $view = [
        'SKTM' => 'surat.templates.sktm',
        'SKD'  => 'surat.templates.domisili',
        'MMS'  => 'surat.templates.mandah-masuk',
        'MDJ'  => 'surat.templates.mandah-jalan',
    ][$kode] ?? null;

    $logoFile = public_path('images/logo-deli-serdang.png');
    $logoSrc = file_exists($logoFile)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile))
        : asset('images/logo-deli-serdang.png');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cetak Surat - {{ $surat->nomor_surat }}</title>
<style>
    @page { size: A4; margin: 0; }
    * { box-sizing: border-box; }
    body { margin: 0; background: #e2e8f0; font-family: "Times New Roman", Times, serif; color: #000; font-size: 12pt; line-height: 1.25; }
    .page { width: 210mm; min-height: 297mm; margin: 16px auto; background: #fff; padding: 14mm 20mm 12mm; box-shadow: 0 2px 8px rgba(0,0,0,.2); }
    .page.compact { font-size: 11pt; line-height: 1.2; }

    .kop { position: relative; text-align: center; padding: 0 0 0 0; min-height: 30mm; }
    .kop img { position: absolute; left: 2mm; top: 0; width: 24mm; }
    .kop .t1 { font-size: 15pt; font-weight: bold; margin: 3mm 0 0 20mm; }
    .kop .t2 { font-size: 16pt; font-weight: bold; margin: 1mm 0 0 20mm; }
    .kop .t3 { font-size: 20pt; font-weight: bold; margin: 0 0 0 20mm; }
    .alamat { border-top: 4px solid #000; border-bottom: 4px solid #000; margin: 2mm -4mm 5mm; padding: 1px 0; text-align: center; font-weight: bold; font-size: 9.5pt; }

    .judul { text-align: center; margin-bottom: 5mm; }
    .judul h1 { display: inline-block; margin: 0; font-size: 15pt; text-decoration: underline; letter-spacing: .3px; }
    .judul p { margin: 0; text-align: center; font-weight: bold; font-size: 12pt; }

    p { margin: 0 0 4mm; text-align: justify; }
    table.bio { border-collapse: collapse; margin: 0 0 4mm; }
    table.bio td { padding: 0; vertical-align: top; }
    table.bio td.l { width: 46mm; }
    table.bio td.c { width: 5mm; }
    table.bio.in { margin-left: 5mm; }

    table.grid { width: 100%; border-collapse: collapse; margin: 1mm 0 3mm; }
    table.grid th, table.grid td { border: 1px solid #000; padding: 0 2mm; height: 5mm; font-size: 10.5pt; text-align: center; vertical-align: middle; }
    table.grid th { font-weight: bold; text-align: center; line-height: 1.1; }

    .ttd { margin-left: 55%; margin-top: 6mm; text-align: center; }
    .ttd .nm { text-align: center; font-weight: bold; margin-top: 3mm; }
    .ttd .space { height: 24mm; display: flex; align-items: center; justify-content: center; }
    .ttd .space small { font: 6.5pt monospace; display: block; text-align: center; }
    .ttd .name { text-align: center; font-weight: bold; text-decoration: underline; }

    .bar { position: fixed; top: 12px; right: 12px; display: flex; gap: 8px; z-index: 9; font-family: Arial, sans-serif; }
    .bar a, .bar button { background: #0f172a; color: #fff; border: 0; border-radius: 10px; padding: 8px 14px; font-size: 13px; text-decoration: none; cursor: pointer; }
    .bar button { background: #2563eb; font-weight: 600; }
    @media print {
        body { background: #fff; }
        .page { margin: 0; box-shadow: none; width: 210mm; }
        .bar { display: none !important; }
    }
</style>
</head>
<body>
<div class="bar no-print">
    <a href="{{ route('surat.show', $surat) }}">&larr; Kembali</a>
    <button onclick="window.print()">Cetak / Simpan PDF</button>
</div>

<div class="page {{ in_array($kode, ['MMS','MDJ']) ? 'compact' : '' }}">
    <div class="kop">
        <img src="{{ $logoSrc }}" alt="Logo Kabupaten Deli Serdang">
        <div class="t1">PEMERINTAH KABUPATEN DELI SERDANG</div>
        <div class="t2">KECAMATAN SUNGGAL</div>
        <div class="t3">DESA TANJUNG SELAMAT</div>
    </div>
    <div class="alamat">Alamat Kantor : Jl. Besar Tanjung Selamat Dusun III. Desa Tanjung Selamat. Kode Pos 20352</div>

    <div class="judul">
        <h1>{{ mb_strtoupper($surat->jenisSurat->nama_jenis) }}</h1>
        <p>Nomor : {{ $surat->nomor_surat }}</p>
    </div>

    @if($view)
        @include($view)
    @else
        <p>Template surat untuk kode <b>{{ $kode }}</b> belum tersedia.</p>
    @endif
</div>
</body>
</html>
