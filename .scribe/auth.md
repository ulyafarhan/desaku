# Autentikasi Permintaan

Untuk melakukan autentikasi pada endpoint yang memerlukan, sertakan header **`Authorization`** dengan nilai **`Bearer {YOUR_AUTH_TOKEN}`**.

## Mendapatkan Token

### Login Warga
```bash
POST /api/v1/auth/login/warga
{
  "nik": "1118060512900001",
  "no_kk": "1118060001000001"
}
```

### Login Administrator
```bash
POST /api/v1/auth/login/admin
{
  "username": "operator",
  "password": "password"
}
```

## Menggunakan Token

Setelah mendapatkan token, sertakan di header setiap request:

```http
Authorization: Bearer 1|abc123def456...
```

## Contoh Lengkap

```bash
# Step 1: Login
curl -X POST http://localhost/api/v1/auth/login/warga \
  -H "Content-Type: application/json" \
  -d '{"nik":"1118060512900001","no_kk":"1118060001000001"}'

# Step 2: Gunakan token dari response
curl http://localhost/api/v1/surat/kategori \
  -H "Authorization: Bearer 1|your_token_here"
```

## Catatan Penting

- Token berlaku selama **session aktif** di server
- Simpan token di tempat aman, jangan bagikan ke publik
- Jika token kedaluarsa, lakukan login ulang
- Setiap user hanya bisa memiliki **1 token aktif** (token sebelumnya otomatis revoked saat login baru)
