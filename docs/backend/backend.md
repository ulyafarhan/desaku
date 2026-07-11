# Dokumentasi Backend - SIG-Udeung

Dokumentasi ini merinci struktur arsitektur, spesifikasi teknis, serta panduan pengujian untuk sisi server (backend) SIG-Udeung.

---

## 1. Tumpukan Teknologi Backend

| Komponen | Versi | Keterangan |
|----------|-------|------------|
| PHP | ^8.3 | Strictly Typed |
| Laravel Framework | ^13.8 | API Engine & Antrean Job |
| Laravel Sanctum | ^4.3 | Autentikasi Token |
| Filament PHP | 5.6.5 | Admin Backoffice |
| Inertia.js (Laravel) | ^3.1 | Konektor SPA |
| DomPDF | ^3.0 | Document Processor (PDF) |
| Simple QRCode | ^4.2 | Generator QR Code |
| PHPUnit | ^12.5.12 | Testing Framework |

---

## 2. Struktur Direktori Utama

```
app/
  Http/Controllers/
    Api/          - 7 controller API (29 endpoint)
    Web/          - 6 controller web (20 rute)
  Models/         - 17 model Eloquent
  Services/       - Logika bisnis terisolasi
  Jobs/           - Tugas asinkronus (PDF, Telegram)
  Filament/       - Sumber daya panel admin
```

---

## 3. Arsitektur Controller

### 3.1. API Controllers (`app/Http/Controllers/Api/`)

| Controller | Endpoint | Fungsi |
|------------|----------|--------|
| `AuthController` | 5 | Login warga/admin, logout, profil, bind Telegram |
| `InformasiPublikController` | 6 | CRUD informasi publik (publik + admin) |
| `StatistikController` | 3 | Statistik demografi, layanan, clear cache |
| `PengajuanSuratController` | 8 | Kategori surat, pengajuan warga, verifikasi admin |
| `MutasiPendudukController` | 5 | Pengajuan mutasi warga, verifikasi admin |
| `VerifikasiController` | 1 | Verifikasi dokumen TTE via hash QR |
| `TelegramWebhookController` | 1 | Webhook pesan masuk Telegram Bot |

### 3.2. Web Controllers (`app/Http/Controllers/Web/`)

| Controller | Rute | Fungsi |
|------------|------|--------|
| `PublicPortalController` | 8 | Beranda, profil, informasi, verifikasi, statistik, aspirasi |
| `CitizenAuthController` | 3 | Login/logout warga (NIK-based) |
| `CitizenDashboardController` | 1 | Dashboard warga (single action) |
| `CitizenProfileController` | 2 | Lihat/perbarui profil warga |
| `CitizenFamilyController` | 2 | Lihat/perbarui data keluarga |
| `CitizenSubmissionController` | 4 | Ajukan surat, lihat pengajuan, cetak PDF |

---

## 4. Struktur Model & Relasi

### 4.1. Model Utama

| Model | Tabel | PK | Keterangan |
|-------|-------|----|------------|
| `Penduduk` | `penduduk` | NIK (VARCHAR 16) | Data kependudukan, extends Authenticatable |
| `Keluarga` | `keluarga` | No KK (VARCHAR 16) | Data Kartu Keluarga |
| `Administrator` | `administrators` | ULID | Admin perangkat desa, Filament User |
| `PengajuanSurat` | `pengajuan_surat` | ULID | Pengajuan surat warga |
| `MutasiPenduduk` | `mutasi_penduduk` | ULID | Laporan mutasi kependudukan |
| `KategoriSurat` | `kategori_surat` | ULID | Template/kategori surat |
| `InformasiPublik` | `informasi_publik` | ULID | Artikel berita/pengumuman |

### 4.2. Model Pendukung

| Model | Tabel | Keterangan |
|-------|-------|------------|
| `TrackingPengajuanSurat` | `tracking_pengajuan_surat` | Log riwayat perubahan status surat |
| `PengaturanGampong` | `pengaturan_gampong` | Konfigurasi sistem (key-value) |
| `PengaturanFrontend` | `pengaturan_frontend` | Konfigurasi konten frontend |
| `ReferensiWilayah` | `referensi_wilayah` | Master data wilayah administrasi |
| `BotKnowledge` | `bot_knowledges` | Basis pengetahuan chatbot Telegram |
| `ChatbotLog` | `chatbot_logs` | Log interaksi chatbot |
| `TelegramBroadcastQueue` | `telegram_broadcast_queue` | Antrean pesan massal Telegram |
| `AuditLog` | `audit_logs` | Log audit aktivitas sistem |
| `TrafficLog` | `traffic_logs` | Log kunjungan website |
| `User` | `users` | Akun pengguna default Laravel |

