# Dokumentasi Sistem SIG-Udeung

Selamat datang di pusat dokumentasi resmi Sistem Informasi Gampong Udeung (SIG-Udeung). Dokumentasi disusun secara terstruktur berdasarkan modul untuk memudahkan pemahaman dan pengembangan sistem.

---

## Berkas Dokumentasi Utama

### Backend & Arsitektur

1. **[docs/backend/backend.md](backend/backend.md)**
   - Spesifikasi backend (Laravel 13, Filament v5.6.5, PHP 8.3+)
   - Struktur controller (7 API + 6 Web = 13 controller)
   - Model & relasi (17 model Eloquent)
   - Optimasi caching AI (Exact Match + Semantic Cache)
   - Multi-AI Fallback & prioritas dinamis
   - Pemantauan server & traffic logs
   - Panduan instalasi lokal

### Frontend

2. **[docs/frontend/frontend.md](frontend/frontend.md)**
   - Spesifikasi frontend (Vue 3, Inertia.js, Vite 8, Tailwind CSS v4)
   - Struktur halaman & rute (20 rute web)
   - Form isian dinamis (Dynamic Form Rendering)
   - Integrasi SweetAlert2 & Skeleton Loader
   - Optimasi SEO & GEO (JSON-LD Schemas)

### Database

3. **[docs/database/database.md](database/database.md)**
   - Skema database MySQL/MariaDB (17 tabel relasional utama + 3 tabel default Laravel)
   - Rincian seluruh kolom & tipe data
   - Indeks optimasi kueri (16 indeks)
   - Normalisasi hingga 3NF

4. **[docs/database/erd.md](database/erd.md)**
   - Diagram ERD lengkap (Mermaid)
   - Struktur detail setiap tabel
   - Aturan relasi & integritas data
   - Cascading delete & restrict rules

### API

5. **[docs/api/api.md](api/api.md)**
   - Dokumentasi lengkap 29 endpoint API
   - 8 endpoint publik, 2 terotentikasi, 8 warga, 11 admin
   - Parameter request & contoh respons
   - Integrasi & pengujian API

6. **[docs/api-contract.yaml](api-contract.yaml)**
   - Kontrak OpenAPI v3 standar YAML
   - Dapat diimpor ke Swagger Editor atau Postman
   - Sinkron dengan seluruh 29 endpoint

---

## Dokumentasi Kode Sumber

### Controllers

- **[app/Http/Controllers/README.md](../app/Http/Controllers/README.md)**
  - Struktur & pembagian controller
  - API Controllers (7 file)
  - Web Controllers (6 file)
  - Method, rute, dan tanggung jawab masing-masing

### Models

- **[app/Models/README.md](../app/Models/README.md)**
  - 17 model Eloquent
  - Relasi, scope, accessor, dan behavior
  - Primary key & casting

### Database

- **[database/README.md](../database/README.md)**
  - 28 berkas migrasi
  - 12 berkas seeder
  - Urutan eksekusi & cara menjalankan

---

## Panduan Ringkas Memulai

### Sebagai Pengembang Baru

1. Mulai dengan membaca berkas [docs/backend/backend.md](backend/backend.md) bagian **Panduan Instalasi**.
2. Jalankan perintah `composer install` dan `npm install` di root proyek.
3. Lakukan migrasi database dengan `php artisan migrate:fresh --seed`.
4. Jalankan server development: `composer run dev`

### Sebagai Pengembang Frontend

1. Pahami daftar endpoint di berkas [docs/api/api.md](api/api.md).
2. Gunakan [docs/api-contract.yaml](api-contract.yaml) untuk menguji respons menggunakan mock server.
3. Jalankan Vite dev server: `npm run dev`

### Sebagai Pengembang Backend

1. Pahami struktur model & relasi di [app/Models/README.md](../app/Models/README.md).
2. Pahami struktur controller di [app/Http/Controllers/README.md](../app/Http/Controllers/README.md).
3. Jalankan tes: `composer run test`

---

## Teknologi yang Digunakan

| Komponen | Versi |
|----------|-------|
| PHP | ^8.3 |
| Laravel Framework | ^13.8 |
| Laravel Sanctum | ^4.3 |
| Filament PHP | 5.6.5 |
| Inertia.js (Laravel) | ^3.1 |
| Vue 3 | ^3.5.38 |
| Vite | ^8.0.16 |
| Tailwind CSS | v4.3.0 |
| PHPUnit | ^12.5.12 |
| Vitest | ^4.1.8 |
| DomPDF | ^3.0 |
| Simple QRCode | ^4.2 |
| WhatsApp Gateway | OpenWA (Baileys) — self-hosted |
| WhatsApp SaaS | Fonnte — fallback otomatis |
| Telegram Bot | Telegram Bot API |
| AI Provider | Google Gemini / OpenAI-compatible |
