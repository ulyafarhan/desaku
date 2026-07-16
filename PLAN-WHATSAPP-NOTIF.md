# PLAN: WhatsApp Notification — SIG-Udeung

## Overview
Ganti (atau tambah) notifikasi Telegram ke WhatsApp.
Gunakan **OpenWA** — self-hosted WhatsApp API Gateway open-source (MIT) — di VPS terpisah.

## Alasan
Dosen: warga Indonesia khususnya pedesaan lebih familiar WhatsApp daripada Telegram.

## Stack
- PHP ^8.3
- Laravel Framework ^13.8
- Filament 5.6.5 (Schema API)
- Inertia.js Laravel ^3.1 + Inertia Vue ^3.4.0
- Vue ^3.5.38
- Tailwind CSS ^4.3.2 + Vite ^8.0.16
- MySQL + Redis
- OpenWA (Docker, Node.js 22 LTS, NestJS 11, Baileys)
- PHPUnit ^12.5

## Arsitektur

```
Laravel App (t3a.medium)           OpenWA (t3a.micro)
gampong.web.id                      Docker Compose
     |                                    |
     |--- POST /api/sessions/{id}/ --->   |  kirim pesan ke warga
     |    messages/send-text              |
     |<-- webhook POST ---------------    |  terima pesan dari warga
     |    /api/v1/whatsapp/webhook        |
     |                                    |
     WhatsAppService.php             Baileys Engine
     (app/Services/)                 (no Chromium, ringan)
```

Keamanan: Security Group t3a.micro buka port 2785 **hanya untuk** IP t3a.medium.

---

## Phase 1: Setup OpenWA di t3a.micro

### 1.1 Launch EC2 t3a.micro

- AMI: Ubuntu 24.04 LTS
- Type: t3a.micro (1 vCPU, 1GB RAM) — ~$8.5/bln
- Storage: 10GB gp3
- Security Group:
  - Port 22 (SSH): IP kamu saja
  - Port 2785 (OpenWA API): IP private t3a.medium (gampong.web.id)
- SSH key pair

### 1.2 Install Docker

```bash
sudo apt update && sudo apt upgrade -y
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker ubuntu
# Logout lalu login lagi (atau newgrp docker)
```

### 1.3 Deploy OpenWA

```bash
git clone https://github.com/rmyndharis/OpenWA.git
cd OpenWA
cp .env.example .env
# Edit .env set API_KEY jika perlu
docker compose -f docker-compose.dev.yml up -d
```

Cek jalan:
```bash
curl http://localhost:2785/api/health
```

### 1.4 Setup Dashboard & Session

1. Buka `http://<PUBLIC_IP>:2785` di browser
2. Buat akun admin
3. Generate API key
4. Create session baru (nama: `sig-udeung`)
5. Scan QR code dengan nomor WhatsApp gateway
6. Test kirim pesan dari dashboard ke nomor kamu

### 1.5 Test API dari server Laravel (t3a.medium)

```bash
curl -X POST http://<OPENWA_PRIVATE_IP>:2785/api/sessions/sig-udeung/messages/send-text \
  -H "Content-Type: application/json" \
  -H "X-API-Key: <API_KEY>" \
  -d '{"chatId": "62812xxxx@c.us", "text": "Test dari SIG-Udeung!"}'
```

---

## Phase 2: Konfigurasi Laravel

### 2.1 Tambah Config

Buka `config/services.php`, tambah setelah array `'telegram'`:

```php
'whatsapp' => [
    'gateway_url' => env('WHA_GATEWAY_URL', 'http://localhost:2785'),
    'api_key'     => env('WHA_API_KEY'),
    'session_id'  => env('WHA_SESSION_ID', 'sig-udeung'),
    'default_target' => env('WHA_DEFAULT_TARGET'),
],
```

### 2.2 Tambah Environment Variables

Di `.env`:

```
WHA_GATEWAY_URL=http://<OPENWA_PRIVATE_IP>:2785
WHA_API_KEY=xxx_isi_dari_openwa_dashboard_xxx
WHA_SESSION_ID=sig-udeung
WHA_DEFAULT_TARGET=62812xxxx@c.us
```

### 2.3 Migration: Tambah no_hp ke Penduduk

Buat migration:

```bash
php artisan make:migration add_no_hp_to_penduduk_table --table=penduduk
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->string('no_hp', 20)->nullable()->after('telegram_chat_id');
        });
    }

    public function down(): void
    {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->dropColumn('no_hp');
        });
    }
};
```

### 2.4 Update Model Penduduk

Buka `app/Models/Penduduk.php`, tambah `'no_hp'` ke `$fillable` array.

---

## Phase 3: WhatsAppService