---

## 5. Panduan Instalasi Lokal

1. Pastikan Composer, PHP 8.3+, MySQL/MariaDB, dan Redis sudah aktif di server lokal Anda.
2. Jalankan perintah instalasi dependensi backend:
   ```bash
   composer install
   ```
3. Salin file konfigurasi lingkungan:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Konfigurasikan kredensial database di `.env`, lalu jalankan migrasi beserta pengisian data awal:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Setup Webhook Bot Telegram:
   ```bash
   php artisan telegram:setup-webhook
   ```
6. Jalankan Server Backend & Queue Worker:
   - Server: `php artisan serve`
   - Queue Worker: `php artisan queue:work`
7. Atau gunakan perintah kombo untuk development:
   ```bash
   composer run dev
   ```
   (Menjalankan server, queue worker, dan Vite secara paralel)

---

## 6. Pengujian & Penjaminan Kualitas (QA)

Sistem backend dilengkapi dengan berkas pengujian otomatis berbasis PHPUnit 12:

- **Unit Testing**: Memvalidasi fungsi helper, generator hash, dan integrasi provider AI.
- **Feature Testing**: Memvalidasi siklus hidup otentikasi, alur persetujuan surat, dan audit log.

### Cara Menjalankan Tes:
```bash
composer run test
```

### Optimasi Query (Bebas N+1 Query)

- Semua model utama (`Penduduk`, `PengajuanSurat`, `Keluarga`) menggunakan eager loading global (`$with`) untuk relasi yang sering diakses.
- Fungsi query Filament admin mengimplementasikan modifikasi `getEloquentQuery` dengan eager load relasi guna memastikan performa database tetap optimal dan bebas dari overhead N+1 query.

---

## 7. Optimasi Performa & Caching AI & Multi-AI Fallback

Sistem backend SIG-Udeung mengimplementasikan hibrida **Exact Match Cache** dan **Semantic Cache** pada `OpenAiProvider` dan `GeminiProvider` untuk menghemat penggunaan token API AI dan mempercepat respons chatbot (<2ms jika hit cache). Selain itu, sistem menggunakan penanganan failover berantai (*fallback*) untuk menjamin ketersediaan layanan.

### 7.1. Alur Kerja Caching AI:

1. **Normalisasi**: Pesan masuk dipangkas spasi kosongnya dan diubah ke huruf kecil (lowercase).
2. **Exact Match Cache**:
   - Sistem memeriksa cache Redis dengan key `ai_exact_[md5(pesan_normal)]`.
   - Jika tidak ada di Redis, sistem mencari kecocokan eksak pada log 24 jam terakhir di tabel `chatbot_logs`.
   - Jika ditemukan, jawaban di-cache ke Redis selama 24 jam dan langsung dikembalikan ke pengguna.
3. **Semantic Cache**:
   - Jika exact match tidak ditemukan, sistem memuat 100 log interaksi unik terakhir dalam 48 jam terakhir dari cache Redis `ai_recent_logs_semantic` (durasi simpan 5 menit).
   - **Tokenisasi & Stopwords**: Pesan masuk dan pesan log dipecah menjadi token kata setelah membuang karakter non-alfanumerik serta kata hubung umum bahasa Indonesia (*stopwords* seperti: *yang, di, dan, itu, dengan, untuk, pada, ke, dari, ini, adalah, akan, atau, saya, anda, kami, kita*).
   - **Jaccard Similarity**: Mengukur rasio irisan token dibanding gabungan token kata unik.
   - **Levenshtein Distance**: Untuk pesan pendek (<20 karakter), jarak Levenshtein dihitung untuk mengukur kedekatan edit teks.
   - **Threshold & Output**: Jika skor kemiripan tertinggi mencapai **>= 80% (0.80)**, sistem mengembalikan balasan ter-cache, menyimpan relasi pencarian baru ke Redis, dan mencatat log dengan penggunaan token `0` (*zero cost*).
   - Jika di bawah 80%, request baru akan diteruskan ke API Provider AI (OpenAI/Gemini).

### 7.2. Mekanisme Multi-AI Fallback & Prioritas Dinamis:

Untuk menjamin keandalan layanan AI tanpa perlu mengedit berkas `.env` di server production, sistem menggunakan kelas `FallbackAiService` yang membungkus antarmuka `AiProviderInterface`.

