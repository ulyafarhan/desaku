# SIG-Udeung (Sistem Informasi Gampong Udeung Terpadu)

> **Solusi Digital Administrasi Desa Berbasis Cloud dengan Integrasi AI dan Arsitektur Modern**

---

## Daftar Isi

- [1. Pendahuluan](#1-pendahuluan)
- [2. Spesifikasi Teknisi](#2-spesifikasi-teknisi)
- [3. Arsitektur Sistem](#3-arsitektur-sistem)
- [4. Instalasi dan Setup](#4-instalasi-dan-setup)
- [5. Struktur Proyek](#5-struktur-proyek)
- [6. Skema Database](#6-skema-database)
- [7. Spesifikasi API](#7-spesifikasi-api)
- [8. Integrasi AI dan Telegram](#8-integrasi-ai-dan-telegram)
- [9. Keamanan Sistem](#9-keamanan-sistem)
- [10. Pengujian](#10-pengujian)
- [11. Peta Jalan](#11-peta-jalan)
- [12. Kontribusi](#12-kontribusi)
- [13. Lisensi](#13-lisensi)

---

## 1. Pendahuluan

### 1.1 Tentang SIG-Udeung

SIG-Udeung (Sistem Informasi Gampong Udeung Terpadu) merupakan platform sistem informasi desa terpadu yang dirancang khusus untuk memodernisasi tata kelola administrasi pada tingkat Gampong (Desa). Melalui pendekatan desentralisasi data, beban kerja administratif yang sebelumnya berpusat pada aparatur desa kini dialihkan secara aman ke partisipasi aktif masyarakat melalui portal layanan mandiri (*self-service*).

### 1.2 Gampong Udeung

Gampong Udeung terletak di Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh. Sistem ini dikembangkan untuk mendukung digitalisasi administrasi desa di wilayah tersebut dengan mengintegrasikan seluruh layanan publik dalam satu platform terpadu.

### 1.3 Visi dan Misi

**Visi**: Mewujudkan ekosistem administrasi desa yang transparan, akuntabel, dan efisien berbasis teknologi cloud guna mengoptimalkan kualitas pelayanan publik.

**Misi**:
- Digitalisasi secara menyeluruh terhadap administrasi kependudukan dan pencatatan sipil
- Penyediaan sistem pengajuan surat mandiri untuk memangkas birokrasi fisik
- Implementasi teknologi kecerdasan buatan (AI) untuk asisten virtual pelayanan 24 jam
- Penjaminan keamanan data sensitif warga melalui sistem audit trail dan kriptografi SHA-256

### 1.4 Target Pengguna

- **Warga Gampong**: Masyarakat yang membutuhkan layanan administrasi desa secara daring
- **Admin Desa**: Keuchik, Sekretaris Desa (Sekdes), dan Operator yang mengelola data dan pengajuan
- **Bot Telegram**: Asisten virtual berbasis AI yang melayani pertanyaan warga secara otomatis

---

## 2. Spesifikasi Teknisi

### 2.1 Stack Backend (Sisi Server)

| Komponen | Versi | Keterangan |
|:---|:---|:---|
| PHP | ^8.3 | Runtime dengan optimasi *strictly typed* |
| Laravel Framework | ^13.8 | Mesin API utama, manajemen antrean, dan penanganan sesi |
| Filament PHP | 5.6.5 | Panel manajemen administratif untuk Keuchik, Sekdes, dan Operator |
| Laravel Sanctum | ^4.3 | Otentikasi token berbasis SHA-256 |
| MySQL / MariaDB | 8.0+ | Database produksi (SQLite untuk fallback lokal) |
| Redis | - | Caching dan *queue manager* untuk pemrosesan asinkronus |
| Barryvdh DomPDF | ^3.0 | Generator dokumen administrasi format PDF |
| Simple QR Code | ^4.2 | Tanda tangan elektronik (TTE) berbasis verifikasi hash SHA-256 |
| Scribe | ^5.10 | Dokumentasi API interaktif (development) |

### 2.2 Stack Frontend (Sisi Klien)

| Komponen | Versi | Keterangan |
|:---|:---|:---|
| Vue 3 | ^3.5.38 | Framework UI dengan Composition API |
| Inertia.js | ^3.4.0 | Konektor SPA tanpa overhead pengembangan API REST eksternal |
| Tailwind CSS | ^4.3.2 | Utility-first CSS framework dengan Vite plugin |
| Vite | ^8.0.16 | Build tool dengan plugin khusus Laravel |
| @lucide/vue | ^1.17.0 | Desain ikon |
| SweetAlert2 | ^11.26.25 | Modal dan notifikasi interaktif |
| Axios | ^1.17.0 | HTTP client |
| laravel-vite-plugin | ^3.1 | Integrasi Vite dengan Laravel |

### 2.3 Stack Pengujian (Testing)

| Komponen | Versi | Keterangan |
|:---|:---|:---|
| PHPUnit | ^12.5.12 | Pengujian backend (Unit dan Feature Test) |
| Vitest | ^4.1.8 | Pengujian frontend komponen Vue |
| @vue/test-utils | ^2.4.11 | Utilitas pengujian komponen Vue |
| @vitest/coverage-v8 | ^4.1.8 | Laporan cakupan pengujian |
| jsdom | ^29.1.1 | simulasi DOM untuk pengujian |

---

## 3. Arsitektur Sistem

### 3.1 Diagram Arsitektur

Sistem ini dibangun menggunakan arsitektur monolit modern 3 layer yang menggabungkan keunggulan kompilasi backend dan reaktivitas frontend dalam satu repositori terpadu.

```
┌─────────────────────────────────────────────────────────────┐
│                 Frontend Client Layer                       │
├─────────────────────────────────────────────────────────────┤
│  Portal Publik & PWA Warga (Inertia.js + Vue 3)            │
│  Styling & UI (Tailwind CSS v4 + Lucide Icons)             │
│  Build Engine (Vite 8)                                      │
└─────────────────────────────────────────────────────────────┘
                            │ (Protokol Inertia)
                            ▼
┌─────────────────────────────────────────────────────────────┐
│             Backend & Admin Platform (Laravel 13)           │
├─────────────────────────────────────────────────────────────┤
│  Dashboard Admin & Operator (Filament PHP v5)               │
│  RESTful API Engine (Sanctum Auth, 29 Endpoint)             │
│  Document Processor (DomPDF & Simple QR Code)               │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                Data & External Service Layer                │
├─────────────────────────────────────────────────────────────┤
│  Database Utama: MySQL 8.0+ / MariaDB / SQLite             │
│  Cache & Queue Manager: Redis                               │
│  Integrasi AI: Google Gemini & OpenAI/Compatible MaaS      │
│  Notifikasi & Bot Webhook: Telegram API                     │
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Penjelasan Layer

- **Frontend Client Layer**: Menangani seluruh antarmuka pengguna melalui portal publik dan area warga. Menggunakan Inertia.js sebagai konektor SPA dengan Vue 3 untuk reaktivitas dan Tailwind CSS untuk styling.
- **Backend & Admin Platform**: Mesin pemrosesan utama berbasis Laravel 13 dengan panel administratif Filament PHP v5. Menyediakan RESTful API dengan otentikasi Sanctum dan pemrosesan dokumen PDF.
- **Data & External Service Layer**: Menangani penyimpanan data via MySQL, caching via Redis, integrasi AI untuk chatbot, dan notifikasi via Telegram API.

---

## 4. Instalasi dan Setup

### 4.1 Persyaratan Sistem

| Komponen | Versi Minimum |
|:---|:---|
| PHP | 8.3 atau lebih tinggi |
| Node.js | 18 atau lebih tinggi |
| MySQL / MariaDB | 8.0+ / 10.4+ |
| Redis Server | Terbaru |
| Composer | Terbaru |

### 4.2 Langkah-Langkah Instalasi

**1. Kloning Repositori**

```bash
git clone [url-repositori]
cd desaku
```

**2. Eksekusi Setup Otomatis**

Perintah berikut akan menginstal dependensi PHP, membuat berkas `.env`, menghasilkan kunci aplikasi, menjalankan migrasi database, menginstal paket NPM, dan melakukan build aset frontend:

```bash
composer run setup
```

**3. Konfigurasi Berkas Lingkungan (.env)**

Sesuaikan kredensial database dan integrasi pihak ketiga pada berkas `.env`:

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

# Jika menggunakan OpenAI / Compatible MaaS
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_MODEL=deepseek-v4-flash
OPENAI_BASE_URL=https://api.openai.com/v1
```

### 4.3 Menjalankan Aplikasi

Gunakan perintah pengembangan terintegrasi untuk menjalankan server PHP, worker antrean, dan server Vite secara simultan:

```bash
composer run dev
```

Aplikasi akan dapat diakses secara lokal melalui alamat `http://localhost:8000`.

---

## 5. Struktur Proyek

```
desaku/
├── app/
│   ├── Console/Commands/          # Perintah CLI Artisan kustom
│   ├── Events/                    # Event aplikasi
│   ├── Filament/                  # Panel administrasi Filament PHP
│   ├── Http/
│   │   ├── Controllers/Api/       # Controller API RESTful
│   │   └── Controllers/Web/       # Controller web (Inertia)
│   ├── Jobs/                      # Job antrean (asinkronus)
│   ├── Models/                    # Definisi model Eloquent ORM
│   ├── Providers/                 # Service Provider aplikasi
│   └── Services/                  # Logika bisnis terisolasi (Service Layer)
├── bootstrap/
│   └── cache/                     # Cache bootstrap aplikasi
├── config/                        # Konfigurasi aplikasi Laravel
├── database/
│   ├── factories/                 # Factory untuk pengujian
│   ├── migrations/                # Skema migrasi database DDL
│   └── seeders/                   # Data awal (seeding) sistem
├── docs/
│   ├── api/                       # Dokumentasi API
│   ├── backend/                   # Dokumentasi backend
│   ├── database/                  # Dokumentasi database
│   ├── design/                    # Dokumentasi desain
│   └── frontend/                  # Dokumentasi frontend
├── lang/
│   └── id/                        # File lokal Bahasa Indonesia
├── public/
│   ├── build/                     # Aset frontend terkompilasi
│   ├── css/                       # CSS statis
│   ├── fonts/                     # Font aplikasi
│   ├── images/                    # Gambar statis
│   ├── js/                        # JavaScript statis
│   ├── storage/                   # Simbolic link storage
│   └── vendor/                    # Vendor前端 (Filament, etc.)
├── resources/
│   ├── css/                       # Sumber CSS
│   ├── js/
│   │   ├── Components/            # Komponen Vue
│   │   ├── Composables/           # Composable Vue
│   │   ├── Layouts/               # Layout aplikasi
│   │   ├── Pages/                 # Halaman Vue (Inertia)
│   │   └── Utils/                 # Utilitas frontend
│   └── views/                     # Blade templates
├── routes/
│   ├── api.php                    # Definisi routing API REST (29 endpoint)
│   └── web.php                    # Definisi routing web (20 rute)
├── storage/
│   ├── app/                       # File aplikasi (upload, PDF, cache)
│   ├── framework/                 # File framework (session, cache)
│   └── logs/                      # Log aplikasi
├── tests/
│   ├── Feature/                   # Pengujian fitur/endpoint end-to-end
│   └── Unit/                      # Unit testing terisolasi
├── vendor/                        # Dependensi PHP (Composer)
└── node_modules/                  # Dependensi JavaScript (NPM)
```

---

## 6. Skema Database

Sistem ini didukung oleh 17 tabel relasional utama yang saling terintegrasi dengan struktur integritas data yang ketat:

| Nama Tabel | Deskripsi Fungsi | Relasi Kunci |
|:---|:---|:---|
| `users` | Autentikasi default Laravel, penyimpanan token Sanctum | Berelasi dengan tabel spesifik peran |
| `administrators` | Menyimpan kredensial admin (Keuchik, Sekdes, Operator) | FK ke `users.id` |
| `penduduk` | Entitas data dasar kependudukan. NIK 16 digit sebagai Primary Key | Relasi utama untuk data keluarga dan surat |
| `keluarga` | Manajemen Kartu Keluarga (KK) | FK ke `penduduk.nik` sebagai kepala keluarga |
| `mutasi_penduduk` | Pencatatan riwayat kelahiran, kematian, kepindahan, kedatangan | FK ke `penduduk.nik` |
| `kategori_surat` | Master data template dan jenis surat administrasi desa | Relasi satu-ke-banyak dengan pengajuan |
| `pengajuan_surat` | Pencatatan pengajuan dokumen dari warga | FK ke `penduduk.nik` dan `kategori_surat.id` |
| `tracking_pengajuan_surat` | Riwayat logis persetujuan dan perpindahan status pengajuan | FK ke `pengajuan_surat.id` |
| `informasi_publik` | Berita, siaran pers, dan pengumuman resmi desa | FK ke `administrators.id` sebagai pembuat |
| `bot_knowledges` | Basis pengetahuan kustom untuk dynamic FAQ dan RAG Context AI Chatbot | Data mandiri yang dapat diatur oleh admin |
| `telegram_broadcast_queue` | Antrean asinkronus pengiriman notifikasi publik | Terhubung dengan sistem Queue Laravel |
| `chatbot_logs` | Catatan log interaksi warga dengan bot Telegram berbasis AI | FK ke `users.id` (jika terikat) |
| `audit_logs` | Jejak audit (*audit trail*) mencakup seluruh aksi mutasi database | FK ke `users.id` |
| `pengaturan_gampong` | Pengaturan konfigurasi dinamis (nama desa, logo, dsb) | Data tunggal (Key-Value Pair) |
| `referensi_wilayah` | Data wilayah administratif (Dusun, RT, RW) | Terhubung dengan data kependudukan |
| `pengaturan_frontend` | Pengaturan identitas dan media sosial aparat gampong untuk frontend publik | Data tunggal (Key-Value Pair) |
| `traffic_logs` | Pencatatan statistik kunjungan/lalu lintas publik secara riil | Data log mandiri |

---

## 7. Spesifikasi API

Seluruh endpoint API berjalan di bawah basis URL: `http://localhost/api` (atau sesuai konfigurasi host lokal).

**Total Rute: 49 (20 Web + 29 API)**

### 7.1 Autentikasi

| Metode | Endpoint | Deskripsi | Otorisasi |
|:---|:---|:---|:---|
| POST | `/v1/auth/login/warga` | Login warga menggunakan NIK dan Nomor KK | Public |
| POST | `/v1/auth/login/admin` | Login admin menggunakan username dan password | Public |
| POST | `/v1/auth/logout` | Mengakhiri sesi dan menghapus token akses | Bearer Token |
| GET | `/v1/auth/profile` | Mengambil data profil pengguna yang sedang login | Bearer Token |
| POST | `/v1/auth/bind-telegram` | Menghubungkan akun warga dengan ID Chat Telegram | Bearer Token (Warga) |

### 7.2 Portal Publik

| Metode | Endpoint | Deskripsi | Otorisasi |
|:---|:---|:---|:---|
| GET | `/v1/informasi` | Mengambil daftar seluruh artikel informasi atau pengumuman | Public |
| GET | `/v1/informasi/{slug}` | Mengambil konten detail informasi publik berdasarkan slug | Public |

### 7.3 Layanan Warga

| Metode | Endpoint | Deskripsi | Otorisasi |
|:---|:---|:---|:---|
| GET | `/v1/surat/kategori` | Mengambil seluruh kategori surat yang aktif | Bearer Token (Warga) |
| GET | `/v1/surat/kategori/{id}` | Mengambil detail persyaratan kategori surat | Bearer Token (Warga) |
| POST | `/v1/surat/pengajuan` | Mengajukan pembuatan surat administrasi baru | Bearer Token (Warga) |
| GET | `/v1/surat/pengajuan` | Mengambil daftar pengajuan surat milik warga | Bearer Token (Warga) |
| GET | `/v1/surat/pengajuan/{id}` | Mengambil status progres pengajuan surat | Bearer Token (Warga) |
| POST | `/v1/mutasi` | Mengajukan dokumen mutasi kependudukan | Bearer Token (Warga) |
| GET | `/v1/mutasi` | Mengambil daftar riwayat permohonan mutasi | Bearer Token (Warga) |

### 7.4 Administrasi (Admin)

| Metode | Endpoint | Deskripsi | Otorisasi |
|:---|:---|:---|:---|
| GET | `/v1/admin/surat/pengajuan` | Mengambil seluruh pengajuan surat dari warga | Bearer Token (Admin) |
| POST | `/v1/admin/surat/pengajuan/{id}/approve` | Menyetujui pengajuan surat warga | Bearer Token (Admin) |
| POST | `/v1/admin/surat/pengajuan/{id}/reject` | Menolak pengajuan surat warga | Bearer Token (Admin) |
| GET | `/v1/admin/mutasi` | Mengambil daftar pengajuan mutasi dari seluruh warga | Bearer Token (Admin) |
| POST | `/v1/admin/mutasi/{id}/approve` | Menyetujui pengajuan mutasi kependudukan | Bearer Token (Admin) |
| POST | `/v1/admin/mutasi/{id}/reject` | Menolak pengajuan mutasi kependudukan | Bearer Token (Admin) |
| GET | `/v1/admin/informasi` | Mengambil seluruh informasi termasuk draf | Bearer Token (Admin) |
| POST | `/v1/admin/informasi` | Membuat draf atau memublikasikan informasi baru | Bearer Token (Admin) |
| PUT | `/v1/admin/informasi/{id}` | Memperbarui konten data informasi publik | Bearer Token (Admin) |
| DELETE | `/v1/admin/informasi/{id}` | Menghapus data informasi publik | Bearer Token (Admin) |
| POST | `/v1/admin/statistik/clear-cache` | Membersihkan cache data statistik pada Redis | Bearer Token (Admin) |

### 7.5 Integrasi Telegram

| Metode | Endpoint | Deskripsi | Otorisasi |
|:---|:---|:---|:---|
| POST | `/v1/telegram/webhook` | Menangani lalu lintas pesan masuk dari bot Telegram | Public (IP Telegram) |

### 7.6 Statistik dan Verifikasi

| Metode | Endpoint | Deskripsi | Otorisasi |
|:---|:---|:---|:---|
| GET | `/v1/statistik/demografi` | Mengambil data agregat demografi kependudukan | Public |
| GET | `/v1/statistik/layanan` | Mengambil data agregat aktivitas pengajuan surat dan mutasi | Public |
| GET | `/v1/verifikasi/{hash}` | Memvalidasi keaslian dokumen surat melalui hash SHA-256 | Public |

### 7.7 Rute Web (20 Rute)

| Metode | Endpoint | Deskripsi | Otorisasi |
|:---|:---|:---|:---|
| GET | `/` | Halaman beranda portal publik | Public |
| GET | `/profil` | Halaman profil gampong | Public |
| GET | `/informasi` | Halaman daftar informasi publik | Public |
| GET | `/informasi/{slug}` | Halaman detail informasi publik | Public |
| GET | `/verifikasi` | Halaman verifikasi dokumen | Public |
| GET | `/verifikasi/{hash}` | Halaman verifikasi dokumen berdasarkan hash | Public |
| GET | `/statistik` | Halaman statistik gampong | Public |
| POST | `/aspirasi` | Pengiriman aspirasi warga | Public (throttle:5,1) |
| GET | `/login` | Halaman login warga | Guest |
| POST | `/login` | Proses login warga | Guest |
| POST | `/logout` | Proses logout warga | Auth:penduduk |
| GET | `/warga/dashboard` | Dashboard warga | Auth:penduduk |
| GET | `/warga/profil` | Halaman profil warga | Auth:penduduk |
| POST | `/warga/profil` | Pembaruan profil warga | Auth:penduduk |
| GET | `/warga/keluarga` | Halaman data keluarga | Auth:penduduk |
| PUT | `/warga/keluarga/{nik}` | Pembaruan data keluarga | Auth:penduduk |
| GET | `/warga/surat/ajukan/{kategori}` | Halaman pengajuan surat | Auth:penduduk |
| POST | `/warga/surat/pengajuan` | Proses pengajuan surat | Auth:penduduk |
| GET | `/warga/pengajuan/{pengajuan}` | Halaman detail pengajuan surat | Auth:penduduk |
| GET | `/warga/pengajuan/{pengajuan}/print` | Cetak dokumen surat | Auth:penduduk |

---

## 8. Integrasi AI dan Telegram

### 8.1 Multi-AI Provider

Sistem mendukung integrasi multi-provider kecerdasan buatan untuk asisten virtual chatbot:

| Provider | Model | Keterangan |
|:---|:---|:---|
| Google Gemini | gemini-pro | Pilihan utama untuk integrasi AI |
| OpenAI Compatible | deepseek-v4-flash | Alternatif melalui API OpenAI |
| Fallback | - | Penanganan otomatis jika provider utama tidak tersedia |

Konfigurasi provider diatur melalui variabel lingkungan `AI_PROVIDER` pada berkas `.env`.

### 8.2 Telegram Bot

Bot Telegram SIG-Udeung dilengkapi dengan basis pengetahuan komprehensif yang mencakup:

- **542 Knowledge Entries**: Basis pengetahuan kustom yang mencakup seluruh informasi layanan desa, FAQ, prosedur administrasi, dan konteks RAG (*Retrieval-Augmented Generation*) untuk chatbot AI
- **Kebijakan Zero-Emoji**: Seluruh interaksi bot menggunakan parser Markdown-to-HTML dan bebas dari segala bentuk emoji demi menjaga kesopanan serta standar administratif kenegaraan resmi
- **Dynamic FAQ**: Sistem FAQ dinamis yang dapat diatur oleh admin melalui panel Filament
- **Caching AI Cerdas**: Exact Match dan Semantic Caching menggunakan tokenized Jaccard Similarity / Levenshtein Distance untuk memotong latensi chatbot (<2ms) dan menekan tagihan token API AI

### 8.3 Webhook dan Broadcasting

- **Webhook Telegram**: Endpoint `/v1/telegram/webhook` menangani lalu lintas pesan masuk secara asinkronus
- **Broadcasting**: Sistem antrean asinkronus berbasis Redis untuk pengiriman notifikasi publik kepada warga
- **Telegram Broadcast Queue**: Tabel khusus untuk mengelola antrean pengiriman notifikasi massal

---

## 9. Keamanan Sistem

Platform dirancang dengan mengutamakan standar keamanan berlapis untuk menjamin integritas data warga:

### 9.1 Laravel Sanctum

Otentikasi token statis menggunakan Laravel Sanctum dengan metode enkripsi token berbasis hash SHA-256 pada database. Mendukung dua tipe pengguna: Warga (NIK-based) dan Admin (username-password).

### 9.2 Tanda Tangan Elektronik (TTE)

Dokumen surat yang disetujui secara otomatis disematkan QR Code unik yang memuat URL verifikasi resmi beserta tanda tangan hash dokumen yang tidak dapat dipalsukan menggunakan algoritma kriptografi SHA-256.

### 9.3 Rate Limiting

Pencegahan serangan Brute Force dan Denial of Service (DoS) melalui batasan akses:
- Login Warga/Admin: 5 percobaan per menit per alamat IP
- Telegram Webhook: 60 permintaan per menit
- Aspirasi Warga: 5 pengiriman per menit

### 9.4 Audit Trail

Pemanfaatan sistem logging audit pada setiap modifikasi entitas data vital (Create, Update, Delete) yang mencatat informasi aktor, stempel waktu, dan detail perubahan data asli.

### 9.5 Pencegahan Injeksi

Pemanfaatan *Prepared Statements* secara default oleh Laravel Eloquent ORM serta pembersihan input (*input sanitization*) pada semua payload request untuk mencegah SQL Injection dan Cross-Site Scripting (XSS).

---

## 10. Pengujian

Proyek ini menerapkan standar pengembangan berbasis pengujian yang mencakup verifikasi backend dan pengujian unit komponen antarmuka.

### 10.1 Pengujian Backend (PHPUnit)

Pengujian backend menggunakan PHPUnit 12 (`^12.5.12`) yang terbagi atas:

- **Unit Test**: Verifikasi fungsi pembantu, algoritma penghasil hash, serta transformasi data
- **Feature Test**: Pengujian siklus hidup API (pendaftaran, persetujuan surat, autentikasi, penolakan transaksi)

Eksekusi pengujian backend:

```bash
composer run test
```

### 10.2 Pengujian Frontend (Vitest)

Pengujian komponen visual menggunakan Vitest (`^4.1.8`) bersama `@vue/test-utils` (`^2.4.11`) dan penanganan DOM melalui pustaka `jsdom` (`^29.1.1`).

Eksekusi pengujian frontend:

```bash
npx vitest run
```

---

## 11. Peta Jalan

Pengembangan sistem dibagi menjadi tiga fase berurutan untuk menjamin stabilitas dan skalabilitas layanan:

### Fase 1: Backend API dan Core Engine (Status: Selesai)

- Desain database relasional MySQL / MariaDB (17 tabel inti)
- Pembuatan serta pengujian 29 endpoint API menggunakan skema otentikasi Sanctum
- Integrasi antrean (Queue) berbasis Redis untuk penanganan pemrosesan asinkronus
- Implementasi integrasi multi-provider AI (Google Gemini & OpenAI/Compatible MaaS)
- Optimasi efisiensi token melalui database-level caching dan penanganan FAQ statis
- Penyempurnaan formatting bot Telegram menggunakan parser Markdown-to-HTML
- Penghapusan seluruh emoji demi tercapainya standarisasi format profesional

### Fase 2: Integrasi Frontend dan Panel Kontrol (Status: Selesai)

- Pengembangan antarmuka pengguna berbasis SPA menggunakan Inertia.js dan Vue 3
- Penerapan layouting responsif menggunakan utility class dari Tailwind CSS v4
- Pembuatan dashboard admin serta pemetaan peran operator berbasis Filament PHP v5
- Pengujian sistem frontend secara menyeluruh menggunakan Vitest
- Implementasi fitur upload dan ganti foto perangkat desa secara dinamis
- Integrasi Optimasi SEO & GEO tingkat tinggi menggunakan structured data JSON-LD
- Penerapan Caching AI cerdas (Exact Match & Semantic Caching)

### Fase 3: Deployment dan Optimasi (Status: Direncanakan)

- Konfigurasi kontainerisasi menggunakan Docker untuk kemudahan replikasi lingkungan produksi
- Penyetelan kebijakan keamanan SSL/TLS 1.3 dan monitoring kinerja server (APM)
- Publikasi dokumentasi API interaktif menggunakan Scribe

---

## 12. Kontribusi

Kontribusi sangat diterima dan diapresiasi. Untuk berkontribusi:

1. Fork repositori ini
2. Buat branch baru untuk fitur atau perbaikan (`git checkout -b fitur/nama-fitur`)
3. Commit perubahan Anda (`git commit -m 'Tambahkan deskripsi perubahan'`)
4. Push ke branch yang dibuat (`git push origin fitur/nama-fitur`)
5. Buka Pull Request

Pastikan seluruh pengujian berjalan sebelum mengirimkan Pull Request:

```bash
composer run test
npx vitest run
```

---

## 13. Lisensi

Proyek ini dilisensikan di bawah **MIT License**.

**Copyright 2026 SIG-Udeung**

Dikembangkan untuk mewujudkan digitalisasi administrasi desa yang profesional dan modern.

---

**SIG-Udeung -- Sistem Informasi Gampong Udeung Terpadu**
