# 🔧 Backend Documentation

Dokumentasi lengkap untuk backend SIG-Udeung (Laravel 13).

## 📋 Daftar File

| File | Deskripsi | Untuk |
|------|-----------|-------|
| [`BACKEND_SUMMARY.md`](BACKEND_SUMMARY.md) | Ringkasan implementasi backend | Semua developer |
| [`README_BACKEND.md`](README_BACKEND.md) | Panduan lengkap backend | Backend developer |
| [`INSTALLATION.md`](INSTALLATION.md) | Panduan instalasi dan setup | DevOps/Developer baru |
| [`TESTING.md`](TESTING.md) | Panduan testing dan QA | QA/Developer |
| [`TEST_RESULTS.md`](TEST_RESULTS.md) | Hasil testing terakhir | QA/Project Manager |

## 🚀 Quick Start

### 1. Setup Environment
```bash
# 1. Clone repository
git clone [repository-url]
cd desaku

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Setup database
php artisan migrate
php artisan db:seed

# 5. Run server
php artisan serve
```

**Detail**: Lihat [`INSTALLATION.md`](INSTALLATION.md)

### 2. Run Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AuthTest

# Run with coverage
php artisan test --coverage
```

**Detail**: Lihat [`TESTING.md`](TESTING.md)

### 3. Generate Documentation
```bash
# Generate API documentation
php artisan scribe:generate

# View documentation
http://localhost/docs
```

## 📊 Backend Overview

### Tech Stack
- **Framework**: Laravel 13
- **PHP**: 8.2+
- **Database**: PostgreSQL 15+
- **Cache**: Redis
- **Queue**: Redis
- **Authentication**: Laravel Sanctum
- **PDF**: DomPDF
- **QR Code**: Simple QR Code

### Architecture
```
app/
├── Console/Commands/          # Artisan commands
├── Http/Controllers/Api/      # API controllers
├── Jobs/                      # Queue jobs
├── Models/                    # Eloquent models
└── Services/                  # Business logic services

database/
├── migrations/                # Database migrations
└── seeders/                   # Database seeders

routes/
└── api.php                    # API routes

tests/
├── Unit/                      # Unit tests
└── Feature/                   # Feature tests
```

## 📝 File Details

### BACKEND_SUMMARY.md
**Ukuran**: ~9.5 KB  
**Isi**:
- Struktur project
- Models dan relasi
- Controllers
- Services
- Jobs dan Commands
- Routes
- Dependencies

**Baca jika**: Mau overview lengkap backend

### README_BACKEND.md
**Ukuran**: ~10.7 KB  
**Isi**:
- Penjelasan detail setiap komponen
- Business logic
- Database design
- API endpoints
- Best practices

**Baca jika**: Mau memahami implementasi detail

### INSTALLATION.md
**Ukuran**: ~9.2 KB  
**Isi**:
- System requirements
- Installation steps
- Configuration
- Database setup
- Troubleshooting

**Baca jika**: Setup environment baru

### TESTING.md
**Ukuran**: ~10.3 KB  
**Isi**:
- Testing strategy
- Unit tests
- Feature tests
- Running tests
- Writing tests

**Baca jika**: Mau run atau write tests

### TEST_RESULTS.md
**Ukuran**: ~7.6 KB  
**Isi**:
- Test execution results
- Coverage report
- Failed tests
- Performance metrics

**Baca jika**: Mau lihat quality metrics

## 🏗️ Architecture

### Models (13)
- Administrator
- AuditLog
- ChatbotLog
- InformasiPublik
- KategoriSurat
- Keluarga
- MutasiPenduduk
- Penduduk
- PengajuanSurat
- PengaturanGampong
- ReferensiWilayah
- TelegramBroadcastQueue
- TrackingPengajuanSurat
- User

### Controllers (7)
- AuthController
- PengajuanSuratController
- MutasiPendudukController
- InformasiPublikController
- StatistikController
- VerifikasiController
- TelegramWebhookController

### Services (4)
- TelegramService
- GeminiAiService
- PdfGeneratorService
- StatistikService

### Jobs (2)
- GenerateSuratPdfJob
- ProcessTelegramBroadcastJob

### Commands (2)
- SetupTelegramWebhook
- ProcessBroadcastQueue

## 🗄️ Database

### Migrations (14)
- users, cache, jobs tables
- administrators
- pengaturan_gampong
- referensi_wilayah
- keluarga
- penduduk
- mutasi_penduduk
- kategori_surat
- pengajuan_surat
- tracking_pengajuan_surat
- informasi_publik
- telegram_broadcast_queue
- chatbot_logs
- audit_logs

### Seeders (3)
- AdministratorSeeder
- KategoriSuratSeeder
- PengaturanGampongSeeder

## 🧪 Testing

### Test Statistics
- **Total Tests**: 52
- **Passed**: 50 (96.15%)
- **Failed**: 2 (3.85%)
- **Unit Tests**: 19
- **Feature Tests**: 33

### Test Coverage
- ✅ Models
- ✅ Services
- ✅ Controllers
- ✅ Authentication
- ✅ Authorization
- ✅ Validation

**Detail**: Lihat [`TEST_RESULTS.md`](TEST_RESULTS.md)

## 🔐 Security

### Implemented
- ✅ Laravel Sanctum authentication
- ✅ Input validation
- ✅ SQL injection protection (Eloquent)
- ✅ XSS protection (Laravel default)
- ✅ CSRF protection
- ✅ Rate limiting
- ✅ Audit logging

### Best Practices
- ✅ Environment variables for secrets
- ✅ Password hashing (bcrypt)
- ✅ Token-based authentication
- ✅ Role-based access control
- ✅ Secure file uploads
- ✅ API rate limiting

## 🚀 Performance

### Optimization
- ✅ Database indexing
- ✅ Eager loading (N+1 prevention)
- ✅ Query optimization
- ✅ Response caching (statistik)
- ✅ Queue jobs (PDF generation)
- ✅ Redis caching

### Monitoring
- ⏳ Query logging
- ⏳ Performance metrics
- ⏳ Error tracking (Sentry)
- ⏳ APM (Application Performance Monitoring)

## 🔄 Development Workflow

### 1. Feature Development
```bash
# 1. Create branch
git checkout -b feature/new-feature

