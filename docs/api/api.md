# Dokumentasi API - SIG-Udeung

Dokumentasi ini merinci spesifikasi lengkap 29 endpoint API, metode autentikasi, parameter request, serta contoh respons untuk platform SIG-Udeung.

---

## 1. Metode Autentikasi API

SIG-Udeung menggunakan **Laravel Sanctum** (v4.3) untuk pengelolaan sesi API:

- **Token Sesi**: Permintaan yang membutuhkan autentikasi wajib menyertakan header berikut:
  ```
  Authorization: Bearer {token_akses_anda}
  Accept: application/json
  ```
- **Masa Berlaku**: Token dapat dicabut sewaktu-waktu melalui endpoint `/v1/auth/logout`.
- **Rate Limiting**: Login diterapkan rate limit 5 percobaan per menit.

---

## 2. Ringkasan Endpoint

| Kategori | Publik | Warga | Admin | Total |
|----------|--------|-------|-------|-------|
| Autentikasi | 2 | 3 | 0 | 5 |
| Informasi Publik | 2 | 0 | 4 | 6 |
| Statistik | 2 | 0 | 1 | 3 |
| Verifikasi | 1 | 0 | 0 | 1 |
| Telegram | 1 | 0 | 0 | 1 |
| Pengajuan Surat | 0 | 5 | 3 | 8 |
| Mutasi Penduduk | 0 | 2 | 3 | 5 |
| **Total** | **8** | **10** | **11** | **29** |

---

## 3. Daftar Lengkap Endpoint API

### 3.1. Modul Autentikasi (Authentication)

**POST `/v1/auth/login/warga`**
- **Fungsi**: Login warga gampong menggunakan NIK dan No KK.
- **Middleware**: `throttle:5,1`
- **Body Parameter**: `nik` (string, 16 digit), `no_kk` (string, 16 digit).
- **Respons**: Token Bearer + data user.

**POST `/v1/auth/login/admin`**
- **Fungsi**: Login perangkat desa/administrator menggunakan username dan password.
- **Middleware**: `throttle:5,1`
- **Body Parameter**: `username` (string), `password` (string).
- **Respons**: Token Bearer + data user.

**POST `/v1/auth/logout`**
- **Fungsi**: Mencabut token akses aktif.
- **Middleware**: `auth:sanctum`
- **Catatan**: Tersedia untuk semua peran (warga & admin).

**GET `/v1/auth/profile`**
- **Fungsi**: Mengambil data profil detail pengguna yang sedang masuk.
- **Middleware**: `auth:sanctum`
- **Respons**: Data profil lengkap sesuai peran.

**POST `/v1/auth/bind-telegram`**
- **Fungsi**: Menghubungkan akun warga dengan ID Chat Telegram untuk notifikasi bot.
- **Middleware**: `auth:sanctum`, `ability:warga`
- **Body Parameter**: `telegram_chat_id` (string).

### 3.2. Modul Informasi Publik (Berita)

**GET `/v1/informasi`**
- **Fungsi**: Mengambil daftar artikel pengumuman atau berita desa yang sudah terbit.
- **Akses**: Terbuka untuk Umum.
- **Query Parameter**: `kategori` (opsional, string) - filter berdasarkan kategori.

**GET `/v1/informasi/{slug}`**
- **Fungsi**: Mengambil konten detail berita berdasarkan parameter slug.
- **Akses**: Terbuka untuk Umum.
- **Catatan**: Kolom `cover_image` dikembalikan sebagai URL gambar lengkap dan dinamis. Jika artikel tidak memiliki cover kustom, properti ini bernilai `null`.

**GET `/v1/admin/informasi`**
- **Fungsi**: Mengambil semua draf maupun berita terbit.
- **Akses**: Khusus Admin.

**POST `/v1/admin/informasi`**
- **Fungsi**: Membuat artikel berita baru.
- **Akses**: Khusus Admin.
- **Body Parameter**: `judul` (string), `konten` (string), `kategori` (string), `cover_image` (string, opsional), `is_published` (boolean, opsional).

**PUT `/v1/admin/informasi/{id}`**
- **Fungsi**: Memperbarui isi artikel berita.
- **Akses**: Khusus Admin.

**DELETE `/v1/admin/informasi/{id}`**
- **Fungsi**: Menghapus berita dari database.
- **Akses**: Khusus Admin.

### 3.3. Modul Statistik Desa

**GET `/v1/statistik/demografi`**
- **Fungsi**: Mengambil data kompilasi kependudukan gampong secara real-time (di-cache).
- **Akses**: Terbuka untuk Umum.

**GET `/v1/statistik/layanan`**
- **Fungsi**: Mengambil data statistik pengajuan pelayanan surat menyurat.
- **Akses**: Terbuka untuk Umum.

