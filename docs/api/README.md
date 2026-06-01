# 📡 API Documentation

Dokumentasi lengkap untuk API SIG-Udeung.

## 📋 Daftar File

| File | Deskripsi | Untuk |
|------|-----------|-------|
| [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md) | Panduan lengkap API dengan semua detail | Semua developer |
| [`01_API_DOCUMENTATION.md`](01_API_DOCUMENTATION.md) | Panduan cara menggunakan dokumentasi | Developer baru |
| [`API_QUICK_REFERENCE.md`](API_QUICK_REFERENCE.md) | Referensi cepat untuk integrasi | Frontend developer |
| [`API_CONTRACT.md`](API_CONTRACT.md) | Kontrak API dengan TypeScript types | Frontend/Mobile developer |
| [`01_AUTH.md`](01_AUTH.md) | Dokumentasi sistem autentikasi | Backend developer |

## 🚀 Quick Start

### 1. Untuk Frontend Developer Baru
```
1. Baca: API_QUICK_REFERENCE.md
2. Review: API_CONTRACT.md
3. Test: http://localhost/docs
4. Import: Postman collection
```

### 2. Untuk Backend Developer
```
1. Baca: 01_AUTH.md
2. Review: API_DOCUMENTATION.md
3. Update: PHPDoc annotations
4. Generate: php artisan scribe:generate
```

### 3. Untuk Mobile Developer
```
1. Baca: API_CONTRACT.md (TypeScript types)
2. Review: API_QUICK_REFERENCE.md
3. Test: Postman collection
4. Integrate: Use OpenAPI spec
```

## 🌐 Web Documentation

### Interactive Documentation
**URL**: http://localhost/docs

**Features**:
- ✅ Try It Out functionality
- ✅ Code examples (Bash & JavaScript)
- ✅ Authentication guide
- ✅ Request/Response examples
- ✅ Search functionality

### Postman Collection
**URL**: http://localhost/docs.postman

**File**: `storage/app/private/scribe/collection.json`

### OpenAPI Specification
**URL**: http://localhost/docs.openapi

**File**: `storage/app/private/scribe/openapi.yaml`

## 📊 API Overview

### Total Endpoints: 29

#### Authentication (5)
- POST `/api/v1/auth/login/warga`
- POST `/api/v1/auth/login/admin`
- POST `/api/v1/auth/logout`
- GET `/api/v1/auth/profile`
- POST `/api/v1/auth/bind-telegram`

#### Pengajuan Surat (8)
- GET `/api/v1/surat/kategori`
- GET `/api/v1/surat/kategori/{id}`
- POST `/api/v1/surat/pengajuan`
- GET `/api/v1/surat/pengajuan`
- GET `/api/v1/surat/pengajuan/{id}`
- GET `/api/v1/admin/surat/pengajuan`
- POST `/api/v1/admin/surat/pengajuan/{id}/approve`
- POST `/api/v1/admin/surat/pengajuan/{id}/reject`

#### Mutasi Penduduk (5)
- POST `/api/v1/mutasi`
- GET `/api/v1/mutasi`
- GET `/api/v1/admin/mutasi`
- POST `/api/v1/admin/mutasi/{id}/approve`
- POST `/api/v1/admin/mutasi/{id}/reject`

#### Informasi Publik (6)
- GET `/api/v1/informasi`
- GET `/api/v1/informasi/{slug}`
- GET `/api/v1/admin/informasi`
- POST `/api/v1/admin/informasi`
- PUT `/api/v1/admin/informasi/{id}`
- DELETE `/api/v1/admin/informasi/{id}`

#### Statistik (3)
- GET `/api/v1/statistik/demografi`
- GET `/api/v1/statistik/layanan`
- POST `/api/v1/admin/statistik/clear-cache`

#### Verifikasi & Webhook (2)
- GET `/api/v1/verifikasi/{hash}`
- POST `/api/v1/telegram/webhook`

## 🔐 Authentication

### Base URL
```
http://localhost/api/v1
```

### Headers
```javascript
{
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  'Authorization': 'Bearer {token}' // untuk endpoint yang memerlukan auth
}
```

