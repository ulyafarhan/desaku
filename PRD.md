# PRODUCT REQUIREMENTS DOCUMENT (PRD)

**Sistem Informasi Gampong (SIG) Terpadu**

**Informasi Dokumen**

* **Nama Produk:** SIG-Udeung (Sistem Informasi Gampong Udeung)
* **Lokasi Implementasi:** Gampong Udeung, Kec. Bandar Baru, Kab. Pidie Jaya, Provinsi Aceh
* **Versi Dokumen:** 1.0 (Final Architecture & Specs)
* **Fokus Rilis (MVP):** Kependudukan Dinamis, Persuratan Mandiri TTE QR-Code, Portal Real-Time, & Telegram AI Gateway

---

## 1. Ringkasan Eksekutif & Visi Produk

SIG-Udeung dirancang untuk menyelesaikan masalah inti administrasi desa melalui pendekatan desentralisasi input. Sistem ini menggeser beban pendataan dari aparatur desa kepada partisipasi aktif warga melalui layanan mandiri (*self-service*). Ekosistem ini menggabungkan layanan birokrasi *paperless* yang sah secara regulasi dengan portal informasi publik yang menarik data secara langsung (*real-time*) dari basis data kependudukan.

---

## 2. Cakupan Ekosistem Aplikasi

Ekosistem SIG-Udeung beroperasi di bawah satu domain utama (`udeung.desa.id`) yang dibagi menjadi empat modul antarmuka utama:

1. **Portal Publik Gampong (Landing Page):** Pintu depan sistem yang dapat diakses publik. Menampilkan profil desa, berita, dan statistik demografi *real-time*.
2. **Progressive Web App (PWA) Warga:** Area privat bagi warga (login via NIK) untuk mengajukan mutasi kependudukan dan memproses surat menyurat digital.
3. **Web Dashboard Admin (SPA):** Panel kontrol terpusat bagi Keuchik, Sekdes, dan Operator untuk memverifikasi pengajuan, mengatur konten portal, dan memantau log sistem.
4. **Telegram Bot & AI Gateway:** Saluran distribusi notifikasi sistem (bebas biaya) dan asisten virtual publik berteknologi Gemini AI.

---

## 3. Tumpukan Teknologi & Infrastruktur

| Komponen | Teknologi Terpilih | Tujuan Penggunaan |
| --- | --- | --- |
<<<<<<< HEAD
| **Frontend Publik** | Next.js (React) | *Server-Side Rendering* (SSR) untuk optimasi SEO dan pemuatan data statistik *real-time*. |
| **Frontend PWA & Admin** | React.js / Alpine.js + Tailwind CSS | Antarmuka dinamis dan responsif dengan dukungan *offline caching* via *Service Worker*. |
| **Backend Core API** | Node.js (Express) | Pemrosesan logika bisnis, *routing* API, kompilasi PDF surat, dan manajemen *state*. |
=======
| **Frontend Publik** | Nuxt.Js | *Server-Side Rendering* (SSR) untuk optimasi SEO dan pemuatan data statistik *real-time*. |
| **Frontend PWA & Admin** | Vue.js / Alpine.js + Tailwind CSS | Antarmuka dinamis dan responsif dengan dukungan ataupun menggunakan filament 5 *offline caching* via *Service Worker*. |
| **Backend Core API** | Laravel 13 | Pemrosesan logika bisnis, *routing* API, kompilasi PDF surat, dan manajemen *state*. |
>>>>>>> 8e6c871 (pembangunan backend dan kontrak api)
| **Database Engine** | PostgreSQL 15+ | Relasional data kependudukan dan tipe data `JSONB` untuk skema formulir surat dinamis. |
| **Queue & Cache** | Redis | Menangani antrean tugas asinkron (*rendering* PDF, notifikasi Telegram berantai). |
| **Integrasi Eksternal** | Kemendagri API, Telegram API, Gemini API | Validasi wilayah, *broadcasting* pesan gratis, dan mesin pemrosesan bahasa alami (NLP). |
| **Deployment** | Docker di VPS Linux, Nginx (Reverse Proxy) | Isolasi *environment* aplikasi, efisiensi *resource* server gampong, dan manajemen SSL (HTTPS). |

---

## 4. Kebutuhan Fungsional (Functional Requirements)

### 4.1. Modul Kependudukan Dinamis & Mutasi (Core Data)

* Sistem harus memfasilitasi pembuatan basis data *Single Source of Truth* berdasarkan NIK dan Nomor KK.
* Warga dapat mengajukan 4 jenis mutasi mandiri via PWA: Kelahiran, Kematian, Kedatangan, dan Kepindahan.
* Admin harus memverifikasi bukti dokumen mutasi sebelum sistem secara otomatis memperbarui status kependudukan dan menyinkronkan data statistik di Portal Publik.

