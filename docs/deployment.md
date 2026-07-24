# Panduan Deployment — Desaku (SIG-Udeung)

> Sistem Informasi Gampong — Laravel 13 + Filament + Inertia + WA Gateway v4

---

## 1. Persyaratan Server

| Komponen | Versi Minimal | Catatan |
|----------|--------------|---------|
| PHP | 8.3+ (8.4 direkomendasikan) | Ekstensi: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `gmp`, `intl`, `json`, `mbstring`, `openssl`, `pcntl`, `pdo_mysql`, `pdo_sqlite`, `tokenizer`, `xml`, `zip` |
| MySQL | 8.0+ | Atau MariaDB 10.6+ |
| Redis | 6+ | Opsional — untuk cache/session performa tinggi |
| Node.js | 20+ | Build asset frontend (Vite) |
| Composer | 2.x | Dependency manager PHP |
| Web Server | Nginx / OpenLiteSpeed / Apache | rewrite module wajib aktif |
| Supervisor | 4.x | Untuk manajemen queue worker |
| SQLite | 3.x | WA Gateway membutuhkan SQLite untuk log + session |

**Rekomendasi VPS:** 2 CPU, 2 GB RAM, 20 GB SSD (cukup untuk gampong skala 5.000–15.000 jiwa).

---

## 2. Langkah Deploy ke Hosting

### 2.1. Clone Repository

```bash
cd /home/ubuntu
git clone https://github.com/your-org/desaku.git
cd desaku
```

### 2.2. Konfigurasi Environment

```bash
cp .env.example .env
nano .env
```

Setel variabel wajib:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gampong.web.id
APP_KEY=                    # akan di-generate otomatis

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desaku
DB_USERNAME=desaku_user
DB_PASSWORD=*****

WHA_GATEWAY_URL=https://wa.gampong.web.id
WHA_API_KEY=*****
```

### 2.3. Install Dependensi & Build

Gunakan script `setup` bawaan Composer:

```bash
composer setup
```

Atau manual:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
npm install --ignore-scripts
npm run build
```

### 2.4. Cache Optimasi

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 2.5. Set Izin Direktori

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 3. Konfigurasi Nginx / OpenLiteSpeed

### Nginx — Virtual Host

```nginx
server {
    listen 80;
    server_name gampong.web.id www.gampong.web.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name gampong.web.id www.gampong.web.id;

    root /home/ubuntu/desaku/public;
    index index.php;

    ssl_certificate     /etc/letsencrypt/live/gampong.web.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/gampong.web.id/privkey.pem;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml image/svg+xml;

    # Static assets — long cache
    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to sensitive files
    location ~ /\.(env|git|htaccess) {
        deny all;
    }
}
```

### OpenLiteSpeed — Root Rewrite

Jika pakai OpenLiteSpeed, aktifkan rewrite di `public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

Pastikan **Rewrite** diaktifkan di panel LiteSpeed → **Virtual Host → Rewrite**.

---

## 4. WhatsApp Notification

Dua provider WhatsApp didukung. `wa-gateway` sebagai primary, `fonnte` sebagai fallback otomatis.

### 4.1. Konfigurasi `.env`

```env
# Provider: 'wa-gateway' atau 'fonnte'
WHA_PROVIDER=wa-gateway

# ═══ WA Gateway (self-hosted) ═══
WHA_GATEWAY_URL=https://wa.gampong.web.id
WHA_API_KEY=uKGhUfIXZJTl8SzOFNsk1Q2q7tygwr0R
WHA_SESSION_ID=sig-udeung

