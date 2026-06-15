# Dokumentasi Entity Relationship Diagram (ERD) - SIG-Udeung

Dokumen ini menjelaskan struktur data lengkap, relasi antarentitas, tipe data (standar MySQL/MariaDB), indeks optimasi, serta konstrain integritas data pada platform SIG-Udeung.

---

## 1. Peta ERD (Mermaid Diagram)

Diagram berikut memetakan seluruh tabel dalam database SIG-Udeung beserta relasinya:

```mermaid
erDiagram
    keluarga {
        varchar_16 no_kk PK
        varchar_16 kepala_keluarga_nik FK
        text alamat
        varchar_50 dusun
        varchar_10 rt_rw
    }
    
    penduduk {
        varchar_16 nik PK
        varchar_16 no_kk FK
        varchar_100 nama_lengkap
        varchar_50 tempat_lahir
        date tanggal_lahir
        char_1 jenis_kelamin
        varchar_20 agama
        varchar_50 pendidikan
        varchar_50 pekerjaan
        varchar_20 status_perkawinan
        varchar_30 status_keluarga
        varchar_20 status_mutasi
        varchar_50 telegram_chat_id UK
        timestamp created_at
        timestamp updated_at
    }

    administrators {
        ulid id PK
        varchar_50 username UK
        varchar_255 password_hash
        varchar_20 role
        timestamp created_at
    }

    pengaturan_gampong {
        ulid id PK
        varchar_50 kunci UK
        text nilai
        varchar_20 tipe_data
        varchar_255 deskripsi
        timestamp updated_at
    }

    referensi_wilayah {
        varchar_15 kode_wilayah PK
        varchar_100 nama_wilayah
        varchar_20 level
        varchar_15 parent_kode FK
    }

    mutasi_penduduk {
        ulid id PK
        varchar_16 nik FK
        varchar_20 jenis_mutasi
        date tanggal_mutasi
        text keterangan
        varchar_255 dokumen_bukti
        varchar_20 status_verifikasi
        ulid diverifikasi_oleh FK
        timestamp created_at
    }

    kategori_surat {
        ulid id PK
        varchar_20 kode_surat UK
        varchar_100 nama_surat
        varchar_100 template_view
        json schema_isian
        json syarat_dokumen
        boolean is_active
    }

    pengajuan_surat {
        ulid id PK
        varchar_30 nomor_registrasi UK
        varchar_16 nik_pemohon FK
        ulid kategori_surat_id FK
        json data_isian
        json file_syarat
        varchar_20 status
        text catatan_penolakan
        varchar_64 qr_hash UK
        varchar_255 file_pdf_url
        ulid diverifikasi_oleh FK
        timestamp created_at
        timestamp updated_at
    }

    tracking_pengajuan_surat {
        ulid id PK
        ulid pengajuan_surat_id FK
        varchar_20 status_sebelumnya
        varchar_20 status_baru
        text keterangan_update
        ulid diupdate_oleh FK
        timestamp created_at
    }

    informasi_publik {
        ulid id PK
        varchar_255 judul
        varchar_255 slug UK
        text konten
        varchar_50 kategori
        varchar_255 cover_image
        boolean is_published
        ulid author_id FK
        timestamp created_at
    }

    bot_knowledges {
        ulid id PK
        varchar_50 kunci UK
        varchar_20 tipe
        varchar_255 pertanyaan_atau_topik
        json kata_kunci
        text jawaban_atau_konten
        boolean is_aktif
        timestamp created_at
        timestamp updated_at
    }

    telegram_broadcast_queue {
        ulid id PK
        text pesan
        varchar_50 kategori_target
        varchar_20 status
        timestamp jadwal_kirim
        timestamp waktu_selesai
        ulid created_by FK
    }

    chatbot_logs {
        ulid id PK
        varchar_50 telegram_chat_id
        text pesan_masuk
        text balasan_ai
        int tokens_used
        timestamp created_at
    }

    audit_logs {
        ulid id PK
        varchar_20 user_type
        varchar_50 user_id
        varchar_50 tindakan
        varchar_50 nama_tabel
        varchar_50 record_id
        json data_lama
        json data_baru
        varchar_45 ip_address
        text user_agent
        timestamp created_at
    }

    keluarga ||--o{ penduduk : "memiliki anggota (1:N)"
    penduduk ||--o{ keluarga : "kepala keluarga (1:1)"
    referensi_wilayah ||--o{ referensi_wilayah : "sub-wilayah (1:N)"
    penduduk ||--o{ mutasi_penduduk : "mengalami mutasi (1:N)"
    administrators ||--o{ mutasi_penduduk : "memverifikasi mutasi (1:N)"
    kategori_surat ||--o{ pengajuan_surat : "jenis surat (1:N)"
    penduduk ||--o{ pengajuan_surat : "mengajukan surat (1:N)"
    administrators ||--o{ pengajuan_surat : "memverifikasi surat (1:N)"
    pengajuan_surat ||--o{ tracking_pengajuan_surat : "mempunyai riwayat (1:N)"
    administrators ||--o{ tracking_pengajuan_surat : "mengubah status (1:N)"
    administrators ||--o{ informasi_publik : "menulis artikel (1:N)"
    administrators ||--o{ telegram_broadcast_queue : "membuat broadcast (1:N)"
```

