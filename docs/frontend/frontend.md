# Dokumentasi Frontend - SIG-Udeung

Dokumentasi ini merinci spesifikasi teknis, arsitektur antarmuka klien, integrasi UI, dan panduan pengujian frontend untuk SIG-Udeung.

---

## 1. Tumpukan Teknologi Frontend

| Komponen | Versi | Keterangan |
|----------|-------|------------|
| Konektor SPA | Inertia.js (Laravel) ^3.1 | Bridge Laravel <-> Vue |
| Framework Klien | Vue 3 ^3.5.38 | Composition API |
| Pre-processor & Styling | Tailwind CSS v4.3.0 | Utility-first CSS |
| Build Tool | Vite 8 ^8.0.16 | Hot Module Replacement |
| Ikon | @lucide/vue ^1.17.0 | Icon library |
| Popup & Notifikasi | SweetAlert2 | Kustomisasi warna Teal/Slate |
| Testing | Vitest ^4.1.8 & @vue/test-utils | Unit testing |

---

## 2. Struktur Direktori Utama

```
resources/js/
  Pages/          - Komponen halaman (Portal Publik, Portal Warga, Autentikasi)
  Components/     - Komponen UI reusable (Card, Modal, Button, Dynamic Input, SkeletonLoader)
  Layouts/        - Layout utama (PublicLayout, AppLayout, CitizenLayout)
  Utils/          - Berkas utilitas pembantu (imageCompressor, alert)
```

---

## 3. Fitur Utama & Logika Frontend

### 3.1. Single File Component (SFC)

Seluruh halaman dikembangkan berbasis SFC (menggabungkan `<script setup>`, `<template>`, dan `<style>` dalam satu berkas `.vue`) untuk reaktivitas yang terisolasi dan modularitas yang tinggi.

### 3.2. Form Isian Dinamis (Dynamic Form Rendering)

Pada halaman pengajuan surat (`Create.vue`), elemen input di-render secara dinamis berdasarkan data skema JSONB (`schema_isian`) yang dikirimkan oleh backend. Hal ini memungkinkan admin menambahkan tipe surat baru tanpa perlu memodifikasi kode frontend.

### 3.3. Integrasi Dialog & Notifikasi Premium (SweetAlert2)

Sistem memiliki modul penanganan dialog terpadu di `resources/js/Utils/alert.js` yang mematikan *style default* bawaan SweetAlert2 dan menyuntikkan *class utility* Tailwind CSS:

- **Konfirmasi Kustom**: Menampilkan konfirmasi tindakan penting (seperti keluar sesi pada `CitizenLayout.vue`) dengan tombol bulat penuh bernuansa **Teal** (setuju) dan **Slate** (batal) serta modal melengkung modern (*rounded-3xl*).
- **Toast & Status**: Menyediakan notifikasi melayang di pojok kanan atas dengan bilah progress pemuatan reaktif untuk notifikasi instan yang estetik.

### 3.4. Transisi Gambar Halus & Skeleton Loader

Untuk memberikan pengalaman memuat yang mulus (*premium user experience*), beranda publik (`Home.vue`) mengimplementasikan skeleton loader asinkronus:

- **Pulsing Placeholder**: Menampilkan kotak berdenyut (`animate-pulse bg-slate-200/60`) selama browser mengunduh berkas gambar sampul berita.
- **Fade-in Transition**: Gambar yang selesai diunduh akan ditampilkan secara perlahan lewat transisi CSS opacity (`opacity-100 duration-300`) sehingga menghindari kemunculan gambar secara patah-patah (*popping effect*).

---

## 4. Rute Halaman Frontend

### 4.1. Halaman Publik (Tanpa Auth)

| Rute | Nama | Fungsi |
|------|------|--------|
| `GET /` | `home` | Beranda portal gampong |
| `GET /profil` | `profile` | Profil gampong |
| `GET /informasi` | `information.index` | Daftar informasi/berita |
| `GET /informasi/{slug}` | `information.show` | Detail informasi |
| `GET /verifikasi` | `verify.index` | Form verifikasi QR |
| `GET /verifikasi/{hash}` | `verify` | Hasil verifikasi dokumen |
| `GET /statistik` | `statistik` | Statistik gampong |
| `POST /aspirasi` | `aspirasi.store` | Kirim aspirasi (throttle 5/1) |

