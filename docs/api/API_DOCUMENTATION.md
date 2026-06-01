# API Documentation - SIG Udeung

## 📚 Dokumentasi API Lengkap

Dokumentasi API untuk Sistem Informasi Gampong (SIG) Udeung telah berhasil dibuat menggunakan **Scribe**.

## 🌐 Akses Dokumentasi

### 1. **Dokumentasi Web Interaktif**
```
http://localhost/docs
```
atau
```
http://your-domain.com/docs
```

Dokumentasi web menyediakan:
- ✅ Interface interaktif yang user-friendly
- ✅ Contoh request dalam Bash dan JavaScript
- ✅ Try It Out button untuk testing langsung
- ✅ Response examples untuk setiap endpoint
- ✅ Penjelasan lengkap parameter dan body
- ✅ Authentication guide

### 2. **Postman Collection**
```
storage/app/private/scribe/collection.json
```
atau akses via:
```
http://localhost/docs.postman
```

Import collection ini ke Postman untuk testing API dengan mudah.

### 3. **OpenAPI Specification**
```
storage/app/private/scribe/openapi.yaml
```
atau akses via:
```
http://localhost/docs.openapi
```

Gunakan OpenAPI spec untuk:
- Generate client SDK
- Import ke Swagger UI
- Integrasi dengan tools lain

## 📋 Daftar Endpoint

### Authentication
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| POST | `/api/v1/auth/login/warga` | Login warga dengan NIK | ❌ |
| POST | `/api/v1/auth/login/admin` | Login admin dengan username/password | ❌ |
| POST | `/api/v1/auth/logout` | Logout dan hapus token | ✅ |
| GET | `/api/v1/auth/profile` | Get profil user | ✅ |
| POST | `/api/v1/auth/bind-telegram` | Bind Telegram Chat ID | ✅ |

### Pengajuan Surat
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/v1/surat/kategori` | Daftar kategori surat | ❌ |
| GET | `/api/v1/surat/kategori/{id}` | Detail kategori surat | ❌ |
| POST | `/api/v1/surat/pengajuan` | Buat pengajuan surat | ✅ |
| GET | `/api/v1/surat/pengajuan` | Daftar pengajuan saya | ✅ |
| GET | `/api/v1/surat/pengajuan/{id}` | Detail pengajuan | ✅ |
| GET | `/api/v1/admin/surat/pengajuan` | [Admin] Daftar semua pengajuan | ✅ |
| POST | `/api/v1/admin/surat/pengajuan/{id}/approve` | [Admin] Setujui pengajuan | ✅ |
| POST | `/api/v1/admin/surat/pengajuan/{id}/reject` | [Admin] Tolak pengajuan | ✅ |

### Mutasi Penduduk
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| POST | `/api/v1/mutasi` | Buat pengajuan mutasi | ✅ |
| GET | `/api/v1/mutasi` | Daftar mutasi saya | ✅ |
| GET | `/api/v1/admin/mutasi` | [Admin] Daftar semua mutasi | ✅ |
| POST | `/api/v1/admin/mutasi/{id}/approve` | [Admin] Setujui mutasi | ✅ |
| POST | `/api/v1/admin/mutasi/{id}/reject` | [Admin] Tolak mutasi | ✅ |

### Informasi Publik
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/v1/informasi` | Daftar informasi publik | ❌ |
| GET | `/api/v1/informasi/{slug}` | Detail informasi | ❌ |
| GET | `/api/v1/admin/informasi` | [Admin] Daftar semua informasi | ✅ |
| POST | `/api/v1/admin/informasi` | [Admin] Buat informasi | ✅ |
| PUT | `/api/v1/admin/informasi/{id}` | [Admin] Update informasi | ✅ |
| DELETE | `/api/v1/admin/informasi/{id}` | [Admin] Hapus informasi | ✅ |

### Statistik
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/v1/statistik/demografi` | Statistik demografi | ❌ |
| GET | `/api/v1/statistik/layanan` | Statistik layanan | ❌ |
| POST | `/api/v1/admin/statistik/clear-cache` | [Admin] Clear cache | ✅ |

### Verifikasi & Webhook
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/v1/verifikasi/{hash}` | Verifikasi QR Code TTE | ❌ |
| POST | `/api/v1/telegram/webhook` | Telegram webhook handler | ❌ |

## 🔐 Authentication

### Untuk Warga
```bash
curl -X POST http://localhost/api/v1/auth/login/warga \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"nik": "1234567890123456"}'
```

Response:
```json
{
  "message": "Login berhasil",
  "user": {
    "nik": "1234567890123456",
    "nama_lengkap": "John Doe",
    ...
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```

### Untuk Admin
```bash
curl -X POST http://localhost/api/v1/auth/login/admin \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"username": "operator", "password": "password123"}'
```

