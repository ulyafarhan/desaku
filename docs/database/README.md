# 🗄️ Database Documentation

Dokumentasi lengkap untuk database SIG-Udeung (PostgreSQL).

## 📋 Overview

Database SIG-Udeung menggunakan PostgreSQL dengan 14 tabel utama yang saling berelasi.

## 🏗️ Database Schema

### Tables (14)

#### 1. **users**
- Primary authentication table (Laravel default)
- Digunakan untuk Sanctum tokens

#### 2. **administrators**
- Admin users (Keuchik, Sekdes, Operator)
- Fields: id, username, password, role, created_at, updated_at

#### 3. **pengaturan_gampong**
- Konfigurasi gampong
- Fields: id, nama_gampong, alamat, kode_pos, telepon, email, logo, dll

#### 4. **referensi_wilayah**
- Data wilayah (Provinsi, Kabupaten, Kecamatan)
- Fields: kode, nama, tingkat, parent_kode

#### 5. **keluarga**
- Data Kartu Keluarga
- Fields: no_kk, alamat, rt, rw, dusun, kepala_keluarga_nik

#### 6. **penduduk**
- Data penduduk gampong
- Fields: nik (PK), nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, pendidikan, pekerjaan, status_perkawinan, status_keluarga, status_mutasi, no_kk (FK), telegram_chat_id

#### 7. **mutasi_penduduk**
- Riwayat mutasi (kelahiran, kematian, kedatangan, kepindahan)
- Fields: id, nik (FK), jenis_mutasi, tanggal_mutasi, keterangan, dokumen_bukti, status_verifikasi, diverifikasi_oleh

#### 8. **kategori_surat**
- Jenis-jenis surat yang bisa diajukan
- Fields: id, nama_surat, kode_surat, deskripsi, template_path, persyaratan (JSON), field_isian (JSON), is_active

#### 9. **pengajuan_surat**
- Pengajuan surat dari warga
- Fields: id, nomor_registrasi, nik_pemohon (FK), kategori_surat_id (FK), data_isian (JSON), file_syarat (JSON), status, file_surat, qr_hash, catatan_penolakan, diverifikasi_oleh

#### 10. **tracking_pengajuan_surat**
- Tracking status pengajuan surat
- Fields: id, pengajuan_surat_id (FK), status_sebelumnya, status_baru, keterangan_update, diupdate_oleh

#### 11. **informasi_publik**
- Berita, pengumuman, agenda gampong
- Fields: id, judul, slug, konten, kategori, cover_image, is_published, author_id (FK)

#### 12. **telegram_broadcast_queue**
- Queue untuk broadcast Telegram
- Fields: id, pesan, target_type, target_ids (JSON), status, dikirim_pada

#### 13. **chatbot_logs**
- Log interaksi chatbot Telegram
- Fields: id, chat_id, user_message, bot_response, created_at

#### 14. **audit_logs**
- Audit trail semua aksi penting
- Fields: id, user_type, user_id, action, table_name, record_id, old_data (JSON), new_data (JSON), ip_address, user_agent

## 🔗 Relationships

### One to Many
- `keluarga` → `penduduk` (1 KK memiliki banyak anggota)
- `kategori_surat` → `pengajuan_surat` (1 kategori untuk banyak pengajuan)
- `pengajuan_surat` → `tracking_pengajuan_surat` (1 pengajuan memiliki banyak tracking)
- `administrators` → `informasi_publik` (1 admin membuat banyak informasi)

### Many to One
- `penduduk` → `keluarga` (banyak penduduk dalam 1 KK)
- `pengajuan_surat` → `penduduk` (banyak pengajuan dari 1 penduduk)
- `pengajuan_surat` → `kategori_surat`
- `mutasi_penduduk` → `penduduk`

### Polymorphic (via audit_logs)
- Semua tabel bisa memiliki audit logs

## 📊 ERD (Entity Relationship Diagram)

```
┌─────────────────┐
│  administrators │
└────────┬────────┘
         │
         │ author_id
         ▼
┌─────────────────┐
│ informasi_publik│
└─────────────────┘

┌─────────────────┐       ┌──────────────┐
│  keluarga       │◄──────┤  penduduk    │
│  (no_kk PK)     │ no_kk │  (nik PK)    │
└─────────────────┘       └──────┬───────┘
                                 │
                    ┌────────────┼────────────┐
                    │                         │
                    ▼                         ▼
         ┌──────────────────┐    ┌──────────────────┐
         │ mutasi_penduduk  │    │ pengajuan_surat  │
         └──────────────────┘    └────────┬─────────┘
                                          │
                                          │ kategori_surat_id
                                          ▼
                              ┌──────────────────┐
                              │ kategori_surat   │
                              └──────────────────┘
                                          │
                                          │ pengajuan_surat_id
                                          ▼
                              ┌──────────────────────────┐
                              │ tracking_pengajuan_surat │
                              └──────────────────────────┘
```

## 🔑 Indexes

### Primary Keys
- `users.id`
- `administrators.id`
- `pengaturan_gampong.id`
- `referensi_wilayah.kode`
- `keluarga.no_kk`
- `penduduk.nik`
- `mutasi_penduduk.id`
- `kategori_surat.id`
- `pengajuan_surat.id`
- `tracking_pengajuan_surat.id`
- `informasi_publik.id`
- `telegram_broadcast_queue.id`
- `chatbot_logs.id`
- `audit_logs.id`

