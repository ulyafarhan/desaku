# API Documentation — Desaku (SIG-Udeung)

Dokumentasi endpoint REST API dan route web untuk Sistem Informasi Gampong.

---

## 1. Web Routes (Laravel — Inertia SSR)

Halaman publik dan area warga — dirender via Inertia + Vue.

### 1.1. Public Pages

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/` | Beranda / Home page | No |
| GET | `/profil` | Profil gampong | No |
| GET | `/informasi` | Daftar informasi publik | No |
| GET | `/informasi/{slug}` | Detail informasi publik | No |
| GET | `/verifikasi` | Halaman verifikasi QR (input) | No |
| GET | `/verifikasi/{hash}` | Verifikasi QR via hash | No |
| GET | `/statistik` | Statistik demografi & layanan | No |
| GET | `/fasilitas` | Daftar fasilitas gampong | No |
| POST | `/aspirasi` | Kirim aspirasi warga (rate limit: 5/jam) | No |

### 1.2. Auth — Guest (Warga)

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/login` | Halaman login warga | No |
| POST | `/login` | Proses login warga (NIK + password) | No |

### 1.3. Auth — Logout

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| POST | `/logout` | Logout warga | Yes: `auth:penduduk` |

### 1.4. Warga Area (setelah login)

Prefix: `/warga` — middleware: `auth:penduduk`

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/warga/dashboard` | Dashboard warga | Yes |
| GET | `/warga/profil` | Lihat profil diri | Yes |
| POST | `/warga/profil` | Update profil diri | Yes |
| GET | `/warga/keluarga` | Data keluarga | Yes |
| PUT | `/warga/keluarga/{nik}` | Update anggota keluarga | Yes |
| GET | `/warga/surat/ajukan/{kategori}` | Form pengajuan surat | Yes |
| POST | `/warga/surat/pengajuan` | Kirim pengajuan surat | Yes |
| GET | `/warga/pengajuan/{pengajuan}` | Detail pengajuan surat | Yes |
| GET | `/warga/pengajuan/{pengajuan}/print` | Cetak surat (PDF) | Yes |

### 1.5. Admin — Notification Settings

Prefix: `/admin` — middleware: `auth:admin`

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/admin/notifications` | Halaman settings notifikasi (Vue/Inertia) | Yes: admin |
| GET | `/admin/notifications/wa/status` | Cek status koneksi WA Gateway | Yes: admin |
| GET | `/admin/notifications/wa/qr` | Ambil QR code pairing WA | Yes: admin |
| POST | `/admin/notifications/wa/test` | Kirim test WA ke nomor tertentu | Yes: admin |
| GET | `/admin/notifications/telegram/status` | Cek status bot Telegram | Yes: admin |
| POST | `/admin/notifications/telegram/broadcast` | Broadcast Telegram ke seluruh warga | Yes: admin |

---

## 2. API Routes (Laravel — REST JSON)

Base path: `/api/v1`

### 2.1. Public Endpoints

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| POST | `/api/v1/auth/login/warga` | Login warga (NIK) — rate limit: 5/ menit | No |
| POST | `/api/v1/auth/login/admin` | Login admin — rate limit: 5/ menit | No |
| GET | `/api/v1/informasi` | Daftar informasi publik | No |
| GET | `/api/v1/informasi/{slug}` | Detail informasi publik | No |
| GET | `/api/v1/statistik/demografi` | Statistik demografi penduduk | No |
| GET | `/api/v1/statistik/layanan` | Statistik layanan surat | No |
| GET | `/api/v1/verifikasi/{hash}` | Verifikasi dokumen via QR | No |
| POST | `/api/v1/telegram/webhook` | Webhook incoming Telegram — rate limit: 60/ menit | No |
| POST | `/api/v1/whatsapp/webhook` | Webhook incoming WhatsApp — rate limit: 60/ menit | No |

### 2.2. Protected — All Authenticated Users

Middleware: `auth:sanctum`

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| POST | `/api/v1/auth/logout` | Logout (semua token) | Yes: Sanctum |
| GET | `/api/v1/auth/profile` | Profil user saat ini | Yes: Sanctum |

### 2.3. Protected — Warga Only

