# API Contract Specification - SIG Udeung

## 📋 Contract Overview

Dokumen ini adalah kontrak API antara Backend dan Frontend untuk memastikan integrasi yang konsisten dan bebas dari halusinasi.

## 🔐 Authentication Contract

### Login Warga
```typescript
// Request
POST /api/v1/auth/login/warga
Content-Type: application/json

{
  nik: string; // 16 digit, required
}

// Success Response (200)
{
  message: string;
  user: {
    nik: string;
    nama_lengkap: string;
    tempat_lahir: string;
    tanggal_lahir: string; // YYYY-MM-DD
    jenis_kelamin: "L" | "P";
    agama: string;
    pendidikan: string;
    pekerjaan: string;
    status_perkawinan: string;
    status_keluarga: string;
    status_mutasi: "Tetap" | "Pindah" | "Meninggal";
    telegram_chat_id?: string;
  };
  token: string;
}

// Error Response (422)
{
  message: string;
  errors: {
    nik: string[];
  }
}
```

### Login Admin
```typescript
// Request
POST /api/v1/auth/login/admin
Content-Type: application/json

{
  username: string; // required
  password: string; // required
}

// Success Response (200)
{
  message: string;
  user: {
    id: number;
    username: string;
    role: "keuchik" | "sekdes" | "operator";
    created_at: string; // ISO 8601
  };
  token: string;
}

// Error Response (422)
{
  message: string;
  errors: {
    username: string[];
  }
}
```

### Logout
```typescript
// Request
POST /api/v1/auth/logout
Authorization: Bearer {token}

// Success Response (200)
{
  message: string;
}
```

### Get Profile
```typescript
// Request
GET /api/v1/auth/profile
Authorization: Bearer {token}

// Success Response (200)
{
  user: {
    // Same as login response user object
    // Plus keluarga relation if warga
    keluarga?: {
      no_kk: string;
      alamat: string;
      rt: string;
      rw: string;
      dusun: string;
    }
  }
}
```

## 📝 Pengajuan Surat Contract

### Get Kategori Surat
```typescript
// Request
GET /api/v1/surat/kategori

// Success Response (200)
{
  data: Array<{
    id: number;
    nama_surat: string;
    kode_surat: string;
    deskripsi: string;
    template_path: string;
    persyaratan: string[]; // ["KTP", "KK", "Foto"]
    field_isian: string[]; // ["keperluan", "alamat_lengkap"]
    is_active: boolean;
    created_at: string;
  }>
}
```

### Create Pengajuan
```typescript
// Request
POST /api/v1/surat/pengajuan
Authorization: Bearer {token}
Content-Type: application/json

{
  kategori_surat_id: number; // required
  data_isian: Record<string, any>; // required, sesuai field_isian
  file_syarat: Record<string, string>; // required, sesuai persyaratan
}

// Example
{
  kategori_surat_id: 1,
  data_isian: {
    keperluan: "Melamar pekerjaan",
    alamat_lengkap: "Jl. Merdeka No. 123"
  },
  file_syarat: {
    ktp: "https://storage.com/ktp.jpg",
    kk: "https://storage.com/kk.jpg"
  }
}

// Success Response (201)
{
  message: string;
  data: {
    id: number;
    nomor_registrasi: string; // "REG/YYYY/MM/XXXXX"
    nik_pemohon: string;
    kategori_surat_id: number;
    data_isian: Record<string, any>;
    file_syarat: Record<string, string>;
    status: "Pending";
    file_surat: null;
    qr_hash: null;
    catatan_penolakan: null;
    diverifikasi_oleh: null;
    created_at: string;
    updated_at: string;
    kategori: {
      id: number;
      nama_surat: string;
      kode_surat: string;
    }
  }
}
```