### Foreign Keys
- `keluarga.kepala_keluarga_nik` → `penduduk.nik`
- `penduduk.no_kk` → `keluarga.no_kk`
- `mutasi_penduduk.nik` → `penduduk.nik`
- `mutasi_penduduk.diverifikasi_oleh` → `administrators.id`
- `pengajuan_surat.nik_pemohon` → `penduduk.nik`
- `pengajuan_surat.kategori_surat_id` → `kategori_surat.id`
- `pengajuan_surat.diverifikasi_oleh` → `administrators.id`
- `tracking_pengajuan_surat.pengajuan_surat_id` → `pengajuan_surat.id`
- `tracking_pengajuan_surat.diupdate_oleh` → `administrators.id`
- `informasi_publik.author_id` → `administrators.id`

### Additional Indexes
- `penduduk.no_kk` (for faster joins)
- `penduduk.status_mutasi` (for filtering)
- `pengajuan_surat.status` (for filtering)
- `pengajuan_surat.nik_pemohon` (for user queries)
- `mutasi_penduduk.status_verifikasi` (for filtering)
- `informasi_publik.slug` (for URL lookup)
- `informasi_publik.is_published` (for filtering)

## 📝 Migrations

Lokasi: `database/migrations/`

### Urutan Migrasi
1. `0001_01_01_000000_create_users_table.php`
2. `0001_01_01_000001_create_cache_table.php`
3. `0001_01_01_000002_create_jobs_table.php`
4. `2024_01_01_000001_create_administrators_table.php`
5. `2024_01_01_000002_create_pengaturan_gampong_table.php`
6. `2024_01_01_000003_create_referensi_wilayah_table.php`
7. `2024_01_01_000004_create_keluarga_table.php`
8. `2024_01_01_000005_create_penduduk_table.php`
9. `2024_01_01_000006_add_kepala_keluarga_to_keluarga_table.php`
10. `2024_01_01_000007_create_mutasi_penduduk_table.php`
11. `2024_01_01_000008_create_kategori_surat_table.php`
12. `2024_01_01_000009_create_pengajuan_surat_table.php`
13. `2024_01_01_000010_create_tracking_pengajuan_surat_table.php`
14. `2024_01_01_000011_create_informasi_publik_table.php`
15. `2024_01_01_000012_create_telegram_broadcast_queue_table.php`
16. `2024_01_01_000013_create_chatbot_logs_table.php`
17. `2024_01_01_000014_create_audit_logs_table.php`
18. `2026_06_01_154734_create_personal_access_tokens_table.php`

### Run Migrations
```bash
# Run all migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Refresh (rollback + migrate)
php artisan migrate:refresh

# Fresh (drop all + migrate)
php artisan migrate:fresh
```

## 🌱 Seeders

Lokasi: `database/seeders/`

### Available Seeders
1. **AdministratorSeeder** - Create default admin users
2. **KategoriSuratSeeder** - Create default surat categories
3. **PengaturanGampongSeeder** - Create gampong settings

### Run Seeders
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=AdministratorSeeder

# Migrate + seed
php artisan migrate:fresh --seed
```

## 🔐 Security

### Sensitive Data
- `administrators.password` - Hashed with bcrypt
- `penduduk.nik` - Encrypted in transit (HTTPS)
- `audit_logs` - Contains old/new data (be careful with PII)

### Best Practices
- ✅ Use prepared statements (Eloquent ORM)
- ✅ Validate all inputs
- ✅ Sanitize outputs
- ✅ Use transactions for critical operations
- ✅ Regular backups
- ✅ Monitor audit logs

## 🚀 Performance

### Optimization
- ✅ Proper indexing on foreign keys
- ✅ Composite indexes for common queries
- ✅ Eager loading to prevent N+1 queries
- ✅ Query result caching (statistik)
- ✅ Database connection pooling

### Monitoring
```bash
# Check slow queries
# Enable PostgreSQL slow query log

# Analyze query performance
EXPLAIN ANALYZE SELECT ...;

# Check table sizes
SELECT 
  schemaname,
  tablename,
  pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS size
FROM pg_tables
WHERE schemaname = 'public'
ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC;
```

## 🔄 Backup & Restore

### Backup
```bash
# Full backup
pg_dump -U username -d database_name > backup.sql

# Backup with compression
pg_dump -U username -d database_name | gzip > backup.sql.gz

# Backup specific tables
pg_dump -U username -d database_name -t table_name > table_backup.sql
```

### Restore
```bash
# Restore from backup
psql -U username -d database_name < backup.sql

# Restore from compressed backup
gunzip -c backup.sql.gz | psql -U username -d database_name
```

## 📊 Statistics

- **Total Tables**: 14
- **Total Migrations**: 18
- **Total Seeders**: 3
- **Total Relationships**: 15+
- **Total Indexes**: 20+

## 📞 Support

### Documentation
- **Backend**: `../backend/README.md`
- **API**: `../api/README.md`

### Resources
- **PostgreSQL Docs**: https://www.postgresql.org/docs/
- **Laravel Migrations**: https://laravel.com/docs/migrations
- **Laravel Eloquent**: https://laravel.com/docs/eloquent

---

**Version**: 1.0.0  
**Last Updated**: June 1, 2026  
**Status**: ✅ Production Ready