Middleware: `auth:sanctum`, `ability:warga`

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| POST | `/api/v1/auth/bind-telegram` | Bind akun Telegram ke warga | Yes: warga |
| GET | `/api/v1/surat/kategori` | Daftar kategori surat | Yes: warga |
| GET | `/api/v1/surat/kategori/{id}` | Detail kategori surat | Yes: warga |
| POST | `/api/v1/surat/pengajuan` | Ajukan surat baru | Yes: warga |
| GET | `/api/v1/surat/pengajuan` | Riwayat pengajuan surat saya | Yes: warga |
| GET | `/api/v1/surat/pengajuan/{id}` | Detail pengajuan surat | Yes: warga |
| POST | `/api/v1/mutasi` | Laporkan mutasi penduduk | Yes: warga |
| GET | `/api/v1/mutasi` | Riwayat mutasi saya | Yes: warga |

### 2.4. Protected — Admin Only

Middleware: `auth:sanctum`, `abilities:admin` — prefix: `/api/v1/admin`

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/api/v1/admin/surat/pengajuan` | Semua pengajuan surat (admin) | Yes: admin |
| POST | `/api/v1/admin/surat/pengajuan/{id}/approve` | Setujui pengajuan surat | Yes: admin |
| POST | `/api/v1/admin/surat/pengajuan/{id}/reject` | Tolak pengajuan surat | Yes: admin |
| GET | `/api/v1/admin/mutasi` | Semua mutasi (admin) | Yes: admin |
| POST | `/api/v1/admin/mutasi/{id}/approve` | Setujui mutasi | Yes: admin |
| POST | `/api/v1/admin/mutasi/{id}/reject` | Tolak mutasi | Yes: admin |
| GET | `/api/v1/admin/informasi` | Manajemen informasi publik | Yes: admin |
| POST | `/api/v1/admin/informasi` | Tambah informasi baru | Yes: admin |
| PUT | `/api/v1/admin/informasi/{id}` | Update informasi | Yes: admin |
| DELETE | `/api/v1/admin/informasi/{id}` | Hapus informasi | Yes: admin |
| POST | `/api/v1/admin/statistik/clear-cache` | Hapus cache statistik | Yes: admin |

---

## 3. WhatsApp Notification — Dual Provider

WhatsApp dikirim melalui **dua provider** dengan auto-fallback.

| Provider | Kapan Dipakai | Konfigurasi |
|----------|--------------|-------------|
| **WA Gateway** (primary) | `WHA_PROVIDER=wa-gateway` | Self-hosted Node.js, QR pairing |
| **Fonnte** (fallback) | Saat primary gagal | SaaS, token-only, 1000 gratis/bulan |

Auto-fallback berjalan otomatis: jika `wa-gateway` gagal dan `FONNTE_TOKEN` terisi, sistem mengirim ulang via Fonnte tanpa intervensi.

---

## 4. WA Gateway REST API

Service Node.js standalone di `https://wa.gampong.web.id` (atau `http://localhost:2785`).

**Auth:** Header `X-API-Key: {API_KEY}`.

### 4.1. Health

| Method | Path | Keterangan |
|--------|------|------------|
| GET | `/api/health` | Cek status server |

### 4.2. Session

| Method | Path | Keterangan |
|--------|------|------------|
| GET | `/api/sessions` | Daftar session aktif |
| POST | `/api/sessions` | Buat session baru |
| GET | `/api/sessions/:id/status` | Status koneksi |
| GET | `/api/sessions/:id/qr` | QR pairing (`?format=html`) |
| PUT | `/api/sessions/:id/webhook` | Set webhook URL |
| DELETE | `/api/sessions/:id` | Hapus session |

### 4.3. Messages

| Method | Path | Keterangan |
|--------|------|------------|
| POST | `/api/sessions/:id/messages/send-text` | Kirim teks |
| POST | `/api/sessions/:id/messages/send-image` | Kirim gambar |
| POST | `/api/sessions/:id/broadcast` | Broadcast massal |

### 4.4. Behavior & Analytics

| Method | Path | Keterangan |
|--------|------|------------|
| GET/POST | `/api/sessions/:id/behavior` | Konfigurasi anti-ban |
| GET | `/api/sessions/:id/analytics/volume` | Volume pesan per jam |

---

## 5. Catatan

- **Rate Limit API Laravel:** 60 request/menit default.
- **Auth WA Gateway:** Header `X-API-Key`. Jika kosong (dev), auth dilewati.
- **Format nomor WA:** `6281234567890` (diformat otomatis oleh sistem).
- **QR Expiry:** ~60 detik. Halaman `?format=html` auto-refresh tiap 12 detik.
- **Fonnte:** Tidak perlu pairing, cukup isi token di `.env`.