### 4.2. Modul Pelayanan Surat Mandiri (Self-Service) & TTE

* Sistem memuat *form* spesifik berdasarkan kategori surat yang ditarik dari *schema* `JSONB`.
* Data identitas warga (Nama, NIK, TTL) otomatis terisi (*auto-fill*) dan dikunci berdasarkan sesi *login*.
* Admin dapat menolak pengajuan dengan memberikan catatan (opsi perbaikan) atau menyetujuinya.
* Persetujuan admin akan memicu *backend* untuk men-*generate* *Hash* SHA-256 yang diubah menjadi QR Code Tanda Tangan Elektronik (TTE).
* Sistem menggabungkan QR Code dan data warga ke dalam format PDF final yang tidak dapat diedit (*read-only*).

### 4.3. Modul Verifikasi Dokumen Eksternal

* Sistem menyediakan *endpoint* publik untuk memvalidasi QR Code TTE yang terdapat pada PDF/kertas cetakan surat.
* Halaman verifikasi menampilkan status keaslian dokumen, tanggal rilis, dan identitas pemohon.

### 4.4. Modul Telegram Gateway & AI Chatbot

* Sistem harus menyediakan mekanisme *binding* akun antara PWA warga dan *Chat ID* Telegram.
* Setiap transisi status pengajuan surat atau mutasi otomatis memicu notifikasi *push* ke Telegram warga.
* Sistem *Chatbot* memproses pesan masuk menggunakan model Gemini AI yang diinjeksi dengan *prompt* khusus berisi konteks regulasi dan prosedur Gampong Udeung.

---

## 5. Alur Kerja Pengguna (User Flows)

### 5.1. Alur Pengajuan Surat Berbasis State Machine

1. **[Warga]** Login PWA -> Pilih Kategori Surat -> Isi Data Variabel Tambahan -> Unggah Syarat -> Submit. *(Status: `PENDING`)*
2. **[Sistem]** Mengirim notifikasi ke grup Telegram Operator Desa.
3. **[Admin]** Buka Dashboard -> Cek Dokumen.
* Jika Syarat Tidak Valid -> Klik Tolak -> Isi Alasan. *(Status: `REJECTED`, Warga mendapat notifikasi)*
* Jika Syarat Valid -> Klik Setujui. *(Status: `APPROVED`)*


4. **[Sistem]** Mengeksekusi *Job Queue*: Generate Hash -> Render PDF + QR TTE -> Simpan ke Server.
5. **[Sistem]** Mengirim *file* PDF final via Telegram *Direct Message* ke warga bersangkutan.

---

## 6. Desain Database & Entity Relationship Diagram (ERD)

Skema basis data berikut telah dinormalisasi hingga 3NF dan dioptimasi menggunakan indeks.