Buat: `app/Services/WhatsAppService.php`
Ikuti pattern yang sama persis dengan `TelegramService.php`.

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $gatewayUrl;
    protected string $apiKey;
    protected string $sessionId;

    public function __construct()
    {
        $this->gatewayUrl = rtrim(config('services.whatsapp.gateway_url', ''), '/');
        $this->apiKey = config('services.whatsapp.api_key', '');
        $this->sessionId = config('services.whatsapp.session_id', 'sig-udeung');
    }

    public function sendMessage(string $target, string $message): bool
    {
        try {
            if (empty($this->apiKey) || empty($this->gatewayUrl)) {
                Log::warning('WhatsApp gateway not configured.');
                return false;
            }

            if (!str_contains($target, '@')) {
                $target = preg_replace('/[^0-9]/', '', $target);
                $target = $target . '@c.us';
            }

            $response = Http::timeout(15)
                ->connectTimeout(10)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post("{$this->gatewayUrl}/api/sessions/{$this->sessionId}/messages/send-text", [
                    'chatId' => $target,
                    'text' => $message,
                ]);

            if (!$response->successful()) {
                Log::error('WhatsApp send failed: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp send error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendImage(string $target, string $imageUrl, string $caption): bool
    {
        try {
            if (empty($this->apiKey) || empty($this->gatewayUrl)) {
                return false;
            }

            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post("{$this->gatewayUrl}/api/sessions/{$this->sessionId}/messages/send-image", [
                    'chatId' => $target,
                    'imageUrl' => $imageUrl,
                    'caption' => $caption,
                ]);

            if (!$response->successful()) {
                Log::error('WhatsApp send image failed: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp send image error: ' . $e->getMessage());
            return false;
        }
    }

    public function broadcast(array $targets, string $message): array
    {
        $results = ['success' => 0, 'failed' => 0];
        foreach ($targets as $target) {
            if ($this->sendMessage($target, $message)) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
            usleep(100000); // 100ms delay antar kirim
        }
        return $results;
    }
}
```

---

## Phase 4: Jobs

### 4.1 Job: Notifikasi Berita Baru

Buat: `app/Jobs/SendNewsWhatsappNotificationJob.php`

```php
<?php

namespace App\Jobs;

use App\Models\InformasiPublik;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendNewsWhatsappNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $informasiId
    ) {}

    public function handle(WhatsAppService $whatsapp): void
    {
        $informasi = InformasiPublik::find($this->informasiId);
        if (!$informasi || !$informasi->is_published) {
            return;
        }

        $target = config('services.whatsapp.default_target');
        if (empty($target)) {
            Log::warning('WhatsApp default target not configured.');
            return;
        }

        $baseUrl = config('app.url');
        $articleUrl = rtrim($baseUrl, '/') . '/informasi/' . $informasi->slug;

        $rawContent = strip_tags($informasi->konten);
        $summary = Str::limit(trim(preg_replace('/\s+/', ' ', $rawContent)), 200, '...');

        $message = "*BERITA & PENGUMUMAN GAMPONG*\n\n";
        $message .= "*" . $informasi->judul . "*\n";
        $message .= "Kategori: #" . str_replace(' ', '', $informasi->kategori) . "\n\n";
        $message .= "\"" . $summary . "\"\n\n";
        $message .= "Baca selengkapnya: " . $articleUrl;

        $whatsapp->sendMessage($target, $message);
    }
}
```

### 4.2 Job: Tracking Surat ke Warga

Buat: `app/Jobs/SendStatusWhatsappJob.php`

Kirim notifikasi ke nomor HP warga saat status pengajuan surat berubah.

```php
<?php

namespace App\Jobs;

use App\Models\Penduduk;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendStatusWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $nik,
        public string $jenis,
        public string $status,
        public string $nomorRegistrasi,
        public ?string $catatan = null
    ) {}

    public function handle(WhatsAppService $whatsapp): void
    {
        $penduduk = Penduduk::find($this->nik);
        if (!$penduduk || empty($penduduk->no_hp)) {
            Log::warning("Warga NIK {$this->nik} tidak punya no_hp terdaftar.");
            return;
        }

        $statusLabels = [
            'Pending'   => 'Menunggu Verifikasi',
            'Diproses'  => 'Sedang Diproses',
            'Disetujui' => 'Disetujui',
            'Ditolak'   => 'Ditolak',
            'Selesai'   => 'Selesai, Siap Diunduh',
        ];

        $message = "*STATUS PENGAJUAN SURAT*\n\n";
        $message .= "Jenis: " . $this->jenis . "\n";
        $message .= "Nomor: " . $this->nomorRegistrasi . "\n";
        $message .= "Status: " . ($statusLabels[$this->status] ?? $this->status) . "\n\n";

        if ($this->catatan) {
            $message .= "Catatan:\n" . $this->catatan . "\n\n";
        }

        $message .= config('app.url');

        $whatsapp->sendMessage($penduduk->no_hp, $message);
    }
}
```

---

## Phase 5: Integrasi dengan Model

### 5.1 Update InformasiPublik

Buka `app/Models/InformasiPublik.php`, tambahkan:

```php
use App\Jobs\SendNewsWhatsappNotificationJob;
```

Di method `boot()`, di dalam event `saved()`, TAMBAHKAN dispatch WA di samping Telegram:

```php
static::saved(function ($model) {
    if ($model->wasRecentlyCreated && $model->is_published) {
        SendNewsTelegramNotificationJob::dispatch($model->id);
        SendNewsWhatsappNotificationJob::dispatch($model->id);
        return;
    }

    if (!$model->wasRecentlyCreated && $model->is_published && !$model->getOriginal('is_published')) {
        SendNewsTelegramNotificationJob::dispatch($model->id);
        SendNewsWhatsappNotificationJob::dispatch($model->id);
    }
});
```

### 5.2 Update PengajuanSurat (Controller)

Buka controller yang menangani approve/reject surat (contoh: `PengajuanSuratController`).
Setelah update status, tambahkan dispatch ke WA:

```php
use App\Jobs\SendStatusWhatsappJob;

