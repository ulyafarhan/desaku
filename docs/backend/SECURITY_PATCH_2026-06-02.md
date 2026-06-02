# Security Patch 2026-06-02

Dokumen ini mencatat patch perbaikan yang dibuat dari telaah risiko keamanan dan integrasi API.

## Ringkasan Perbaikan

1. Login warga tidak lagi cukup memakai NIK saja. Endpoint `POST /api/v1/auth/login/warga` sekarang mewajibkan `nik` dan `no_kk`, lalu hanya menerbitkan token jika pasangan data cocok dan status warga masih `Tetap`.
2. Token warga sekarang dibuat dengan ability `warga`; token admin tetap dibuat dengan ability `admin`.
3. Route warga dan admin dipisahkan dengan middleware Sanctum ability:
   - Route warga: `auth:sanctum` + `ability:warga`
   - Route admin: `auth:sanctum` + `abilities:admin`
   - Logout dan profile tetap tersedia untuk semua token yang valid.
4. Endpoint login diberi rate limit `throttle:5,1`; endpoint Telegram webhook diberi `throttle:60,1`.
5. Webhook Telegram tidak lagi memanggil Gemini AI secara synchronous untuk pesan biasa. Pesan didispatch ke `ProcessTelegramMessageJob`, sehingga webhook dapat segera membalas `200 OK`.
6. Notifikasi Telegram yang memakai `parse_mode=HTML` sekarang meng-escape nilai dinamis seperti nomor registrasi, jenis mutasi, status, dan catatan admin.

## File Yang Diubah

- `bootstrap/app.php`: alias middleware Sanctum `ability` dan `abilities`.
- `routes/api.php`: throttle dan pemisahan route berdasarkan ability.
- `app/Http/Controllers/Api/AuthController.php`: validasi `no_kk` dan token ability warga.
- `app/Http/Controllers/Api/TelegramWebhookController.php`: dispatch job untuk pesan AI.
- `app/Jobs/ProcessTelegramMessageJob.php`: job baru untuk proses Gemini dan balasan Telegram.
- `app/Services/TelegramService.php`: escaping HTML untuk notifikasi dinamis.
- `tests/Feature/*`: penyesuaian token warga dan login warga.
- `docs/api/API_CONTRACT.md`, `docs/api/API_QUICK_REFERENCE.md`, `docs/api/01_AUTH.md`: kontrak login warga diperbarui.

## Dampak Ke Frontend

Frontend wajib mengirim `no_kk` saat login warga:

```json
{
  "nik": "1234567890123456",
  "no_kk": "1234567890123456"
}
```

Token warga yang diterima dari endpoint login hanya bisa mengakses endpoint warga. Token warga tidak bisa mengakses endpoint `/api/v1/admin/*`.

## Catatan Lanjutan

Patch ini menutup risiko langsung tanpa migrasi skema besar. Untuk hardening berikutnya, autentikasi warga sebaiknya ditingkatkan ke aktivasi password atau OTP, dan tabel `penduduk` sebaiknya memakai surrogate key (`id`) dengan `nik` sebagai unique index.
