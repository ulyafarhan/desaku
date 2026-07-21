# SIG-Udeung

**Sistem Informasi Gampong Udeung** — Platform digitalisasi administrasi gampong (desa) yang mencakup portal warga, panel administrasi, notifikasi WhatsApp/Telegram, chatbot AI, dan manajemen fasilitas desa.

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 13.8 (PHP 8.3+) |
| Admin Panel | Filament 5.6.5 |
| Frontend | Vue 3 + Inertia.js 3 |
| CSS | Tailwind CSS 4 |
| Build Tool | Vite 8 |
| Database | MySQL |
| Cache & Session | Database / Redis |
| PDF Generator | DomPDF 3 |
| QR Code | SimpleSoftware QRCode |
| API Docs | Scribe 5 |
| WhatsApp Gateway | Node.js + Baileys 6.7 (microservice terpisah) |
| AI Chatbot | Google Gemini / OpenAI-compatible (DeepSeek, dll) |
| Telegram Bot | Telegram Bot API |

## Quick Start

### Prerequisites

- PHP 8.3+ (dengan extensions: mbstring, xml, curl, gd, zip)
- Composer 2
- Node.js 20+
- MySQL 8+
- (Opsional) Redis untuk cache/session

### Setup

```bash
# Clone repository
git clone https://github.com/your-org/desaku.git
cd desaku

# Install dependencies & setup (satu command)
composer setup
```

Atau manual:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

### Konfigurasi

1. Copy `.env.example` ke `.env` dan isi konfigurasi database, API keys, dll.
2. Untuk WhatsApp Gateway, jalankan service terpisah di `wa-gateway/`:

```bash
cd wa-gateway
npm install
npm start
```

3. Untuk development dengan hot-reload:

```bash
composer dev
```

Ini menjalankan 3 proses secara bersamaan: Laravel server, Queue worker, dan Vite dev server.

## Project Structure

```
desaku/
├── app/
│   ├── Filament/          # Admin panel: Resources, Pages, Widgets, Auth
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/       # Portal warga (citizen dashboard, surat, profil)
│   │   │   ├── Admin/     # API proxy untuk notification settings
│   │   │   └── Api/       # REST API endpoints
│   │   └── Middleware/
│   ├── Jobs/              # Queue jobs (notifikasi, proses async)
│   ├── Models/            # Eloquent models (Penduduk, Keluarga, Surat, dll)
│   └── Services/          # Business logic: WhatsApp, Telegram, AI, PDF, Statistik
├── config/
│   └── services.php       # Konfigurasi WhatsApp, Gemini, OpenAI, dll
├── database/
│   ├── migrations/
│   └── seeders/
├── docs/                  # Dokumentasi proyek (PRD, ERD, API, Frontend, Backend)
├── resources/
│   └── js/                # Vue 3 components (Inertia pages)
├── routes/
│   ├── web.php            # Rute portal warga & admin
│   └── api.php            # REST API routes
├── tests/                 # PHPUnit tests
├── wa-gateway/            # WhatsApp Gateway microservice (Node.js + Baileys)
│   ├── server.mjs         # Entry point
│   └── src/
│       ├── behavior/      # Anti-ban behavior engine
│       ├── session.js     # Koneksi WhatsApp
│       └── webhook.js     # Webhook delivery processor
└── vite.config.js
```

## WhatsApp Gateway

Microservice Node.js terpisah yang menjembatani Laravel dengan WhatsApp via Baileys. Fitur:

- **Multi-session**: beberapa akun WhatsApp aktif bersamaan
- **Behavior Engine**: anti-ban dengan randomisasi delay, typing simulation, dan daily limits
- **Auto-reconnect**: sesi otomatis reconnect saat restart
- **Webhook delivery**: retry mechanism untuk pengiriman notifikasi
- **SQLite**: penyimpanan sesi dan state

Default berjalan di port `2785`.

> Lihat [wa-gateway/README.md](wa-gateway/README.md) untuk dokumentasi lengkap.

## Environment Variables

