<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Administrator;
use App\Models\AuditLog;
use App\Models\ChatbotLog;
use App\Models\InformasiPublik;
use App\Models\KategoriSurat;
use App\Models\Keluarga;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\PengaturanGampong;
use App\Models\ReferensiWilayah;
use App\Models\TelegramBroadcastQueue;
use App\Models\TrackingPengajuanSurat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Ensure Administrator has at least 50 records
        if (Administrator::count() === 0) {
            $this->call(AdministratorSeeder::class);
        }
        
        $adminCount = Administrator::count();
        if ($adminCount < 50) {
            for ($i = $adminCount + 1; $i <= 50; $i++) {
                Administrator::create([
                    'username' => 'staff_' . $i,
                    'password' => bcrypt('password123'),
                    'role' => $i % 3 === 0 ? 'keuchik' : ($i % 3 === 1 ? 'sekdes' : 'operator'),
                ]);
            }
        }
        
        $admin = Administrator::first();

        // 2. Ensure Kategori Surat has at least 50 records
        if (KategoriSurat::count() === 0) {
            $this->call(KategoriSuratSeeder::class);
        }
        
        $kategoriCount = KategoriSurat::count();
        if ($kategoriCount < 50) {
            for ($i = $kategoriCount + 1; $i <= 50; $i++) {
                KategoriSurat::create([
                    'kode_surat' => 'SKD' . $i,
                    'nama_surat' => 'Surat Keterangan Dummy #' . $i,
                    'template_view' => 'domisili',
                    'schema_isian' => [
                        [
                            'field' => 'keperluan',
                            'label' => 'Keperluan',
                            'type' => 'text',
                            'required' => true,
                        ]
                    ],
                    'syarat_dokumen' => ['KTP', 'Kartu Keluarga'],
                    'is_active' => true,
                ]);
            }
        }
        $kategoriSuratList = KategoriSurat::all();

        // 3. Ensure PengaturanGampong has at least 50 records
        if (PengaturanGampong::count() === 0) {
            $this->call(PengaturanGampongSeeder::class);
        }
        
        $settingsCount = PengaturanGampong::count();
        if ($settingsCount < 50) {
            for ($i = $settingsCount + 1; $i <= 50; $i++) {
                PengaturanGampong::create([
                    'kunci' => 'dummy_setting_' . $i,
                    'nilai' => 'value_' . $i,
                    'tipe_data' => 'string',
                    'deskripsi' => 'Pengaturan dummy #' . $i,
                ]);
            }
        }

        // 4. Referensi Wilayah (ReferensiWilayah) - 55 Records
        ReferensiWilayah::truncate();
        
        ReferensiWilayah::create([
            'kode_wilayah' => '11',
            'nama_wilayah' => 'Provinsi Aceh',
            'level' => 'provinsi',
            'parent_kode' => null,
        ]);
        
        ReferensiWilayah::create([
            'kode_wilayah' => '11.18',
            'nama_wilayah' => 'Kab. Pidie Jaya',
            'level' => 'kabupaten',
            'parent_kode' => '11',
        ]);
        
        ReferensiWilayah::create([
            'kode_wilayah' => '11.18.06',
            'nama_wilayah' => 'Kec. Bandar Baru',
            'level' => 'kecamatan',
            'parent_kode' => '11.18',
        ]);
        
        for ($i = 1; $i <= 52; $i++) {
            $code = sprintf('11.18.06.2%03d', $i);
            $name = ($i === 1) ? 'Gampong Udeung' : 'Gampong Dummy ' . $i;
            ReferensiWilayah::create([
                'kode_wilayah' => $code,
                'nama_wilayah' => $name,
                'level' => 'desa',
                'parent_kode' => '11.18.06',
            ]);
        }

        // Arrays of raw dummy data to generate realistic Acehnese/Indonesian values
        $firstNames = ['Muhammad', 'Teuku', 'Cut', 'Ahmad', 'Zulkifli', 'Nurdin', 'Fatimah', 'Mariani', 'Zainab', 'Syarifuddin', 'Cut Nyak', 'Nadia', 'Aulia', 'Rizal', 'Yusuf', 'Iskandar', 'Rahmad', 'Sri', 'Dewi', 'Siti', 'Indra', 'Hendra', 'Herman', 'Yuliana', 'Rina', 'M. Amin', 'Suryani', 'M. Hasan', 'Faisal', 'Nadia Safira'];
        $lastNames = ['Hussein', 'Saputra', 'Pratama', 'Putra', 'Putri', 'Sari', 'Lestari', 'Hidayat', 'Wulandari', 'Kurniawan', 'Ramadhan', 'Wijaya', 'Arif', 'Maulana', 'Fitri', 'Hasanah', 'Ginting', 'Nasution', 'Lubis', 'Siregar', 'Munandar', 'Akbar', 'Fadilah', 'Pratiwi', 'Utami', 'Azizah', 'Mulia', 'Bakri', 'Gade', 'Jamil'];
        
        $dusuns = ['Dusun Phoroh', 'Dusun Neulop', 'Dusun Garuda', 'Dusun Cot'];
        $jobs = ['Petani', 'PNS', 'Pedagang', 'Wiraswasta', 'Buruh Harian', 'IRT', 'Pelajar/Mahasiswa', 'Pensiunan'];
        $educations = ['SD', 'SMP', 'SMA', 'D3', 'S1', 'Tidak/Belum Sekolah'];
        
        // 5. Generate Families (Keluarga) - 55 Records
        Keluarga::truncate();
        $families = [];
        for ($i = 1; $i <= 55; $i++) {
            $noKk = sprintf('111806%010d', $i + 1000000000); // 16 digit KK
            $rt = rand(1, 4);
            $rw = rand(1, 2);
            $rtRw = sprintf('RT %02d / RW %02d', $rt, $rw);
            
            $families[] = Keluarga::create([
                'no_kk' => $noKk,
                'alamat' => 'Jl. Phoroh No. ' . rand(1, 120) . ', Gampong Udeung',
                'dusun' => $dusuns[array_rand($dusuns)],
                'rt_rw' => $rtRw,
                'kepala_keluarga_nik' => null, // Filled later
            ]);
        }

        // 6. Generate Residents (Penduduk) - 165 Records (3 per family)
        Penduduk::truncate();
        $residents = [];
        $nikCounter = 1;
        
        foreach ($families as $fam) {
            for ($j = 0; $j < 3; $j++) {
                $nik = sprintf('111806%010d', $nikCounter + 2000000000); // 16 digit NIK
                $nikCounter++;
                
                $gender = ($j === 0) ? 'L' : (($j === 1) ? 'P' : (rand(0, 1) ? 'L' : 'P'));
                
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = $firstName . ' ' . $lastName;
                
                $birthDate = ($j === 0) 
                     ? Carbon::now()->subYears(rand(35, 60)) 
                     : (($j === 1) ? Carbon::now()->subYears(rand(30, 55)) : Carbon::now()->subYears(rand(2, 25)));
                    
                $statusKeluarga = ($j === 0) 
                    ? 'Kepala Keluarga' 
                    : (($j === 1) ? 'Istri' : 'Anak');
                    
                $statusPerkawinan = ($j === 0 || $j === 1) 
                    ? 'Kawin' 
                    : (rand(0, 1) ? 'Belum Kawin' : 'Kawin');
                
                $res = Penduduk::create([
                    'nik' => $nik,
                    'no_kk' => $fam->no_kk,
                    'nama_lengkap' => $fullName,
                    'tempat_lahir' => 'Pidie Jaya',
                    'tanggal_lahir' => $birthDate,
                    'jenis_kelamin' => $gender,
                    'agama' => 'Islam',
                    'pendidikan' => $educations[array_rand($educations)],
                    'pekerjaan' => $jobs[array_rand($jobs)],
                    'status_perkawinan' => $statusPerkawinan,
                    'status_keluarga' => $statusKeluarga,
                    'status_mutasi' => 'Tetap',
                    'telegram_chat_id' => null,
                ]);
                
                $residents[] = $res;
                
                if ($statusKeluarga === 'Kepala Keluarga') {
                    $fam->update([
                        'kepala_keluarga_nik' => $res->nik,
                    ]);
                }
            }
        }

        // 7. Generate Public Information / News (InformasiPublik) - 55 Records
        InformasiPublik::truncate();
        $kategoriInformasi = ['Berita', 'Pengumuman', 'Kegiatan', 'Sosialisasi', 'Agenda'];
        $newsTitles = [
            'Rapat Koordinasi Evaluasi Quick Wins Kampung Bebas Narkoba',
            'Pemerintah Gampong Udeung Salurkan BLT Dana Desa Tahap I',
            'Sosialisasi Bahaya Penyalahgunaan Narkoba Menyasar Pemuda Gampong',
            'Jadwal Gotong Royong Massal Kebersihan Lingkungan Masjid Gampong',
            'Dayah Raudhatul Ulum Selenggarakan Kajian Rutin Mingguan',
            'SD Negeri Neulop Mate Buka Pendaftaran Siswa Baru Tahun Pelajaran 2026/2027',
            'Peresmian Jembatan Gantung Garuda Penghubung Gampong Udeung - Ara',
            'Pemberdayaan Lahan Kakao Melalui Kelompok Tani Mandiri Gampong Udeung',
            'Pelatihan Pembuatan Kemasan Kue Tradisional Khas Aceh Bagi IRT',
            'Pemberian Makanan Tambahan Posyandu Balita dan Lansia Dusun Cot',
            'Penyuluhan Pertanian Optimalisasi Lahan Jagung Terintegrasi',
            'Musyawarah Rencana Pembangunan Gampong (Musrenbang) Udeung',
            'Peluncuran Sistem Digital Mandiri Warga Platform Desaku Udeung',
            'Pemilihan Anggota Tuha Peut Gampong Udeung Periode 2026-2032',
            'Peringatan Maulid Nabi Muhammad SAW di Masjid Jami Gampong',
        ];

        for ($i = 1; $i <= 55; $i++) {
            $baseTitle = $newsTitles[($i - 1) % count($newsTitles)];
            $judul = $baseTitle . ' Bagian #' . $i;
            $slug = Str::slug($judul);
            
            InformasiPublik::create([
                'judul' => $judul,
                'slug' => $slug,
                'konten' => '<p>Ini adalah isi informasi / konten resmi dari Gampong Udeung mengenai <strong>' . $judul . '</strong>. Kegiatan ini dihadiri oleh perangkat desa, tokoh masyarakat, pemuda, dan warga setempat. Diharapkan dengan adanya program ini dapat meningkatkan kesejahteraan, keamanan, dan keterbukaan informasi publik bagi seluruh masyarakat.</p><p>Mari bersama-sama kita dukung kemajuan Gampong Udeung menuju desa mandiri digital bebas narkoba!</p>',
                'kategori' => $kategoriInformasi[array_rand($kategoriInformasi)],
                'cover_image' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=600&auto=format&fit=crop',
                'is_published' => true,
                'author_id' => $admin->id,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
            ]);
        }

        // 8. Generate Letter Submissions (PengajuanSurat) - 55 Records
        PengajuanSurat::truncate();
        TrackingPengajuanSurat::truncate();
        
        $statuses = ['Pending', 'Diproses', 'Disetujui', 'Ditolak', 'Selesai'];
        
        for ($i = 1; $i <= 55; $i++) {
            $pemohon = $residents[array_rand($residents)];
            $kategori = $kategoriSuratList->random();
            $status = $statuses[array_rand($statuses)];
            
            $createdDate = Carbon::now()->subDays(rand(1, 45));
            $updatedDate = (in_array($status, ['Disetujui', 'Ditolak', 'Selesai'])) 
                ? (clone $createdDate)->addDays(rand(1, 3)) 
                : $createdDate;
            
            $catatanPenolakan = ($status === 'Ditolak') 
                ? 'Berkas dokumen persyaratan KK / Surat Pengantar Dusun tidak lengkap.' 
                : null;
                
            $filePdfUrl = (in_array($status, ['Disetujui', 'Selesai']))
                ? '/storage/surat/' . $kategori->kode_surat . '_' . $i . '.pdf'
                : null;
                
            $qrHash = (in_array($status, ['Disetujui', 'Selesai']))
                ? Str::random(32)
                : null;

            $verifikatorId = ($status !== 'Pending') ? $admin->id : null;

            $pengajuan = PengajuanSurat::create([
                'nomor_registrasi' => $createdDate->format('Ymd') . sprintf('-%04d', $i + 100),
                'nik_pemohon' => $pemohon->nik,
                'kategori_surat_id' => $kategori->id,
                'data_isian' => [
                    'keperluan' => 'Keperluan pengurusan dokumen administrasi keluarga #' . $i,
                    'keterangan_tambahan' => 'Diproses secara online via portal warga.',
                ],
                'file_syarat' => [
                    'ktp' => 'uploads/persyaratan/ktp_' . $pemohon->nik . '.jpg',
                    'kk' => 'uploads/persyaratan/kk_' . $pemohon->no_kk . '.jpg',
                ],
                'status' => $status,
                'catatan_penolakan' => $catatanPenolakan,
                'qr_hash' => $qrHash,
                'file_pdf_url' => $filePdfUrl,
                'diverifikasi_oleh' => $verifikatorId,
                'created_at' => $createdDate,
                'updated_at' => $updatedDate,
            ]);

            TrackingPengajuanSurat::create([
                'pengajuan_surat_id' => $pengajuan->id,
                'status_sebelumnya' => 'Pending',
                'status_baru' => ($status === 'Pending') ? 'Pending' : 'Diproses',
                'keterangan_update' => 'Pengajuan berhasil masuk ke sistem antrean pelayanan.',
                'diupdate_oleh' => null,
                'created_at' => $createdDate,
            ]);

            if ($status !== 'Pending') {
                TrackingPengajuanSurat::create([
                    'pengajuan_surat_id' => $pengajuan->id,
                    'status_sebelumnya' => 'Pending',
                    'status_baru' => $status,
                    'keterangan_update' => ($status === 'Ditolak') 
                        ? 'Pengajuan ditolak: ' . $catatanPenolakan 
                        : 'Pengajuan disetujui dan diverifikasi oleh petugas.',
                    'diupdate_oleh' => $admin->id,
                    'created_at' => $updatedDate,
                ]);
            }
        }

        // 9. Generate Mutations (MutasiPenduduk) - 55 Records
        MutasiPenduduk::truncate();
        $jenisMutasi = ['Kelahiran', 'Kematian', 'Kedatangan', 'Kepindahan'];
        $statusVerifikasi = ['Pending', 'Disetujui', 'Ditolak'];
        
        for ($i = 1; $i <= 55; $i++) {
            $pendudukMutasi = $residents[array_rand($residents)];
            $jenis = $jenisMutasi[array_rand($jenisMutasi)];
            $verif = $statusVerifikasi[array_rand($statusVerifikasi)];
            
            $mutasiDate = Carbon::now()->subDays(rand(1, 60));
            
            MutasiPenduduk::create([
                'nik' => $pendudukMutasi->nik,
                'jenis_mutasi' => $jenis,
                'tanggal_mutasi' => $mutasiDate,
                'keterangan' => 'Mutasi kependudukan jenis ' . $jenis . ' warga atas nama ' . $pendudukMutasi->nama_lengkap . ' nomor registrasi #' . $i,
                'dokumen_bukti' => 'uploads/mutasi/bukti_' . $pendudukMutasi->nik . '.pdf',
                'status_verifikasi' => $verif,
                'diverifikasi_oleh' => ($verif !== 'Pending') ? $admin->id : null,
                'created_at' => $mutasiDate,
            ]);
            
            if ($verif === 'Disetujui') {
                if ($jenis === 'Kematian') {
                    $pendudukMutasi->update(['status_mutasi' => 'Meninggal']);
                } elseif ($jenis === 'Kepindahan') {
                    $pendudukMutasi->update(['status_mutasi' => 'Pindah']);
                }
            }
        }

        // 10. Seed Audit Logs (AuditLog) - 55 Records
        AuditLog::truncate();
        for ($i = 1; $i <= 55; $i++) {
            $userType = $i % 2 === 0 ? 'admin' : 'warga';
            $actorAdmin = Administrator::inRandomOrder()->first();
            $actorWarga = Penduduk::inRandomOrder()->first();
            $userId = $userType === 'admin' ? $actorAdmin->id : $actorWarga->nik;
            $tindakan = ['CREATE', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT', 'EXPORT'][rand(0, 5)];
            $tables = ['penduduk', 'keluarga', 'pengajuan_surat', 'mutasi_penduduk', 'informasi_publik'];
            $targetTable = $tables[rand(0, 4)];
            
            AuditLog::create([
                'user_type' => $userType,
                'user_id' => $userId,
                'tindakan' => $tindakan,
                'nama_tabel' => $targetTable,
                'record_id' => rand(1, 100),
                'data_lama' => $tindakan === 'UPDATE' ? ['status' => 'Pending'] : null,
                'data_baru' => $tindakan === 'UPDATE' ? ['status' => 'Disetujui'] : null,
                'ip_address' => '127.0.0.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        // 11. Seed Chatbot Logs (ChatbotLog) - 55 Records
        ChatbotLog::truncate();
        $sampleQuestions = [
            'Bagaimana cara mengurus surat domisili?',
            'Apa syarat membuat SKTM?',
            'Berapa jumlah penduduk di Dusun Phoroh?',
            'Siapa kepala gampong Udeung?',
            'Kapan gotong royong massal diadakan?',
            'Halo, bisa bantu saya?',
            'Bagaimana cara mendaftar akun warga?',
            'Apakah surat SKU saya sudah disetujui?',
        ];
        $sampleReplies = [
            'Untuk mengurus Surat Keterangan Domisili, silakan login ke portal warga menggunakan NIK dan No KK, lalu pilih menu Pengajuan Surat dan isi form Domisili.',
            'Syarat membuat SKTM adalah KTP, Kartu Keluarga, dan Foto Rumah. Pengajuan dapat dilakukan secara online melalui portal warga.',
            'Berdasarkan data kependudukan terbaru, Dusun Phoroh memiliki populasi aktif yang terdata di sistem admin.',
            'Kepala Gampong (Keuchik) Gampong Udeung saat ini adalah Bapak Keuchik.',
            'Gotong royong massal dijadwalkan secara berkala, informasi lengkap dapat Anda lihat di menu Berita dan Informasi Publik.',
            'Halo! Saya asisten pintar Gampong Udeung. Ada yang bisa saya bantu terkait layanan administrasi desa?',
            'Warga tidak perlu mendaftar akun baru. Anda bisa langsung masuk menggunakan NIK dan Nomor KK yang sudah terdaftar di Dinas Kependudukan.',
            'Anda dapat memantau status pengajuan surat secara realtime pada dashboard portal warga Anda di bagian Status Pengajuan.',
        ];

        for ($i = 1; $i <= 55; $i++) {
            $idx = rand(0, count($sampleQuestions) - 1);
            ChatbotLog::create([
                'telegram_chat_id' => 'tele_' . rand(100000000, 999999999),
                'pesan_masuk' => $sampleQuestions[$idx],
                'balasan_ai' => $sampleReplies[$idx],
                'tokens_used' => rand(150, 450),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        // 12. Seed Telegram Broadcast Queue (TelegramBroadcastQueue) - 55 Records
        TelegramBroadcastQueue::truncate();
        $broadcastMsgs = [
            'Pengumuman: Gotong royong kebersihan lingkungan masjid akan dilaksanakan besok pagi pukul 08:00 WIB.',
            'BLT Dana Desa Tahap I sudah dapat dicairkan di kantor gampong dengan membawa KK dan KTP.',
            'Diimbau kepada seluruh warga untuk tetap waspada terhadap bahaya narkoba di lingkungan keluarga.',
            'Pemberian imunisasi tambahan balita akan diadakan di Posyandu Dusun Cot pada hari Kamis depan.',
            'Sosialisasi sertifikat tanah massal gratis (PTSL) akan diselenggarakan di aula kantor desa.',
        ];
        $targets = ['Semua Warga', 'Kepala Keluarga', 'Dusun Phoroh', 'Dusun Neulop', 'Dusun Garuda', 'Dusun Cot'];
        $queueStatuses = ['Queued', 'Processing', 'Completed', 'Failed'];

        for ($i = 1; $i <= 55; $i++) {
            $msg = $broadcastMsgs[rand(0, count($broadcastMsgs) - 1)] . ' #' . $i;
            $status = $queueStatuses[rand(0, count($queueStatuses) - 1)];
            $createdBy = Administrator::inRandomOrder()->first()?->id;
            
            $sendTime = Carbon::now()->subDays(rand(1, 30));
            $endTime = $status === 'Completed' || $status === 'Failed' ? (clone $sendTime)->addMinutes(rand(1, 10)) : null;

            TelegramBroadcastQueue::create([
                'pesan' => $msg,
                'kategori_target' => $targets[rand(0, count($targets) - 1)],
                'status' => $status,
                'jadwal_kirim' => $sendTime,
                'waktu_selesai' => $endTime,
                'created_by' => $createdBy,
            ]);
        }

        // 13. Seed Users - 55 Records
        User::truncate();
        for ($i = 1; $i <= 55; $i++) {
            User::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => bcrypt('password123'),
            ]);
        }

        // 14. Seed Notifications - 55 Records
        DB::table('notifications')->truncate();
        for ($i = 1; $i <= 55; $i++) {
            $adminUser = Administrator::inRandomOrder()->first();
            DB::table('notifications')->insert([
                'id' => Str::uuid()->toString(),
                'type' => 'Filament\\Notifications\\Notification',
                'notifiable_type' => 'App\\Models\\Administrator',
                'notifiable_id' => $adminUser->id,
                'data' => json_encode([
                    'id' => Str::random(10),
                    'title' => 'Pengajuan Surat Baru #' . $i,
                    'body' => 'Ada pengajuan surat baru yang membutuhkan verifikasi.',
                    'actions' => [],
                    'format' => 'filament',
                ]),
                'read_at' => $i % 3 === 0 ? Carbon::now()->subMinutes(rand(1, 60)) : null,
                'created_at' => Carbon::now()->subHours($i),
                'updated_at' => Carbon::now()->subHours($i),
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