```sql
CREATE TABLE administrators (
    id BIGSERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pengaturan_gampong (
    id SERIAL PRIMARY KEY,
    kunci VARCHAR(50) UNIQUE NOT NULL,
    nilai TEXT NOT NULL,
    tipe_data VARCHAR(20) DEFAULT 'string',
    deskripsi VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_pengaturan_kunci ON pengaturan_gampong(kunci);

CREATE TABLE referensi_wilayah (
    kode_wilayah VARCHAR(15) PRIMARY KEY,
    nama_wilayah VARCHAR(100) NOT NULL,
    level VARCHAR(20) NOT NULL,
    parent_kode VARCHAR(15),
    CONSTRAINT fk_wilayah_parent FOREIGN KEY (parent_kode) REFERENCES referensi_wilayah(kode_wilayah) ON DELETE RESTRICT
);
CREATE INDEX idx_wilayah_parent ON referensi_wilayah(parent_kode);

CREATE TABLE keluarga (
    no_kk VARCHAR(16) PRIMARY KEY,
    alamat TEXT NOT NULL,
    dusun VARCHAR(50) NOT NULL,
    rt_rw VARCHAR(10)
);

CREATE TABLE penduduk (
    nik VARCHAR(16) PRIMARY KEY,
    no_kk VARCHAR(16) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin CHAR(1) NOT NULL,
    agama VARCHAR(20) NOT NULL,
    pendidikan VARCHAR(50) NOT NULL,
    pekerjaan VARCHAR(50) NOT NULL,
    status_perkawinan VARCHAR(20) NOT NULL,
    status_keluarga VARCHAR(30) NOT NULL,
    status_mutasi VARCHAR(20) DEFAULT 'Tetap',
    telegram_chat_id VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_keluarga FOREIGN KEY (no_kk) REFERENCES keluarga(no_kk) ON DELETE RESTRICT
);
CREATE INDEX idx_penduduk_nama ON penduduk(nama_lengkap);
CREATE INDEX idx_penduduk_no_kk ON penduduk(no_kk);

ALTER TABLE keluarga 
ADD COLUMN kepala_keluarga_nik VARCHAR(16),
ADD CONSTRAINT fk_kepala_keluarga FOREIGN KEY (kepala_keluarga_nik) REFERENCES penduduk(nik) DEFERRABLE INITIALLY DEFERRED;

CREATE TABLE mutasi_penduduk (
    id BIGSERIAL PRIMARY KEY,
    nik VARCHAR(16) NOT NULL,
    jenis_mutasi VARCHAR(20) NOT NULL,
    tanggal_mutasi DATE NOT NULL,
    keterangan TEXT NOT NULL,
    dokumen_bukti VARCHAR(255) NOT NULL,
    status_verifikasi VARCHAR(20) DEFAULT 'Pending',
    diverifikasi_oleh BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mutasi_penduduk FOREIGN KEY (nik) REFERENCES penduduk(nik) ON DELETE CASCADE,
    CONSTRAINT fk_mutasi_admin FOREIGN KEY (diverifikasi_oleh) REFERENCES administrators(id) ON DELETE SET NULL
);
CREATE INDEX idx_mutasi_status ON mutasi_penduduk(status_verifikasi);

CREATE TABLE kategori_surat (
    id SERIAL PRIMARY KEY,
    kode_surat VARCHAR(20) UNIQUE NOT NULL,
    nama_surat VARCHAR(100) NOT NULL,
    template_view VARCHAR(100) NOT NULL,
    schema_isian JSONB NOT NULL,
    syarat_dokumen JSONB NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE pengajuan_surat (
    id BIGSERIAL PRIMARY KEY,
    nomor_registrasi VARCHAR(30) UNIQUE NOT NULL,
    nik_pemohon VARCHAR(16) NOT NULL,
    kategori_surat_id INT NOT NULL,
    data_isian JSONB NOT NULL,
    file_syarat JSONB NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    catatan_penolakan TEXT,
    qr_hash VARCHAR(64) UNIQUE,
    file_pdf_url VARCHAR(255),
    diverifikasi_oleh BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pengajuan_penduduk FOREIGN KEY (nik_pemohon) REFERENCES penduduk(nik) ON DELETE CASCADE,
    CONSTRAINT fk_pengajuan_kategori FOREIGN KEY (kategori_surat_id) REFERENCES kategori_surat(id) ON DELETE RESTRICT,
    CONSTRAINT fk_pengajuan_admin FOREIGN KEY (diverifikasi_oleh) REFERENCES administrators(id) ON DELETE SET NULL
);
CREATE INDEX idx_pengajuan_status ON pengajuan_surat(status);
CREATE INDEX idx_pengajuan_nik ON pengajuan_surat(nik_pemohon);

CREATE TABLE tracking_pengajuan_surat (
    id BIGSERIAL PRIMARY KEY,
    pengajuan_surat_id BIGINT NOT NULL,
    status_sebelumnya VARCHAR(20),
    status_baru VARCHAR(20) NOT NULL,
    keterangan_update TEXT,
    diupdate_oleh BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tracking_pengajuan FOREIGN KEY (pengajuan_surat_id) REFERENCES pengajuan_surat(id) ON DELETE CASCADE,
    CONSTRAINT fk_tracking_admin FOREIGN KEY (diupdate_oleh) REFERENCES administrators(id) ON DELETE SET NULL
);
CREATE INDEX idx_tracking_pengajuan_id ON tracking_pengajuan_surat(pengajuan_surat_id);

CREATE TABLE informasi_publik (
    id BIGSERIAL PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    konten TEXT NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    cover_image VARCHAR(255),
    is_published BOOLEAN DEFAULT FALSE,
    author_id BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_informasi_author FOREIGN KEY (author_id) REFERENCES administrators(id) ON DELETE SET NULL
);
CREATE INDEX idx_informasi_kategori ON informasi_publik(kategori);
CREATE INDEX idx_informasi_slug ON informasi_publik(slug);

CREATE TABLE telegram_broadcast_queue (
    id BIGSERIAL PRIMARY KEY,
    pesan TEXT NOT NULL,
    kategori_target VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'Queued',
    jadwal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    waktu_selesai TIMESTAMP,
    created_by BIGINT,
    CONSTRAINT fk_broadcast_admin FOREIGN KEY (created_by) REFERENCES administrators(id) ON DELETE SET NULL
);
CREATE INDEX idx_broadcast_status ON telegram_broadcast_queue(status);
CREATE INDEX idx_broadcast_jadwal ON telegram_broadcast_queue(jadwal_kirim);

CREATE TABLE chatbot_logs (
    id BIGSERIAL PRIMARY KEY,
    telegram_chat_id VARCHAR(50) NOT NULL,
    pesan_masuk TEXT NOT NULL,
    balasan_ai TEXT NOT NULL,
    tokens_used INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_chatbot_chat_id ON chatbot_logs(telegram_chat_id);

CREATE TABLE audit_logs (
    id BIGSERIAL PRIMARY KEY,
    user_type VARCHAR(20) NOT NULL,
    user_id BIGINT,
    tindakan VARCHAR(50) NOT NULL,
    nama_tabel VARCHAR(50) NOT NULL,
    record_id VARCHAR(50),
    data_lama JSONB,
    data_baru JSONB,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_audit_tabel_record ON audit_logs(nama_tabel, record_id);
CREATE INDEX idx_audit_waktu ON audit_logs(created_at);

```

