# Style Guide — SI SELAMAT
> Sistem Informasi Surat Elektronik & Administrasi · Desa Tanjung Selamat, Kec. Sunggal, Deli Serdang  
> Stack: **Laravel 12 + Tailwind CSS v4** (Blade) · **React + Tailwind CDN** (SPA/frontend lama)  
> Semua token warna sudah diuji kontras ≥ 4.5:1 (WCAG AA).

---

## 1. Filosofi Desain

| Prinsip | Penerapan |
|---|---|
| **Pemerintahan berwibawa** | Palet navy + putih, bukan warna cerah/playful |
| **Aksesibilitas pertama** | Outline fokus selalu terlihat, `aria-*` pada setiap interaktif, min-touch-target 44 px |
| **Informatif, bukan dekoratif** | Tidak ada ikon warna-warni, tidak ada shadow tebal, tidak ada gradient |
| **Tipografi bersih** | Satu typeface (Plus Jakarta Sans), hierarki ketat via ukuran & bobot |
| **Hemat warna** | Warna aksen hanya untuk signal (status, error, success) — bukan estetika |

---

## 2. Design Tokens (CSS Custom Properties)

Semua token didefinisikan di [`resources/css/app.css`](resources/css/app.css) dalam `@theme {}`.

### 2.1 Warna Brand

| Token | Hex | Kegunaan |
|---|---|---|
| `--color-brand-50` | `#eff4ff` | Latar hover ghost button, latar empty-state icon |
| `--color-brand-100` | `#dbe6ff` | Latar ringan info banner |
| `--color-brand-200` | `#bfcfff` | Border ringan brand |
| `--color-brand-600` | `#1d4ed8` | Tautan, nav aktif, link "Lihat semua" |
| `--color-brand-700` | `#1e3a8a` | **Tombol primer**, tab aktif underline, angka stat emphasis |
| `--color-brand-900` | `#172554` | Hover tombol primer, teks logo |
| `--color-gold-400` | `#fbbf24` | Aksen KHUSUS di atas background brand-900 saja |

> **Aturan:** Jangan gunakan `brand-700` sebagai warna teks di atas latar putih jika teks lebih kecil dari 14px — gunakan `brand-900`.

### 2.2 Permukaan & Teks (Ink)

| Token | Hex | Kegunaan |
|---|---|---|
| `--color-canvas` | `#f5f7fa` | Background halaman (body) |
| `--color-surface` | `#ffffff` | Background kartu, input, header |
| `--color-ink-900` | `#0f172a` | Teks utama (judul, label, nilai penting) |
| `--color-ink-700` | `#334155` | Teks data sekunder (tanggal, detail tabel) |
| `--color-ink-600` | `#475569` | Teks deskripsi, placeholder visible, label form |
| `--color-ink-500` | `#64748b` | Border input/kontrol |
| `--color-line` | `#e2e8f0` | Garis dekoratif (divider, border kartu) |

### 2.3 Warna Status

Digunakan **hanya** untuk badge, banner notifikasi, dan tombol aksi.

| Situasi | Background (badge/banner) | Teks | Tombol |
|---|---|---|---|
| **Draft / Peringatan** | `warning-100` `#fef3c7` | `warning-800` `#92400e` | — |
| **Menunggu / Info** | `info-100` `#dbeafe` | `info-800` `#1e40af` | — |
| **Disetujui / Sukses** | `success-100` `#d1fae5` | `success-800` `#065f46` | `success-700` `#047857` |
| **Ditolak / Bahaya** | `danger-100` `#ffe4e6` | `danger-800` `#9f1239` | `danger-700` `#be123c` |

---

## 3. Tipografi

Font: **Plus Jakarta Sans Variable** (self-hosted via `@fontsource-variable`).  
Fallback: `ui-sans-serif, system-ui, sans-serif`.  
Font cetak surat: **Times New Roman** / serif.

### Skala Ukuran

