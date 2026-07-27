# Portal Resmi & Sistem Manajemen Pondok Pesantren Ulul Albab Makassar

Aplikasi portal website interaktif dan sistem manajemen pesantren terpadu (PPDB Online, Berita, Galeri, dan Direktori Guru) menggunakan **Laravel 11 (Backend REST API)** dan **Svelte 5 + Tailwind CSS v4 + Vite (Frontend SPA)**.

---

## 🏗️ Arsitektur Sistem
- **Frontend**: Svelte 5 (Single Page Application, Runes Reactive System `$state`, `$derived`, `$props`)
- **Backend**: Laravel 11 (REST API, Sanctum Token Authentication, Stateless API)
- **Styling & UI**: Tailwind CSS v4, Lucide Icons, Glassmorphism UI, Lightbox Preview Modal, AOS Animations
- **Database**: MySQL / MariaDB

---

## 📂 Struktur Direktori Utama
- `/app/` - Source code Backend Laravel (Controllers, Models, Requests, Resources)
- `/routes/api.php` - Definisi Endpoint REST API Public & Admin Protected
- `/resources/js/` - Source code Frontend Svelte 5 SPA (Pages, Components, Services, Admin Panel)
- `/resources/css/` - Styling utama Tailwind CSS & animasi kustom
- `/public/build/` - Hasil kompilasi statis dari Vite untuk production siap deploy

---

## 🚀 1. Persiapan & Instalasi Sistem

### A. Prasyarat Sistem
Pastikan di komputer/server Anda telah terinstall:
- PHP >= 8.2 (Disarankan menggunakan Laragon / XAMPP)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL / MariaDB

### B. Instalasi & Konfigurasi Backend
1. Buka terminal di dalam folder project root:
   ```bash
   cd d:/laragon/www/ulul-albab
   ```
2. Jalankan perintah instalasi dependensi PHP:
   ```bash
   composer install
   ```
3. Salin file `.env.example` menjadi `.env` (jika belum ada):
   ```bash
   cp .env.example .env
   ```
4. Generate *Application Key* Laravel:
   ```bash
   php artisan key:generate
   ```
5. Buka file `.env` dan sesuaikan koneksi database MySQL Anda:
   ```env
   APP_NAME="Pondok Pesantren Ulul Albab Makassar"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ulul_albab
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### C. Migrasi Database & Seeder
Buat database MySQL baru dengan nama `ulul_albab` (melalui phpMyAdmin atau terminal). Kemudian jalankan migrasi beserta data awal (seeder):
```bash
php artisan migrate:fresh --seed
```
*Catatan: Seeder akan otomatis mengisi data awal seperti Profil, Visi Misi, Sejarah, Program SMA/MA, Data Guru, Galeri, Berita, serta Akun Administrator.*

### D. Menghubungkan Penyimpanan Foto/Gambar (Storage Link)
Agar gambar yang diupload admin (foto guru, berita, galeri, program) dapat dibaca oleh publik, hubungkan storage dengan perintah:
```bash
php artisan storage:link
```

---

## 💻 2. Menjalankan Aplikasi di Komputer Lokal

Karena sistem Frontend dan Backend menyatu dalam satu repositori terintegrasi, Anda dapat menjalankan server backend dan frontend secara bersamaan.

### Langkah 1: Jalankan Server Backend API (Terminal 1)
```bash
php artisan serve
```
Backend API Laravel akan aktif di: `http://127.0.0.1:8000`

### Langkah 2: Jalankan Frontend Svelte / Vite (Terminal 2)
Buka terminal baru di folder yang sama, lalu install dependensi Node.js dan jalankan server pengembangan:
```bash
npm install
npm run dev
```
Buka URL lokal yang muncul (biasanya `http://localhost:5173` atau domain Laragon Anda `http://ulul-albab.test`) di browser.

### Langkah 3: Build untuk Production (Saat Deploy Hosting)
Saat akan diupload ke hosting / server production, compile kode Svelte menjadi aset statis:
```bash
npm run build
```

---

## 🔐 3. Informasi Akun Administrator (Login)