---

## 2. Struktur Detail Tabel

### 2.1. Tabel `users` & `administrators`
Menangani hak akses dan peran di tingkat administrasi desa (Keuchik, Sekdes, Operator).
* **`administrators`**:
  * `id`: `ULID PRIMARY KEY` -> Identifier unik administrator.
  * `username`: `VARCHAR(50) UNIQUE` -> Username login unik.
  * `password_hash`: `VARCHAR(255)` -> Hash kata sandi menggunakan bcrypt.
  * `role`: `VARCHAR(20)` -> Peran administratif (Keuchik, Sekdes, Operator).
  * `created_at`: `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`.

### 2.2. Tabel `keluarga`
Menyimpan data identitas Kartu Keluarga (KK).
* `no_kk`: `VARCHAR(16) PRIMARY KEY` -> Nomor KK 16 digit.
* `alamat`: `TEXT` -> Alamat fisik rumah tangga.
* `dusun`: `VARCHAR(50)` -> Nama dusun wilayah gampong.
* `rt_rw`: `VARCHAR(10)` -> RT/RW.
* `kepala_keluarga_nik`: `VARCHAR(16) FOREIGN KEY REFERENCES penduduk(nik) ON DELETE RESTRICT` -> NIK Kepala Keluarga.

### 2.3. Tabel `penduduk`
Basis data kependudukan dinamis.
* `nik`: `VARCHAR(16) PRIMARY KEY` -> NIK 16 digit.
* `no_kk`: `VARCHAR(16) FOREIGN KEY REFERENCES keluarga(no_kk) ON DELETE RESTRICT` -> Relasi ke Kartu Keluarga.
* `nama_lengkap`: `VARCHAR(100)` -> Nama lengkap sesuai KTP.
* `tempat_lahir`: `VARCHAR(50)`.
* `tanggal_lahir`: `DATE`.
* `jenis_kelamin`: `CHAR(1)` -> L (Laki-laki) atau P (Perempuan).
* `agama`: `VARCHAR(20)`.
* `pendidikan`: `VARCHAR(50)`.
* `pekerjaan`: `VARCHAR(50)`.
* `status_perkawinan`: `VARCHAR(20)`.
* `status_keluarga`: `VARCHAR(30)` -> Hubungan dalam keluarga (Kepala Keluarga, Istri, Anak, dll).
* `status_mutasi`: `VARCHAR(20) DEFAULT 'Tetap'` -> Status kependudukan (Tetap, Meninggal, Pindah, dll).
* `telegram_chat_id`: `VARCHAR(50) UNIQUE NULL` -> Chat ID Telegram terhubung untuk bot gateway.

### 2.4. Tabel `mutasi_penduduk`
Riwayat perubahan demografi kependudukan.
* `id`: `ULID PRIMARY KEY`.
* `nik`: `VARCHAR(16) FOREIGN KEY REFERENCES penduduk(nik) ON DELETE CASCADE`.
* `jenis_mutasi`: `VARCHAR(20)` -> Kelahiran, Kematian, Kedatangan, Kepindahan.
* `tanggal_mutasi`: `DATE`.
* `keterangan`: `TEXT`.
* `dokumen_bukti`: `VARCHAR(255)` -> Path file bukti surat mutasi.
* `status_verifikasi`: `VARCHAR(20) DEFAULT 'Pending'` -> Pending, Disetujui, Ditolak.
* `diverifikasi_oleh`: `ULID NULL FOREIGN KEY REFERENCES administrators(id)`.