| Nama token / kelas | Ukuran | Line-height | Kegunaan |
|---|---|---|---|
| `text-xs` | 12px | — | Minimum absolut (sub-label, font-mono metadata) |
| `text-sm` | 14px | — | **Minimum teks isi** (tabel, deskripsi, label form) |
| `text-base` | 16px | — | Input field |
| `text-title-sm` | 18px / 1.125rem | 1.625rem | Judul kartu (`<h2>`) |
| `text-title` | 22px / 1.375rem | 1.875rem | Judul halaman (`<h1>`) |
| `text-display` | 30px / 1.875rem | 2.375rem | Angka statistik dashboard |

### Bobot yang Dipakai

| Bobot | Class | Kapan |
|---|---|---|
| 400 Regular | (default) | Teks isi, deskripsi |
| 500 Medium | `font-medium` | Label kolom tabel, teks data penting |
| 600 Semibold | `font-semibold` | Judul, tombol, label form, tab aktif |
| 700 Bold | `font-bold` | Logo, judul halaman (kadang) |

### Aturan Khusus

- **`font-mono`** wajib untuk: NIK, No. KK, nomor surat, token QR.  
- **`tabular-nums`** wajib untuk angka yang sejajar (tanggal, statistik).  
- **`uppercase`** pada input NIK/nama (via class langsung di field).

---

## 4. Radius & Bentuk

| Token | Nilai | Dipakai pada |
|---|---|---|
| `--radius-control` / `rounded-control` | `0.5rem` (8px) | Input, tombol, badge, dialog, icon-button |
| `--radius-card` / `rounded-card` | `0.75rem` (12px) | Kartu (`x-card`), modal dialog `<dialog>` |

> Tidak ada radius > 12px di dalam halaman utama. Radius besar hanya di halaman Login (SPA) → `rounded-2xl` / `rounded-3xl`.

---

## 5. Komponen Blade

### 5.1 Tombol (`x-button` / `.btn`)

```blade
<x-button>Simpan</x-button>                          {{-- primary (default) --}}
<x-button variant="secondary">Batal</x-button>
<x-button variant="danger">Hapus</x-button>
<x-button variant="success">Setujui</x-button>
<x-button variant="ghost">Reset</x-button>
<x-button size="sm">Kecil</x-button>
<x-button href="{{ route('surat.create') }}">Buat Surat</x-button>  {{-- render <a> --}}
```

| Variant | Background | Teks | Hover |
|---|---|---|---|
| `primary` | `brand-700` | putih | `brand-900` |
| `secondary` | `surface` + border `ink-500` | `ink-900` | `canvas` |
| `danger` | `danger-700` | putih | `danger-800` |
| `success` | `success-700` | putih | `success-800` |
| `ghost` | transparan | `ink-700` | `brand-50` |

**Aturan:**
- Tinggi minimum **44px** (`min-h-11`) ukuran `md`, **36px** (`min-h-9`) ukuran `sm`.
- Selalu gunakan `gap-2` antara icon dan label.
- Icon berukuran `size-4` (16px) di dalam tombol.

### 5.2 Icon-Button (Aksi Tabel)

Tombol aksi baris tabel (lihat, edit, hapus, cetak) menggunakan pola:

```blade
{{-- Netral --}}
class="inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-canvas hover:text-ink-900"

{{-- Destruktif (Hapus) --}}
class="inline-flex size-9 items-center justify-center rounded-control text-ink-600 hover:bg-danger-100 hover:text-danger-800"
```

Selalu sertakan `aria-label` dan `title` yang spesifik, contoh:  
`aria-label="Hapus surat 001/DESA/2026"`.

### 5.3 Kartu (`x-card`)

```blade
<x-card>...</x-card>                    {{-- dengan padding p-5 sm:p-6 --}}
<x-card :padded="false">...</x-card>    {{-- tanpa padding, diatur oleh konten --}}
```

Styling: `rounded-card border border-line bg-surface` — **tidak ada shadow**.

### 5.4 Input Field (`x-field`)

```blade
<x-field label="NIK" name="nik" required hint="16 digit" inputmode="numeric" />
<x-field label="Alamat" name="alamat" as="textarea" rows="3" />
<x-field label="Agama" name="agama" as="select">
    <option value="">Pilih agama</option>
    ...
</x-field>
```

