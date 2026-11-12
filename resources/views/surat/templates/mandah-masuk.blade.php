<p style="margin-bottom:2mm">Kepala Desa Tanjung Selamat Kecamatan Sunggal Kabupaten Deli Serdang dengan ini menerangkan bahwa:</p>
@include('surat.templates._bio', ['rows' => [
    'Nama Lengkap' => $w->nama,
    'Tempat/Tanggal Lahir' => $ttl,
    'Nik' => $w->nik,
    'Jenis Kelamin' => $w->jenis_kelamin,
    'Bangsa / Agama' => 'Indonesia / '.$w->agama,
    'Pekerjaan' => $w->pekerjaan,
    'Status Perkawinan' => $w->status_perkawinan,
    'Alamat' => $w->alamat.($w->rt || $w->rw ? ' RT '.($w->rt ?? '-').' / RW '.($w->rw ?? '-') : ''),
]])
<p style="margin-bottom:2mm">Memang benar nama tersebut di atas berdasarkan Surat Keterangan Jalan/Mandah dengan Nomor:{!! $v('no_mandah', '..........') !!}dikeluarkan Oleh {!! $v('penerbit', '..................') !!} Mandah Masuk ke :</p>
<table class="bio">
    @foreach(['Dusun'=>$v('dusun'), 'Desa/Kelurahan'=>'Tanjung Selamat', 'Kecamatan'=>'Sunggal', 'Kabupaten'=>'Deli Serdang', 'Provinsi'=>'Sumatera Utara', 'Alasan Mandah'=>$v('alasan'), 'Lamanya'=>$v('lamanya'), 'Banyaknya Pengikut'=>$v('jumlah_pengikut', count($d['pengikut'] ?? []) ?: '')] as $l => $val)
    <tr><td class="l">{{ $l }}</td><td class="c">:</td><td>{!! $val !!}</td></tr>
    @endforeach
</table>
@include('surat.templates._pengikut', ['minRows'=>3, 'noLabel'=>'No', 'tglLabel'=>'TGL<br>LAHIR', 'hubLabel'=>'HUB<br>KELUARGA'])
<p style="margin-bottom:3mm">Surat Keterangan Mandah ini berlaku hanya {!! $v('berlaku_bulan', '.........') !!} Bulan sejak tanggal {!! $v('berlaku_dari') ? $fmt($d['berlaku_dari']) : '……' !!} s/d {!! $v('berlaku_sampai') ? $fmt($d['berlaku_sampai']) : '……..' !!} Yang bersangkutan harap segera meminta Surat Keterangan Mandah dari alamat asal selambat-lambatnya seminggu sebelum berakhirnya Surat Keterangan Mandah ini agar dapat memperpanjang berlakunya Surat Keterangan Mandah ini dan diketahui oleh Kepala Dusun.</p>
<p style="margin-bottom:3mm">Demikian Surat Keterangan Mandah Masuk ini diperbuat dengan sebenarnya untuk dapat dipergunakan seperlunya. Apabila terdapat kekeliruan maka akan kami perbaiki sebagaimana mestinya.</p>
<div style="display:flex; justify-content:space-between; align-items:flex-start">
    <div style="width:45%; padding-top:11mm">
        Diketahui, Diperiksa<br>&nbsp;dan Diawasi Oleh :<br>
        <div style="margin-top:2mm">{!! $v('diperiksa_oleh', '................................') !!}</div>
        <div style="height:22mm"></div>
        <b>{!! $v('kadus_nama', '.................................') !!}</b>
    </div>
</div>