### 2.5. Tabel `kategori_surat`
Skema surat administratif dinamis.
* `id`: `ULID PRIMARY KEY`.
* `kode_surat`: `VARCHAR(20) UNIQUE` -> Contoh: SKTM, SKU, Domisili.
* `nama_surat`: `VARCHAR(100)`.
* `template_view`: `VARCHAR(100)` -> Nama view file blade PDF.
* `schema_isian`: `JSON` -> Struktur skema dinamis form Vue.
* `syarat_dokumen`: `JSON` -> Berkas prasyarat wajib yang harus diunggah.
* `is_active`: `BOOLEAN DEFAULT TRUE`.

### 2.6. Tabel `pengajuan_surat`
Pencatatan pengajuan surat mandiri oleh warga.
* `id`: `ULID PRIMARY KEY`.
* `nomor_registrasi`: `VARCHAR(30) UNIQUE` -> Nomor register surat formal.
* `nik_pemohon`: `VARCHAR(16) FOREIGN KEY REFERENCES penduduk(nik) ON DELETE CASCADE`.
* `kategori_surat_id`: `ULID FOREIGN KEY REFERENCES kategori_surat(id) ON DELETE RESTRICT`.
* `data_isian`: `JSON` -> Data variabel yang diisi warga sesuai skema.
* `file_syarat`: `JSON` -> Path file dokumen prasyarat terunggah.
* `status`: `VARCHAR(20) DEFAULT 'Pending'` -> Pending, Approved, Rejected.
* `catatan_penolakan`: `TEXT NULL` -> Catatan dari operator jika ditolak.
* `qr_hash`: `VARCHAR(64) UNIQUE NULL` -> Hash dokumen SHA-256 untuk TTE.
* `file_pdf_url`: `VARCHAR(255) NULL` -> URL file dokumen PDF final.
* `diverifikasi_oleh`: `ULID NULL FOREIGN KEY REFERENCES administrators(id)`.

### 2.7. Tabel `tracking_pengajuan_surat`
Log status persetujuan surat berantai.
* `id`: `ULID PRIMARY KEY`.
* `pengajuan_surat_id`: `ULID FOREIGN KEY REFERENCES pengajuan_surat(id) ON DELETE CASCADE`.
* `status_sebelumnya`: `VARCHAR(20)`.
* `status_baru`: `VARCHAR(20)`.
* `keterangan_update`: `TEXT`.
* `diupdate_oleh`: `ULID NULL FOREIGN KEY REFERENCES administrators(id)`.

### 2.8. Tabel `informasi_publik`
Berita dan siaran pers desa.
* `id`: `ULID PRIMARY KEY`.
* `judul`: `VARCHAR(255)`.
* `slug`: `VARCHAR(255) UNIQUE` -> URL friendly slug.
* `konten`: `TEXT` -> Konten teks utama (HTML format).
* `kategori`: `VARCHAR(50)`.
* `cover_image`: `VARCHAR(255) NULL`.
* `is_published`: `BOOLEAN DEFAULT FALSE`.
* `author_id`: `ULID NULL FOREIGN KEY REFERENCES administrators(id)`.

---

## 3. Aturan Relasi Terikat & Integritas

1. **One-to-Many (`keluarga` ke `penduduk`)**:
   * Satu keluarga (`keluarga`) memiliki satu atau banyak anggota keluarga (`penduduk`).
   * Penghapusan data keluarga dibatasi (`ON DELETE RESTRICT`) jika masih ada penduduk yang terikat pada `no_kk` tersebut.
2. **One-to-One (`penduduk` ke `keluarga` sebagai kepala keluarga)**:
   * Setiap keluarga memiliki satu kepala keluarga yang diidentifikasi oleh `kepala_keluarga_nik`.
3. **Cascading Delete pada Transaksi**:
   * Jika data `penduduk` dihapus, seluruh data transaksi terkait seperti `mutasi_penduduk` dan `pengajuan_surat` akan dihapus secara otomatis (`ON DELETE CASCADE`) untuk mencegah residu data tak terikat.