**Anatomi field:**
1. `<label>` → `text-sm font-medium text-ink-900`
2. Tanda wajib → `(wajib)` dalam `font-normal text-danger-700`
3. `<input>` / `<select>` / `<textarea>` → class `.field`
4. Hint → `text-sm text-ink-600` di bawah input
5. Error → `text-sm font-medium text-danger-700` + border input jadi `border-danger-700`

**Class `.field`:**
```css
w-full rounded-control border border-ink-500 bg-surface px-3.5 py-2.5
text-base text-ink-900 placeholder:text-ink-600/80
disabled:bg-canvas disabled:text-ink-600
```

### 5.5 Judul Halaman (`x-page-header`)

```blade
<x-page-header
    title="Daftar Surat"
    subtitle="Kelola permohonan surat."
    :back="route('surat.index')">
    <x-slot:actions>
        <x-button href="...">Buat surat</x-button>
    </x-slot:actions>
</x-page-header>
```

Layout: `flex-col sm:flex-row` — judul kiri, aksi kanan. Tombol kembali: `btn btn-secondary` dengan icon `arrow-left size-5`.

### 5.6 Tabel Data (`x-data-table`)

```blade
<x-data-table caption="Daftar warga">
    <x-slot:head>
        <tr>
            <th scope="col">Nama</th>
            ...
            <th scope="col" class="text-right"><span class="sr-only">Aksi</span></th>
        </tr>
    </x-slot:head>
    @foreach($items as $item)
    <tr class="hover:bg-canvas/60">
        <td>...</td>
    </tr>
    @endforeach
</x-data-table>
```

- `<thead>` → `bg-canvas text-ink-600 border-b border-line`
- `<tbody>` → `divide-y divide-line`
- Sel: `px-4 py-3.5` (body), `px-4 py-3` (head)
- Hover baris: `hover:bg-canvas/60`
- Kolom tersembunyi mobile: `hidden lg:table-cell`

**Konvensi sel isi:**
```blade
{{-- Primary info --}}
<p class="font-medium text-ink-900 whitespace-nowrap">{{ $nama }}</p>
{{-- Secondary (sub-teks, kode) --}}
<p class="text-xs text-ink-600 font-mono">{{ $nik }}</p>
{{-- Tanggal --}}
<td class="whitespace-nowrap tabular-nums text-ink-700">{{ $tgl }}</td>
```

### 5.7 Badge Status (`x-status-badge`)

```blade
<x-status-badge :status="$surat->status" />
```

| Status | Label | Style |
|---|---|---|
| `draft` | Draf | `bg-warning-100 text-warning-800` |
| `diajukan` | Menunggu validasi | `bg-info-100 text-info-800` |
| `disetujui` | Disetujui | `bg-success-100 text-success-800` |
| `ditolak` | Ditolak | `bg-danger-100 text-danger-800` |

Format: `rounded-full px-2.5 py-1 text-xs font-semibold` + dot `size-1.5 rounded-full bg-current`.

### 5.8 Statistik Strip (`x-stat`)

```blade
<x-card :padded="false" class="overflow-hidden">
    <div class="grid grid-cols-2 gap-px bg-line lg:grid-cols-4">
        <x-stat label="Total penduduk" :value="1234" hint="Warga terdaftar" />
        <x-stat label="Draf surat" :value="5" emphasis />  {{-- angka jadi brand-700 --}}
    </div>
</x-card>
```

- Angka biasa: `text-display font-semibold text-ink-900`  
- Angka emphasis (perlu tindak lanjut): `text-display font-semibold text-brand-700`

### 5.9 Keadaan Kosong (`x-empty-state`)

```blade
<x-empty-state
    icon="search"
    title="Data tidak ditemukan"
    description="Coba kata kunci lain.">
    <x-button variant="secondary" href="...">Reset pencarian</x-button>
</x-empty-state>
```

Layout: terpusat, icon dalam lingkaran `bg-brand-50 text-brand-700`, judul `font-semibold`, deskripsi `text-sm text-ink-600 max-w-sm`.

### 5.10 Dialog Konfirmasi (`x-confirm-dialog`)

