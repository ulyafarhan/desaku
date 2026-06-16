# Dokumentasi Backend - SIG-Udeung

Dokumentasi ini merinci struktur arsitektur, spesifikasi teknis, serta panduan pengujian untuk sisi server (backend) SIG-Udeung.

---

## 1. Tumpukan Teknologi Backend
* **Runtime**: PHP 8.3+ (Strictly Typed)
* **Framework**: Laravel 13.x (API Engine & Antrean Job)
* **Admin Backoffice**: Filament PHP v5.6.5
* **Cache & Antrean**: Redis Server
* **Document Processor**: Barryvdh DomPDF (`^3.0`) & Simple QrCode (`^4.2`)

---

## 2. Struktur Direktori Utama
* `app/Http/Controllers/Api/`: Controller yang menangani request/response 29 endpoint API.
* `app/Services/`: Lapisan logika bisnis terisolasi (TelegramService, GeminiProvider, StatistikService).
* `app/Jobs/`: Eksekusi tugas asinkronus (Pembuatan PDF Surat & Broadcast Telegram).
* `app/Models/`: Definisi entitas model Eloquent beserta relasi database.

---

## 3. Panduan Instalasi Lokal
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
   * Server: `php artisan serve`
   * Queue Worker: `php artisan queue:work`

---

## 4. Pengujian & Penjaminan Kualitas (QA)
Sistem backend dilengkapi dengan 52 berkas pengujian otomatis berbasis PHPUnit 12:
* **Unit Testing**: Memvalidasi fungsi helper, generator hash, dan integrasi provider AI.
* **Feature Testing**: Memvalidasi siklus hidup otentikasi, alur persetujuan surat, dan audit log.

### Cara Menjalankan Tes:
```bash
composer run test
```

### Optimasi Query (Bebas N+1 Query)
* Semua model utama (`Penduduk`, `PengajuanSurat`, `Keluarga`) menggunakan eager loading global (`$with`) untuk relasi yang sering diakses.
* Fungsi query Filament admin mengimplementasikan modifikasi `getEloquentQuery` dengan eager load relasi guna memastikan performa database tetap optimal dan bebas dari overhead N+1 query.

---

## 5. Optimasi Performa & Caching AI (Exact & Semantic Caching)
Sistem backend SIG-Udeung mengimplementasikan hibrida **Exact Match Cache** dan **Semantic Cache** pada `OpenAiProvider` dan `GeminiProvider` untuk menghemat penggunaan token API AI dan mempercepat respons chatbot (<2ms jika hit cache).

### 5.1. Alur Kerja Caching AI:
1. **Normalisasi**: Pesan masuk dipangkas spasi kosongnya dan diubah ke huruf kecil (lowercase).
2. **Exact Match Cache**:
   * Sistem memeriksa cache Redis dengan key `ai_exact_[md5(pesan_normal)]`.
   * Jika tidak ada di Redis, sistem mencari kecocokan eksak pada log 24 jam terakhir di tabel `chatbot_logs`.
   * Jika ditemukan, jawaban di-cache ke Redis selama 24 jam dan langsung dikembalikan ke pengguna.
3. **Semantic Cache**:
   * Jika exact match tidak ditemukan, sistem memuat 100 log interaksi unik terakhir dalam 48 jam terakhir dari cache Redis `ai_recent_logs_semantic` (durasi simpan 5 menit).
   * **Tokenisasi & Stopwords**: Pesan masuk dan pesan log dipecah menjadi token kata setelah membuang karakter non-alfanumerik serta kata hubung umum bahasa Indonesia (*stopwords* seperti: *yang, di, dan, itu, dengan, untuk, pada, ke, dari, ini, adalah, akan, atau, saya, anda, kami, kita*).
   * **Jaccard Similarity**: Mengukur rasio irisan token dibanding gabungan token kata unik.
   * **Levenshtein Distance**: Untuk pesan pendek (<20 karakter), jarak Levenshtein dihitung untuk mengukur kedekatan edit teks.
   * **Threshold & Output**: Jika skor kemiripan tertinggi mencapai **>= 80% (0.80)**, sistem mengembalikan balasan ter-cache, menyimpan relasi pencarian baru ke Redis, dan mencatat log dengan penggunaan token `0` (*zero cost*).
   * Jika di bawah 80%, request baru akan diteruskan ke API Provider AI (OpenAI/Gemini).

---

## 6. Fitur Pemantauan Server & Lalu Lintas (Traffic)

Untuk membantu administrator gampong dalam memantau kesehatan server secara real-time dan melacak aktivitas kunjungan warga, backend SIG-Udeung dilengkapi dengan sistem pemantauan terintegrasi:

### 6.1. Middleware Pemantau Lalu Lintas (`TrackTraffic`)
* Terdaftar secara global dalam grup middleware `web` di `bootstrap/app.php`.
* Memfilter request internal admin (`/admin*`), request AJAX Livewire (`/livewire*`), dan request telemetri agar data lalu lintas murni berasal dari warga/publik.
* Menganalisis *User Agent* secara dinamis untuk mengidentifikasi apakah request berasal dari bot/crawler mesin pencari.
* Menyimpan log kunjungan ke dalam tabel `traffic_logs` (IP address, user agent, URL path, HTTP method, referer, dan status bot).

### 6.2. Widget Pemantau Performa Server (`ServerPerformanceWidget`)
* Membaca kapasitas ruang penyimpanan (disk space) secara real-time menggunakan fungsi `disk_free_space` & `disk_total_space`.
* Mendeteksi penggunaan memori (RAM) fisik server. Mendukung pembacaan Windows OS menggunakan perintah `wmic` dan Linux OS dengan mem-parsing berkas `/proc/meminfo`.
* Menampilkan informasi sistem operasi, versi PHP, versi Laravel, dan alamat IP server secara dinamis pada dasbor backoffice admin.

### 6.3. Widget Dasbor Kustom Lainnya
* **`TrafficChartWidget`**: Menyajikan grafik garis (line chart) interaktif 7 hari terakhir yang menghitung statistik kunjungan harian unik warga (mengecualikan search engine bot).
* **`RecentSubmissionsWidget`**: Menampilkan tabel 5 permohonan pengajuan surat terbaru dari warga secara langsung di dasbor backoffice dengan badge warna-warni dinamis (Pending, Proses, Selesai, Ditolak).