Gunakan kredensial berikut untuk masuk ke **Admin Panel** (`#/login` atau `#/admin`) dan mengelola seluruh konten pesantren:

- **Email:** `admin@gmail.com` *(atau email yang diatur di UserSeeder)*
- **Password:** `admin123`

---

## 🌐 4. Daftar Endpoint API (REST API)

### A. Public Endpoints (Tanpa Login)
- `GET /api/v1/home` - Mengambil ikhtisar data beranda (hero slider, program, prestasi, berita terbaru, statistik)
- `GET /api/v1/profil` - Informasi profil pesantren
- `GET /api/v1/sejarah` - Sejarah pendirian pesantren
- `GET /api/v1/visimisi` - Visi dan misi lembaga
- `GET /api/v1/program` - Daftar program unggulan SMA/MA beserta foto banner
- `GET /api/v1/guru` - Direktori tenaga pendidik, ustadz/ustadzah, dan staf
- `GET /api/v1/prestasi` - Daftar prestasi akademik & non-akademik santri
- `GET /api/v1/galeri` - Album foto kegiatan pesantren
- `GET /api/v1/berita` - Daftar artikel berita dan pengumuman terbaru
- `GET /api/v1/kontak` - Informasi kontak, nomor WhatsApp, dan lokasi
- `POST /api/v1/ppdb/register` - Form pendaftaran santri baru (PPDB Online) menghasilkan Nomor Registrasi Unik
- `GET /api/v1/ppdb/check` - Cek status kelulusan/verifikasi PPDB calon santri

### B. Authentication Endpoints
- `POST /api/v1/auth/login` - Autentikasi Admin (Mengembalikan Sanctum Bearer Token & Cookie)
- `POST /api/v1/auth/logout` - Keluar dari sesi Admin (Membutuhkan Bearer Token)
- `GET /api/v1/auth/me` - Profil admin yang sedang login

### C. Admin Protected Endpoints (Wajib Bearer Token / Login)
- `GET /api/v1/admin/dashboard` - Statistik pesanan/pendaftar PPDB, jumlah guru, dan berita
- `GET|POST|PUT|DELETE /api/v1/admin/berita` - CRUD Berita & upload thumbnail gambar
- `GET|POST|PUT|DELETE /api/v1/admin/galeri` - CRUD Galeri fotokegiatan pesantren
- `GET|POST|PUT|DELETE /api/v1/admin/guru` - CRUD Data guru, jabatan, dan foto profil
- `GET|POST|PUT|DELETE /api/v1/admin/program` - CRUD Program pendidikan & upload foto banner program
- `GET|POST|PUT|DELETE /api/v1/admin/prestasi` - CRUD Prestasi santri
- `GET|PUT|DELETE /api/v1/admin/ppdb` - Manajemen pendaftar PPDB (Verifikasi status, filter jenjang & export)
- `GET|POST|PUT|DELETE /api/v1/admin/users` - Manajemen akun administrator sistem
- `GET|PUT /api/v1/admin/settings` - Pengaturan global website, logo, dan nomor kontak

---

## ✨ Fitur Unggulan UI/UX
1. **Desain Super Premium & Modern**: Warna khas hijau pesantren yang elegan berpadu dengan efek *Glassmorphism*, *Smooth Floating Card*, dan animasi AOS.
2. **Auto-Running Slider Tanpa Henti**: Carousel otomatis pada halaman Guru, Galeri, dan Berita yang meluncur mulus tanpa perlu diklik manual.
3. **Lightbox Preview Modal**: Klik foto manapun (di halaman Guru, Galeri, Berita, maupun Program) untuk melihat foto ukuran penuh dengan resolusi tinggi.
4. **PPDB Online Terintegrasi**: Calon santri mendapatkan nomor registrasi unik dan notifikasi otomatis.
5. **Clean Desktop View**: Batang scrollbar (*browser scrollbar*) disembunyikan secara global untuk tampilan layar penuh yang bersih tanpa mengurangi fungsi scroll.

---
*Dikembangkan untuk Pondok Pesantren Ulul Albab Makassar — Mencetak Generasi Qurani, Berilmu, dan Berakhlak Mulia.*
