# 🎨 DESIGN.md: Spesifikasi Desain UI/UX & Panduan Frontend "Desaku"

Dokumen ini merupakan spesifikasi arsitektur antarmuka (UI) dan pengalaman pengguna (UX) untuk proyek **"Desaku"**. Dokumen ini dirancang sebagai instruksi teknis utama bagi AI atau pengembang frontend untuk membangun aplikasi web yang modern, berkinerja tinggi (ringan), sangat responsif, dan selaras dengan backend secara konsisten.

---

## 📌 1. Prinsip Utama Desain (Design Principles)

Untuk memastikan aplikasi ini ramah pengguna, terutama bagi masyarakat desa dengan beragam latar belakang dan kapasitas perangkat:

1. **Aksesibilitas & Keterbacaan Tinggi:**
   * Menggunakan kontras warna yang cukup tinggi sesuai standar WCAG AA.
   * Ukuran teks tubuh (body text) minimal 16px di perangkat seluler agar mudah dibaca oleh warga lansia.
2. **Pendekatan Seluler Terlebih Dahulu (Mobile-First):**
   * Lebih dari 80% akses diperkirakan datang dari perangkat seluler. Tata letak harus fleksibel tanpa adanya pergeseran tata letak yang mengganggu (*no layout shifts*).
3. **Optimasi Performa & Ringan (Lightweight Engine):**
   * Meminimalkan ukuran bundel CSS/JS.
   * Menghindari penggunaan pustaka animasi pihak ketiga yang berat. Menggunakan transisi CSS murni atau animasi berbasis Tailwind.
   * Penundaan pemuatan aset (*lazy loading*) untuk gambar-gambar profil desa yang berukuran besar.
4. **Desain Komponen Modular:**
   * Setiap elemen antarmuka yang berulang (tombol, input, kartu) wajib dibuat sebagai komponen yang dapat dibagikan (*shared components*) untuk menjaga konsistensi visual dan fungsionalitas.

---

## 🎨 2. Sistem Desain & Token Visual

### A. Palet Warna (Color Palette)
Perpaduan warna bernuansa alam (melambangkan desa) dikombinasikan dengan warna modern yang memberikan kesan tepercaya dan profesional.

| Kategori | Token Tailwind | Nilai Hex | Penggunaan Utama |
| :--- | :--- | :--- | :--- |
| **Primary (Brand)** | `teal-700` | `#0F766E` | Warna dominan, tombol aksi utama, header menu, elemen aktif. |
| **Secondary** | `amber-600` | `#D97706` | Aksen visual, lencana status *pending*, pengumuman penting. |
| **Background (Base)**| `slate-50` | `#F8FAFC` | Latar belakang halaman umum agar bersih dan tidak melelahkan mata. |
| **Surface (Card)** | `white` | `#FFFFFF` | Latar belakang kartu informasi, formulir, dan kontainer utama. |
| **Dark Text** | `slate-800` | `#1E293B` | Teks utama, judul, navigasi teks. |
| **Muted Text** | `slate-500` | `#64748B` | Teks pembantu, takarir (caption), tanggal artikel. |
| **Success** | `emerald-600` | `#059669` | Status disetujui, tombol sukses, notifikasi positif. |
| **Danger** | `red-600` | `#DC2626` | Status ditolak, tombol hapus, pesan kesalahan (error). |
| **Border** | `slate-200` | `#E2E8F0` | Pembatas antar elemen, garis tepi input formulir. |

### B. Tipografi
* **Font Utama:** `Inter` atau `system-ui, sans-serif` (pilihan modern, bersih, dan hemat bandwidth karena menggunakan font sistem).
* **Ukuran & Bobot:**
  * `Hero Display`: 32px (Seluler) / 48px (Desktop) - Bold (700)
  * `Section Title`: 24px (Seluler) / 32px (Desktop) - Semi-Bold (600)
  * `Sub-heading`: 18px / 20px - Medium (500)
  * `Body Text`: 16px - Regular (400)
  * `Helper / Caption`: 14px - Regular (400)

