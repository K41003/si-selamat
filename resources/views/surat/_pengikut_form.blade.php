{{-- Tabel pengikut (Mandah Masuk / Mandah Jalan) --}}
<div class="font-sans space-y-2">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-canvas">
                    <th class="border border-ink-900 px-2 py-1 w-10">NO</th>
                    <th class="border border-ink-900 px-2 py-1">NAMA</th>
                    <th class="border border-ink-900 px-2 py-1 w-36">JENIS KELAMIN</th>
                    <th class="border border-ink-900 px-2 py-1 w-40"><span x-text="kode === 'MDJ' ? 'TANGGAL LAHIR' : 'TGL LAHIR'"></span></th>
                    <th class="border border-ink-900 px-2 py-1 w-40"><span x-text="kode === 'MDJ' ? 'HUBUNGAN KELUARGA' : 'HUB KELUARGA'"></span></th>
                    <th class="border border-ink-900 px-1 py-1 w-10"></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(p, i) in pengikut" :key="i">
                    <tr>
                        <td class="border border-ink-900 px-2 py-1 text-center font-bold" x-text="i + 1"></td>
                        <td class="border border-ink-900 p-0"><input type="text" :name="`data_isian[pengikut][${i}][nama]`" x-model="p.nama" class="w-full border-0 px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"></td>
                        <td class="border border-ink-900 p-0">
                            <select :name="`data_isian[pengikut][${i}][jenis_kelamin]`" x-model="p.jenis_kelamin" class="w-full border-0 px-1 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <option value=""></option><option>Laki-laki</option><option>Perempuan</option>
                            </select>
                        </td>
                        <td class="border border-ink-900 p-0"><input type="date" :name="`data_isian[pengikut][${i}][tanggal_lahir]`" x-model="p.tanggal_lahir" class="w-full border-0 px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"></td>
                        <td class="border border-ink-900 p-0"><input type="text" :name="`data_isian[pengikut][${i}][hubungan]`" x-model="p.hubungan" placeholder="Istri / Anak" class="w-full border-0 px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500"></td>
                        <td class="border border-ink-900 text-center"><button type="button" @click="removePengikut(i)" class="text-danger-700 font-bold px-2" aria-label="Hapus baris">&times;</button></td>
                    </tr>
                </template>
                <tr x-show="pengikut.length === 0"><td colspan="6" class="border border-ink-900 px-2 py-2 text-center text-xs text-ink-600">Belum ada pengikut (kosongkan jika pergi sendiri)</td></tr>
            </tbody>
        </table>
    </div>
    <button type="button" @click="addPengikut()" class="btn btn-secondary btn-sm text-xs">+ Tambah pengikut</button>
</div>
