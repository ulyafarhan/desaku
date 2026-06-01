# 🚀 Getting Started with SIG-Udeung

Panduan lengkap untuk memulai development dengan SIG-Udeung.

## 📋 Prerequisites

Sebelum memulai, pastikan Anda memiliki:

- **PHP 8.2+** - [Download](https://www.php.net/downloads)
- **Composer** - [Download](https://getcomposer.org/download/)
- **PostgreSQL 15+** - [Download](https://www.postgresql.org/download/)
- **Redis** - [Download](https://redis.io/download)
- **Node.js 18+** - [Download](https://nodejs.org/)
- **Git** - [Download](https://git-scm.com/download)

### Verify Installation

```bash
# Check PHP version
php --version

# Check Composer
composer --version

# Check PostgreSQL
psql --version

# Check Redis
redis-cli --version

# Check Node.js
node --version
npm --version

# Check Git
git --version
```

## 🔧 Setup Development Environment

### 1. Clone Repository

```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/desaku.git
cd desaku

# Or if you have SSH key
git clone git@github.com:YOUR_USERNAME/desaku.git
cd desaku
```

### 2. Install PHP Dependencies

```bash
# Install composer dependencies
composer install

# If you get permission errors on Windows, try:
composer install --no-interaction
```

### 3. Install Node Dependencies

```bash
# Install npm packages
npm install

# Or using yarn
yarn install
```

### 4. Setup Environment File

```bash
# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Configure Database

Edit `.env` file dan set database configuration:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sig_udeung
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Atau gunakan SQLite untuk development:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database.sqlite
```

### 6. Create Database

```bash
# Create database (PostgreSQL)
createdb sig_udeung

# Or using psql
psql -U postgres -c "CREATE DATABASE sig_udeung;"
```

### 7. Run Migrations

```bash
# Run all migrations
php artisan migrate

# If you want to rollback
php artisan migrate:rollback

# Fresh migration (drop all tables and migrate)
php artisan migrate:fresh
```

### 8. Seed Database

```bash
# Run all seeders
php artisan db:seed

# Or migrate + seed
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=AdministratorSeeder
```

### 9. Generate API Documentation

```bash
# Generate Scribe documentation
php artisan scribe:generate

# Documentation akan tersedia di:
# http://localhost:8000/docs
```

## 🚀 Running the Application

### Start Development Server

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Server akan berjalan di http://localhost:8000
```

### Start Redis (if needed)

```bash
# Terminal 2: Start Redis
redis-server

# Or on Windows
redis-server.exe
```

### Start Queue Worker (if needed)

```bash
# Terminal 3: Start queue worker
php artisan queue:work

# Or with specific queue
php artisan queue:work --queue=default
```

## 📖 Accessing Documentation

Setelah server berjalan, akses dokumentasi di:

- **Interactive API Docs**: http://localhost:8000/docs
- **Postman Collection**: http://localhost:8000/docs.postman
- **OpenAPI Spec**: http://localhost:8000/docs.openapi

## 🧪 Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run specific test method
php artisan test --filter=testLoginWarga

# Run with coverage
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel
```

## 🔍 Code Quality

### Format Code

```bash
# Format code using Pint
php artisan pint

# Check formatting without fixing
php artisan pint --test
```

### Check for Issues

```bash
# Run PHPStan (static analysis)
./vendor/bin/phpstan analyse

# Run Psalm (static analysis)
./vendor/bin/psalm
```

## 📝 Common Commands

### Artisan Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan optimize
php artisan config:cache
php artisan route:cache

# Generate API docs
php artisan scribe:generate

# Create new model
php artisan make:model ModelName -m

# Create new controller
php artisan make:controller ControllerName

# Create new migration
php artisan make:migration create_table_name

# Create new test
php artisan make:test TestName

# Create new job
php artisan make:job JobName

# Create new command
php artisan make:command CommandName
```

### Composer Commands

```bash
# Update dependencies
composer update

# Check for outdated packages
composer outdated

# Check for security vulnerabilities
composer audit

# Dump autoloader
composer dump-autoload
```

## 🐛 Troubleshooting

### Database Connection Error

```bash
# Check if PostgreSQL is running
psql -U postgres -c "SELECT 1"

# Check database exists
psql -U postgres -l | grep sig_udeung

# Create database if not exists
createdb sig_udeung
```

### Permission Denied Error

```bash
# On Linux/Mac
chmod -R 775 storage bootstrap/cache

# On Windows (run as Administrator)
icacls "storage" /grant:r "%USERNAME%:F" /t
icacls "bootstrap\cache" /grant:r "%USERNAME%:F" /t
```

### Composer Memory Error

```bash
# Increase memory limit
php -d memory_limit=-1 composer install
```

### Redis Connection Error

```bash
# Check if Redis is running
redis-cli ping

# Should return: PONG

# If not running, start Redis
redis-server
```

## 📚 Next Steps

1. **Read Documentation**
   - [`README.md`](README.md) - Project overview
   - [`docs/README.md`](docs/README.md) - Full documentation index
   - [`docs/api/API_QUICK_REFERENCE.md`](docs/api/API_QUICK_REFERENCE.md) - API reference

2. **Explore Codebase**
   - Check `app/Models/` untuk database models
   - Check `app/Http/Controllers/Api/` untuk API controllers
   - Check `routes/api.php` untuk API routes
   - Check `tests/` untuk test examples

3. **Try API**
   - Open http://localhost:8000/docs
   - Try "Try It Out" feature
   - Test endpoints dengan Postman

4. **Write Code**
   - Create feature branch: `git checkout -b feature/your-feature`
   - Write code dan tests
   - Commit dengan clear message
   - Push dan create pull request

5. **Learn More**
   - [Laravel Documentation](https://laravel.com/docs)
   - [PostgreSQL Documentation](https://www.postgresql.org/docs/)
   - [Redis Documentation](https://redis.io/documentation)

## 🤝 Contributing

Untuk berkontribusi:

1. Fork repository
2. Create feature branch
3. Make changes
4. Write tests
5. Commit dengan clear message
6. Push dan create PR

Lihat [`CONTRIBUTING.md`](CONTRIBUTING.md) untuk detail lengkap.

## 📞 Need Help?

- **Documentation**: [`docs/README.md`](docs/README.md)
- **API Docs**: http://localhost:8000/docs
- **Issues**: GitHub Issues
- **Email**: support@gampong-udeung.go.id
- **Telegram**: @SIGUdeungBot

## 🎯 Development Workflow

### Daily Development

```bash
# 1. Start of day
git pull origin main

# 2. Create feature branch
git checkout -b feature/your-feature

# 3. Make changes
# ... edit files ...

# 4. Run tests
php artisan test

# 5. Format code
php artisan pint

# 6. Commit
git add .
git commit -m "feat: add your feature"

# 7. Push
git push origin feature/your-feature

# 8. Create PR on GitHub
```

### Before Committing

```bash
# 1. Run tests
php artisan test

# 2. Format code
php artisan pint

# 3. Check for issues
./vendor/bin/phpstan analyse

# 4. Update documentation if needed
php artisan scribe:generate

# 5. Commit
git commit -m "feat: add feature"
```

## 📊 Project Structure

```
desaku/
├── app/                    # Application code
│   ├── Console/           # Artisan commands
│   ├── Http/              # Controllers, middleware, requests
│   ├── Jobs/              # Queue jobs
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic
├── database/              # Database files
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── routes/                # Route definitions
├── tests/                 # Test files
├── docs/                  # Documentation
├── storage/               # Application storage
├── vendor/                # Composer dependencies
├── node_modules/          # NPM dependencies
├── .env                   # Environment variables
├── composer.json          # PHP dependencies
├── package.json           # NPM dependencies
└── README.md              # Project README
```

## ✅ Checklist

Sebelum mulai development, pastikan:

- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] PostgreSQL installed
- [ ] Redis installed
- [ ] Node.js installed
- [ ] Repository cloned
- [ ] Dependencies installed (`composer install`, `npm install`)
- [ ] `.env` file configured
- [ ] Database created
- [ ] Migrations run (`php artisan migrate`)
- [ ] Seeders run (`php artisan db:seed`)
- [ ] Server running (`php artisan serve`)
- [ ] Tests passing (`php artisan test`)
- [ ] Documentation accessible (http://localhost:8000/docs)

## 🎉 Ready to Go!

Anda sekarang siap untuk mulai development dengan SIG-Udeung!

Untuk pertanyaan atau bantuan, lihat dokumentasi atau hubungi tim support.

**Happy Coding! 🚀**

---

**Last Updated**: June 1, 2026
**Version**: 1.0.0
