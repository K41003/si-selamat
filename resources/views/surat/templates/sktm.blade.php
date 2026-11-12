<p>Kepala Desa Tanjung Selamat Kecamatan Sunggal Kabupaten Deli Serdang dengan ini menerangkan bahwa :</p>
@include('surat.templates._bio', ['rows' => [
    'Nama Lengkap' => $w->nama,
    'Tempat/Tanggal Lahir' => $ttl,
    'NIK' => $w->nik,
    'Jenis Kelamin' => $w->jenis_kelamin,
    'Bangsa / Agama' => 'Indonesia / '.$w->agama,
    'Status' => $w->status_perkawinan,
    'Pekerjaan' => $w->pekerjaan,
    'Alamat' => $w->alamat.($w->rt || $w->rw ? ' RT '.($w->rt ?? '-').' / RW '.($w->rw ?? '-') : ''),
]])
<p>Nama tersebut di atas benar Penduduk Desa Tanjung Selamat Kecamatan Sunggal Kabupaten Deli Serdang, dan menurut sepengetahuan kami memang benar nama tersebut di atas berasal dari <b>Keluarga Kurang Mampu/Miskin</b>.</p>
<p>Surat Keterangan ini dibuat atas permintaan yang bersangkutan guna untuk melengkapi persyaratan administrasi {!! $v('keperluan', '.................') !!}</p>
<p>Demikianlah Surat Keterangan ini diperbuat dengan sebenarnya untuk dapat dipergunakan seperlunya.</p>
