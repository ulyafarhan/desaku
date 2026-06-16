# Dokumentasi Sistem SIG-Udeung

Selamat datang di pusat dokumentasi resmi Sistem Informasi Gampong Udeung (SIG-Udeung). Seluruh dokumentasi telah disederhanakan dan dikelompokkan berdasarkan modul peran utama secara rapi dan profesional.

---

## Berkas Dokumentasi Utama

Untuk memudahkan pemahaman sistem secara cepat dan profesional, dokumentasi dibagi menjadi subfolder terstruktur berikut (masing-masing folder hanya berisi **satu berkas utama**):

1. **[docs/backend/backend.md](backend/backend.md)**
   * Berisi spesifikasi backend, arsitektur core API (Laravel 13 & Filament PHP v5), optimasi hibrida Exact Match + Semantic Caching AI, penanganan query N+1, log pengujian (PHPUnit), dan instruksi pengembangan backend.
2. **[docs/frontend/frontend.md](frontend/frontend.md)**
   * Berisi spesifikasi frontend (Vue 3, Inertia.js, Vite 8, Tailwind CSS v4), detail Single File Component (SFC), form dinamis, penanganan avatar, optimasi SEO & GEO menggunakan JSON-LD, dan pengujian unit (Vitest).
3. **[docs/database/database.md](database/database.md)** & **[docs/database/erd.md](database/erd.md)**
   * Berisi skema database MySQL/MariaDB (17 tabel utama), diagram kardinalitas relasi, pemetaan lengkap ERD rinci, penempatan indexing kueri, dan sistem logging audit data (audit trail).
4. **[docs/api/api.md](api/api.md)**
   * Berisi panduan lengkap integrasi 29 endpoint API, alur autentikasi token Sanctum, parameter data isian dinamis, dan integrasi webhook bot Telegram.
5. **[docs/api-contract.yaml](api-contract.yaml)**
   * Kontrak standar OpenAPI v3 untuk pengujian endpoints dan integrasi client SDK secara otomatis.

---

## Panduan Ringkas Memulai

* **Sebagai Pengembang Baru**:
  1. Mulai dengan membaca berkas [docs/backend/backend.md](backend/backend.md) bagian **Panduan Instalasi**.
  2. Jalankan perintah `composer install` dan `npm install` di root proyek.
  3. Lakukan migrasi database dengan `php artisan migrate:fresh --seed`.
* **Sebagai Pengembang Frontend**:
  1. Pahami daftar endpoint di berkas [docs/api/api.md](api/api.md).
  2. Gunakan [docs/api-contract.yaml](api-contract.yaml) untuk menguji respons menggunakan mock server.
