# Database Schema - SIG-Udeung

No schema modifications are required for this update. The existing database structure is sufficient.

## Schema details:
### Table: `pengajuan_surat`
- `id` (bigint, PK)
- `nomor_registrasi` (varchar, unique)
- `nik_pemohon` (varchar, FK -> penduduk.nik)
- `kategori_surat_id` (bigint, FK -> kategori_surat.id)
- `data_isian` (json)
- `file_syarat` (json)
- `status` (enum: Pending, Diproses, Disetujui, Ditolak, Selesai)
- `catatan_penolakan` (text, nullable)
- `qr_hash` (varchar, unique, nullable)
- `file_pdf_url` (varchar, nullable) - will store `/warga/pengajuan/{id}/print`
- `diverifikasi_oleh` (bigint, FK -> administrators.id)
- `created_at` (timestamp)
- `updated_at` (timestamp)