### 4.2. Halaman Autentikasi (Guest Only)

| Rute | Nama | Fungsi |
|------|------|--------|
| `GET /login` | `login` | Form login warga (NIK-based) |
| `POST /login` | `login.store` | Proses login warga |

### 4.3. Halaman Warga (Auth:penduduk)

| Rute | Nama | Fungsi |
|------|------|--------|
| `GET /warga/dashboard` | `warga.dashboard` | Dashboard warga |
| `GET /warga/profil` | `warga.profil.show` | Lihat profil |
| `POST /warga/profil` | `warga.profil.update` | Perbarui profil |
| `GET /warga/keluarga` | `warga.keluarga.index` | Data keluarga |
| `PUT /warga/keluarga/{nik}` | `warga.keluarga.update` | Perbarui data anggota |
| `GET /warga/surat/ajukan/{kategori}` | `warga.surat.create` | Form pengajuan surat |
| `POST /warga/surat/pengajuan` | `warga.surat.store` | Kirim pengajuan surat |
| `GET /warga/pengajuan/{pengajuan}` | `warga.pengajuan.show` | Detail pengajuan |
| `GET /warga/pengajuan/{pengajuan}/print` | `warga.pengajuan.print` | Cetak PDF surat |

---

## 5. Panduan Menjalankan Frontend & Testing

### Pemasangan & Menjalankan Development Server

1. Instal dependensi NPM (termasuk SweetAlert2):
   ```bash
   npm install
   ```
2. Jalankan server kompilasi Vite (hot-reloading):
   ```bash
   npm run dev
   ```
3. Kompilasi untuk mode produksi (production build):
   ```bash
   npm run build
   ```

### Menjalankan Pengujian Unit Frontend

Pengujian unit antarmuka menggunakan **Vitest** dan **jsdom** untuk mensimulasikan DOM browser secara lokal.

```bash
npx vitest run
```

---

## 6. Optimasi SEO & GEO (Search Engine & Generative Engine Optimization)

Aplikasi frontend SIG-Udeung dilengkapi dengan sistem meta-tag dinamis dan structured data JSON-LD untuk mempermudah indeksasi oleh mesin pencari konvensional (Google) maupun Generative AI Search Engine (Gemini, SearchGPT, Perplexity).

### 6.1. Komponen `<Head>` Dinamis

Setiap halaman publik menggunakan komponen `<Head>` dari Inertia.js untuk menyematkan meta tag berikut:

- `title` & `description`: Diperbarui secara dinamis sesuai konten halaman.
- `og:title`, `og:description`, `og:image`: Menunjang optimalisasi visual saat halaman dibagikan di media sosial.
- `keywords`: Tag kata kunci yang dihasilkan secara otomatis oleh AI untuk memperkuat keterkaitan konten di search engine.

### 6.2. Skema Data Terstruktur (JSON-LD Schemas)

- **`GovernmentOrganization` (Home.vue & Profile.vue)**: Menyediakan data formal Gampong Udeung, seperti koordinat geografis (latitude & longitude), alamat kantor, kontak resmi, wilayah administratif parent (Bandar Baru, Pidie Jaya, Aceh), serta relasi organisasi pemerintahan gampong.
- **`NewsArticle` (Information/Show.vue)**: Disematkan pada detail berita/pengumuman desa, memuat data penulis (author), tanggal diterbitkan (datePublished), tanggal diperbarui (dateModified), gambar utama (cover image), dan informasi penerbit (publisher).
- **`WebSite` & `BreadcrumbList`**: Membantu memetakan navigasi situs secara hierarkis bagi crawler AI dan search engine.
- **Penyematan Komponen Dinamis**: Script JSON-LD di dalam `<template>` dibungkus menggunakan `<component :is="'script'"` untuk mematuhi aturan strict dari Vue compiler dan mencegah *side-effect warnings* saat build/test.
