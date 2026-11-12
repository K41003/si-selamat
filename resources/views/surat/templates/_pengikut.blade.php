@php
    $rowsP = array_values($d['pengikut'] ?? []);
    $total = max($minRows, count($rowsP));
@endphp
<table class="grid">
    <tr>
        <th style="width:8mm">{{ $noLabel }}</th>
        <th>NAMA</th>
        <th style="width:26mm">JENIS<br>KELAMIN</th>
        <th style="width:26mm">{{ $tglLabel }}</th>
        <th style="width:30mm">{{ $hubLabel }}</th>
    </tr>
    @for($i = 0; $i < $total; $i++)
    @php $p = $rowsP[$i] ?? []; @endphp
    <tr>
        <td style="text-align:center"><b>{{ $i + 1 }}</b></td>
        <td>{{ $p['nama'] ?? '' }}</td>
        <td>{{ $p['jenis_kelamin'] ?? '' }}</td>
        <td>{{ !empty($p['tanggal_lahir']) ? $fmt($p['tanggal_lahir']) : '' }}</td>
        <td>{{ $p['hubungan'] ?? '' }}</td>
    </tr>
    @endfor
</table>
