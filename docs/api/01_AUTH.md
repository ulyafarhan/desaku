# 📘 SIG-Udeung API Documentation

**Base URL:** https://udeung.desa.id/api/v1  
**Version:** 1.0  
**Last Updated:** 2024-06-01

## 📋 Table of Contents

1. [Authentication](#authentication)
2. [Pengajuan Surat](#pengajuan-surat)
3. [Mutasi Penduduk](#mutasi-penduduk)
4. [Informasi Publik](#informasi-publik)
5. [Statistik](#statistik)
6. [Verifikasi](#verifikasi)
7. [Error Handling](#error-handling)
8. [Status Codes](#status-codes)

## 🔐 Authentication

### Login Warga (NIK + No KK)

**Endpoint:** POST /auth/login/warga

**Request Body:**
`json
{
  \"nik\": \"1234567890123456\",
  \"no_kk\": \"1234567890123456\"
}
`

**Response Success (200):**
`json
{
  \"message\": \"Login berhasil\",
  \"user\": {
    \"nik\": \"1234567890123456\",
    \"nama_lengkap\": \"John Doe\",
    \"tempat_lahir\": \"Jakarta\",
    \"tanggal_lahir\": \"1990-01-01\",
    \"jenis_kelamin\": \"L\",
    \"agama\": \"Islam\",
    \"pendidikan\": \"S1\",
    \"pekerjaan\": \"Programmer\",
    \"status_perkawinan\": \"Belum Kawin\",
    \"status_keluarga\": \"Anak\",
    \"status_mutasi\": \"Tetap\",
    \"keluarga\": {
      \"no_kk\": \"1234567890123456\",
      \"alamat\": \"Jl. Test No. 123\",
      \"dusun\": \"Dusun A\",
      \"rt_rw\": \"001/002\"
    }
  },
  \"token\": \"1|abcdefghijklmnopqrstuvwxyz\"
}
`

**Response Error (422):**
`json
{
  \"message\": \"The nik field is required.\",
  \"errors\": {
    \"nik\": [
      \"NIK, No KK, atau status warga tidak valid.\"
    ]
  }
}
`

---

### Login Admin

**Endpoint:** POST /auth/login/admin

**Request Body:**
`json
{
  \"username\": \"operator\",
  \"password\": \"password123\"
}
`

**Response Success (200):**
`json
{
  \"message\": \"Login berhasil\",
  \"user\": {
    \"id\": 1,
    \"username\": \"operator\",
    \"role\": \"operator\",
    \"created_at\": \"2024-01-01T00:00:00.000000Z\"
  },
  \"token\": \"2|abcdefghijklmnopqrstuvwxyz\"
}
`

---

### Logout

**Endpoint:** POST /auth/logout  
**Auth Required:** Yes

**Headers:**
`
Authorization: Bearer {token}
`

**Response Success (200):**
`json
{
  \"message\": \"Logout berhasil\"
}
`

---

### Get Profile

**Endpoint:** GET /auth/profile  
**Auth Required:** Yes

**Response Success (200):**
`json
{
  \"user\": {
    \"nik\": \"1234567890123456\",
    \"nama_lengkap\": \"John Doe\",
    \"keluarga\": {
      \"no_kk\": \"1234567890123456\",
      \"alamat\": \"Jl. Test No. 123\"
    }
  }
}
`

---

### Bind Telegram

**Endpoint:** POST /auth/bind-telegram  
**Auth Required:** Yes (Warga only)

**Request Body:**
`json
{
  \"telegram_chat_id\": \"123456789\"
}
`

**Response Success (200):**
`json
{
  \"message\": \"Telegram berhasil terhubung\"
}
`
