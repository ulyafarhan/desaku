# API Quick Reference - Frontend Integration

## 🚀 Quick Start

### Base URL
```
http://localhost/api/v1
```

### Headers (Required)
```javascript
{
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  'Authorization': 'Bearer {token}' // untuk endpoint yang memerlukan auth
}
```

## 🔐 Authentication Flow

### 1. Login Warga
```javascript
POST /auth/login/warga
Body: { "nik": "1234567890123456" }
Response: { "token": "...", "user": {...} }
```

### 2. Login Admin
```javascript
POST /auth/login/admin
Body: { "username": "operator", "password": "password123" }
Response: { "token": "...", "user": {...} }
```

### 3. Store Token
```javascript
localStorage.setItem('auth_token', response.token);
localStorage.setItem('user', JSON.stringify(response.user));
```

### 4. Use Token
```javascript
const token = localStorage.getItem('auth_token');
headers: { 'Authorization': `Bearer ${token}` }
```

### 5. Logout
```javascript
POST /auth/logout
Headers: { 'Authorization': 'Bearer {token}' }
// Clear local storage
localStorage.removeItem('auth_token');
localStorage.removeItem('user');
```

## 📋 Common Workflows

### Workflow 1: Pengajuan Surat (Warga)

```javascript
// Step 1: Get kategori surat
GET /surat/kategori
// Response: [{ id: 1, nama_surat: "...", persyaratan: [...], field_isian: [...] }]

// Step 2: Upload file persyaratan (implement your own upload)
// Upload KTP, KK, dll ke storage
// Get URLs: { ktp: "url1", kk: "url2" }

// Step 3: Submit pengajuan
POST /surat/pengajuan
Headers: { 'Authorization': 'Bearer {token}' }
Body: {
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

// Step 4: Track pengajuan
GET /surat/pengajuan
// Response: [{ id: 1, nomor_registrasi: "...", status: "Pending", ... }]

// Step 5: Get detail & tracking
GET /surat/pengajuan/{id}
// Response: { data: { ..., tracking: [...] } }
```

### Workflow 2: Approve Pengajuan (Admin)

```javascript
// Step 1: Get semua pengajuan
GET /admin/surat/pengajuan?status=Pending
Headers: { 'Authorization': 'Bearer {admin_token}' }

// Step 2: Approve
POST /admin/surat/pengajuan/{id}/approve
Headers: { 'Authorization': 'Bearer {admin_token}' }

// OR Reject
POST /admin/surat/pengajuan/{id}/reject
Headers: { 'Authorization': 'Bearer {admin_token}' }
Body: { catatan_penolakan: "Dokumen tidak lengkap" }
```

### Workflow 3: Mutasi Penduduk

```javascript
// Step 1: Submit mutasi
POST /mutasi
Headers: { 'Authorization': 'Bearer {token}' }
Body: {
  nik: "1234567890123456",
  jenis_mutasi: "Kelahiran", // Kelahiran, Kematian, Kedatangan, Kepindahan
  tanggal_mutasi: "2026-06-01",
  keterangan: "Lahir di RSUD",
  dokumen_bukti: "https://storage.com/akta.jpg"
}

// Step 2: Track mutasi
GET /mutasi
Headers: { 'Authorization': 'Bearer {token}' }
```

### Workflow 4: Informasi Publik

```javascript
// Public: Get informasi
GET /informasi?kategori=Berita
// Response: { data: [...], links: {...}, meta: {...} }

// Public: Get detail
GET /informasi/{slug}
// Response: { data: { judul, konten, cover_image, ... } }

// Admin: Create informasi
POST /admin/informasi
Headers: { 'Authorization': 'Bearer {admin_token}' }
Body: {
  judul: "Musyawarah Gampong 2026",
  konten: "<p>Akan dilaksanakan...</p>",
  kategori: "Pengumuman",
  cover_image: "https://storage.com/cover.jpg",
  is_published: true
}
```

### Workflow 5: Statistik Dashboard

```javascript
// Get statistik demografi
GET /statistik/demografi
// Response: {
//   data: {
//     total_penduduk: 1250,
//     total_keluarga: 320,
//     jenis_kelamin: { L: 625, P: 625 },
//     agama: {...},
//     pendidikan: {...},
//     pekerjaan: {...}
//   }
// }

// Get statistik layanan
GET /statistik/layanan
// Response: {
//   data: {
//     pengajuan_surat: { total, pending, disetujui, ... },
//     mutasi_penduduk: { total, pending, ... }
//   }
// }
```

