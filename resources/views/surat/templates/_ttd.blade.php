<div class="ttd" @isset($ttdStyle) style="{{ $ttdStyle }}" @endisset>
    <div>Dibuat di: &nbsp;Tanjung Selamat</div>
    <div>Pada Tanggal: {{ $tglSurat }}</div>
    <div class="nm">Kepala Desa Tanjung Selamat<br>Kecamatan Sunggal</div>
    <div class="space">
        @if($surat->validasi && $surat->validasi->qr_code_token)
            <div><svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor"><path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm8-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm11 1h2v2h-2v-2zm-3-3h2v2h-2v-2zm6 0h2v2h-2v-2zm-3 3h2v2h-2v-2zm3 3h2v2h-2v-2zm-6 0h2v2h-2v-2zm3-3h2v2h-2v-2z"/></svg><small>{{ $surat->validasi->qr_code_token }}</small></div>
        @endif
    </div>
    <div class="name">{{ $namaKades }}</div>
</div>
