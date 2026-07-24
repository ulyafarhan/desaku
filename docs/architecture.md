# Arsitektur Sistem SIG-Udeung

Sistem Informasi Gampong untuk **Gampong Udeung, Pidie Jaya, Aceh**.

---

## Ringkasan

```
┌─────────────────────────────────────────────────────────────────────┐
│                       PRODUCTION ENVIRONMENT                        │
│                                                                     │
│  ┌───────────────────────┐            ┌──────────────────────────┐  │
│  │                       │   HTTP     │                          │  │
│  │   LARAVEL APP         │───────────▶│  WA Gateway (OpenWA)     │  │
│  │   (PHP 8.3+)          │◀───────────│  Node.js + Baileys       │  │
│  │                       │  Webhook   │  • Behavior Engine       │  │
│  │  ┌─────────────────┐  │            │  • Multi-session         │  │
│  │  │ Controllers      │  │            │  • Auto-reconnect       │  │
│  │  │  • API (7)       │  │            └──────────────────────────┘  │
│  │  │  • Web (6)       │  │                          │              │
│  │  │  • Admin (panel) │  │            ┌──────────────────────────┐  │
│  │  ├─────────────────┤  │   HTTP     │                          │  │
│  │  │ Services         │  │───────────▶│  Fonnte (SaaS)           │  │
│  │  │  • WhatsApp ─────│──│─ fallback ─│  (fallback otomatis)     │  │
│  │  │  • Telegram      │  │            └──────────────────────────┘  │
│  │  │  • AI Fallback   │  │                                          │
│  │  │  • PDF Generator │  │            ┌──────────────────────────┐  │
│  │  │  • QR Code       │  │   HTTP     │                          │  │
│  │  ├─────────────────┤  │───────────▶│  Telegram Bot API         │  │
│  │  │ Jobs (Queue)     │  │            │  • Notifikasi warga      │  │
│  │  │  • SendStatusWA  │  │            │  • Chatbot AI            │  │
│  │  │  • SendNewsWA    │  │            │  • Broadcast grup        │  │
│  │  │  • SendNewsTG    │  │            └──────────────────────────┘  │
│  │  │  • GeneratePDF   │  │                                          │
│  │  ├─────────────────┤  │            ┌──────────────────────────┐  │
│  │  │ Models (17)      │  │◀──────────│  MySQL Database          │  │
│  │  │  • Penduduk      │  │  Eloquent │  17 tabel + 3 Laravel    │  │
│  │  │  • PengajuanSurat│  │           └──────────────────────────┘  │
│  │  │  • KategoriSurat │  │                                          │
│  │  │  • +14 lainnya   │  │            ┌──────────────────────────┐  │
│  │  └─────────────────┘  │            │  Redis                   │  │
│  │                       │◀───────────│  Queue + Cache + Session │  │
│  └───────────────────────┘  Queue      └──────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 1. Komponen Utama

### 1.1. Laravel Application (Web Server)

Backend monolitik yang menangani seluruh logika bisnis, API, dan rendering halaman.

| Lapisan | Teknologi | Peran |
|---------|-----------|-------|
| Routing | Laravel Router | 29 API endpoint + 20 web route |
| Controller | 13 controller | Memproses request, validasi, respons |
| Service | 6 service class | Logika bisnis terisolasi |
| Job | 5 queue jobs | Tugas asinkron (notifikasi, PDF) |
| Model | 17 Eloquent models | Abstraksi database |
| Filament | Admin panel | CRUD penduduk, surat, pengaturan |

### 1.2. WhatsApp Gateway — Dual Provider

| Provider | Tipe | Kelebihan |
|----------|------|-----------|
| **WA Gateway** (primary) | Self-hosted (Node.js + Baileys) | Gratis, kendali penuh, anti-ban engine |
| **Fonnte** (fallback) | SaaS | Token-only, tanpa VPS, 1000 pesan gratis/bulan |

**Auto-fallback:** Jika primary (`wa-gateway`) gagal terkirim dan token Fonnte tersedia, sistem otomatis mengirim ulang via Fonnte tanpa intervensi manual.

### 1.3. Telegram Bot

| Fungsi | Mekanisme |
|--------|-----------|
| Notifikasi status surat | `TelegramService::notifyPengajuanStatus()` — dikirim ke personal chat warga |
| Notifikasi berita | `SendNewsTelegramNotificationJob` — dikirim ke grup @kabargampongudeung |
| Chatbot AI | `ProcessTelegramMessageJob` → Gemini/OpenAI → knowledge base desa |

### 1.4. Queue & Redis

| Komponen | Driver | Fungsi |
|----------|--------|--------|
| Queue default | `sync` (dev) / `redis` (production) | Notifikasi WhatsApp, PDF, broadcast |
| Cache | `database` / `redis` | Response AI, session |
| Session | `database` / `redis` | Login warga & admin |

> **Production WAJIB** menggunakan queue worker (`php artisan queue:work`) agar notifikasi dan job berat tidak memblokir respons server.

---

## 2. Alur Notifikasi

### 2.1. Status Surat

```
Warga submit pengajuan
  → Telelegram: notifyPengajuanStatus('Pending')    — ke personal chat (jika terdaftar)
  → WhatsApp: SendStatusWhatsappJob::dispatch()     — ke queue → ke WA Gateway / Fonnte

Admin approve (via Filament / API)
  → GenerateSuratPdfJob::dispatchSync()             — generate QR, nomor surat, status 'Selesai'
     → Telegram: sendMessage() + notifyPengajuanStatus('Selesai')
     → WhatsApp: SendStatusWhatsappJob::dispatch()
  → Telegram: notifyPengajuanStatus('Disetujui')    — dari controller

Admin reject (via Filament / API)
  → Telegram: notifyPengajuanStatus('Ditolak')
  → WhatsApp: SendStatusWhatsappJob::dispatch()
```

### 2.2. Berita Baru

```
Admin publish berita
  → InformasiPublik::saved() event
     → SendNewsTelegramNotificationJob::dispatch()  — ke grup Telegram
     → SendNewsWhatsappNotificationJob::dispatch()   — ke semua warga (broadcast)
```

---

## 3. Database

17 tabel relasional + 3 tabel Laravel default. Semua primary key menggunakan ULID kecuali `penduduk` (NIK) dan tabel pivot.

| Tabel | PK | Relasi Utama |
|-------|----|-------------|
| `penduduk` | `nik` (VARCHAR 16) | → keluarga (KK), → pengajuan_surat |
| `keluarga` | `no_kk` (VARCHAR 16) | → penduduk (kepala keluarga) |
| `pengajuan_surat` | `id` (ULID) | → kategori_surat, → penduduk, → tracking |
| `kategori_surat` | `id` (ULID) | Jenis surat + template + body_content |
| `mutasi_penduduk` | `id` (ULID) | → penduduk |
| `pengaturan_gampong` | key-value | Konfigurasi desa + AI |

---

## 4. Keamanan

- Autentikasi: Sanctum token-based (API) + session-based (Web)
- Role: `penduduk` (warga), `administrator` (admin desa)
- Verifikasi dokumen via QR Code + hash SHA-256
- Webhook Telegram/WhatsApp divalidasi dengan secret header
- Rate limiting: 60 req/menit (API publik), 5/jam (aspirasi)

---

## 5. Teknologi

| Komponen | Versi |
|----------|-------|
| PHP | 8.3+ |
| Laravel | 13.8 |
| Filament | 5.6.5 |
| Vue 3 + Inertia | 3.5 + 3.1 |
| MySQL | 8.0+ |
| Redis | 6+ |
| Node.js (WA Gateway) | 20+ |