Response:
```json
{
  "message": "Login berhasil",
  "user": {
    "id": 1,
    "username": "operator",
    "role": "operator"
  },
  "token": "2|abcdefghijklmnopqrstuvwxyz"
}
```

### Menggunakan Token
Setelah login, gunakan token di header setiap request:
```bash
curl -X GET http://localhost/api/v1/auth/profile \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

## 📝 Contoh Penggunaan

### 1. Login Warga
```javascript
const response = await fetch('http://localhost/api/v1/auth/login/warga', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    nik: '1234567890123456'
  })
});

const data = await response.json();
const token = data.token;
```

### 2. Buat Pengajuan Surat
```javascript
const response = await fetch('http://localhost/api/v1/surat/pengajuan', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    kategori_surat_id: 1,
    data_isian: {
      keperluan: 'Melamar pekerjaan',
      alamat_lengkap: 'Jl. Merdeka No. 123'
    },
    file_syarat: {
      ktp: 'https://storage.com/ktp.jpg',
      kk: 'https://storage.com/kk.jpg'
    }
  })
});

const data = await response.json();
```

### 3. Get Statistik Demografi
```javascript
const response = await fetch('http://localhost/api/v1/statistik/demografi', {
  headers: {
    'Accept': 'application/json'
  }
});

const data = await response.json();
console.log(data.data.total_penduduk);
```

### 4. Verifikasi QR Code
```javascript
const hash = 'abc123def456789';
const response = await fetch(`http://localhost/api/v1/verifikasi/${hash}`, {
  headers: {
    'Accept': 'application/json'
  }
});

const data = await response.json();
if (data.valid) {
  console.log('Dokumen valid:', data.data);
} else {
  console.log('Dokumen tidak valid:', data.message);
}
```

## 🎨 Fitur Dokumentasi

### 1. **Try It Out**
Dokumentasi web memiliki fitur "Try It Out" yang memungkinkan testing endpoint langsung dari browser.

### 2. **Code Examples**
Setiap endpoint memiliki contoh kode dalam:
- Bash (cURL)
- JavaScript (Fetch API)

### 3. **Response Examples**
Setiap endpoint menampilkan contoh response untuk:
- Success response (200, 201)
- Error response (400, 401, 403, 404, 422)

### 4. **Parameter Documentation**
Dokumentasi lengkap untuk:
- URL Parameters
- Query Parameters
- Body Parameters
- Headers

## 🔄 Update Dokumentasi

Jika ada perubahan pada API, regenerate dokumentasi dengan:

```bash
php artisan scribe:generate
```

## 📦 Export Dokumentasi

### Export ke Static HTML
Ubah config `type` menjadi `static` di `config/scribe.php`:
```php
'type' => 'static',
```

Kemudian generate:
```bash
php artisan scribe:generate
```

Dokumentasi akan di-generate ke folder `public/docs/` dan bisa di-deploy terpisah.

## 🚀 Deployment

### Production Setup
1. Set base URL di `.env`:
```env
APP_URL=https://your-domain.com
```

2. Generate dokumentasi:
```bash
php artisan scribe:generate
```

3. Dokumentasi akan tersedia di:
```
https://your-domain.com/docs
```

### Protect Documentation (Optional)
Jika ingin protect dokumentasi di production, tambahkan middleware di `config/scribe.php`:
```php
'laravel' => [
    'middleware' => ['auth:sanctum'],
],
```

## 📚 Resources

- **Scribe Documentation**: https://scribe.knuckles.wtf/laravel
- **Laravel Sanctum**: https://laravel.com/docs/sanctum
- **OpenAPI Specification**: https://swagger.io/specification/

## 🎯 Best Practices

1. **Selalu update PHPDoc** saat menambah/mengubah endpoint
2. **Regenerate dokumentasi** setelah perubahan API
3. **Test endpoint** menggunakan Postman collection
4. **Versioning API** untuk backward compatibility
5. **Rate limiting** untuk mencegah abuse
6. **CORS configuration** untuk frontend integration

## 🔒 Security Notes

1. **Token Security**: Jangan expose token di client-side logs
2. **HTTPS Only**: Gunakan HTTPS di production
3. **Rate Limiting**: Sudah aktif (60 req/min per IP)
4. **Input Validation**: Semua input sudah divalidasi
5. **SQL Injection**: Protected by Eloquent ORM
6. **XSS Protection**: Laravel default protection

## 📞 Support

Untuk pertanyaan atau issue terkait API:
- Email: support@gampong-udeung.go.id
- Telegram: @SIGUdeungBot
- GitHub: [repository-url]

---

**Generated by Scribe** - API Documentation Generator for Laravel
**Version**: 1.0.0
**Last Updated**: June 1, 2026