- **Penyimpanan Konfigurasi**: Seluruh rantai penyedia AI cadangan disimpan dalam format JSON (`ai_providers_list`) pada tabel database `pengaturan_gampong`.
- **Pengurutan Prioritas**: Setiap penyedia dikonfigurasi melalui Filament dengan kolom `priority`. Sistem secara otomatis mengurutkan pemanggilan dari prioritas angka terkecil ke terbesar.
- **Failover Otomatis**: Jika pemanggilan API dari penyedia utama mengalami limitasi kuota (HTTP 429), token habis/salah (HTTP 401), atau error koneksi lainnya sehingga menghasilkan nilai `null` atau melemparkan exception, sistem otomatis merekam peringatan log dan langsung mencoba penyedia cadangan berikutnya.
- **Kompatibilitas Mundur**: Apabila konfigurasi daftar penyedia dinamis di database kosong, sistem akan otomatis beralih menggunakan kredensial tunggal default yang tertera pada berkas `.env` (Gemini atau OpenAI).

---

## 8. Fitur Pemantauan Server & Lalu Lintas (Traffic)

Untuk membantu administrator gampong dalam memantau kesehatan server secara real-time dan melacak aktivitas kunjungan warga, backend SIG-Udeung dilengkapi dengan sistem pemantauan terintegrasi:

### 8.1. Middleware Pemantau Lalu Lintas (`TrackTraffic`)

- Terdaftar secara global dalam grup middleware `web` di `bootstrap/app.php`.
- Memfilter request internal admin (`/admin*`), request AJAX Livewire (`/livewire*`), dan request telemetri agar data lalu lintas murni berasal dari warga/publik.
- Menganalisis *User Agent* secara dinamis untuk mengidentifikasi apakah request berasal dari bot/crawler mesin pencari.
- Menyimpan log kunjungan ke dalam tabel `traffic_logs` (IP address, user agent, URL path, HTTP method, referer, dan status bot).

### 8.2. Widget Pemantau Performa Server (`ServerPerformanceWidget`)

- Membaca kapasitas ruang penyimpanan (disk space) secara real-time menggunakan fungsi `disk_free_space` & `disk_total_space`.
- Mendeteksi penggunaan memori (RAM) fisik server. Mendukung pembacaan Windows OS menggunakan perintah `wmic` dan Linux OS dengan mem-parsing berkas `/proc/meminfo`.
- Menampilkan informasi sistem operasi, versi PHP, versi Laravel, dan alamat IP server secara dinamis pada dasbor backoffice admin.

### 8.3. Widget Dasbor Kustom Lainnya

- **`TrafficChartWidget`**: Menyajikan grafik garis (line chart) interaktif 7 hari terakhir yang menghitung statistik kunjungan harian unik warga (mengecualikan search engine bot).
- **`RecentSubmissionsWidget`**: Menampilkan tabel 5 permohonan pengajuan surat terbaru dari warga secara langsung di dasbor backoffice dengan badge warna-warni dinamis (Pending, Proses, Selesai, Ditolak).

---

## 9. Autentikasi & Otorisasi

### 9.1. Dua Sistem Autentikasi

| Sistem | Guard | User Model | Penyimpanan |
|--------|-------|------------|-------------|
| API (Sanctum) | `auth:sanctum` | `Penduduk` + `Administrator` | `personal_access_tokens` |
| Web (Session) | `auth:penduduk` | `Penduduk` | `sessions` |

### 9.2. Level Otorisasi

| Level | Middleware | Keterangan |
|-------|-----------|------------|
| Publik | Tanpa auth | Informasi publik, statistik, verifikasi |
| Terotentikasi | `auth:sanctum` | Logout, profil (warga & admin) |
| Warga | `auth:sanctum`, `ability:warga` | Pengajuan surat, mutasi, bind Telegram |
| Admin | `auth:sanctum`, `abilities:admin` | Verifikasi, manajemen konten & statistik |

---

## 10. Komponen Pendukung Lainnya

### 10.1. Filament Resources

Panel admin Filament menyediakan antarmuka manajemen untuk:
- Data Penduduk & Keluarga
- Pengajuan Surat & Mutasi Penduduk
- Informasi Publik (Berita)
- Kategori Surat
- Pengaturan Gampong & Frontend
- Bot Knowledge (Knowledge Base Chatbot)
- Audit Logs & Traffic Logs

### 10.2. Jobs & Queue

| Job | Fungsi |
|-----|--------|
| `GenerateSuratPdfJob` | Membuat PDF surat dari template Blade |
| `SendTelegramDocumentJob` | Mengirim PDF surat ke Telegram warga |
| `SendNewsTelegramNotificationJob` | Mengirim notifikasi berita baru ke Telegram |

### 10.3. Services

| Service | Fungsi |
|---------|--------|
| `TelegramService` | Interaksi dengan Telegram Bot API |
| `StatistikService` | Perhitungan & caching statistik |
| `FallbackAiService` | Manajemen multi-AI provider dengan failover |