```blade
<x-confirm-dialog
    id="hapus-{{ $item->id }}"
    :action="route('surat.destroy', $item)"
    method="DELETE"
    title="Hapus surat ini?"
    confirm-label="Hapus surat"
    variant="danger">
    <p>Surat <span class="font-mono">{{ $item->nomor }}</span> akan dihapus.</p>
</x-confirm-dialog>

{{-- Buka dengan: --}}
<button onclick="openDialog('hapus-{{ $item->id }}')">Hapus</button>
```

Gunakan `<dialog>` native — bukan div modal. Lebar: `min(28rem, 92vw)`.  
Tombol selalu: **Batal** (secondary) di kiri, **Aksi** (variant) di kanan.

---

## 6. Layout & Spasi

### 6.1 Struktur Halaman

```
<body class="h-full flex flex-col bg-canvas">
  <header class="sticky top-0 z-40 border-b border-line bg-surface">
    <!-- Logo | Nav tabs | User info + Logout -->
  </header>
  <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
    <div class="space-y-6">
      <x-page-header ... />
      <!-- Konten utama -->
    </div>
  </main>
  <footer class="border-t border-line py-5 text-center text-xs text-ink-600">
    ...
  </footer>
</body>
```

### 6.2 Grid Sistem

| Pola | Class | Kapan |
|---|---|---|
| 2 kolom (detail) | `grid grid-cols-1 md:grid-cols-2 gap-6` | Detail surat: warga + data isian |
| 1 + sidebar | `grid grid-cols-1 lg:grid-cols-[1fr_20rem] gap-6` | Dashboard: tabel + aktivitas |
| Statistik strip | `grid grid-cols-2 lg:grid-cols-4 gap-px bg-line` | Strip stat dalam satu kartu |
| Form fieldset | `grid grid-cols-1 md:grid-cols-2 gap-5` | Kolom form berpasangan |

### 6.3 Spasi Vertikal

| Konteks | Class |
|---|---|
| Antar section utama | `space-y-6` |
| Antar field dalam form | `space-y-5` |
| Antar item dalam card | `space-y-4` |
| Padding card header | `px-5 py-4 sm:px-6` |

---

## 7. Navigasi

### Header Utama (Sticky)

- Tinggi: `h-16`
- Logo: gambar `size-9` + teks "SI SELAMAT" (bold) + subtext kecamatan
- Nav tabs: underline style, aktif = `border-brand-700 font-semibold text-brand-700`
- Tab badge (pending): `rounded-full bg-warning-100 text-warning-800 text-xs`

### Tab Filter (dalam halaman)

Sama persis dengan nav header — underline style, bukan pill/chip.  
Hitungan jumlah ditampilkan sebagai `font-normal tabular-nums text-ink-600`.

---

## 8. Notifikasi & Feedback

### Banner Flash (session)

```blade
{{-- Sukses --}}
<div class="mb-6 flex items-start gap-3 rounded-control border border-success-700/30 bg-success-100/60 p-4 text-sm text-success-800">
    <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0"/>
    {{ session('success') }}
</div>

{{-- Error --}}
<div class="mb-6 flex items-start gap-3 rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800">
    <x-icon name="exclamation" class="mt-0.5 size-5 shrink-0"/>
    {{ session('error') }}
</div>
```

### Catatan Tolak Kades

```blade
<div role="note" class="rounded-control border border-danger-700/30 bg-danger-100/60 p-4 text-sm text-danger-800">
    <p class="font-semibold">Catatan dari Kepala Desa</p>
    <p class="mt-1">{{ $catatan }}</p>
</div>
```

---

## 9. Aksesibilitas

| Kebutuhan | Implementasi |
|---|---|
| Skip link | `<a href="#konten-utama" class="sr-only focus:not-sr-only ...">` |
| Fokus keyboard | `outline: 3px solid brand-600; outline-offset: 2px` (otomatis via `:focus-visible`) |
| Latar gelap | `.on-dark :focus-visible { outline-color: white }` |
| Kolom aksi tabel | `<th><span class="sr-only">Aksi</span></th>` |
| Label tersembunyi | `<label class="sr-only">Cari warga</label>` |
| Dialog | `<dialog aria-labelledby="...">` + klik latar menutup |
| Tabel | `<caption class="sr-only">`, `tabindex="0"` pada wrapper scroll |
| Icon-button | `aria-label` spesifik per item |
| Reduced motion | Semua animasi dimatikan via `prefers-reduced-motion` |
| NIK sensitif | Ditampilkan tersamar (masked) di tabel; NIK lengkap tidak dikirim ke browser kecuali via `x-nik-reveal` |

