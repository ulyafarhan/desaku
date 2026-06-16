<div align="center">

<svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;">
  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
  <polyline points="9 22 9 12 15 12 15 22"/>
</svg>

# SIG-Udeung

### Sistem Informasi Gampong Udeung Terpadu

**Solusi Digital Administrasi Desa Berbasis Cloud dengan Integrasi AI dan Arsitektur Modern**

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.3.0-38B2AC?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![Filament PHP](https://img.shields.io/badge/Filament_PHP-5.6.5-F59E0B?style=flat-square&logo=laravel)](https://filamentphp.com)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-v3.1-9553E9?style=flat-square&logo=inertia)](https://inertiajs.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql)](https://mysql.com)

[Pendahuluan](#1-pendahuluan) • [Spesifikasi Teknologi](#2-spesifikasi-teknologi) • [Arsitektur](#3-arsitektur-dan-struktur-sistem) • [Skema Database](#4-skema-database) • [Instalasi](#5-panduan-instalasi-dan-konfigurasi) • [Spesifikasi API](#6-spesifikasi-teknis-api) • [Keamanan](#7-keamanan-sistem) • [Pengujian](#8-pengujian-sistem-testing) • [Peta Jalan](#9-peta-jalan-dan-status-proyek)

</div>

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> 1. Pendahuluan

**SIG-Udeung** merupakan platform sistem informasi desa terpadu yang dirancang khusus untuk memodernisasi tata kelola administrasi pada tingkat Gampong (Desa). Melalui pendekatan desentralisasi data, beban kerja administratif yang sebelumnya berpusat pada aparatur desa kini dialihkan secara aman ke partisipasi aktif masyarakat melalui portal layanan mandiri (*self-service*).

#### Visi dan Misi
* **Visi**: Mewujudkan ekosistem administrasi desa yang transparan, akuntabel, dan efisien berbasis teknologi cloud guna mengoptimalkan kualitas pelayanan publik.
* **Misi**:
  * Digitalisasi secara menyeluruh terhadap administrasi kependudukan dan pencatatan sipil.
  * Penyediaan sistem pengajuan surat mandiri untuk memangkas birokrasi fisik.
  * Implementasi teknologi kecerdasan buatan (AI) untuk asisten virtual pelayanan 24 jam.
  * Penjaminan keamanan data sensitif warga melalui sistem audit trail dan kriptografi SHA-256.

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg> 2. Spesifikasi Teknologi

Sistem ini dibangun menggunakan arsitektur monolitik modern berbasis komponen (*Single Page Application*) yang menggabungkan keunggulan kompilasi backend dan reaktivitas frontend dalam satu repositori terpadu.

#### Stack Backend (Sisi Server)
* **Runtime**: PHP 8.3 dengan optimasi struktur *strictly typed*.
* **Framework**: Laravel 13.x sebagai mesin API utama, manajemen antrean (*queueing*), dan penanganan sesi.
* **Administration Engine**: Filament PHP v5.6.5 sebagai panel manajemen administratif untuk Keuchik, Sekretaris Desa (Sekdes), dan Operator.
* **Database**: MySQL 8.0+ / MariaDB (Production) dan SQLite (Local Fallback/Development).
* **Caching & Queue**: Redis untuk penanganan asinkronus broadcast Telegram dan kompilasi data performa tinggi.
* **Service Tambahan**:
  * Barryvdh DomPDF (`^3.0`) untuk generator dokumen administrasi format PDF.
  * Simple QR Code (`^4.2`) untuk tanda tangan elektronik (TTE) berbasis verifikasi hash SHA-256.

#### Stack Frontend (Sisi Klien)
* **Konektor**: Inertia Laravel (`^3.1`) dengan `@inertiajs/vue3` (`^3.4.0`) untuk interaksi SPA tanpa overhead pengembangan API REST eksternal untuk modul internal.
* **Framework UI**: Vue 3 (`^3.5.38`) dengan struktur Composition API.
* **Pre-processor & Styling**: Tailwind CSS v4.3.0 yang terkompilasi langsung melalui Vite compiler (`@tailwindcss/vite`) untuk performa rendering CSS yang lebih cepat.
* **Build Tool**: Vite 8 (`^8.0.16`) dengan plugin khusus Laravel Vite.
* **Desain Ikon**: `@lucide/vue` (`^1.17.0`).

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><polygon points="12 2 2 7 12 12 22 7 12 2</polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg> 3. Arsitektur dan Struktur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                 Frontend Client Layer                       │
├─────────────────────────────────────────────────────────────┤
│  • Portal Publik & PWA Warga (Inertia.js + Vue 3)           │
│  • Styling & UI (Tailwind CSS v4 + Lucide Icons)            │
│  • Build Engine (Vite 8)                                    │
└─────────────────────────────────────────────────────────────┘
                            │ (Protokol Inertia)
                            ▼
┌─────────────────────────────────────────────────────────────┐
│             Backend & Admin Platform (Laravel 13)            │
├─────────────────────────────────────────────────────────────┤
│  • Dashboard Admin & Operator (Filament PHP v5)              │
│  • RESTful API Engine (Sanctum Auth, 29 Endpoints)          │
│  • Document Processor (DomPDF & Simple QR Code)             │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                 Data & External Service Layer               │
├─────────────────────────────────────────────────────────────┤
│  • Database Utama: MySQL 8.0+ / MariaDB / SQLite            │
│  • Cache & Queue Manager: Redis                             │
│  • Integrasi AI: Google Gemini & OpenAI/Compatible MaaS (Alibaba Cloud Model Studio, DeepSeek, dll) │
│  • Notifikasi & Bot Webhook: Telegram API                   │
└─────────────────────────────────────────────────────────────┘
```

#### Struktur Direktori Proyek
```
desaku/
├── app/
│   ├── Console/Commands/          # Perintah CLI Artisan kustom
│   ├── Http/Controllers/Api/      # Controller API RESTful
│   ├── Jobs/                      # Job antrean (asinkronus)
│   ├── Models/                    # Definisi model Eloquent ORM
│   └── Services/                  # Logika bisnis terisolasi (Service Layer)
├── database/
│   ├── migrations/                # Skema migrasi database DDL
│   └── seeders/                   # Data awal (seeding) sistem
├── routes/
│   └── api.php                    # Definisi routing API REST
├── tests/
│   ├── Unit/                      # Unit testing terisolasi
│   └── Feature/                   # Pengujian fitur/endpoint end-to-end
├── docs/                          # Berkas dokumentasi teknis sistem
└── storage/
    └── app/private/scribe/        # Berkas dokumentasi API tergenerasi
```

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M3 5v14c0 2.2 4 4 9 4s9-1.8 9-4V5"></path><path d="M3 12c0 2.2 4 4 9 4s9-1.8 9-4"></path></svg> 4. Skema Database

Sistem ini didukung oleh 17 tabel relasional utama yang saling terintegrasi dengan struktur integritas data yang ketat:

| Nama Tabel | Deskripsi Fungsi | Relasi Kunci |
| :--- | :--- | :--- |
| `users` | Autentikasi default Laravel, penyimpanan token Sanctum. | Berelasi dengan tabel spesifik peran. |
| `administrators` | Menyimpan kredensial admin (Keuchik, Sekdes, Operator). | FK ke `users.id`. |
| `penduduk` | Entitas data dasar kependudukan. NIK 16 digit sebagai Primary Key. | Relasi utama untuk data keluarga dan surat. |
| `keluarga` | Manajemen Kartu Keluarga (KK). | FK ke `penduduk.nik` sebagai kepala keluarga. |
| `mutasi_penduduk` | Pencatatan riwayat kelahiran, kematian, kepindahan, kedatangan. | FK ke `penduduk.nik`. |
| `kategori_surat` | Master data template dan jenis surat administrasi desa. | Relasi satu-ke-banyak dengan pengajuan. |
| `pengajuan_surat` | Pencatatan pengajuan dokumen dari warga. | FK ke `penduduk.nik` dan `kategori_surat.id`. |
| `tracking_pengajuan_surat` | Riwayat logis persetujuan dan perpindahan status pengajuan. | FK ke `pengajuan_surat.id`. |
| `informasi_publik` | Berita, siaran pers, dan pengumuman resmi desa. | FK ke `administrators.id` sebagai pembuat. |
| `bot_knowledges` | Basis pengetahuan kustom untuk dynamic FAQ dan RAG Context AI Chatbot. | Data mandiri yang dapat diatur secara dinamis oleh admin. |
| `telegram_broadcast_queue` | Antrean asinkronus pengiriman notifikasi publik. | Terhubung dengan sistem Queue Laravel. |
| `chatbot_logs` | Catatan log interaksi warga dengan bot Telegram berbasis AI. | FK ke `users.id` (jika terikat). |
| `audit_logs` | Jejak audit (*audit trail*) mencakup seluruh aksi mutasi database. | FK ke `users.id`. |
| `pengaturan_gampong` | Pengaturan konfigurasi dinamis (nama desa, logo, dsb). | Data tunggal (Key-Value Pair). |
| `referensi_wilayah` | Data wilayah administratif (Dusun, RT, RW). | Terhubung dengan data kependudukan. |
| `pengaturan_frontend` | Menyimpan pengaturan identitas dan media sosial aparat gampong untuk frontend publik. | Data tunggal (Key-Value Pair). |
| `traffic_logs` | Pencatatan statistik kunjungan/lalu lintas publik secara riil. | Data log mandiri. |

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg> 5. Panduan Instalasi dan Konfigurasi

Sistem menyediakan skrip otomasi komprehensif di dalam `composer.json` untuk mempercepat konfigurasi awal lingkungan pengembangan.

#### Persyaratan Sistem
* PHP versi 8.3 atau lebih tinggi.
* Node.js versi 18 atau lebih tinggi.
* MySQL versi 8.0 atau MariaDB versi 10.4 atau lebih tinggi.
* Redis Server.
* Composer Package Manager.

#### Langkah-langkah Instalasi

1. **Unduh Repositori**
   ```bash
   git clone [url-repositori-anda]
   cd desaku
   ```

2. **Eksekusi Setup Otomatis**
   Gunakan perintah Composer kustom berikut yang akan menginstal dependensi PHP, membuat berkas `.env`, menghasilkan kunci aplikasi, menjalankan migrasi database, menginstal paket NPM, dan melakukan build aset frontend dalam satu langkah terintegrasi:
   ```bash
   composer run setup
   ```

3. **Konfigurasi Berkas Lingkungan (.env)**
   Sesuaikan kredensial database dan integrasi pihak ketiga pada berkas `.env` yang baru terbuat:
   ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=desaku
    DB_USERNAME=root
    DB_PASSWORD=

   TELEGRAM_BOT_TOKEN=your_telegram_bot_token_here

   # Konfigurasi Integrasi AI (gemini atau openai)
   AI_PROVIDER=openai

   # Jika menggunakan Google Gemini
   GEMINI_API_KEY=your_gemini_api_key_here
   GEMINI_MODEL=gemini-pro

   # Jika menggunakan OpenAI / Compatible MaaS (Alibaba Cloud Model Studio, DeepSeek, dll)
   OPENAI_API_KEY=your_openai_api_key_here
   OPENAI_MODEL=deepseek-v4-flash
   OPENAI_BASE_URL=https://api.openai.com/v1
   ```

4. **Menjalankan Server Pengembangan**
   Gunakan perintah pengembangan terintegrasi untuk menjalankan server PHP, worker antrean, dan server Vite secara simultan:
   ```bash
   composer run dev
   ```
   Sistem akan dapat diakses secara lokal melalui alamat `http://localhost:8000`.

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><globe></globe><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> 6. Spesifikasi Teknis API

Seluruh endpoint API berjalan di bawah basis URL: `http://localhost/api` (atau sesuai konfigurasi host lokal Anda).

#### Komponen Skema Kesalahan Global (Shared Components)

##### `ValidationException` (HTTP 422)
Terjadi jika payload yang dikirimkan tidak memenuhi kriteria validasi sistem.
```json
{
  "message": "Errors overview.",
  "errors": {
    "field_name": [
      "Pesan detail kegagalan validasi field."
    ]
  }
}
```

##### `AuthenticationException` (HTTP 401)
Terjadi ketika permintaan akses tidak menyertakan token otentikasi yang valid.
```json
{
  "message": "Error overview."
}
```

##### `ModelNotFoundException` (HTTP 404)
Terjadi jika data entitas yang diminta berdasarkan parameter ID/slug tidak ditemukan pada database.
```json
{
  "message": "Error overview."
}
```

---

#### 6.1. Modul Autentikasi (Authentication)

##### `POST /v1/auth/login/warga`
* **Deskripsi**: Login menggunakan NIK dan KK untuk warga gampong.
* **Operation ID**: `auth.loginWarga`
* **Payload Request (JSON)**:
  | Nama Field | Tipe | Karakteristik | Deskripsi |
  | :--- | :--- | :--- | :--- |
  | `nik` | string | Wajib, Panjang: 16 | Nomor Induk Kependudukan warga |
  | `no_kk` | string | Wajib, Panjang: 16 | Nomor Kartu Keluarga |

* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `422 Unprocessable Entity` (`ValidationException`)

##### `POST /v1/auth/login/admin`
* **Deskripsi**: Login menggunakan username dan password untuk administrator gampong (Keuchik, Sekdes, Operator).
* **Operation ID**: `auth.loginAdmin`
* **Payload Request (JSON)**:
  | Nama Field | Tipe | Karakteristik | Deskripsi |
  | :--- | :--- | :--- | :--- |
  | `username` | string | Wajib | Kredensial username akun admin |
  | `password` | string | Wajib | Kata sandi akun admin |

* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `422 Unprocessable Entity` (`ValidationException`)

##### `POST /v1/auth/logout`
* **Deskripsi**: Mengakhiri sesi aktif dan menghapus token akses saat ini.
* **Operation ID**: `auth.logout`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `GET /v1/auth/profile`
* **Deskripsi**: Mengambil data profil detail milik user yang saat ini sedang login (warga maupun administrator).
* **Operation ID**: `auth.profile`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `POST /v1/auth/bind-telegram`
* **Deskripsi**: Menghubungkan akun warga dengan ID Chat Telegram untuk menerima pemberitahuan otomatis secara langsung.
* **Operation ID**: `auth.bindTelegram`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Payload Request (JSON)**:
  | Nama Field | Tipe | Karakteristik | Deskripsi |
  | :--- | :--- | :--- | :--- |
  | `telegram_chat_id` | string | Wajib | ID unik chat/user bot Telegram |

* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `422 Unprocessable Entity` (`ValidationException`)
  * `401 Unauthorized` (`AuthenticationException`)

---

#### 6.2. Modul Informasi Publik (InformasiPublik)

##### `GET /v1/informasi`
* **Deskripsi**: Mengambil daftar seluruh artikel informasi atau pengumuman gampong yang telah dipublikasikan secara terbuka.
* **Operation ID**: `informasiPublik.index`
* **Otentikasi**: Terbuka untuk umum (Public)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```

##### `GET /v1/informasi/{slug}`
* **Deskripsi**: Mengambil konten detail informasi publik berdasarkan parameter slug artikel.
* **Operation ID**: `informasiPublik.show`
* **Otentikasi**: Terbuka untuk umum (Public)
* **Parameter Path**:
  * `slug` (string, Wajib): Pengenal artikel (Contoh: `pemilihan-keuchik-2026`)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```

##### `GET /v1/admin/informasi`
* **Deskripsi**: Mengambil seluruh daftar informasi, termasuk draf yang belum terbit (Khusus Admin).
* **Operation ID**: `informasiPublik.adminIndex`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Parameter Query**:
  * `is_published` (boolean, Opsional, Default: `false`): Memfilter data berdasarkan status publikasi.
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `POST /v1/admin/informasi`
* **Deskripsi**: Membuat draf atau langsung memublikasikan informasi resmi baru (Khusus Admin).
* **Operation ID**: `informasiPublik.store`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Payload Request (JSON)**:
  | Nama Field | Tipe | Karakteristik | Deskripsi |
  | :--- | :--- | :--- | :--- |
  | `judul` | string | Wajib, Max: 255 | Judul berita/informasi |
  | `konten` | string | Wajib | Isi konten detail informasi |
  | `kategori` | string | Wajib, Max: 50 | Pengelompokan pengumuman |
  | `cover_image` | string / null | Opsional, dapat bernilai null | URL atau path citra sampul berita |
  | `is_published` | boolean | Opsional | Status langsung terbit atau draf |

* **Respons Sukses (HTTP 200)**:
  ```json
  201
  ```
* **Respons Gagal**:
  * `422 Unprocessable Entity` (`ValidationException`)
  * `401 Unauthorized` (`AuthenticationException`)

##### `PUT /v1/admin/informasi/{id}`
* **Deskripsi**: Memperbarui konten data informasi publik yang telah ada (Khusus Admin).
* **Operation ID**: `informasiPublik.update`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Parameter Path**:
  * `id` (string, Wajib): ID Unik data informasi
* **Payload Request (JSON)**:
  | Nama Field | Tipe | Karakteristik | Deskripsi |
  | :--- | :--- | :--- | :--- |
  | `judul` | string | Opsional, Max: 255 | Judul baru berita |
  | `konten` | string | Opsional | Isi konten terbaru berita |
  | `kategori` | string | Opsional, Max: 50 | Kategori berita |
  | `cover_image` | string / null | Opsional, dapat bernilai null | Gambar sampul baru berita |
  | `is_published` | boolean | Opsional | Status rilis |

* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `422 Unprocessable Entity` (`ValidationException`)
  * `401 Unauthorized` (`AuthenticationException`)

##### `DELETE /v1/admin/informasi/{id}`
* **Deskripsi**: Menghapus data informasi publik dari sistem (Khusus Admin).
* **Operation ID**: `informasiPublik.destroy`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Parameter Path**:
  * `id` (string, Wajib): ID Unik data informasi yang akan dihapus
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

---

#### 6.3. Modul Mutasi Penduduk (MutasiPenduduk)

##### `POST /v1/mutasi`
* **Deskripsi**: Mengajukan dokumen mutasi kependudukan baru (Kelahiran, Kematian, Kedatangan, atau Kepindahan).
* **Operation ID**: `mutasiPenduduk.store`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Payload Request (JSON)**:
  | Nama Field | Tipe | Karakteristik | Deskripsi |
  | :--- | :--- | :--- | :--- |
  | `nik` | string | Wajib | NIK warga yang mengalami mutasi |
  | `jenis_mutasi` | string | Wajib, Enum: `Kelahiran`, `Kematian`, `Kedatangan`, `Kepindahan` | Jenis peristiwa mutasi penduduk |
  | `tanggal_mutasi` | string | Wajib, Format: `date-time` | Tanggal terjadinya peristiwa mutasi |
  | `keterangan` | string | Wajib | Catatan atau alasan peristiwa mutasi |
  | `dokumen_bukti` | string | Wajib | URL atau referensi berkas bukti pendukung fisik |

* **Respons Sukses (HTTP 200)**:
  ```json
  201
  ```
* **Respons Gagal**:
  * `422 Unprocessable Entity` (`ValidationException`)
  * `401 Unauthorized` (`AuthenticationException`)

##### `GET /v1/mutasi`
* **Deskripsi**: Mengambil daftar seluruh riwayat permohonan mutasi yang diajukan oleh akun warga yang bersangkutan.
* **Operation ID**: `mutasiPenduduk.index`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `GET /v1/admin/mutasi`
* **Deskripsi**: Mengambil daftar komprehensif pengajuan mutasi dari seluruh warga (Khusus Admin).
* **Operation ID**: `mutasiPenduduk.adminIndex`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `POST /v1/admin/mutasi/{id}/approve`
* **Deskripsi**: Menyetujui pengajuan mutasi kependudukan warga. Tindakan ini memicu perubahan otomatis data status pada tabel penduduk (Khusus Admin).
* **Operation ID**: `mutasiPenduduk.approve`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Parameter Path**:
  * `id` (string, Wajib): ID unik pengajuan mutasi
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `POST /v1/admin/mutasi/{id}/reject`
* **Deskripsi**: Menolak pengajuan mutasi kependudukan warga (Khusus Admin).
* **Operation ID**: `mutasiPenduduk.reject`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Parameter Path**:
  * `id` (string, Wajib): ID unik pengajuan mutasi
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

---

#### 6.4. Modul Pengajuan Surat (PengajuanSurat)

##### `GET /v1/surat/kategori`
* **Deskripsi**: Mengambil seluruh kategori surat resmi yang aktif dan tersedia untuk diajukan oleh warga gampong.
* **Operation ID**: `pengajuanSurat.kategori`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `GET /v1/surat/kategori/{id}`
* **Deskripsi**: Mengambil parameter detail persyaratan dan skema isian dari suatu kategori surat spesifik berdasarkan ID.
* **Operation ID**: `pengajuanSurat.detailKategori`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Parameter Path**:
  * `id` (string, Wajib): ID kategori surat
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `POST /v1/surat/pengajuan`
* **Deskripsi**: Mengajukan pembuatan surat administrasi gampong baru.
* **Operation ID**: `pengajuanSurat.store`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Payload Request (JSON)**:
  | Nama Field | Tipe | Karakteristik | Deskripsi |
  | :--- | :--- | :--- | :--- |
  | `kategori_surat_id` | integer | Wajib | ID kategori surat yang diajukan |
  | `data_isian` | array of strings | Wajib, MinItem: 1 | Kumpulan string isian formulir dinamis sesuai template |
  | `file_syarat` | array of strings | Wajib, MinItem: 1 | Kumpulan string path/URL file dokumen prasyarat |

* **Respons Sukses (HTTP 200)**:
  ```json
  201
  ```
* **Respons Gagal**:
  * `422 Unprocessable Entity` (`ValidationException`)
  * `401 Unauthorized` (`AuthenticationException`)

##### `GET /v1/surat/pengajuan`
* **Deskripsi**: Mengambil daftar seluruh pengajuan dokumen surat milik warga yang sedang login saat ini.
* **Operation ID**: `pengajuanSurat.index`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `GET /v1/surat/pengajuan/{id}`
* **Deskripsi**: Mengambil status progres dan detail formulir isian dari satu pengajuan surat tertentu berdasarkan ID.
* **Operation ID**: `pengajuanSurat.show`
* **Otentikasi**: Diperlukan (`Bearer Token`)
* **Parameter Path**:
  * `id` (string, Wajib): ID pengajuan surat yang dicari
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `404 Not Found` (`ModelNotFoundException`)
  * `401 Unauthorized` (`AuthenticationException`)

##### `GET /v1/admin/surat/pengajuan`
* **Deskripsi**: Mengambil daftar seluruh permohonan pengajuan surat yang masuk dari warga (Khusus Admin).
* **Operation ID**: `pengajuanSurat.adminIndex`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `POST /v1/admin/surat/pengajuan/{id}/approve`
* **Deskripsi**: Menyetujui pengajuan surat warga. Aksi ini secara otomatis memicu generator dokumen PDF, menyisipkan hash TTE, dan mengirimkan notifikasi instan kepada pemohon (Khusus Admin).
* **Operation ID**: `pengajuanSurat.approve`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Parameter Path**:
  * `id` (string, Wajib): ID pengajuan surat warga
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

##### `POST /v1/admin/surat/pengajuan/{id}/reject`
* **Deskripsi**: Menolak pengajuan dokumen surat warga disertai dengan alasan administratif penolakan (Khusus Admin).
* **Operation ID**: `pengajuanSurat.reject`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Parameter Path**:
  * `id` (string, Wajib): ID pengajuan surat warga
* **Payload Request (JSON)**:
  | Nama Field | Tipe | Karakteristik | Deskripsi |
  | :--- | :--- | :--- | :--- |
  | `catatan_penolakan` | string | Wajib | Alasan penolakan administratif surat |

* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `422 Unprocessable Entity` (`ValidationException`)
  * `401 Unauthorized` (`AuthenticationException`)

---

#### 6.5. Modul Statistik (Statistik)

##### `GET /v1/statistik/demografi`
* **Deskripsi**: Mengambil data kompilasi agregat demografi kependudukan gampong (Rasio jenis kelamin, persentase rentang usia, tingkat pendidikan, mata pencaharian, dan agama). Hasil kompilasi data di-cache otomatis selama 1 hari pada Redis untuk menjaga efisiensi kinerja server.
* **Operation ID**: `statistik.demografi`
* **Otentikasi**: Terbuka untuk umum (Public)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```

##### `GET /v1/statistik/layanan`
* **Deskripsi**: Mengambil data agregat aktivitas pengajuan surat resmi dan mutasi kependudukan gampong. Hasil kompilasi data di-cache otomatis selama 1 hari pada Redis untuk menjaga efisiensi kinerja server.
* **Operation ID**: `statistik.layanan`
* **Otentikasi**: Terbuka untuk umum (Public)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```

##### `POST /v1/admin/statistik/clear-cache`
* **Deskripsi**: Membersihkan cache data statistik pada Redis untuk memaksa regenerasi kalkulasi data terbaru secara real-time (Khusus Admin).
* **Operation ID**: `statistik.clearCache`
* **Otentikasi**: Diperlukan (`Bearer Token` Admin)
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```
* **Respons Gagal**:
  * `401 Unauthorized` (`AuthenticationException`)

---

#### 6.6. Modul Webhook & Verifikasi (Verifikasi)

##### `POST /v1/telegram/webhook`
* **Deskripsi**: Menangani lalu lintas data pesan masuk (*incoming message*) dari bot Telegram secara asinkronus menggunakan webhook Telegram API.
* **Operation ID**: `telegramWebhook.handle`
* **Otentikasi**: Terbuka untuk umum / Terverifikasi IP Telegram Server
* **Respons Sukses (HTTP 200)**:
  ```json
  {
    "ok": true
  }
  ```

##### `GET /v1/verifikasi/{hash}`
* **Deskripsi**: Memvalidasi keaslian dokumen surat fisik gampong yang dicetak secara mandiri oleh warga dengan mencocokkan kode hash digital TTE berbasis algoritma kriptografi SHA-256.
* **Operation ID**: `verifikasi.verify`
* **Otentikasi**: Terbuka untuk umum (Public)
* **Parameter Path**:
  * `hash` (string, Wajib): Kode string hash SHA-256 unik yang tertera pada QR Code dokumen fisik
* **Respons Sukses (HTTP 200)**:
  ```json
  200
  ```

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> 7. Keamanan Sistem

Platform dirancang dengan mengutamakan standar keamanan berlapis untuk menjamin integritas data warga:

* **Otentikasi Token Statis**: Menggunakan Laravel Sanctum dengan metode enkripsi token berbasis hash SHA-256 pada database.
* **Tanda Tangan Elektronik (TTE)**: Dokumen surat yang disetujui secara otomatis disematkan QR Code unik yang memuat URL verifikasi resmi beserta tanda tangan hash dokumen yang tidak dapat dipalsukan.
* **Rate Limiting**: Pencegahan serangan Brute Force dan Denial of Service (DoS) melalui batasan akses global sebanyak 60 permintaan per menit per alamat IP.
* **Audit Trail**: Pemanfaatan sistem logging audit pada setiap modifikasi entitas data vital (Create, Update, Delete) yang mencatat informasi aktor, stempel waktu, dan detail perubahan data asli.
* **Penanganan SQL Injection & XSS**: Pemanfaatan *Prepared Statements* secara default oleh Laravel Eloquent ORM serta pembersihan input (*input sanitization*) pada semua payload request.
* **Kebijakan Zero-Emoji & Format Formal**: Seluruh interaksi bot Telegram dikonversi menggunakan parser Markdown-to-HTML dan bebas dari segala bentuk emoji non-profesional demi menjaga kesopanan serta standar administratif kenegaraan resmi.

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg> 8. Pengujian Sistem (Testing)

Proyek ini menerapkan standar pengembangan berbasis pengujian yang mencakup verifikasi backend dan pengujian unit komponen antarmuka.

#### Struktur Coverage Uji
* **Pengujian Backend**: Terdiri dari berkas pengujian menggunakan PHPUnit 12 (`^12.5.12`) yang terbagi atas:
  * **Unit Test**: Verifikasi fungsi pembantu, algoritma penghasil hash, serta transformasi data.
  * **Feature Test**: Pengujian siklus hidup API (pendaftaran, persetujuan surat, autentikasi, penolakan transaksi).
* **Pengujian Frontend**: Pengujian komponen visual menggunakan Vitest (`^4.1.8`) bersama `@vue/test-utils` dan penanganan DOM melalui pustaka `jsdom`.

#### Eksekusi Pengujian Backend
Untuk melakukan verifikasi kebenaran logika backend dan memastikan konfigurasi cache bersih, gunakan perintah berikut:
```bash
composer run test
```

#### Eksekusi Pengujian Frontend
Untuk melakukan verifikasi logika antarmuka komponen Vue:
```bash
npx vitest run
```

---

### <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg> 9. Peta Jalan dan Status Proyek

Pengembangan sistem dibagi menjadi tiga fase berurutan untuk menjamin stabilitas dan skalabilitas layanan:

#### Fase 1: Backend API & Core Engine (Status: Selesai)
* Desain database relasional MySQL / MariaDB (15 tabel inti).
* Pembuatan serta pengujian 29 endpoint API menggunakan skema otentikasi Sanctum.
* Integrasi antrean (Queue) berbasis Redis untuk penanganan pemrosesan asinkronus.
* Implementasi integrasi multi-provider AI (Google Gemini & OpenAI/Compatible MaaS seperti Alibaba Cloud Model Studio) untuk fungsionalitas asisten pintar (Chatbot Telegram) serta alat bantu copywriting dan rekomendasi SEO artikel di panel admin.
* Optimasi efisiensi token melalui database-level caching (caching balasan identik 24 jam) dan penanganan FAQ statis.
* Penyempurnaan formatting bot Telegram menggunakan parser Markdown-to-HTML.
* Menghapus seluruh emoji di seluruh kode program dan konfigurasi demi tercapainya standarisasi format profesional.

#### Fase 2: Integrasi Frontend & Panel Kontrol (Status: Selesai)
* Pengembangan antarmuka pengguna berbasis SPA menggunakan Inertia.js dan Vue 3.
* Penerapan layouting responsif menggunakan utility class dari Tailwind CSS v4.0.
* Pembuatan dashboard admin serta pemetaan peran operator berbasis Filament PHP v5.x.
* Pengujian sistem frontend secara menyeluruh menggunakan Vitest.
* Implementasi fitur upload & ganti foto perangkat desa secara dinamis dari database (`PengaturanGampong`).
* Integrasi Optimasi SEO & GEO tingkat tinggi menggunakan structured data JSON-LD (`GovernmentOrganization`, `NewsArticle`) dan meta-tag dinamis untuk meningkatkan indexability mesin pencari dan generative AI engine.
* Penerapan Caching AI cerdas (Exact Match & Semantic Caching menggunakan tokenized Jaccard Similarity / Levenshtein Distance) untuk chatbot guna memotong latensi chatbot (<2ms) dan menekan tagihan token API AI.

#### Fase 3: Deployment & Optimasi (Status: Direncanakan)
* Konfigurasi kontainerisasi menggunakan Docker untuk kemudahan replikasi lingkungan produksi.
* Penyetelan kebijakan keamanan SSL/TLS 1.3 dan monitoring kinerja server (APM).
* Publikasi dokumentasi API interaktif menggunakan Scribe.

---

<div align="center">

**SIG-Udeung — Sistem Informasi Gampong Udeung Terpadu**  
Dikembangkan untuk mewujudkan digitalisasi administrasi desa yang profesional dan modern.

</div>