### C. Jarak (Spacing) & Grid
* Berorientasi pada kelipatan 8pt (`4px`, `8px`, `16px`, `24px`, `32px`, `48px`, `64px`).
* Batas lebar konten desktop (*container layout*): `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`.

---

## 🏗️ 3. Arsitektur Komponen Bersama (Shared Components)

AI diinstruksikan untuk membangun komponen-komponen ini secara terpisah dan modular agar dapat digunakan kembali di seluruh bagian aplikasi.

### 1. `Button` (Tombol Reusable)
* **Varian:** `primary` (teal), `secondary` (amber), `outline` (border-teal), `danger` (red), `ghost` (tanpa latar belakang).
* **Interaksi:**
  * Hover desktop: Perubahan warna lebih gelap sedikit (`hover:bg-teal-800`).
  * Efek tekan: `active:scale-95 transition-transform duration-150`.
  * Integrasi *loading state*: Menampilkan spinner mini di dalam tombol saat melakukan pengiriman data (API call).

### 2. `Card` (Kartu Informasi & Statistik)
* Desain minimalis tanpa bayangan tebal.
* Menggunakan border halus `border border-slate-200` dan bayangan mikro `shadow-sm hover:shadow-md transition-shadow`.

### 3. `FormInput` & `FormSelect` (Input Formulir)
* Komponen input standar yang menampilkan label, placeholder, indikator wajib diisi (*asterisk* `*`), dan area pesan kesalahan (*error message*) di bawahnya secara dinamis.
* Fokus input: Ring luar berwarna teal lembut (`focus:ring-2 focus:ring-teal-500 focus:border-teal-500`).

### 4. `SkeletonLoader` (Indikator Pemuatan)
* Menggantikan spinner tradisional dengan animasi berdenyut abu-abu (`animate-pulse`) yang menyerupai bentuk asli data (seperti bentuk teks baris atau bentuk kartu) guna mengurangi persepsi waktu tunggu bagi warga.

---

## 🗺️ 4. Peta Halaman & Spesifikasi Tata Letak (Layouts)

Aplikasi dibagi menjadi tiga ruang utama yang memiliki karakteristik berbeda namun tetap menggunakan tema desain yang selaras.

### A. PORTAL PUBLIK (Akses Terbuka untuk Umum & Warga)
*Berfokus pada penyajian informasi desa yang informatif dan estetik.*

1. **Halaman Beranda (Landing Page):**
   * **Hero Section:** Judul sambutan yang hangat, latar belakang foto wilayah desa yang terkompresi dengan baik, disertai dua tombol aksi (CTA): "Ajukan Surat Online" (Primary) dan "Pelajari Profil Desa" (Outline).
   * **Informasi Statistik Cepat:** 4 kartu ringkas yang menyajikan jumlah penduduk, luas wilayah, jumlah dusun, dan layanan aktif.
   * **Daftar Berita Terbaru:** Grid berisi maksimal 3 berita terbaru tentang kegiatan desa dengan penyaringan (filter) kategori sederhana.
2. **Halaman Lembaga & Profil:**
   * **Struktur Organisasi:** Bagan hierarki yang disederhanakan. Pada perangkat seluler, bagan ini berubah menjadi daftar kartu vertikal agar tidak memakan tempat ke samping.
   * **Peta Wilayah:** Peta yang terintegrasi (disarankan menggunakan LeafletJS/OpenStreetMap secara *lazy-loaded* agar beban memori tetap ringan).

---

### B. PORTAL LAYANAN MANDIRI (Khusus Warga Terdaftar)
*Berfokus pada efisiensi proses administrasi dan kegunaan form.*