### Get My Pengajuan
```typescript
// Request
GET /api/v1/surat/pengajuan?page=1
Authorization: Bearer {token}

// Success Response (200)
{
  data: Array<PengajuanSurat>;
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
  }
}

type PengajuanSurat = {
  id: number;
  nomor_registrasi: string;
  nik_pemohon: string;
  kategori_surat_id: number;
  data_isian: Record<string, any>;
  file_syarat: Record<string, string>;
  status: "Pending" | "Diproses" | "Disetujui" | "Ditolak" | "Selesai";
  file_surat: string | null;
  qr_hash: string | null;
  catatan_penolakan: string | null;
  diverifikasi_oleh: number | null;
  created_at: string;
  updated_at: string;
  kategori: {
    id: number;
    nama_surat: string;
    kode_surat: string;
  };
  tracking: Array<{
    id: number;
    status_sebelumnya: string | null;
    status_baru: string;
    keterangan_update: string;
    diupdate_oleh: number | null;
    created_at: string;
  }>;
}
```

### Get Pengajuan Detail
```typescript
// Request
GET /api/v1/surat/pengajuan/{id}
Authorization: Bearer {token}

// Success Response (200)
{
  data: {
    // Same as PengajuanSurat type above
    // Plus pemohon and tracking.updater relations
    pemohon: {
      nik: string;
      nama_lengkap: string;
      // ... other fields
    };
    tracking: Array<{
      id: number;
      status_sebelumnya: string | null;
      status_baru: string;
      keterangan_update: string;
      diupdate_oleh: number | null;
      created_at: string;
      updater: {
        id: number;
        username: string;
        role: string;
      } | null;
    }>;
  }
}
```

### [Admin] Get All Pengajuan
```typescript
// Request
GET /api/v1/admin/surat/pengajuan?status=Pending&page=1
Authorization: Bearer {admin_token}

// Query Params (optional)
status?: "Pending" | "Diproses" | "Disetujui" | "Ditolak" | "Selesai"

// Success Response (200)
{
  data: Array<PengajuanSurat>;
  links: { /* pagination links */ };
  meta: { /* pagination meta */ };
}
```

### [Admin] Approve Pengajuan
```typescript
// Request
POST /api/v1/admin/surat/pengajuan/{id}/approve
Authorization: Bearer {admin_token}

// Success Response (200)
{
  message: string;
  data: PengajuanSurat;
}
```

### [Admin] Reject Pengajuan
```typescript
// Request
POST /api/v1/admin/surat/pengajuan/{id}/reject
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  catatan_penolakan: string; // required
}

// Success Response (200)
{
  message: string;
  data: PengajuanSurat;
}
```

## 👥 Mutasi Penduduk Contract

### Create Mutasi
```typescript
// Request
POST /api/v1/mutasi
Authorization: Bearer {token}
Content-Type: application/json

{
  nik: string; // 16 digit, required
  jenis_mutasi: "Kelahiran" | "Kematian" | "Kedatangan" | "Kepindahan"; // required
  tanggal_mutasi: string; // YYYY-MM-DD, required
  keterangan: string; // required
  dokumen_bukti: string; // URL, required
}

// Success Response (201)
{
  message: string;
  data: {
    id: number;
    nik: string;
    jenis_mutasi: string;
    tanggal_mutasi: string;
    keterangan: string;
    dokumen_bukti: string;
    status_verifikasi: "Pending";
    diverifikasi_oleh: null;
    created_at: string;
    updated_at: string;
  }
}
```

### Get My Mutasi
```typescript
// Request
GET /api/v1/mutasi?page=1
Authorization: Bearer {token}

// Success Response (200)
{
  data: Array<MutasiPenduduk>;
  links: { /* pagination */ };
  meta: { /* pagination */ };
}

type MutasiPenduduk = {
  id: number;
  nik: string;
  jenis_mutasi: "Kelahiran" | "Kematian" | "Kedatangan" | "Kepindahan";
  tanggal_mutasi: string;
  keterangan: string;
  dokumen_bukti: string;
  status_verifikasi: "Pending" | "Disetujui" | "Ditolak";
  diverifikasi_oleh: number | null;
  created_at: string;
  updated_at: string;
  penduduk: {
    nik: string;
    nama_lengkap: string;
  };
}
```

