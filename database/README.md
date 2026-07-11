# Dokumentasi Database - Migrations & Seeders

Dokumentasi ini menjelaskan struktur migrasi dan seeders dalam aplikasi SIG-Udeung.

---

## Struktur Direktori

```
database/
  migrations/           - Berkas migrasi database (28 file)
  seeders/              - Berkas seeder untuk data awal (12 file)
  factories/            - Model factories untuk testing
  database.sqlite       - Database SQLite (development)
```

---

## Migrations

Migrasi database dijalankan secara berurutan berdasarkan timestamp. Berikut urutan dan fungsi setiap migrasi:

### Migrasi Default Laravel

| File | Fungsi |
|------|--------|
| `0001_01_01_000000_create_users_table.php` | Tabel users default Laravel |
| `0001_01_01_000001_create_cache_table.php` | Tabel cache & cache_locks |
| `0001_01_01_000002_create_jobs_table.php` | Tabel jobs, job_batches, failed_jobs |

### Migrasi Inti SIG-Udeung

| File | Fungsi |
|------|--------|
| `2024_01_01_000001_create_administrators_table.php` | Tabel administrators (admin gampong) |
| `2024_01_01_000002_create_pengaturan_gampong_table.php` | Tabel pengaturan_gampong (konfigurasi sistem) |
| `2024_01_01_000003_create_referensi_wilayah_table.php` | Tabel referensi_wilayah (master data wilayah) |
| `2024_01_01_000004_create_keluarga_table.php` | Tabel keluarga (data KK) |
| `2024_01_01_000005_create_penduduk_table.php` | Tabel penduduk (data kependudukan) |
| `2024_01_01_000006_add_kepala_keluarga_to_keluarga_table.php` | Menambahkan kolom kepala_keluarga_nik ke keluarga |
| `2024_01_01_000007_create_mutasi_penduduk_table.php` | Tabel mutasi_penduduk (laporan mutasi) |
| `2024_01_01_000008_create_kategori_surat_table.php` | Tabel kategori_surat (template surat) |
| `2024_01_01_000009_create_pengajuan_surat_table.php` | Tabel pengajuan_surat (pengajuan warga) |
| `2024_01_01_000010_create_tracking_pengajuan_surat_table.php` | Tabel tracking_pengajuan_surat (log status) |
| `2024_01_01_000011_create_informasi_publik_table.php` | Tabel informasi_publik (berita/artikel) |
| `2024_01_01_000012_create_telegram_broadcast_queue_table.php` | Tabel telegram_broadcast_queue |
| `2024_01_01_000013_create_chatbot_logs_table.php` | Tabel chatbot_logs (log chatbot) |
| `2024_01_01_000014_create_audit_logs_table.php` | Tabel audit_logs (log audit) |

### Migrasi Tambahan

| File | Fungsi |
|------|--------|
| `2026_06_01_155242_create_personal_access_tokens_table.php` | Tabel personal_access_tokens (Sanctum) |
| `2026_06_02_100846_create_notifications_table.php` | Tabel notifications (Laravel) |
| `2026_06_02_120506_add_document_fields_to_penduduk_table.php` | Menambahkan foto_profil, foto_ktp, foto_kk ke penduduk |
| `2026_06_13_231500_add_seo_fields_to_informasi_publik_table.php` | Menambahkan meta_description, kata_kunci ke informasi_publik |
| `2026_06_15_203700_create_bot_knowledges_table.php` | Tabel bot_knowledges (knowledge base chatbot) |
| `2026_06_16_011000_alter_user_id_in_sessions_table.php` | Memodifikasi kolom user_id di sessions |
| `2026_06_16_225800_add_performance_indexes_to_tables.php` | Menambahkan indeks performa ke tabel utama |
| `2026_06_16_231600_create_pengaturan_frontend_table.php` | Tabel pengaturan_frontend (konfigurasi konten) |
| `2026_06_16_234400_add_created_at_index_to_chatbot_logs.php` | Menambahkan indeks created_at ke chatbot_logs |
| `2026_06_16_235000_create_traffic_logs_table.php` | Tabel traffic_logs (log kunjungan) |
| `2026_07_10_155242_add_nomor_surat_to_pengajuan_surat_table.php` | Menambahkan kolom nomor_surat ke pengajuan_surat |