// Di method approve/reject:
if ($pengajuan->penduduk && !empty($pengajuan->penduduk->no_hp)) {
    SendStatusWhatsappJob::dispatch(
        $pengajuan->penduduk->nik,
        $pengajuan->kategori?->nama_surat ?? 'Surat',
        $pengajuan->status,
        $pengajuan->nomor_registrasi ?? '-',
        $request->catatan ?? null
    );
}
```

---

## Phase 6: Webhook (Opsional — untuk incoming message)

Digunakan jika nanti warga bisa WA-an ke bot.

### 6.1 Webhook Controller

Buat: `app/Http/Controllers/Api/WhatsAppWebhookController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('WhatsApp Webhook Received', $request->all());

        if ($message = $request->input('data.message')) {
            $from = $message['from'] ?? '';
            $text = $message['text']['body'] ?? '';
            // Proses auto-reply sederhana
        }

        return response()->json(['ok' => true]);
    }
}
```

### 6.2 Route

Buka `routes/api.php`, tambah di grup public `v1`:

```php
Route::post('/whatsapp/webhook', [WhatsAppWebhookController::class, 'handle'])->middleware('throttle:60,1');
```

### 6.3 Setup Webhook di OpenWA

```bash
curl -X POST http://<OPENWA_PRIVATE_IP>:2785/api/sessions/sig-udeung/webhooks \
  -H "Content-Type: application/json" \
  -H "X-API-Key: <API_KEY>" \
  -d '{
    "url": "https://gampong.web.id/api/v1/whatsapp/webhook",
    "events": ["message.received", "session.status"],
    "secret": "rahasia123"
  }'
```

---

## Phase 7: Testing

### 7.1 Test Kirim Manual via Tinker

```bash
cd /www/wwwroot/gampong.web.id
php artisan tinker
```

```php
$wa = app(App\Services\WhatsAppService::class);
$wa->sendMessage('62812xxxx@c.us', '*Test dari SIG-Udeung*');
```

### 7.2 Test Job Berita Baru

```php
App\Jobs\SendNewsWhatsappNotificationJob::dispatch('01kxk9bkm6nbdw88g9j57jfsqn');
```

(Ganti ID dengan salah satu ULID informasi dari database)

### 7.3 Test Job Tracking Surat

```php
App\Jobs\SendStatusWhatsappJob::dispatch(
    'nik_warga_disini',
    'Surat Keterangan Tidak Mampu',
    'Disetujui',
    'REG-202507-001',
    'Silakan ambil surat di kantor desa.'
);
```

### 7.4 Cek Queue

```bash
php artisan queue:work redis --stop-when-empty --tries=3
# atau via Supervisor
```

---

## 8. Ringkasan File

| No | File | Action |
|----|------|--------|
| 1 | `config/services.php` | EDIT (tambah config whatsapp) |
| 2 | `.env` | EDIT (tambah WHA_* vars) |
| 3 | `database/migrations/xxxx_add_no_hp_to_penduduk_table.php` | BUAT |
| 4 | `app/Models/Penduduk.php` | EDIT (tambah no_hp ke fillable) |
| 5 | `app/Services/WhatsAppService.php` | BUAT |
| 6 | `app/Jobs/SendNewsWhatsappNotificationJob.php` | BUAT |
| 7 | `app/Jobs/SendStatusWhatsappJob.php` | BUAT |
| 8 | `app/Models/InformasiPublik.php` | EDIT (tambah dispatch WA) |
| 9 | `app/Http/Controllers/Api/WhatsAppWebhookController.php` | BUAT (opsional) |
| 10 | `routes/api.php` | EDIT (tambah route webhook) |

---

## 9. Catatan Penting

- **Nomor WhatsApp cadangan**: jangan pakai nomor pribadi, bisa kena ban
- **Risk**: OpenWA pakai Baileys (unofficial), ada risiko nomor diblokir Meta
- **Mitigasi**: jangan spam, kirim hanya notifikasi penting (1-2x/hari)
- **Queue WAJIB aktif**: job harus diproses via `queue:work` agar tidak blocking
- **Telegram tetap jalan**: WA ditambahkan di samping Telegram, bukan mengganti