### Workflow 6: Verifikasi QR Code

```javascript
// Scan QR Code, get hash
const hash = "abc123def456789";

// Verify
GET /verifikasi/{hash}
// Response: {
//   valid: true,
//   message: "Dokumen valid",
//   data: {
//     nomor_registrasi: "...",
//     jenis_surat: "...",
//     nama_pemohon: "...",
//     tanggal_terbit: "..."
//   }
// }
```

## 🎨 React/Vue Example

### React Hook for API Calls

```javascript
// useApi.js
import { useState, useEffect } from 'react';

const API_BASE = 'http://localhost/api/v1';

export const useApi = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const getToken = () => localStorage.getItem('auth_token');

  const apiCall = async (endpoint, options = {}) => {
    setLoading(true);
    setError(null);

    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options.headers,
    };

    const token = getToken();
    if (token && !options.skipAuth) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    try {
      const response = await fetch(`${API_BASE}${endpoint}`, {
        ...options,
        headers,
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Request failed');
      }

      setLoading(false);
      return data;
    } catch (err) {
      setError(err.message);
      setLoading(false);
      throw err;
    }
  };

  return { apiCall, loading, error };
};

// Usage in component
function PengajuanList() {
  const { apiCall, loading, error } = useApi();
  const [pengajuan, setPengajuan] = useState([]);

  useEffect(() => {
    const fetchPengajuan = async () => {
      try {
        const data = await apiCall('/surat/pengajuan');
        setPengajuan(data.data);
      } catch (err) {
        console.error('Failed to fetch:', err);
      }
    };

    fetchPengajuan();
  }, []);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      {pengajuan.map(item => (
        <div key={item.id}>{item.nomor_registrasi}</div>
      ))}
    </div>
  );
}
```

### Vue Composable for API Calls

```javascript
// useApi.js
import { ref } from 'vue';

const API_BASE = 'http://localhost/api/v1';

export const useApi = () => {
  const loading = ref(false);
  const error = ref(null);

  const getToken = () => localStorage.getItem('auth_token');

  const apiCall = async (endpoint, options = {}) => {
    loading.value = true;
    error.value = null;

    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options.headers,
    };

    const token = getToken();
    if (token && !options.skipAuth) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    try {
      const response = await fetch(`${API_BASE}${endpoint}`, {
        ...options,
        headers,
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Request failed');
      }

      loading.value = false;
      return data;
    } catch (err) {
      error.value = err.message;
      loading.value = false;
      throw err;
    }
  };

  return { apiCall, loading, error };
};

// Usage in component
<script setup>
import { ref, onMounted } from 'vue';
import { useApi } from './useApi';

const { apiCall, loading, error } = useApi();
const pengajuan = ref([]);

onMounted(async () => {
  try {
    const data = await apiCall('/surat/pengajuan');
    pengajuan.value = data.data;
  } catch (err) {
    console.error('Failed to fetch:', err);
  }
});
</script>

<template>
  <div v-if="loading">Loading...</div>
  <div v-else-if="error">Error: {{ error }}</div>
  <div v-else>
    <div v-for="item in pengajuan" :key="item.id">
      {{ item.nomor_registrasi }}
    </div>
  </div>
</template>
```

## 🔄 Pagination

Endpoints yang mengembalikan list menggunakan Laravel pagination:

```javascript
GET /surat/pengajuan?page=2

Response:
{
  "data": [...],
  "links": {
    "first": "http://localhost/api/v1/surat/pengajuan?page=1",
    "last": "http://localhost/api/v1/surat/pengajuan?page=5",
    "prev": "http://localhost/api/v1/surat/pengajuan?page=1",
    "next": "http://localhost/api/v1/surat/pengajuan?page=3"
  },
  "meta": {
    "current_page": 2,
    "from": 11,
    "last_page": 5,
    "per_page": 10,
    "to": 20,
    "total": 50
  }
}
```

## ⚠️ Error Handling

### Standard Error Response
```javascript
{
  "message": "Error message",
  "errors": {
    "field_name": ["Error detail 1", "Error detail 2"]
  }
}
```