# ═══ Fonnte (SaaS fallback) ═══
FONNTE_TOKEN=token_fonnte_kamu
```

**Auto-fallback:** Jika `WHA_PROVIDER=wa-gateway` gagal mengirim dan `FONNTE_TOKEN` terisi, sistem otomatis kirim via Fonnte — tanpa perlu ganti `.env`.

### 4.2. WA Gateway (Self-hosted)

Microservice Node.js terpisah dari Laravel.

#### Install & Jalankan

```bash
cd /home/ubuntu/desaku/wa-gateway
npm install
cp .env.example .env
nano .env
```

Konfigurasi `.env`:

```
PORT=2785
API_KEY=isi_dengan_random_string_kuat
SOCKS5_PROXY=socks5://127.0.0.1:40000
AUTH_DIR=./auth_info
DB_PATH=./data/wagateway.db
RATE_LIMIT_MS=1500
WEBHOOK_URL=https://gampong.web.id/api/v1/whatsapp/webhook
LOG_LEVEL=silent
```

`SOCKS5_PROXY` hanya diperlukan jika IP VPS diblokir WhatsApp. Gunakan Cloudflare WARP.

#### Systemd Service

```bash
sudo cp wa-gateway.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable wa-gateway
sudo systemctl start wa-gateway
```

#### Ekspos via Cloudflare Tunnel (Opsional)

```bash
cloudflared tunnel create wa-gateway
cloudflared tunnel route dns wa-gateway wa.gampong.web.id
```

Konfigurasi `~/.cloudflared/config.yml`:

```yaml
tunnel: <tunnel-id>
ingress:
  - hostname: wa.gampong.web.id
    service: http://localhost:2785
  - service: http_status:404
