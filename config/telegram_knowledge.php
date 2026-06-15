<?php

return [
    'greetings' => [
        'keywords' => ['halo', 'hai', 'p', 'tes', 'test', 'assalamualaikum', 'pagi', 'siang', 'sore', 'malam', 'hello', 'ping', 'hi'],
        'response' => "Halo! Selamat datang di Bot SIG-Udeung\n\nAda yang bisa saya bantu hari ini? Anda bisa menanyakan tentang pengajuan surat, persyaratan dokumen, profil gampong, atau kontak penting.\n\nKetik pertanyaan Anda secara langsung atau gunakan tombol menu jika tersedia.",
    ],
    'faqs' => [
        [
            'keywords' => ['sktm', 'tidak mampu', 'keterangan tidak mampu'],
            'response' => "*Surat Keterangan Tidak Mampu (SKTM)*\n\n*Persyaratan Dokumen:*\n1. Fotokopi Kartu Keluarga (KK)\n2. Fotokopi KTP Pemohon (Orang tua/Wali jika untuk sekolah)\n3. Surat Pengantar dari Kepala Dusun\n4. Surat Pernyataan Tidak Mampu bermeterai 10.000\n\n*Cara Pengajuan:*\nLogin ke PWA SIG-Udeung menggunakan NIK Anda, masuk to menu *Pengajuan Surat*, pilih *SKTM*, lalu isi data dan unggah dokumen persyaratan.",
        ],
        [
            'keywords' => ['domisili', 'tempat tinggal', 'keterangan domisili'],
            'response' => "*Surat Keterangan Domisili*\n\n*Persyaratan Dokumen:*\n1. Fotokopi Kartu Keluarga (KK)\n2. Fotokopi KTP Pemohon\n3. Surat Pengantar dari Kepala Dusun\n\n*Cara Pengajuan:*\nLogin ke PWA SIG-Udeung dengan NIK Anda, pilih menu *Pengajuan Surat* -> *Surat Keterangan Domisili*, isi formulir lalu unggah berkas.",
        ],
        [
            'keywords' => ['sku', 'keterangan usaha', 'izin usaha', 'buat usaha'],
            'response' => "*Surat Keterangan Usaha (SKU)*\n\n*Persyaratan Dokumen:*\n1. Fotokopi Kartu Keluarga (KK) & KTP\n2. Surat Pengantar dari Kepala Dusun\n3. Foto tempat usaha / kegiatan usaha\n\n*Cara Pengajuan:*\nAjukan secara online melalui PWA SIG-Udeung pada menu *Pengajuan Surat* -> *Surat Keterangan Usaha*.",
        ],
        [
            'keywords' => ['pengantar ktp', 'buat ktp', 'ktp baru', 'syarat ktp'],
            'response' => "*Surat Pengantar KTP*\n\n*Persyaratan Dokumen:*\n1. Fotokopi Kartu Keluarga (KK)\n2. Berusia minimal 17 tahun atau sudah menikah\n3. Surat Pengantar dari Kepala Dusun\n\n*Cara Pengajuan:*\nAjukan di PWA SIG-Udeung pada menu *Pengajuan Surat* -> *Surat Pengantar KTP*.",
        ],
        [
            'keywords' => ['kelahiran', 'surat lahir', 'akta lahir', 'lahir'],
            'response' => "*Surat Keterangan Kelahiran*\n\n*Persyaratan Dokumen:*\n1. Fotokopi KK & KTP Suami-Istri\n2. Surat Keterangan Kelahiran dari Bidan/Klinik/RS (jika ada)\n3. Fotokopi Buku Nikah orang tua\n\n*Cara Pengajuan:*\nAjukan laporan kelahiran di menu *Mutasi Penduduk* pada PWA SIG-Udeung.",
        ],
        [
            'keywords' => ['pindah', 'datang', 'mutasi', 'keluar', 'masuk'],
            'response' => "*Mutasi Kependudukan (Pindah/Datang)*\n\n*1. Kedatangan (Pindah Masuk):*\nUnggah Surat Keterangan Pindah (SKP) dari daerah asal melalui menu *Mutasi Penduduk* di PWA.\n\n*2. Kepindahan (Pindah Keluar):*\nAjukan permohonan Surat Pindah Keluar melalui menu *Mutasi Penduduk* di PWA dengan melampirkan KK dan KTP.",
        ],
        [
            'keywords' => ['kontak', 'telepon', 'wa', 'whatsapp', 'nomor', 'keuchik', 'perangkat'],
            'response' => "*Kontak Penting Gampong Udeung:*\n\n* Kantor Keuchik: 0812-xxxx-xxxx\n* Sekdes (Sekretaris Desa): 0852-xxxx-xxxx\n* Operator SIG-Udeung: 0823-xxxx-xxxx\n\nAnda juga bisa langsung datang ke Kantor Keuchik Gampong Udeung setiap hari kerja.",
        ],
        [
            'keywords' => ['jam kerja', 'buka', 'tutup', 'jadwal', 'kantor desa', 'kantor keuchik'],
            'response' => "*Jam Operasional Kantor Keuchik Gampong Udeung:*\n\n* Senin - Kamis: 08.30 - 16.00 WIB\n* Jumat: 08.30 - 16.30 WIB (Istirahat Shalat Jumat: 12.00 - 14.00 WIB)\n* Sabtu, Minggu & Libur Nasional: *Tutup*\n\nPengajuan online melalui PWA SIG-Udeung dapat dilakukan kapan saja (24 jam), namun verifikasi berkas dilakukan pada jam kerja.",
        ],
        [
            'keywords' => ['kematian', 'meninggal', 'wafat', 'surat kematian'],
            'response' => "*Surat Keterangan Kematian*\n\n*Persyaratan Dokumen:*\n1. Fotokopi KK & KTP mendiang (jika ada)\n2. Fotokopi KTP pelapor\n3. Surat pengantar kematian dari dusun / RS\n\n*Cara Pengajuan:*\nAjukan laporan kematian melalui menu *Mutasi Penduduk* di PWA SIG-Udeung.",
        ],
        [
            'keywords' => ['skck', 'catatan kepolisian', 'pengantar skck'],
            'response' => "*Surat Pengantar SKCK*\n\n*Persyaratan Dokumen:*\n1. Fotokopi KK & KTP Pemohon\n2. Surat Pengantar dari Kepala Dusun\n\n*Cara Pengajuan:*\nAjukan di PWA SIG-Udeung pada menu *Pengajuan Surat* -> *Surat Pengantar SKCK*.",
        ],
        [
            'keywords' => ['belum menikah', 'belum kawin', 'keterangan belum menikah'],
            'response' => "*Surat Keterangan Belum Menikah*\n\n*Persyaratan Dokumen:*\n1. Fotokopi KK & KTP Pemohon\n2. Surat Pengantar dari Kepala Dusun\n\n*Cara Pengajuan:*\nAjukan di PWA SIG-Udeung pada menu *Pengajuan Surat* -> *Surat Keterangan Belum Menikah*.",
        ],
        [
            'keywords' => ['beda nama', 'beda identitas', 'selisih nama'],
            'response' => "*Surat Keterangan Beda Nama*\n\n*Persyaratan Dokumen:*\n1. Fotokopi KK & KTP\n2. Fotokopi dokumen yang memiliki perbedaan nama (misal Ijazah, Akta Lahir, dll)\n3. Surat Pengantar dari Kepala Dusun\n\n*Cara Pengajuan:*\nAjukan di PWA SIG-Udeung pada menu *Pengajuan Surat* -> *Surat Keterangan Beda Nama*.",
        ],
        [
            'keywords' => ['kk baru', 'pecah kk', 'buat kk'],
            'response' => "*Pengurusan Kartu Keluarga (KK)*\n\n*Persyaratan Dokumen:*\n1. Kartu Keluarga (KK) asli yang lama\n2. Buku Nikah / Akta Cerai (untuk pecah/buat KK baru)\n3. Surat Pengantar dari Kepala Dusun\n\n*Cara Pengajuan:*\nHubungi operator desa melalui nomor layanan desa atau kunjungi langsung Kantor Keuchik untuk bantuan administrasi fisik.",
        ],
        [
            'keywords' => ['kehilangan', 'surat kehilangan', 'pengantar kehilangan'],
            'response' => "*Surat Pengantar Kehilangan*\n\n*Persyaratan Dokumen:*\n1. Fotokopi KK & KTP\n2. Penjelasan barang/dokumen yang hilang\n\n*Cara Pengajuan:*\nAjukan di PWA SIG-Udeung pada menu *Pengajuan Surat* -> *Surat Pengantar Kehilangan* untuk dibawa ke kepolisian.",
        ],
        [
            'keywords' => ['bantuan', 'blt', 'bansos', 'pkh'],
            'response' => "*Informasi Bantuan Sosial (Bansos/BLT/PKH):*\n\n* Kriteria Penerima: Ditentukan berdasarkan musyawarah desa (Musdes) dan data terpadu kesejahteraan sosial (DTKS).\n* Pengajuan Mandiri: Warga dapat berkonsultasi dengan Kepala Dusun masing-masing untuk memeriksa status kepesertaan bansos Anda di database DTKS.",
        ],
    ],
    'kb' => [
        'profil' => 'Gampong Udeung terletak di Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh. Keuchik (Kepala Desa) saat ini dijabat oleh Bapak Keuchik Udeung. Gampong ini memiliki beberapa dusun aktif dan berkomitmen menerapkan digitalisasi pelayanan melalui sistem SIG-Udeung.',
        'pwa_sig' => 'Sistem Informasi Gampong Udeung (SIG-Udeung) dapat diakses oleh warga untuk mengajukan berbagai surat secara mandiri. Warga perlu login menggunakan NIK yang terdaftar. Fitur utama meliputi Pengajuan Surat, Data Keluarga, Kelengkapan Biodata, dan pelaporan Mutasi Penduduk.',
        'syarat_umum' => 'Untuk semua pengajuan pelayanan administrasi di Gampong Udeung, warga diwajibkan memiliki NIK yang valid dan terdaftar di database kependudukan desa, serta melampirkan berkas penunjang yang jelas (tidak buram/terpotong).',
        'pengambilan_surat' => 'Setelah pengajuan surat disetujui oleh perangkat desa, dokumen PDF surat resmi yang ditandatangani secara elektronik (QR Code) akan langsung dikirimkan oleh bot Telegram ini kepada pemohon. Surat juga dapat diunduh langsung melalui dashboard PWA.',
        'layanan' => 'Layanan surat digital SIG-Udeung mencakup SKTM, Surat Keterangan Domisili, Surat Keterangan Usaha (SKU), Surat Pengantar KTP, Surat Pengantar SKCK, Surat Keterangan Belum Menikah, Surat Keterangan Beda Nama, dan Surat Pengantar Kehilangan.',
        'alasan_ditolak' => 'Alasan utama pengajuan surat ditolak oleh perangkat desa meliputi: berkas persyaratan buram/tidak terbaca, dokumen salah unggah, data formulir tidak sesuai dengan database kependudukan, atau NIK tidak terdaftar.',
    ],
];