### Common HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized (token invalid/expired)
- `403` - Forbidden (no permission)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

### Error Handling Example
```javascript
try {
  const data = await apiCall('/surat/pengajuan', {
    method: 'POST',
    body: JSON.stringify(formData)
  });
  
  // Success
  console.log('Success:', data);
  
} catch (err) {
  // Handle error
  if (err.message.includes('401')) {
    // Token expired, redirect to login
    localStorage.removeItem('auth_token');
    window.location.href = '/login';
  } else if (err.message.includes('422')) {
    // Validation error, show to user
    alert('Validation error: ' + err.message);
  } else {
    // Other errors
    console.error('Error:', err);
  }
}
```

## 🎯 Status Values

### Pengajuan Surat Status
- `Pending` - Menunggu verifikasi
- `Diproses` - Sedang diproses
- `Disetujui` - Disetujui, sedang generate PDF
- `Ditolak` - Ditolak
- `Selesai` - Selesai, PDF tersedia

### Mutasi Penduduk Status
- `Pending` - Menunggu verifikasi
- `Disetujui` - Disetujui
- `Ditolak` - Ditolak

### Jenis Mutasi
- `Kelahiran`
- `Kematian`
- `Kedatangan`
- `Kepindahan`

### Kategori Informasi
- `Berita`
- `Pengumuman`
- `Agenda`
- `Lainnya`

## 📱 Telegram Integration

### Bind Telegram
```javascript
// User gets chat_id from bot (/start command)
POST /auth/bind-telegram
Headers: { 'Authorization': 'Bearer {token}' }
Body: { telegram_chat_id: "123456789" }
```

Setelah bind, user akan menerima notifikasi Telegram untuk:
- Status pengajuan surat berubah
- Status mutasi berubah
- Informasi penting dari gampong

## 🔍 Search & Filter

### Filter Pengajuan (Admin)
```javascript
GET /admin/surat/pengajuan?status=Pending
GET /admin/surat/pengajuan?status=Disetujui
```

### Filter Mutasi (Admin)
```javascript
GET /admin/mutasi?status_verifikasi=Pending
GET /admin/mutasi?jenis_mutasi=Kelahiran
GET /admin/mutasi?status_verifikasi=Pending&jenis_mutasi=Kelahiran
```

### Filter Informasi
```javascript
GET /informasi?kategori=Berita
GET /informasi?kategori=Pengumuman
```

### Filter Informasi (Admin)
```javascript
GET /admin/informasi?is_published=true
GET /admin/informasi?is_published=false
```

## 📊 Real-time Updates

Untuk real-time updates, gunakan polling atau WebSocket:

### Polling Example
```javascript
// Poll setiap 30 detik
setInterval(async () => {
  const data = await apiCall('/surat/pengajuan');
  updateUI(data);
}, 30000);
```

### WebSocket (Future Enhancement)
```javascript
// Laravel Echo + Pusher/Socket.io
Echo.private(`user.${userId}`)
  .listen('PengajuanStatusUpdated', (e) => {
    console.log('Status updated:', e.pengajuan);
  });
```

## 🎨 UI/UX Recommendations

### Loading States
```javascript
if (loading) return <Spinner />;
```

### Empty States
```javascript
if (data.length === 0) return <EmptyState message="Belum ada pengajuan" />;
```

### Error States
```javascript
if (error) return <ErrorMessage message={error} />;
```

### Success Feedback
```javascript
// Show toast/notification
toast.success('Pengajuan berhasil dibuat!');
```

### Confirmation Dialogs
```javascript
// Before approve/reject
if (confirm('Yakin ingin menyetujui pengajuan ini?')) {
  await apiCall(`/admin/surat/pengajuan/${id}/approve`, { method: 'POST' });
}
```

## 🚀 Performance Tips

1. **Cache static data** (kategori surat, statistik)
2. **Debounce search** inputs
3. **Lazy load** images
4. **Paginate** large lists
5. **Optimize** re-renders
6. **Use** loading skeletons
7. **Implement** infinite scroll for better UX

## 📚 Additional Resources

- **Full Documentation**: http://localhost/docs
- **Postman Collection**: http://localhost/docs.postman
- **OpenAPI Spec**: http://localhost/docs.openapi

---

**Happy Coding! 🚀**
