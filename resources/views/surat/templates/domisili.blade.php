<p>Kepala Desa Tanjung Selamat Kecamatan Sunggal Kabupaten Deli Serdang dengan ini menerangkan&nbsp;bahwa&nbsp;:</p>
@include('surat.templates._bio', ['rows' => [
    'Nama Lengkap' => $w->nama,
    'Tempat/Tanggal Lahir' => $ttl,
    'NIK' => $w->nik,
    'Jenis Kelamin' => $w->jenis_kelamin,
    'Bangsa / Agama' => 'Indonesia / '.$w->agama,
    'Status Perkawinan' => $w->status_perkawinan,
    'Pekerjaan' => $w->pekerjaan,
    'Alamat' => $w->alamat.($w->rt || $w->rw ? ' RT '.($w->rt ?? '-').' / RW '.($w->rw ?? '-') : ''),
]])
<p>Nama tersebut di atas menurut sepengetahuan kami pada saat ini memang benar <b>Berdomisili/Bertempat {!! $v('bertempat', '.............') !!}</b> di Dusun {!! $v('dusun', 'IV') !!} Desa Tanjung Selamat Kec. Sunggal Kab. Deli Serdang.</p>
<p>Surat Keterangan ini diberikan atas permintaan yang bersangkutan adalah bermaksud untuk kelengkapan administrasi.</p>
<p>Demikianlah Surat Keterangan ini diperbuat dengan sebenarnya untuk dapat dipergunakan seperlunya dan apabila dikemudian hari terdapat kekeliruan maka kami akan memperbaiki sebagaimana mestinya.</p>
@include('surat.templates._ttd')