---

## 7. Kebutuhan Non-Fungsional (Non-Functional Requirements)

* **Keamanan & Enkripsi:** Koneksi terenkripsi TLS 1.3 (HTTPS). Payload API yang dimutasi dilindungi JSON Web Token (JWT) dengan *HTTP-Only Cookies*. Hash QR Code dibangkitkan dari kombinasi data esensial menggunakan SHA-256.
* **Performa:** Kecepatan respon API maksimal 500ms pada *load* normal. *Landing Page* (Next.js) harus mendapatkan skor Lighthouse di atas 90 untuk metrik kinerja dan aksesibilitas.
* **Ketahanan API Telegram:** Eksekusi *broadcast* di-handle asinkron oleh Redis Queue untuk menghindari *rate limiting* dan *banned IP* dari *server* Telegram.
* **Auditability:** Segala jenis perubahan status surat, pergantian data kependudukan, dan pengaturan konfigurasi wajib direkam secara berkesinambungan di tabel `audit_logs`.

---

## 8. Panduan Antarmuka & UX (User Experience)

* **Aksesibilitas Multi-Generasi:** PWA ditujukan untuk berbagai kelompok umur warga. UI harus bersih, menggunakan jenis *font* tanpa kait (Sans-Serif), kontras warna rasio tinggi, dan tidak menyertakan elemen animasi berat.
* **Mobile-First Design:** Semua formulir persuratan, daftar berita gampong, dan dasbor PWA dioptimalkan untuk pengoperasian satu tangan pada layar ponsel standar (rasio potret).
* **Indikator Status Jelas:** Setiap fase pengajuan surat (Pending, Proses, Selesai) divisualisasikan dengan diagram alir berjenjang atau garis waktu interaktif (*timeline*) dengan penanda warna yang intuitif.

---

## 9. Infrastruktur Sistem & Anggaran Implementasi

Sistem diterapkan menggunakan topologi kluster *container* tunggal yang menyeimbangkan stabilitas dan biaya anggaran Dana Desa.

* **Server Host:** VPS berbasis Linux (Ubuntu 22.04 LTS), spesifikasi min. 2 Core CPU & 2 GB RAM.
* **Domain & SSL:** Ekstensi resmi instansi (`.desa.id`) diproteksi melalui Cloudflare Free Tier (CDN + *DDoS Protection* + Edge SSL).
* **Manajemen Biaya Operasional:** Tidak dikenakan biaya langganan untuk model AI dasar (Gemini Free Tier) maupun gerbang notifikasi (Telegram Bot API). Alokasi anggaran berfokus murni pada keberlangsungan infrastruktur komputasi *server* tahunan (Estimasi ± Rp 2.000.000 / Tahun).

---

## 10. Peta Jalan Implementasi (Deployment Roadmap)

1. **Fase 1 (Bulan 1): Setup & Impor Kependudukan.** Pembuatan *environment*, inisialisasi basis data PostgreSQL, dan migrasi rekapan kependudukan Gampong Udeung yang sudah ada ke dalam skema *database* SIG.
2. **Fase 2 (Bulan 2): Digitalisasi Surat & Landing Page.** Penyusunan templat 5 surat administrasi paling krusial, logika generator QR Code TTE, dan integrasi modul statistik Next.js.
3. **Fase 3 (Bulan 3): Interkoneksi Telegram & AI.** Membuka *webhook* penghubung Telegram, konfigurasi antrean Redis, dan pengujian keakuratan *prompt* asisten virtual Gemini.
4. **Fase 4 (Bulan 4): Peluncuran Terbatas & Go-Live.** Uji coba internal bersama Perangkat Gampong Udeung, perbaikan *bug*, diakhiri dengan sosialisasi menyeluruh (*grand launching*) ke seluruh warga gampong.
