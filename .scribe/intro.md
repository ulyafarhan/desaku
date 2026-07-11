# Pendahuluan

Dokumentasi ini menyediakan semua informasi yang Anda butuhkan untuk bekerja dengan API SIG-Udeung.

**Base URL:** `{base_url}/api/v1`

## Tentang SIG-Udeung

SIG-Udeung (Sistem Informasi Gampong Udeung) adalah platform digital administrasi desa untuk Gampong Udeung, Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh.

## Endpoint Overview

| Metode | Endpoint | Autentikasi | Deskripsi |
|--------|----------|-------------|-----------|
| POST | `/auth/login/warga` | Tidak | Login warga dengan NIK |
| POST | `/auth/login/admin` | Tidak | Login administrator |
| POST | `/auth/logout` | Ya | Logout pengguna |
| GET | `/auth/profile` | Ya | Ambil profil pengguna |
| POST | `/auth/bind-telegram` | Ya | Hubungkan akun Telegram |
| GET | `/informasi` | Tidak | Daftar informasi publik |
| GET | `/informasi/{slug}` | Tidak | Detail informasi |
| GET | `/statistik/demografi` | Tidak | Statistik demografi |
| GET | `/statistik/layanan` | Tidak | Statistik layanan |
| GET | `/verifikasi/{hash}` | Tidak | Verifikasi dokumen |
| POST | `/telegram/webhook` | Tidak | Webhook Telegram |
| GET | `/surat/kategori` | Ya | Daftar kategori surat |
| POST | `/surat/pengajuan` | Ya | Ajukan surat |
| GET | `/surat/pengajuan` | Ya | Riwayat pengajuan |
| POST | `/mutasi` | Ya | Ajukan mutasi |
| GET | `/mutasi` | Ya | Riwayat mutasi |
| GET | `/admin/surat/pengajuan` | Admin | Kelola pengajuan surat |
| POST | `/admin/surat/pengajuan/{id}/approve` | Admin | Setujui pengajuan |
| POST | `/admin/surat/pengajuan/{id}/reject` | Admin | Tolak pengajuan |
| GET | `/admin/mutasi` | Admin | Kelola mutasi |
| POST | `/admin/mutasi/{id}/approve` | Admin | Setujui mutasi |
| POST | `/admin/mutasi/{id}/reject` | Admin | Tolak mutasi |
| GET | `/admin/informasi` | Admin | Kelola informasi |
| POST | `/admin/informasi` | Admin | Buat informasi baru |
| PUT | `/admin/informasi/{id}` | Admin | Perbarui informasi |
| DELETE | `/admin/informasi/{id}` | Admin | Hapus informasi |
| POST | `/admin/statistik/clear-cache` | Admin | Hapus cache statistik |