# 2. Write code
# 3. Write tests
php artisan test

# 4. Update documentation
php artisan scribe:generate

# 5. Commit
git commit -m "feat: add new feature"

# 6. Push
git push origin feature/new-feature

# 7. Create PR
```

### 2. Bug Fix
```bash
# 1. Create branch
git checkout -b fix/bug-description

# 2. Fix bug
# 3. Write test to prevent regression
php artisan test

# 4. Commit
git commit -m "fix: resolve bug description"

# 5. Push and PR
```

### 3. Documentation Update
```bash
# 1. Update PHPDoc
# 2. Generate docs
php artisan scribe:generate

# 3. Update markdown files
# 4. Commit
git commit -m "docs: update documentation"
```

## 📦 Dependencies

### Main Dependencies
```json
{
  "laravel/framework": "^11.0",
  "laravel/sanctum": "^4.0",
  "barryvdh/laravel-dompdf": "^3.0",
  "simplesoftwareio/simple-qrcode": "^4.2",
  "knuckleswtf/scribe": "^5.10"
}
```

### Dev Dependencies
```json
{
  "phpunit/phpunit": "^11.0",
  "laravel/pint": "^1.13",
  "nunomaduro/collision": "^8.0"
}
```

## 🛠️ Maintenance

### Regular Tasks
- [ ] Update dependencies
- [ ] Run tests
- [ ] Check logs
- [ ] Monitor performance
- [ ] Backup database
- [ ] Clear cache
- [ ] Update documentation

### Commands
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Optimize
php artisan optimize
php artisan config:cache
php artisan route:cache

# Maintenance mode
php artisan down
php artisan up
```

## 📞 Support

### Documentation
- **Backend Guide**: [`README_BACKEND.md`](README_BACKEND.md)
- **Installation**: [`INSTALLATION.md`](INSTALLATION.md)
- **Testing**: [`TESTING.md`](TESTING.md)

### Contact
- **Email**: support@gampong-udeung.go.id
- **Telegram**: @SIGUdeungBot

### Resources
- **Laravel Docs**: https://laravel.com/docs
- **Laravel Sanctum**: https://laravel.com/docs/sanctum
- **PHPUnit**: https://phpunit.de/documentation.html

## 🎯 Next Steps

### For New Developers
1. ✅ Read [`INSTALLATION.md`](INSTALLATION.md)
2. ✅ Setup environment
3. ✅ Read [`BACKEND_SUMMARY.md`](BACKEND_SUMMARY.md)
4. ✅ Run tests
5. ✅ Explore codebase

### For Experienced Developers
1. ✅ Review [`README_BACKEND.md`](README_BACKEND.md)
2. ✅ Check [`TEST_RESULTS.md`](TEST_RESULTS.md)
3. ✅ Start contributing

---

**Version**: 1.0.0  
**Last Updated**: June 1, 2026  
**Status**: ✅ Production Ready