---

## Seeders

Seeders digunakan untuk mengisi data awal ke dalam database. Dapat dijalankan secara individual atau secara bersamaan melalui `DatabaseSeeder`.

### DatabaseSeeder

Seeder utama yang menjalankan seluruh seeder secara berurutan:

```php
// Urutan eksekusi:
1. WilayahPendudukSeeder    // Data referensi wilayah & data penduduk
2. AdministratorSeeder      // Akun admin default
3. PengaturanGampongSeeder  // Konfigurasi sistem awal
4. KategoriSuratSeeder      // Template/kategori surat
5. BeritaSeeder             // Artikel berita contoh
6. BotKnowledgeSeeder       // Basis pengetahuan chatbot
7. DummyDataSeeder          // Data dummy tambahan
8. TransaksiDummySeeder     // Data transaksi dummy
9. DemoAccountSeeder        // Akun demo untuk testing
10. TrafficLogSeeder         // Log traffic sample
```

### Rincian Seeder

#### WilayahPendudukSeeder

Mengisi data referensi wilayah administrasi (provinsi, kabupaten, kecamatan, gampong) dan data penduduk awal.

#### AdministratorSeeder

Membuat akun administrator default:
- **Keuchik**: `keuchik` / `password`
- **Sekdes**: `sekdes` / `password`
- **Operator**: `operator` / `password`

#### PengaturanGampongSeeder

Mengisi konfigurasi sistem awal seperti:
- Identitas gampong (nama, alamat, visi misi)
- Kredensial API (Telegram Bot Token, API Key AI)
- Pengaturan lainnya

#### KategoriSuratSeeder

Membuat template/kategori surat yang tersedia:
- SKTM (Surat Keterangan Tidak Mampu)
- SKU (Surat Keterangan Usaha)
- Domisili (Surat Keterangan Domisili)
- Keterangan Lainnya

#### BeritaSeeder

Mengisi artikel berita/pengumuman contoh untuk portal publik.

#### BotKnowledgeSeeder

Mengisi basis pengetahuan (knowledge base) chatbot Telegram dengan pasangan pertanyaan/jawaban.

#### DummyDataSeeder

Mengisi data dummy tambahan untuk keperluan testing dan demonstrasi.

#### TransaksiDummySeeder

Mengisi data transaksi dummy (pengajuan surat, mutasi) untuk keperluan testing.

#### DemoAccountSeeder

Membuat akun demo untuk keperluan testing/presentasi.

#### TrafficLogSeeder

Mengisi log traffic sample untuk keperluan demonstrasi grafik dasbor.

---

## Cara Menjalankan

### Fresh Migration + Seed
```bash
php artisan migrate:fresh --seed
```

### Jalankan Seeder Saja
```bash
php artisan db:seed
```

### Jalankan Seeder Tertentu
```bash
php artisan db:seed --class=AdministratorSeeder
```

### Rollback Migrasi
```bash
php artisan migrate:rollback
```

---

## Catatan Penting

1. **Urutan Seeder**: Urutan eksekusi seeder sangat penting karena beberapa seeder bergantung pada data dari seeder sebelumnya (misal: `Penduduk` bergantung pada `Keluarga`).

2. **Data Production**: Seeder tidak seharusnya dijalankan di lingkungan production karena akan menimpa data yang ada.

3. **Migrasi Berulang**: Gunakan `migrate:fresh` untuk reset seluruh database dan menjalankan ulang semua migrasi dari awal.

4. **SQLite**: Untuk development dan testing, proyek menggunakan SQLite. Untuk production, gunakan MySQL/MariaDB.