### [Admin] Get All Mutasi
```typescript
// Request
GET /api/v1/admin/mutasi?status_verifikasi=Pending&jenis_mutasi=Kelahiran&page=1
Authorization: Bearer {admin_token}

// Query Params (optional)
status_verifikasi?: "Pending" | "Disetujui" | "Ditolak"
jenis_mutasi?: "Kelahiran" | "Kematian" | "Kedatangan" | "Kepindahan"

// Success Response (200)
{
  data: Array<MutasiPenduduk>;
  links: { /* pagination */ };
  meta: { /* pagination */ };
}
```

### [Admin] Approve/Reject Mutasi
```typescript
// Approve
POST /api/v1/admin/mutasi/{id}/approve
Authorization: Bearer {admin_token}

// Reject
POST /api/v1/admin/mutasi/{id}/reject
Authorization: Bearer {admin_token}

// Success Response (200)
{
  message: string;
  data: MutasiPenduduk;
}
```

## 📰 Informasi Publik Contract

### Get Informasi (Public)
```typescript
// Request
GET /api/v1/informasi?kategori=Berita&page=1

// Query Params (optional)
kategori?: string

// Success Response (200)
{
  data: Array<InformasiPublik>;
  links: { /* pagination */ };
  meta: { /* pagination */ };
}

type InformasiPublik = {
  id: number;
  judul: string;
  slug: string;
  konten: string; // HTML
  kategori: string;
  cover_image: string | null;
  is_published: boolean;
  author_id: number;
  created_at: string;
  updated_at: string;
  author: {
    id: number;
    username: string;
  };
}
```

### Get Informasi Detail (Public)
```typescript
// Request
GET /api/v1/informasi/{slug}

// Success Response (200)
{
  data: InformasiPublik;
}
```

### [Admin] CRUD Informasi
```typescript
// Get All (including drafts)
GET /api/v1/admin/informasi?is_published=true&page=1
Authorization: Bearer {admin_token}

// Create
POST /api/v1/admin/informasi
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  judul: string; // required
  konten: string; // required, HTML
  kategori: string; // required
  cover_image?: string; // optional, URL
  is_published?: boolean; // optional, default false
}

// Update
PUT /api/v1/admin/informasi/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  judul?: string;
  konten?: string;
  kategori?: string;
  cover_image?: string;
  is_published?: boolean;
}

// Delete
DELETE /api/v1/admin/informasi/{id}
Authorization: Bearer {admin_token}

// Success Response (200)
{
  message: string;
}
```

## 📊 Statistik Contract

### Get Statistik Demografi
```typescript
// Request
GET /api/v1/statistik/demografi

// Success Response (200)
{
  data: {
    total_penduduk: number;
    total_keluarga: number;
    jenis_kelamin: {
      L: number;
      P: number;
    };
    agama: Record<string, number>;
    pendidikan: Record<string, number>;
    pekerjaan: Record<string, number>;
    status_perkawinan: Record<string, number>;
  }
}
```

### Get Statistik Layanan
```typescript
// Request
GET /api/v1/statistik/layanan

// Success Response (200)
{
  data: {
    pengajuan_surat: {
      total: number;
      pending: number;
      diproses: number;
      disetujui: number;
      ditolak: number;
      selesai: number;
      per_kategori: Record<string, number>;
    };
    mutasi_penduduk: {
      total: number;
      pending: number;
      disetujui: number;
      ditolak: number;
      per_jenis: Record<string, number>;
    };
  }
}
```

### [Admin] Clear Cache
```typescript
// Request
POST /api/v1/admin/statistik/clear-cache
Authorization: Bearer {admin_token}

// Success Response (200)
{
  message: string;
}
```

## ✅ Verifikasi Contract

### Verify QR Code
```typescript
// Request
GET /api/v1/verifikasi/{hash}

// Success Response (200) - Valid
{
  valid: true;
  message: string;
  data: {
    nomor_registrasi: string;
    jenis_surat: string;
    nama_pemohon: string;
    nik_pemohon: string;
    tanggal_terbit: string; // DD-MM-YYYY
    diverifikasi_oleh: string;
  }
}

// Success Response (200) - Not Finished
{
  valid: false;
  message: "Dokumen belum selesai diproses";
}

// Error Response (404) - Not Found
{
  valid: false;
  message: "Dokumen tidak ditemukan atau tidak valid";
}
```