1. **Autentikasi:**
   * Form login menggunakan kombinasi NIK dan Kata Sandi.
   * Dilengkapi petunjuk keamanan data pribadi secara transparan di area bawah form.
2. **Dasbor Warga (Citizen Dashboard):**
   * **Status Pengajuan Surat:** Menampilkan daftar surat yang sedang diajukan beserta status terbarunya melalui diagram alir sederhana atau penanda warna (Kuning: Menunggu, Biru: Diproses, Hijau: Selesai, Merah: Ditolak).
   * **Katalog Pengajuan:** Grid tombol berikon bersih yang mewakili jenis surat (misal: Surat Pengantar, Keterangan Usaha, Keterangan Tidak Mampu).
3. **Alur Pengisian Form (Multi-step Form Wizard):**
   * Membagi formulir pengajuan surat yang panjang menjadi maksimal 3 langkah (1: Data Diri, 2: Syarat Dokumen/Unggah Berkas, 3: Tinjauan Akhir).
   * Dilengkapi komponen pembantu visual (*step indicator*) yang ramah seluler di bagian atas.

---

### C. DASBOR ADMIN (Untuk Perangkat Desa)
*Berfokus pada pengelolaan data yang padat, kejelasan status, dan kecepatan operasional.*

1. **Tata Letak Utama (Dashboard Layout):**
   * **Sidebar (Desktop):** Navigasi vertikal minimalis yang dapat disembunyikan (*collapsible*).
   * **Drawer Menu (Seluler):** Menu geser samping yang responsif dan dapat diakses dengan gestur sentuh atau tombol hamburger.
2. **Manajemen Data Penduduk (Data Table):**
   * Tabel interaktif dengan fitur pencarian cepat (*search as you type*), filter per wilayah RT/RW, dan navigasi halaman (*pagination*) tanpa menyegarkan halaman sepenuhnya.
   * **Aturan Responsif:** Di bawah resolusi `768px` (tablet/ponsel), tabel secara otomatis dikonversi menjadi barisan kartu (*cards*) untuk menghindari gulir horizontal (*horizontal scrolling*).
3. **Inbox Pemrosesan Surat:**
   * Daftar antrean pengajuan surat dari warga.
   * **Modul Verifikasi:** Menampilkan data formulir warga bersandingan langsung dengan dokumen unggahan warga (KTP/KK) dalam satu layar (menggunakan kolom ganda di desktop atau modal di seluler) untuk mempercepat proses persetujuan oleh petugas desa.

---

## ⚡ 5. Mikro-Interaksi & Panduan Performa

1. **Transisi Halaman:**
   * Menggunakan transisi memudar halus (*fade transition*) dengan durasi singkat (`transition-opacity duration-150 ease-out`) agar perpindahan halaman terasa instan.
2. **Status Kosong (Empty State):**
   * Jika tidak ada data yang ditemukan (misal: tidak ada riwayat pengajuan surat), tampilkan visual ilustrasi garis tipis (*line-art*) minimalis disertai teks penjelasan yang ramah dan tombol pemicu aksi.
3. **Penanganan Kesalahan (Error Handling):**
   * Jika terjadi kendala jaringan saat memanggil API, sistem harus menampilkan notifikasi melayang (*toast notification*) di pojok layar secara otomatis yang tidak menginterupsi pekerjaan pengguna.
4. **Optimasi Berkas:**
   * Seluruh ikon menggunakan SVG (disarankan menggunakan pustaka seperti `lucide-react` secara modular sehingga hanya ikon yang digunakan saja yang masuk ke dalam bundel).

---

## ⚙️ 6. Panduan Integrasi Backend (Backend Alignment)

Untuk menyelaraskan antara UI frontend dengan API backend pada proyek "Desaku":

1. **Format Respons Standar:** Frontend harus siap memproses respons JSON dari backend dengan format umum seperti:
   ```json
   {
     "success": true,
     "message": "Data berhasil dimuat",
     "data": { ... }
   }