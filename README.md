# SI SELAMAT
Sistem Informasi Surat Elektronik dan Layanan Administrasi Masyarakat Tanjung Selamat

Stack: **Laravel 12** · Blade · Tailwind CSS (Vite) · Alpine.js · MySQL

## Status Pengembangan (Step 1: Fondasi)

Sudah selesai:
- Struktur project Laravel 12 resmi
- 6 migration (users, warga, jenis_surat, surat, surat_validasi, activity_logs)
- 6 Model dengan relasi lengkap
- Middleware `role` (pembatasan akses Staff/Kades)
- Modul Autentikasi (login, logout, redirect dashboard sesuai role)
- Dashboard Staff & Dashboard Kades (versi awal)
- Seeder akun default + master jenis surat

Belum dikerjakan (langkah selanjutnya):
- Modul Kelola Data Warga (CRUD)
- Modul Permohonan Surat (auto-fill NIK, draft, ajukan)
- Modul Validasi Surat & E-Signature QR Code (Kades)
- Modul Arsip & Cetak Surat (PDF)
- Modul Log Aktivitas (UI + filter periode)

## Cara Menjalankan (Lokal)

### 1. Prasyarat
- PHP >= 8.2 dengan extension: mbstring, xml, mysql, curl, zip, bcmath
- Composer 2.x
- Node.js >= 18 & npm
- MySQL 8.x (atau MariaDB)

### 2. Install dependency
```bash
composer install
npm install
```

### 3. Konfigurasi environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`, sesuaikan koneksi database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=si_selamat
DB_USERNAME=root
DB_PASSWORD=
```

Buat database MySQL-nya dulu:
```sql
CREATE DATABASE si_selamat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Migrasi & Seeder
```bash
php artisan migrate --seed
```

Ini akan membuat 2 akun default untuk testing:

| Role  | Username | Password |
|-------|----------|----------|
| Staff | staff    | password |
| Kades | kades    | password |

**Wajib ganti password ini sebelum production.**

### 5. Build asset frontend
```bash
npm run dev    # mode development (watch)
# atau
npm run build  # mode production
```

### 6. Jalankan server
```bash
php artisan serve
```

Buka `http://localhost:8000` di browser.

## Struktur Database Singkat

- `users` — akun Staff & Kades (kolom `role`)
- `warga` — data kependudukan lengkap (NIK, KK, TTL, dsb)
- `jenis_surat` — master dinamis jenis surat + `template_field` (JSON) untuk form custom per jenis
- `surat` — permohonan surat (status: draft → diajukan → disetujui/ditolak)
- `surat_validasi` — riwayat validasi Kades + path QR Code (e-signature)
- `activity_logs` — audit trail semua aksi penting

## Catatan Deploy ke Shared Hosting/VPS

- Pastikan document root diarahkan ke folder `public/`
- Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env` produksi
- Jalankan `php artisan config:cache` dan `php artisan route:cache` setelah deploy
- Storage untuk QR Code & PDF surat perlu folder writable: `storage/app/public`, lalu jalankan `php artisan storage:link`
# si-selamat