| Variable | Deskripsi | Default |
|----------|-----------|---------|
| `APP_NAME` | Nama aplikasi | `Desaku` |
| `APP_URL` | URL aplikasi | - |
| `APP_KEY` | Laravel encryption key | (auto-generated) |
| `DB_CONNECTION` | Database driver | `mysql` |
| `DB_HOST` | Database host | `127.0.0.1` |
| `DB_PORT` | Database port | `3306` |
| `DB_DATABASE` | Nama database | `desaku` |
| `DB_USERNAME` | Database username | `root` |
| `DB_PASSWORD` | Database password | - |
| `SESSION_DRIVER` | Driver session | `database` |
| `CACHE_STORE` | Driver cache | `database` |
| `QUEUE_CONNECTION` | Driver queue | `database` |
| `TELEGRAM_BOT_TOKEN` | Token bot Telegram | - |
| `TELEGRAM_WEBHOOK_URL` | URL webhook Telegram | - |
| `TELEGRAM_GROUP_CHAT_ID` | ID grup Telegram | - |
| `GEMINI_API_KEY` | API key Google Gemini | - |
| `GEMINI_MODEL` | Model Gemini | `gemini-flash-lite-latest` |
| `AI_PROVIDER` | Provider AI (`gemini`/`openai`) | `openai` |
| `OPENAI_API_KEY` | API key OpenAI-compatible | - |
| `OPENAI_MODEL` | Model OpenAI-compatible | `deepseek-v4-flash` |
| `OPENAI_BASE_URL` | Base URL OpenAI-compatible | - |
| `WHA_PROVIDER` | Provider WhatsApp (`wa-gateway`/`fonnte`) | `wa-gateway` |
| `WHA_GATEWAY_URL` | URL WhatsApp Gateway | `https://wa.gampong.web.id` |
| `WHA_API_KEY` | API key WhatsApp Gateway | - |
| `WHA_SESSION_ID` | ID sesi WhatsApp | `default` |
| `FONNTE_TOKEN` | Token Fonnte (alternatif) | - |

## Available Commands

| Command | Deskripsi |
|---------|-----------|
| `composer setup` | Setup lengkap: install, migrate, build |
| `composer dev` | Development mode (server + queue + vite concurrently) |
| `composer deploy` | Deployment: cache config/routes/views, migrate, build |
| `composer test` | Clear config cache lalu jalankan PHPUnit |
| `npm run dev` | Vite dev server dengan hot-reload |
| `npm run build` | Build assets untuk production |
| `php artisan serve` | Jalankan Laravel dev server |
| `php artisan queue:listen` | Jalankan queue worker |
| `php artisan migrate` | Jalankan migrasi database |
| `cd wa-gateway && npm start` | Jalankan WhatsApp Gateway |

## Key Features

- **Portal Warga** — beranda, profil gampong, informasi publik, statistik, fasilitas desa, form aspirasi
- **Autentikasi NIK** — warga login menggunakan NIK (Nomor Induk Kependudukan)
- **Dashboard Warga** — profil, data keluarga, pengajuan surat, cetak surat
- **Pengajuan Surat Online** — warga mengajukan berbagai jenis surat, admin memproses
- **Tracking Surat** — warga memantau status pengajuan surat secara real-time
- **Verifikasi QR** — verifikasi keabsahan dokumen melalui QR code
- **Panel Admin (Filament)** — CRUD penduduk, keluarga, kategori surat, pengajuan, fasilitas, informasi publik, audit log, pengaturan gampong, knowledge base chatbot
- **Notifikasi WhatsApp** — pengiriman notifikasi status surat via WhatsApp (WA Gateway atau Fonnte)
- **Notifikasi Telegram** — broadcast info ke grup Telegram
- **Chatbot AI** — asisten virtual berbasis Gemini/OpenAI dengan knowledge base desa
- **PDF Generator** — cetak surat dalam format PDF (DomPDF)
- **Statistik Desa** — visualisasi data kependudukan dan pengajuan surat
- **Audit Log** — pelacakan semua aktivitas admin
- **Anti-Ban WhatsApp** — behavior engine untuk menghindari pemblokiran nomor WhatsApp

## Deployment

Dokumentasi lengkap deployment ada di [docs/deployment.md](docs/deployment.md).

Ringkasan langkah:

1. Setup server (PHP 8.3+, MySQL, Node.js)
2. Clone repo dan jalankan `composer deploy`
3. Konfigurasi `.env` (database, API keys, WhatsApp Gateway URL)
4. Jalankan WhatsApp Gateway sebagai service terpisah
5. Konfigurkan web server (Nginx/Apache) untuk pointing ke `public/`
6. Setup queue worker dan scheduler

## Documentation

Dokumentasi lengkap tersedia di folder [`docs/`](docs/):

- [Product Requirements (PRD)](docs/prd.md)
- [Database ERD](docs/database/erd.md)
- [Database Schema](docs/database/database.md)
- [API Contract](docs/api-contract.yaml)
- [API Documentation](docs/api/api.md)
- [Backend Architecture](docs/backend/backend.md)
- [Frontend Architecture](docs/frontend/frontend.md)
- [Design System](docs/design/google.com-design.md)

## License

MIT
