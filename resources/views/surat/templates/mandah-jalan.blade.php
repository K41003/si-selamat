<p>Kepala Desa Tanjung Selamat Kecamatan Sunggal Kabupaten Deli Serdang dengan ini menerangkan&nbsp;bahwa&nbsp;:</p>
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
<p style="margin-bottom:2mm">Memang benar nama tersebut di atas&nbsp;:</p>
<table class="bio">
    @foreach(['Mandah /Jalan ke'=>$v('tujuan'), 'Desa/Kelurahan'=>$v('desa'), 'Kecamatan'=>$v('kecamatan'), 'Kota/Kabupaten'=>$v('kabupaten'), 'Provinsi'=>$v('provinsi'), 'Keperluan'=>$v('keperluan'), 'Lamanya'=>$v('lamanya'), 'Pengikut'=>$v('jumlah_pengikut', count($d['pengikut'] ?? []) ?: '')] as $l => $val)
    <tr><td class="l">{{ $l }}</td><td class="c">:</td><td>{!! $val !!}</td></tr>
    @endforeach
</table>
@include('surat.templates._pengikut', ['minRows'=>4, 'noLabel'=>'NO', 'tglLabel'=>'TANGGAL<br>LAHIR', 'hubLabel'=>'HUBUNGAN<br>KELUARGA'])
<p>Sesampainya di tempat tujuan, yang bersangkutan selambat-lambatnya 2 x 24 jam harus segera melaporkan diri kepada Aparat Pemerintahan setempat.</p>
<p>Demikianlah Surat Keterangan Mandah/Jalan ini diperbuat dengan sebenarnya untuk dapat dipergunakan seperlunya.</p>
@include('surat.templates._ttd')