```

#### Pairing WhatsApp

```
https://wa.gampong.web.id/api/sessions/sig-udeung/qr?format=html
```

### 4.3. Fonnte (SaaS - Alternatif)

Jika WA Gateway sering banned, ganti provider ke Fonnte — cukup token, tanpa VPS.

1. Daftar di [fonnte.com](https://fonnte.com) (1000 pesan gratis/bulan)
2. Salin token ke `.env`:
   ```env
   WHA_PROVIDER=fonnte
   FONNTE_TOKEN=token_dari_fonnte
   ```
3. Tidak perlu pairing QR. Status di panel admin `/admin/notifications`.

---

## 5. Cron Jobs & Queue Worker

### 5.1. Cron Job

Tambahkan ke crontab (`crontab -e`):

```cron
* * * * * cd /home/ubuntu/desaku && php artisan schedule:run >> /dev/null 2>&1
```

### 5.2. Queue Worker via Supervisor

Instal Supervisor:

```bash
sudo apt install supervisor
```

Buat file `/etc/supervisor/conf.d/desaku-worker.conf`:

```ini
[program:desaku-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/ubuntu/desaku/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/home/ubuntu/desaku/storage/logs/worker.log
stopwaitsecs=3600
```

Jalankan:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start desaku-worker:*
```

### 5.3. Perintah Manual (untuk troubleshooting)

```bash
# Test queue
php artisan queue:listen --tries=1

# Cek jadwal
php artisan schedule:list

# Test notifikasi WA
php artisan tinker
> \App\Services\WhatsAppService::send('6281234567890', 'Test dari server')
```

---

## 6. Troubleshooting

### 6.1. Halaman 500 / White Screen

```bash
# Cek log
tail -f storage/logs/laravel.log

# Cek izin
ls -la storage/bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Cek environment
php artisan config:clear
php artisan config:cache
```

### 6.2. WA Gateway Tidak Terkoneksi

```bash
# Cek status service
sudo systemctl status wa-gateway
journalctl -u wa-gateway -n 50 --no-pager

# Cek port
curl http://localhost:2785/api/health

# Cek log
tail -f wa-gateway/data/*.log
```

Solusi umum:
- Pastikan `API_KEY` sama di `.env` Laravel (`WHA_API_KEY`) dan WA Gateway (`API_KEY`).
- VPS pakai cloud IP? Aktifkan SOCKS5 proxy WARP.
- WA kadang disconnect otomatis — systemd `Restart=always` akan reconnect.
- **Jika sering banned:** ganti ke Fonnte — cukup isi `WHA_PROVIDER=fonnte` + token, tanpa VPS.

### 6.3. Notifikasi WhatsApp Tidak Terkirim

```bash
# Cek log Laravel
tail -f storage/logs/laravel.log | grep -i whatsapp

# Cek apakah Fonnte terkonfigurasi (fallback)
php artisan tinker
> config('services.whatsapp.fonnte_token')
> config('services.whatsapp.provider')

# Cek queue worker (jika pakai Redis)
sudo supervisorctl status desaku-worker:*
```

Penyebab umum:
- **WA Gateway banned** → sistem otomatis fallback ke Fonnte (jika token terisi).
- **Fonnte juga error** → cek saldo/token di fonnte.com.
- **Queue worker mati** → Notifikasi WA menumpuk di Redis, tidak terkirim.

### 6.4. Asset Frontend Rusak (CSS/JS tidak muncul)

```bash
# Rebuild
npm run build

# Hapus cache browser (Ctrl+F5) atau cache server
php artisan view:clear
```

### 6.5. Queue Tidak Berjalan

```bash
# Cek supervisor
sudo supervisorctl status

# Cek tabel jobs
php artisan tinker
> DB::table('jobs')->count();

# Restart worker
sudo supervisorctl restart desaku-worker:*
```

### 6.6. Migrasi Error

```bash
# Rollback + migrate ulang (hati-hati: data hilang)
php artisan migrate:fresh --force

# Atau cek status
php artisan migrate:status
```

### 6.7. File Upload Gagal

```bash
# Cek storage link
php artisan storage:link

# Cek disk space
df -h

# Cek limit upload (php.ini)
php -i | grep upload_max_filesize
```

### 6.8. Let's Encrypt / SSL

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d gampong.web.id -d www.gampong.web.id
```

---

## 7. Post-Deployment Checklist

Gunakan checklist ini setelah deploy selesai.

### □ Server & Lingkungan
- [ ] PHP versi sesuai (≥8.3) dengan ekstensi yang dibutuhkan
- [ ] Composer versi 2.x
- [ ] Node.js versi 20+
- [ ] MySQL/MariaDB aktif dan bisa diakses
- [ ] Cron job sudah terpasang (`php artisan schedule:run`)
- [ ] Supervisor berjalan dengan worker queue
- [ ] Storage link (public → storage)

### □ Aplikasi Laravel
- [ ] `.env` terisi lengkap (DB, WA Gateway, Telegram, AI)
- [ ] `APP_KEY` sudah di-generate
- [ ] `APP_DEBUG=false`
- [ ] Migrasi database berhasil (cek tabel)
- [ ] Config/route/view cache sudah dijalankan
- [ ] Frontend sudah build (`npm run build`)

### □ WhatsApp Notification
- [ ] Dual provider: `FONNTE_TOKEN` terisi di `.env` (fallback)
- [ ] Jika pakai WA Gateway: service berjalan (`systemctl status wa-gateway`)
- [ ] API Key sama antara Laravel dan WA Gateway
- [ ] QR sudah dipairing dengan WhatsApp
- [ ] Atau jika pakai Fonnte: `WHA_PROVIDER=fonnte` + token valid

### □ Web Server & SSL
- [ ] Virtual host Nginx/OLS terkonfigurasi
- [ ] HTTPS aktif (Let's Encrypt)
- [ ] Redirect HTTP → HTTPS jalan
- [ ] Rewrite/pretty URL berfungsi

### □ Verifikasi Fungsional
- [ ] Halaman publik: beranda, profil, informasi, statistik
- [ ] Login warga (NIK) & admin (email)
- [ ] Dashboard warga & pengajuan surat
- [ ] Panel admin Filament
- [ ] Notifikasi WhatsApp (test via admin → /admin/notifications)
- [ ] Notifikasi Telegram (test broadcast)
- [ ] API endpoints bisa diakses (cek dengan curl/Postman)
- [ ] QR code verifikasi bisa discan

### □ Monitoring
- [ ] Log rotasi berfungsi
- [ ] Worker log (`storage/logs/worker.log`) tidak ada error
- [ ] WA Gateway tidak disconnect dalam 24 jam pertama

---

> **Desaku — SIG-Udeung**  
> Dokumentasi: `/docs/`  |  WA Gateway: `/wa-gateway/`  |  Tanya: admin@gampong.web.id