---

## 10. Pola Khusus

### Form Bertingkat (Fieldset)

```blade
<fieldset class="space-y-5">
    <legend class="mb-4 text-base font-semibold text-ink-900">Identitas</legend>
    ...
</fieldset>
<fieldset class="space-y-5 border-t border-line pt-6">
    <legend class="mb-4 text-base font-semibold text-ink-900">Data Pribadi</legend>
    ...
</fieldset>
```

Pemisah antar fieldset: `border-t border-line pt-6` (bukan garis visual dekoratif).

### Detail Item (`<dl>`)

```blade
<dl class="space-y-4">
    <div>
        <dt class="text-sm text-ink-600">Nama lengkap</dt>
        <dd class="mt-0.5 font-medium text-ink-900">{{ $nama }}</dd>
    </div>
</dl>
```

### Tautan "Lihat semua"

```blade
<a href="..." class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-900">
    Lihat semua <x-icon name="arrow-right" class="size-4"/>
</a>
```

### Log Aktivitas (Timeline sederhana)

```blade
<ul class="divide-y divide-line border-t border-line">
    <li class="px-5 py-3.5">
        <p class="text-sm font-medium text-ink-900">{{ $aksi }}</p>
        <p class="mt-0.5 line-clamp-2 text-sm text-ink-600">{{ $deskripsi }}</p>
        <p class="mt-1 text-xs text-ink-600">{{ $nama }} &bull; {{ $waktu->diffForHumans() }}</p>
    </li>
</ul>
```

---

## 11. Halaman Login (SPA / React)

Halaman login menggunakan komponen React (`LoginView.tsx`) dengan Tailwind CDN — **bukan** sistem token Blade.

| Aspek | Nilai |
|---|---|
| Background | `bg-[#f8fafc]` |
| Container | `max-w-md w-full` |
| Logo box | `h-14 w-14 rounded-2xl bg-[#1E3A8A] text-white` teks "TS" |
| Kartu | `rounded-2xl sm:rounded-3xl bg-white p-6 sm:p-8 border border-slate-200 shadow-2xs` |
| Tombol utama | `bg-[#1E3A8A] hover:bg-[#172554] rounded-xl py-2.5 text-sm font-semibold text-white` |
| Input | `rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#1E3A8A]/20` |
| Error | `bg-rose-50 border border-rose-200 text-rose-800 rounded-lg` |

> Desain Login SPA lebih "modern" dibanding halaman utama Blade. Saat membuat halaman baru di Blade, ikuti token sistem di **Bagian 2**, bukan nilai hardcode dari Login SPA.

---

## 12. Print / Cetak Surat

- Semua elemen UI menggunakan class `no-print` → `display: none !important` saat print.
- Font surat cetak: **serif** (Times New Roman) untuk tampilan dokumen resmi.
- Template surat berada di `resources/views/surat/templates/`.

---

## 13. Checklist Komponen Baru

Sebelum membuat view atau komponen baru, pastikan:

- [ ] Gunakan token `--color-*` atau class Tailwind dari sistem token (bukan nilai hex hardcode)
- [ ] Semua input dibungkus `x-field` (bukan `<input>` polos)
- [ ] Tombol menggunakan `x-button` atau class `.btn .btn-{variant}`
- [ ] Area aksi kanan tabel menggunakan pola icon-button `size-9`
- [ ] Semua tabel menggunakan `x-data-table` dengan `caption`
- [ ] Konfirmasi destruktif menggunakan `x-confirm-dialog` (bukan `window.confirm`)
- [ ] Keadaan kosong menggunakan `x-empty-state`
- [ ] Setiap icon-button punya `aria-label` spesifik
- [ ] Angka NIK/No. KK menggunakan `font-mono`
- [ ] Angka statistik/tanggal menggunakan `tabular-nums`
- [ ] Radius menggunakan `rounded-control` (input/tombol) atau `rounded-card` (kartu/dialog)

