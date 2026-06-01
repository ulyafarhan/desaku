# 🚀 Panduan Instalasi SIG-Udeung Backend

Panduan lengkap instalasi backend SIG-Udeung dari awal hingga siap production.

## 📋 Prerequisites

Pastikan sistem Anda sudah terinstall:

- **PHP 8.3+** dengan extensions:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  - GD (untuk QR Code)
  
- **PostgreSQL 15+**
- **Redis 6+**
- **Composer 2+**
- **Node.js 18+ & NPM**
- **Git**

## 🔧 Instalasi Step-by-Step

### 1. Clone Repository

```bash
cd /var/www
git clone <repository-url> sig-udeung
cd sig-udeung
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit file `.env`:

```env
APP_NAME="SIG-Udeung"
APP_ENV=production
APP_KEY=base64:xxx # sudah digenerate
APP_DEBUG=false
APP_URL=https://udeung.desa.id

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sig_udeung
DB_USERNAME=sig_user
DB_PASSWORD=your_secure_password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=your_redis_password
REDIS_PORT=6379

# Queue
QUEUE_CONNECTION=redis

# Cache
CACHE_STORE=redis

# Session
SESSION_DRIVER=redis

# Telegram
TELEGRAM_BOT_TOKEN=123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
TELEGRAM_WEBHOOK_URL=https://udeung.desa.id/api/v1/telegram/webhook

# Gemini AI
GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemini-pro
```

### 4. Setup Database

```bash
# Buat database PostgreSQL
sudo -u postgres psql
```

Di PostgreSQL prompt:

```sql
CREATE DATABASE sig_udeung;
CREATE USER sig_user WITH ENCRYPTED PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE sig_udeung TO sig_user;
\q
```

Jalankan migrasi:

```bash
# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed
```

### 5. Setup Storage & Permissions

```bash
# Create storage link
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 6. Setup Telegram Bot

Dapatkan Bot Token dari [@BotFather](https://t.me/botfather):

1. Chat dengan @BotFather
2. Kirim `/newbot`
3. Ikuti instruksi
4. Copy token yang diberikan
5. Paste ke `.env` di `TELEGRAM_BOT_TOKEN`

Setup webhook:

```bash
php artisan telegram:setup-webhook
```

### 7. Optimize untuk Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 8. Setup Queue Worker

#### Menggunakan Supervisor

Install Supervisor:

```bash
sudo apt install supervisor
```

Buat config file `/etc/supervisor/conf.d/sig-udeung-worker.conf`:

```ini
[program:sig-udeung-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sig-udeung/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/sig-udeung/storage/logs/worker.log
stopwaitsecs=3600
```

Start worker:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start sig-udeung-worker:*
```

### 9. Setup Scheduled Tasks

Edit crontab:

```bash
sudo crontab -e -u www-data
```

Tambahkan:

```cron
* * * * * cd /var/www/sig-udeung && php artisan schedule:run >> /dev/null 2>&1
```

### 10. Setup Web Server (Nginx)

Buat config `/etc/nginx/sites-available/sig-udeung`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name udeung.desa.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name udeung.desa.id;
    root /var/www/sig-udeung/public;

    # SSL Configuration (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/udeung.desa.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/udeung.desa.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=api:10m rate=60r/m;
    
    location /api/ {
        limit_req zone=api burst=10 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/sig-udeung /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 11. Setup SSL dengan Let's Encrypt

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d udeung.desa.id
```

### 12. Setup Firewall

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 13. Setup Redis Security

Edit `/etc/redis/redis.conf`:

```conf
bind 127.0.0.1
requirepass your_redis_password
```

Restart Redis:

```bash
sudo systemctl restart redis
```

### 14. Setup PostgreSQL Security

Edit `/etc/postgresql/15/main/pg_hba.conf`:

```conf
# Local connections
local   all             all                                     md5
host    all             all             127.0.0.1/32            md5
```

Restart PostgreSQL:

```bash
sudo systemctl restart postgresql
```

### 15. Setup Backup Otomatis

Buat script backup `/usr/local/bin/backup-sig-udeung.sh`:

```bash
#!/bin/bash

BACKUP_DIR="/var/backups/sig-udeung"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="sig_udeung"
DB_USER="sig_user"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
PGPASSWORD="your_secure_password" pg_dump -U $DB_USER $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/sig-udeung/storage/app/public

# Delete backups older than 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

Buat executable:

```bash
sudo chmod +x /usr/local/bin/backup-sig-udeung.sh
```

Tambahkan ke crontab (backup setiap hari jam 2 pagi):

```bash
sudo crontab -e
```

```cron
0 2 * * * /usr/local/bin/backup-sig-udeung.sh >> /var/log/sig-udeung-backup.log 2>&1
```

## ✅ Verifikasi Instalasi

### 1. Check Health Endpoint

```bash
curl https://udeung.desa.id/up
```

### 2. Test API

```bash
curl https://udeung.desa.id/api/v1/statistik/demografi
```

### 3. Check Queue Worker

```bash
sudo supervisorctl status sig-udeung-worker:*
```

### 4. Check Logs

```bash
tail -f storage/logs/laravel.log
tail -f storage/logs/worker.log
```

## 🔐 Default Admin Accounts

Setelah seeding, gunakan akun berikut untuk login pertama kali:

| Username | Password | Role |
|----------|----------|------|
| keuchik | password123 | keuchik |
| sekdes | password123 | sekdes |
| operator | password123 | operator |

**⚠️ PENTING:** Segera ganti password default setelah login pertama!

## 📊 Monitoring

### Check System Resources

```bash
# CPU & Memory
htop

# Disk usage
df -h

# Database connections
sudo -u postgres psql -c "SELECT count(*) FROM pg_stat_activity;"

# Redis info
redis-cli info
```

### Application Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx access logs
tail -f /var/log/nginx/access.log

# Nginx error logs
tail -f /var/log/nginx/error.log

# Queue worker logs
tail -f storage/logs/worker.log
```

## 🐛 Troubleshooting

### Queue tidak berjalan

```bash
# Restart queue worker
sudo supervisorctl restart sig-udeung-worker:*

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Cache issues

```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Permission issues

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Database connection error

```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

## 📞 Support

Jika mengalami masalah, hubungi tim pengembang atau buat issue di repository.

---

**Selamat! Backend SIG-Udeung sudah siap digunakan! 🎉**
