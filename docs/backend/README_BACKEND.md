# SIG-Udeung Backend Documentation

Backend API untuk Sistem Informasi Gampong (SIG) Udeung menggunakan Laravel 13.

## 📋 Daftar Isi

- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Database](#database)
- [API Endpoints](#api-endpoints)
- [Services](#services)
- [Queue Jobs](#queue-jobs)
- [Testing](#testing)

## 🚀 Instalasi

### Prerequisites

- PHP 8.3+
- PostgreSQL 15+
- Redis
- Composer
- Node.js & NPM

### Langkah Instalasi

```bash
# Clone repository
git clone <repository-url>
cd desaku

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Setup database
php artisan migrate --seed

# Install Laravel Sanctum
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Install PDF Generator (DomPDF)
composer require barryvdh/laravel-dompdf

# Install QR Code Generator
composer require simplesoftwareio/simple-qrcode

# Create storage link
php artisan storage:link

# Start development server
php artisan serve
```

## ⚙️ Konfigurasi

### Environment Variables

Edit file `.env` dan sesuaikan konfigurasi:

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sig_udeung
DB_USERNAME=postgres
DB_PASSWORD=your_password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Telegram Bot
TELEGRAM_BOT_TOKEN=your_bot_token_from_botfather
TELEGRAM_WEBHOOK_URL=https://udeung.desa.id/api/v1/telegram/webhook

# Gemini AI
GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemini-pro
```

### Setup Telegram Webhook

```bash
php artisan tinker

# Di dalam tinker
$telegram = app(\App\Services\TelegramService::class);
$telegram->setWebhook(config('services.telegram.webhook_url'));
```

## 🗄️ Database

### Struktur Database

Database menggunakan PostgreSQL dengan 14 tabel utama:

1. **administrators** - Data admin (Keuchik, Sekdes, Operator)
2. **penduduk** - Data kependudukan
3. **keluarga** - Data keluarga (KK)
4. **mutasi_penduduk** - Mutasi kependudukan
5. **kategori_surat** - Jenis-jenis surat
6. **pengajuan_surat** - Pengajuan surat warga
7. **tracking_pengajuan_surat** - History status surat
8. **informasi_publik** - Berita & informasi gampong
9. **pengaturan_gampong** - Konfigurasi sistem
10. **referensi_wilayah** - Data wilayah Indonesia
11. **telegram_broadcast_queue** - Antrian broadcast
12. **chatbot_logs** - Log percakapan AI
13. **audit_logs** - Audit trail sistem

### Migrasi & Seeding

```bash
# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Atau sekaligus
php artisan migrate:fresh --seed
```

### Default Admin Accounts

Setelah seeding, tersedia 3 akun admin:

| Username | Password | Role |
|----------|----------|------|
| keuchik | password123 | keuchik |
| sekdes | password123 | sekdes |
| operator | password123 | operator |

## 🔌 API Endpoints

### Authentication

#### Login Warga (NIK)
```http
POST /api/v1/auth/login/warga
Content-Type: application/json

{
  "nik": "1234567890123456"
}
```

#### Login Admin
```http
POST /api/v1/auth/login/admin
Content-Type: application/json

{
  "username": "operator",
  "password": "password123"
}
```

#### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

#### Get Profile
```http
GET /api/v1/auth/profile
Authorization: Bearer {token}
```

#### Bind Telegram
```http
POST /api/v1/auth/bind-telegram
Authorization: Bearer {token}
Content-Type: application/json

{
  "telegram_chat_id": "123456789"
}
```

### Pengajuan Surat

#### Get Kategori Surat
```http
GET /api/v1/surat/kategori
Authorization: Bearer {token}
```

#### Submit Pengajuan Surat
```http
POST /api/v1/surat/pengajuan
Authorization: Bearer {token}
Content-Type: application/json

{
  "kategori_surat_id": 1,
  "data_isian": {
    "keperluan": "Melamar pekerjaan",
    "lama_tinggal": 10
  },
  "file_syarat": {
    "ktp": "/storage/uploads/ktp.jpg",
    "kk": "/storage/uploads/kk.jpg"
  }
}
```

#### Get Pengajuan Surat User
```http
GET /api/v1/surat/pengajuan
Authorization: Bearer {token}
```

#### Admin: Approve Pengajuan
```http
POST /api/v1/admin/surat/pengajuan/{id}/approve
Authorization: Bearer {admin_token}
```

#### Admin: Reject Pengajuan
```http
POST /api/v1/admin/surat/pengajuan/{id}/reject
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "catatan_penolakan": "Dokumen tidak lengkap"
}
```

### Mutasi Penduduk

#### Submit Mutasi
```http
POST /api/v1/mutasi
Authorization: Bearer {token}
Content-Type: application/json

{
  "nik": "1234567890123456",
  "jenis_mutasi": "Kelahiran",
  "tanggal_mutasi": "2024-01-15",
  "keterangan": "Kelahiran anak pertama",
  "dokumen_bukti": "/storage/uploads/akta.jpg"
}
```

#### Get Mutasi User
```http
GET /api/v1/mutasi
Authorization: Bearer {token}
```

#### Admin: Approve Mutasi
```http
POST /api/v1/admin/mutasi/{id}/approve
Authorization: Bearer {admin_token}
```

### Informasi Publik

#### Get Informasi (Public)
```http
GET /api/v1/informasi?kategori=berita
```

#### Get Detail Informasi
```http
GET /api/v1/informasi/{slug}
```

#### Admin: Create Informasi
```http
POST /api/v1/admin/informasi
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "judul": "Judul Berita",
  "konten": "Isi berita...",
  "kategori": "berita",
  "is_published": true
}
```

### Statistik

#### Get Statistik Demografi (Public)
```http
GET /api/v1/statistik/demografi
```

#### Get Statistik Layanan (Public)
```http
GET /api/v1/statistik/layanan
```

### Verifikasi QR Code

#### Verify Document
```http
GET /api/v1/verifikasi/{hash}
```

Response jika valid:
```json
{
  "valid": true,
  "message": "Dokumen valid",
  "data": {
    "nomor_registrasi": "20240115-0001",
    "jenis_surat": "Surat Keterangan Domisili",
    "nama_pemohon": "John Doe",
    "nik_pemohon": "1234567890123456",
    "tanggal_terbit": "15-01-2024",
    "diverifikasi_oleh": "operator"
  }
}
```

## 🛠️ Services

### TelegramService

Service untuk integrasi Telegram Bot API.

```php
use App\Services\TelegramService;

$telegram = app(TelegramService::class);

// Kirim pesan
$telegram->sendMessage($chatId, 'Hello World!');

// Kirim dokumen
$telegram->sendDocument($chatId, $filePath, 'Caption');

// Broadcast ke banyak user
$telegram->broadcast($chatIds, 'Pesan broadcast');

// Notifikasi status pengajuan
$telegram->notifyPengajuanStatus($nik, 'Disetujui', $nomorRegistrasi);
```

### GeminiAiService

Service untuk integrasi Gemini AI chatbot.

```php
use App\Services\GeminiAiService;

$gemini = app(GeminiAiService::class);

// Generate response
$response = $gemini->generateResponse($userMessage, $chatId);

// Check health
$isHealthy = $gemini->checkHealth();
```

### PdfGeneratorService

Service untuk generate PDF surat dengan QR Code TTE.

```php
use App\Services\PdfGeneratorService;

$pdfService = app(PdfGeneratorService::class);

// Generate PDF
$pdfUrl = $pdfService->generateSuratPdf($pengajuan);
```

### StatistikService

Service untuk statistik real-time.

```php
use App\Services\StatistikService;

$statistik = app(StatistikService::class);

// Get demografi
$demografi = $statistik->getDemografi();

// Get layanan
$layanan = $statistik->getLayanan();

// Clear cache
$statistik->clearCache();
```

## ⚡ Queue Jobs

### Setup Queue Worker

```bash
# Development
php artisan queue:work

# Production (dengan supervisor)
php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
```

### Available Jobs

#### GenerateSuratPdfJob

Job untuk generate PDF surat secara asinkron.

```php
use App\Jobs\GenerateSuratPdfJob;

GenerateSuratPdfJob::dispatch($pengajuan);
```

#### ProcessTelegramBroadcastJob

Job untuk broadcast pesan Telegram.

```php
use App\Jobs\ProcessTelegramBroadcastJob;

ProcessTelegramBroadcastJob::dispatch($broadcast);
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AuthTest

# With coverage
php artisan test --coverage
```

## 📦 Additional Packages Required

Tambahkan package berikut ke `composer.json`:

```bash
# Laravel Sanctum (API Authentication)
composer require laravel/sanctum

# DomPDF (PDF Generator)
composer require barryvdh/laravel-dompdf

# QR Code Generator
composer require simplesoftwareio/simple-qrcode

# HTTP Client (sudah included di Laravel 13)
# Guzzle HTTP
```

## 🔐 Security

### API Rate Limiting

API menggunakan rate limiting default Laravel:
- 60 requests per minute untuk authenticated users
- 10 requests per minute untuk guest users

### CORS Configuration

Edit `config/cors.php` untuk production:

```php
'paths' => ['api/*'],
'allowed_origins' => ['https://udeung.desa.id'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

### SSL/TLS

Pastikan menggunakan HTTPS di production dengan SSL certificate dari Let's Encrypt atau Cloudflare.

## 📝 Audit Logging

Semua aksi penting dicatat di tabel `audit_logs`:

```php
use App\Models\AuditLog;

AuditLog::log(
    userType: 'admin',
    userId: $admin->id,
    tindakan: 'approve',
    namaTabel: 'pengajuan_surat',
    recordId: $pengajuan->id
);
```

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate production key: `php artisan key:generate`
- [ ] Optimize: `php artisan optimize`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Setup queue worker dengan Supervisor
- [ ] Setup scheduled tasks dengan Cron
- [ ] Configure Redis untuk cache & queue
- [ ] Setup SSL certificate
- [ ] Configure firewall
- [ ] Setup backup database

### Supervisor Configuration

```ini
[program:sig-udeung-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
stopwaitsecs=3600
```

## 📞 Support

Untuk pertanyaan atau issue, hubungi tim pengembang SIG-Udeung.

---

**Version:** 1.0.0  
**Last Updated:** 2024-01-01
