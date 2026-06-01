# Contributing to SIG-Udeung

Terima kasih telah tertarik untuk berkontribusi pada SIG-Udeung! Panduan ini akan membantu Anda memahami proses kontribusi kami.

## 🤝 Code of Conduct

Kami berkomitmen untuk menyediakan lingkungan yang ramah dan inklusif bagi semua kontributor. Harap baca dan patuhi [Code of Conduct](CODE_OF_CONDUCT.md) kami.

## 🚀 Cara Berkontribusi

### 1. Melaporkan Bug

Jika Anda menemukan bug, silakan buat issue dengan informasi berikut:

- **Deskripsi**: Jelaskan bug dengan detail
- **Langkah Reproduksi**: Langkah-langkah untuk mereproduksi bug
- **Perilaku yang Diharapkan**: Apa yang seharusnya terjadi
- **Perilaku Aktual**: Apa yang benar-benar terjadi
- **Environment**: OS, PHP version, Laravel version, dll
- **Screenshots**: Jika relevan

### 2. Mengusulkan Fitur

Untuk mengusulkan fitur baru:

1. Buat issue dengan label `enhancement`
2. Jelaskan use case dan manfaat fitur
3. Berikan contoh implementasi jika memungkinkan
4. Tunggu feedback dari maintainer

### 3. Membuat Pull Request

#### Setup Development Environment

```bash
# 1. Fork repository
# 2. Clone fork Anda
git clone https://github.com/YOUR_USERNAME/desaku.git
cd desaku

# 3. Add upstream remote
git remote add upstream https://github.com/ORIGINAL_OWNER/desaku.git

# 4. Create feature branch
git checkout -b feature/your-feature-name

# 5. Install dependencies
composer install
npm install

# 6. Setup environment
cp .env.example .env
php artisan key:generate

# 7. Run migrations
php artisan migrate

# 8. Run tests
php artisan test
```

#### Development Workflow

```bash
# 1. Make your changes
# 2. Write tests for new features
# 3. Run tests to ensure everything passes
php artisan test

# 4. Run linter
php artisan pint

# 5. Commit with clear message
git commit -m "feat: add new feature"

# 6. Push to your fork
git push origin feature/your-feature-name

# 7. Create Pull Request on GitHub
```

#### Commit Message Guidelines

Gunakan format berikut untuk commit messages:

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types**:
- `feat`: Fitur baru
- `fix`: Bug fix
- `docs`: Dokumentasi
- `style`: Formatting, missing semicolons, dll
- `refactor`: Refactoring code
- `perf`: Performance improvement
- `test`: Adding tests
- `chore`: Build process, dependencies, dll

**Examples**:
```
feat(auth): add NIK-based authentication
fix(surat): resolve PDF generation issue
docs(api): update API documentation
test(models): add Penduduk model tests
```

### 4. Pull Request Guidelines

Sebelum submit PR, pastikan:

- [ ] Anda telah membaca dokumentasi
- [ ] Anda telah membuat branch dari `main`
- [ ] Anda telah menjalankan tests dan semuanya pass
- [ ] Anda telah menambahkan tests untuk fitur baru
- [ ] Anda telah update dokumentasi jika diperlukan
- [ ] Anda telah menjalankan linter
- [ ] Commit messages jelas dan deskriptif
- [ ] PR description menjelaskan perubahan dengan detail

#### PR Template

```markdown
## Description
Jelaskan perubahan yang Anda buat.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Related Issues
Closes #(issue number)

## Testing
Jelaskan bagaimana Anda telah menguji perubahan ini.

## Screenshots (if applicable)
Tambahkan screenshots jika relevan.

## Checklist
- [ ] Tests pass
- [ ] Documentation updated
- [ ] No breaking changes
```

## 📝 Development Guidelines

### Code Style

Kami menggunakan [Laravel Pint](https://laravel.com/docs/pint) untuk code formatting.

```bash
# Format code
php artisan pint

# Check formatting
php artisan pint --test
```

### Testing

Semua fitur baru harus memiliki tests.

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AuthTest

# Run with coverage
php artisan test --coverage
```

### Documentation

Update dokumentasi untuk setiap perubahan:

1. Update PHPDoc di code
2. Update API documentation jika ada endpoint baru
3. Update README atau docs jika ada perubahan signifikan

```bash
# Generate API documentation
php artisan scribe:generate
```

### Database Migrations

Untuk perubahan database:

1. Buat migration file
2. Tulis migration code
3. Test migration up dan down
4. Update dokumentasi database

```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback
php artisan migrate:rollback
```

## 🔍 Review Process

1. **Automated Checks**: GitHub Actions akan menjalankan tests dan linter
2. **Code Review**: Maintainer akan review code Anda
3. **Feedback**: Kami akan memberikan feedback jika ada perubahan yang diperlukan
4. **Approval**: Setelah approval, PR akan di-merge

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [API Documentation](docs/api/README.md)
- [Backend Documentation](docs/backend/README.md)

## 🎯 Areas for Contribution

Kami sangat menerima kontribusi di area berikut:

- **Bug Fixes**: Perbaiki bug yang ada
- **Features**: Implementasi fitur baru
- **Documentation**: Improve dokumentasi
- **Tests**: Tambah test coverage
- **Performance**: Optimize performance
- **Security**: Improve security

## 💬 Questions?

Jika Anda memiliki pertanyaan:

1. Cek dokumentasi terlebih dahulu
2. Buka discussion di GitHub
3. Hubungi maintainer

## 📜 License

Dengan berkontribusi, Anda setuju bahwa kontribusi Anda akan dilisensikan di bawah [MIT License](LICENSE).

---

**Terima kasih telah berkontribusi pada SIG-Udeung! 🙏**
