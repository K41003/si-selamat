{{-- Tabel yang ramah pembaca layar & keyboard: <caption> (sr-only), wilayah scroll yang bisa difokus,
     dan gaya sel seragam. Pemanggil tetap menulis <th scope="col">.
     Pakai:
     <x-data-table caption="Daftar permohonan surat">
         <x-slot:head><tr><th scope="col">Nomor</th>...</tr></x-slot:head>
         @foreach(...) <tr><td>...</td></tr> @endforeach
     </x-data-table> --}}
@props(['caption'])
<div class="overflow-x-auto" role="region" aria-label="{{ $caption }}" tabindex="0">
    <table class="w-full text-left text-sm [&_td]:px-4 [&_td]:py-3.5 [&_th]:px-4 [&_th]:py-3 [&_th]:font-semibold">
        <caption class="sr-only">{{ $caption }}</caption>
        <thead class="border-b border-line bg-canvas text-ink-700">{{ $head }}</thead>
        <tbody class="divide-y divide-line">{{ $slot }}</tbody>
    </table>
</div>