## 🤖 Telegram Contract

### Bind Telegram
```typescript
// Request
POST /api/v1/auth/bind-telegram
Authorization: Bearer {token}
Content-Type: application/json

{
  telegram_chat_id: string; // required, unique
}

// Success Response (200)
{
  message: string;
}

// Error Response (403)
{
  message: "Hanya warga yang dapat bind Telegram";
}

// Error Response (422)
{
  message: string;
  errors: {
    telegram_chat_id: ["The telegram chat id has already been taken."];
  }
}
```

## 🔄 Pagination Contract

All paginated endpoints return:
```typescript
{
  data: Array<T>;
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
  }
}
```

## ⚠️ Error Contract

### Validation Error (422)
```typescript
{
  message: string;
  errors: Record<string, string[]>;
}
```

### Unauthorized (401)
```typescript
{
  message: "Unauthenticated.";
}
```

### Forbidden (403)
```typescript
{
  message: string;
}
```

### Not Found (404)
```typescript
{
  message: string;
}
```

### Server Error (500)
```typescript
{
  message: "Server Error";
}
```

## 📝 Type Definitions (TypeScript)

```typescript
// Enums
type JenisKelamin = "L" | "P";
type StatusMutasi = "Tetap" | "Pindah" | "Meninggal";
type RoleAdmin = "keuchik" | "sekdes" | "operator";
type StatusPengajuan = "Pending" | "Diproses" | "Disetujui" | "Ditolak" | "Selesai";
type StatusVerifikasi = "Pending" | "Disetujui" | "Ditolak";
type JenisMutasi = "Kelahiran" | "Kematian" | "Kedatangan" | "Kepindahan";

// User Types
interface Warga {
  nik: string;
  nama_lengkap: string;
  tempat_lahir: string;
  tanggal_lahir: string;
  jenis_kelamin: JenisKelamin;
  agama: string;
  pendidikan: string;
  pekerjaan: string;
  status_perkawinan: string;
  status_keluarga: string;
  status_mutasi: StatusMutasi;
  telegram_chat_id?: string;
  keluarga?: Keluarga;
}

interface Admin {
  id: number;
  username: string;
  role: RoleAdmin;
  created_at: string;
}

interface Keluarga {
  no_kk: string;
  alamat: string;
  rt: string;
  rw: string;
  dusun: string;
}

// API Response Types
interface LoginResponse {
  message: string;
  user: Warga | Admin;
  token: string;
}

interface PaginatedResponse<T> {
  data: T[];
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
  };
}

interface ErrorResponse {
  message: string;
  errors?: Record<string, string[]>;
}
```

## 🎯 Contract Guarantees

### Backend Guarantees
1. ✅ All endpoints return JSON
2. ✅ All dates in ISO 8601 format (YYYY-MM-DD or full timestamp)
3. ✅ All paginated responses follow same structure
4. ✅ All error responses follow same structure
5. ✅ Token authentication via Bearer token
6. ✅ CORS enabled for frontend domain
7. ✅ Rate limiting: 60 requests per minute
8. ✅ Response time: < 500ms (cached), < 2s (uncached)

### Frontend Responsibilities
1. ✅ Store token securely (localStorage/sessionStorage)
2. ✅ Include Authorization header for protected endpoints
3. ✅ Handle 401 (redirect to login)
4. ✅ Handle 422 (show validation errors)
5. ✅ Handle 500 (show error message)
6. ✅ Implement loading states
7. ✅ Implement error states
8. ✅ Respect rate limiting

## 📞 Contract Violations

If you encounter any deviation from this contract:
1. Check API documentation: http://localhost/docs
2. Verify request format matches contract
3. Check response in browser DevTools
4. Report to backend team with:
   - Endpoint URL
   - Request payload
   - Expected response
   - Actual response
   - Error message (if any)

---

**Contract Version**: 1.0.0
**Last Updated**: June 1, 2026
**Status**: ✅ ACTIVE
