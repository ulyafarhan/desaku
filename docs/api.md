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

## 3. WA Gateway REST API

Service Node.js standalone di `https://wa.gampong.web.id` (atau `http://localhost:2785`).

**Auth:** Header `X-API-Key: {API_KEY}` — lihat `.env` WA Gateway.

### 3.1. Health

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/api/health` | Cek status server WA Gateway | No |

### 3.2. Session Management

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/api/sessions` | Daftar semua session aktif | Yes |
| POST | `/api/sessions` | Buat session baru | Yes |
| GET | `/api/sessions/:id/status` | Status koneksi WhatsApp | Yes |
| GET | `/api/sessions/:id/qr` | QR code pairing (`?format=html` untuk HTML view) | Yes |
| PUT | `/api/sessions/:id/webhook` | Set webhook URL untuk session | Yes |
| DELETE | `/api/sessions/:id` | Hapus session & disconnect | Yes |

### 3.3. Messages

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| POST | `/api/sessions/:id/messages` | Kirim pesan (type: text/image/audio/document) | Yes |
| POST | `/api/sessions/:id/messages/send-text` | Kirim pesan teks (legacy) | Yes |
| POST | `/api/sessions/:id/messages/send-image` | Kirim gambar (legacy) | Yes |
| GET | `/api/sessions/:id/messages` | Riwayat pesan terkirim | Yes |
| POST | `/api/sessions/:id/broadcast` | Broadcast ke banyak nomor | Yes |
| GET | `/api/sessions/:id/incoming` | Pesan masuk terbaru (50 terakhir) | Yes |

### 3.4. Behavior Engine (AI)

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/api/sessions/:id/behavior` | Konfigurasi AI behavior | Yes |
| POST | `/api/sessions/:id/behavior` | Update konfigurasi AI behavior | Yes |

### 3.5. User Profiles

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/api/sessions/:id/users` | Daftar profil user | Yes |
| GET | `/api/sessions/:id/users/:userId` | Detail profil user | Yes |
| PUT | `/api/sessions/:id/users/:userId/persona` | Update persona user (quick/normal/relaxed) | Yes |

### 3.6. FAQ

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/api/sessions/:id/faq` | Daftar FAQ | Yes |
| POST | `/api/sessions/:id/faq` | Tambah FAQ baru | Yes |
| DELETE | `/api/sessions/:id/faq/:faqId` | Hapus FAQ | Yes |

### 3.7. Templates

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/api/sessions/:id/templates` | Daftar template pesan | Yes |
| POST | `/api/sessions/:id/templates/:intent` | Simpan template per intent | Yes |

### 3.8. Analytics

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| GET | `/api/sessions/:id/analytics/personas` | Distribusi persona user | Yes |
| GET | `/api/sessions/:id/analytics/sources` | Sumber pesan masuk | Yes |
| GET | `/api/sessions/:id/analytics/volume` | Volume pesan per jam (24 jam) | Yes |

---

## 4. Catatan

- **Rate Limit API Laravel:** 60 request/menit default, kecuali disebut khusus.
- **Auth WA Gateway:** Header `X-API-Key` atau query param `?apikey=`. Jika `API_KEY` kosong (dev), auth dilewati.
- **Format ChatId WA:** Nomor dengan kode negara tanpa `+` atau spasi, contoh: `6281234567890@s.whatsapp.net` atau cukup `6281234567890` (akan diformat otomatis).
- **Broadcast WA:** Maksimal ~100 nomor per request (rate limit internal 1.5s/pesan).
- **QR Expiry:** QR code WhatsApp berlaku ~60 detik. Halaman `?format=html` auto-refresh tiap 12 detik.