### Login Flow
```javascript
// 1. Login
POST /auth/login/warga
Body: { "nik": "1234567890123456" }

// 2. Get token
Response: { "token": "...", "user": {...} }

// 3. Store token
localStorage.setItem('auth_token', token);

// 4. Use token
Headers: { 'Authorization': 'Bearer {token}' }
```

## 📝 File Details

### API_DOCUMENTATION.md
**Ukuran**: ~9 KB  
**Isi**:
- Panduan lengkap semua endpoint
- Contoh request/response
- Error handling
- Best practices
- Security notes

**Baca jika**: Butuh referensi lengkap API

### API_QUICK_REFERENCE.md
**Ukuran**: ~13 KB  
**Isi**:
- Quick start guide
- Common workflows
- React/Vue examples
- Error handling
- Type definitions

**Baca jika**: Mau cepat integrasi frontend

### API_CONTRACT.md
**Ukuran**: ~16 KB  
**Isi**:
- TypeScript type definitions
- Request/Response contracts
- Enum values
- Pagination structure
- Error formats

**Baca jika**: Butuh type safety dan kontrak jelas

### 01_AUTH.md
**Ukuran**: ~3 KB  
**Isi**:
- Authentication implementation
- Login flows
- Token management
- Security considerations

**Baca jika**: Implementasi auth di backend

### 01_API_DOCUMENTATION.md
**Ukuran**: ~9 KB  
**Isi**:
- Cara menggunakan dokumentasi
- Update documentation
- PHPDoc examples
- Best practices

**Baca jika**: Mau update/maintain dokumentasi

## 🔄 Update Documentation

### Kapan Update?
- Menambah endpoint baru
- Mengubah request/response format
- Menambah parameter baru
- Mengubah validation rules
- Menambah error handling

### Cara Update?

1. **Update PHPDoc di Controller**
```php
/**
 * @group Group Name
 * @authenticated
 * @bodyParam field type required Description. Example: value
 * @response 200 {"data": {...}}
 */
public function method() {}
```

2. **Generate Documentation**
```bash
php artisan scribe:generate
```

3. **Review Changes**
```
http://localhost/docs
```

4. **Update Manual Docs** (jika perlu)
```bash
# Edit file markdown yang relevan
vim docs/api/API_QUICK_REFERENCE.md
```

## 🎯 Use Cases

### Use Case 1: Frontend Integration
```
1. Read: API_QUICK_REFERENCE.md
2. Review: API_CONTRACT.md
3. Import: Postman collection
4. Test: All endpoints
5. Implement: API service layer
```

### Use Case 2: Mobile App Development
```
1. Read: API_CONTRACT.md
2. Generate: SDK from OpenAPI spec
3. Test: Postman collection
4. Implement: API client
```

### Use Case 3: Third-party Integration
```
1. Read: API_DOCUMENTATION.md
2. Get: API credentials
3. Test: Postman collection
4. Integrate: Using OpenAPI spec
```

### Use Case 4: API Testing
```
1. Import: Postman collection
2. Setup: Environment variables
3. Test: All endpoints
4. Report: Issues/bugs
```

## 📊 Statistics

- **Total Endpoints**: 29
- **Public Endpoints**: 8
- **Authenticated Endpoints**: 21
- **Admin-only Endpoints**: 13
- **Groups**: 6
- **PHPDoc Annotations**: 100+
- **Code Examples**: 58+
- **Response Examples**: 60+

## 🔒 Security

- ✅ Laravel Sanctum authentication
- ✅ Bearer token authorization
- ✅ Input validation
- ✅ SQL injection protection
- ✅ XSS protection
- ✅ CSRF protection
- ✅ Rate limiting (60 req/min)

## 📞 Support

### Documentation
- **Web**: http://localhost/docs
- **Postman**: http://localhost/docs.postman
- **OpenAPI**: http://localhost/docs.openapi

### Contact
- **Email**: support@gampong-udeung.go.id
- **Telegram**: @SIGUdeungBot

### Resources
- **Scribe**: https://scribe.knuckles.wtf/laravel
- **Laravel Sanctum**: https://laravel.com/docs/sanctum
- **OpenAPI**: https://swagger.io/specification/

---

**Version**: 1.0.0  
**Last Updated**: June 1, 2026  
**Status**: ✅ Production Ready