**POST `/v1/admin/statistik/clear-cache`**
- **Fungsi**: Memaksa pembersihan cache statistik pada Redis.
- **Akses**: Khusus Admin.

### 3.4. Modul Verifikasi QR & Telegram Webhook

**GET `/v1/verifikasi/{hash}`**
- **Fungsi**: Validasi dokumen fisik melalui hash QR Code TTE untuk menampilkan keaslian isi surat.
- **Akses**: Terbuka untuk Umum.

**POST `/v1/telegram/webhook`**
- **Fungsi**: Endpoint penangkap webhook pesan masuk Telegram Bot untuk dihubungkan ke asisten AI (Gemini/OpenAI).
- **Akses**: Terbuka untuk Umum / Telegram IP.
- **Middleware**: `throttle:60,1`

### 3.5. Modul Pengajuan Surat (Submission)

**GET `/v1/surat/kategori`**
- **Fungsi**: Mengambil daftar seluruh jenis/kategori surat aktif (SKTM, SKU, Domisili, dll).
- **Akses**: Warga (token wajib).

**GET `/v1/surat/kategori/{id}`**
- **Fungsi**: Mengambil skema isian dinamis (JSONB) dan berkas persyaratan dari satu kategori surat.
- **Akses**: Warga (token wajib).
- **Parameter**: `id` (string, ULID).

**POST `/v1/surat/pengajuan`**
- **Fungsi**: Mengirimkan pengajuan surat baru beserta unggahan file prasyarat.
- **Akses**: Warga (token wajib).
- **Body Parameter**: `kategori_surat_id` (string, ULID), `data_isian` (object), `file_syarat` (object).

**GET `/v1/surat/pengajuan`**
- **Fungsi**: Melihat daftar seluruh riwayat pengajuan surat milik warga bersangkutan.
- **Akses**: Warga (token wajib).

**GET `/v1/surat/pengajuan/{id}`**
- **Fungsi**: Melihat status pelacakan (tracking) detail pengajuan surat tertentu.
- **Akses**: Warga (token wajib).
- **Parameter**: `id` (string, ULID).

**GET `/v1/admin/surat/pengajuan`**
- **Fungsi**: Mengambil daftar semua surat masuk yang menunggu verifikasi.
- **Akses**: Khusus Admin.
- **Query Parameter**: `status` (opsional, string).

**POST `/v1/admin/surat/pengajuan/{id}/approve`**
- **Fungsi**: Menyetujui pengajuan surat, otomatis men-generate QR Code TTE dan mengirim PDF ke Telegram.
- **Akses**: Khusus Admin.
- **Parameter**: `id` (string, ULID).

**POST `/v1/admin/surat/pengajuan/{id}/reject`**
- **Fungsi**: Menolak surat dengan memberikan alasan penolakan.
- **Akses**: Khusus Admin.
- **Parameter**: `id` (string, ULID).
- **Body Parameter**: `catatan_penolakan` (string).

### 3.6. Modul Mutasi Penduduk

**POST `/v1/mutasi`**
- **Fungsi**: Mengajukan peristiwa mutasi kependudukan (Kelahiran, Kematian, Pindah Masuk/Keluar).
- **Akses**: Warga (token wajib).
- **Body Parameter**: `nik`, `jenis_mutasi`, `tanggal_mutasi`, `keterangan`, `dokumen_bukti`.

**GET `/v1/mutasi`**
- **Fungsi**: Mengambil daftar riwayat permohonan mutasi milik warga bersangkutan.
- **Akses**: Warga (token wajib).

**GET `/v1/admin/mutasi`**
- **Fungsi**: Mengambil daftar semua mutasi masuk yang menunggu verifikasi.
- **Akses**: Khusus Admin.
- **Query Parameter**: `status_verifikasi` (opsional), `jenis_mutasi` (opsional).

**POST `/v1/admin/mutasi/{id}/approve`**
- **Fungsi**: Menyetujui pengajuan mutasi dan secara otomatis memperbarui status kependudukan warga terkait.
- **Akses**: Khusus Admin.
- **Parameter**: `id` (string, ULID).

**POST `/v1/admin/mutasi/{id}/reject`**
- **Fungsi**: Menolak pengajuan mutasi kependudukan.
- **Akses**: Khusus Admin.
- **Parameter**: `id` (string, ULID).

---

## 4. Integrasi & Pengujian API

- **Koleksi Postman**: Tersedia di direktori `storage/app/private/scribe/collection.json` untuk diimpor langsung ke Postman Desktop.
- **Spesifikasi OpenAPI**: File spesifikasi kontrak standar YAML berada di `docs/api-contract.yaml` (dapat diimpor ke Swagger Editor).
