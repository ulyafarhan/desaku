<?php

/**
 * SEEDER — Pengaturan Gampong (Desa Udeung)
 *
 * Mengisi data konfigurasi awal Gampong Udeung berdasarkan studi
 * uji kelayakan di Kecamatan Bandar Baru, Kabupaten Pidie Jaya,
 * Provinsi Aceh. Data mencakup identitas wilayah, kontak, pejabat,
 * visi-misi, dan pengaturan lainnya.
 *
 * @see \App\Models\PengaturanGampong
 */

namespace Database\Seeders;

use App\Models\PengaturanGampong;
use Illuminate\Database\Seeder;

class PengaturanGampongSeeder extends Seeder
{
    /**
     * Buat pengaturan default untuk Gampong Udeung.
     *
     * Identitas wilayah: nama_gampong, kecamatan, kabupaten, provinsi, kode_pos
     * Kontak          : email, telepon, alamat_kantor
     * Pejabat         : nama_keuchik, nip_keuchik, nama_sekdes
     * Profil          : logo_gampong, visi, misi, sejarah singkat
     * Administrasi    : jam_pelayanan, batas_pengajuan
     */
    public function run(): void
    {
        $settings = [
            // Identitas Wilayah
            [
                'kunci' => 'nama_gampong',
                'nilai' => 'Gampong Udeung',
                'tipe_data' => 'string',
                'deskripsi' => 'Nama resmi gampong',
            ],
            [
                'kunci' => 'kecamatan',
                'nilai' => 'Bandar Baru',
                'tipe_data' => 'string',
                'deskripsi' => 'Nama kecamatan',
            ],
            [
                'kunci' => 'kabupaten',
                'nilai' => 'Pidie Jaya',
                'tipe_data' => 'string',
                'deskripsi' => 'Nama kabupaten',
            ],
            [
                'kunci' => 'provinsi',
                'nilai' => 'Aceh',
                'tipe_data' => 'string',
                'deskripsi' => 'Nama provinsi',
            ],
            [
                'kunci' => 'kode_pos',
                'nilai' => '24186',
                'tipe_data' => 'string',
                'deskripsi' => 'Kode pos gampong',
            ],
            [
                'kunci' => 'kode_desa',
                'nilai' => '11.18.06.2001',
                'tipe_data' => 'string',
                'deskripsi' => 'Kode desa sesuai Kemendagri',
            ],

            // Kontak & Alamat
            [
                'kunci' => 'email',
                'nilai' => 'gampong.udeung@pidiejaya.go.id',
                'tipe_data' => 'string',
                'deskripsi' => 'Email resmi gampong',
            ],
            [
                'kunci' => 'telepon',
                'nilai' => '0852-7000-1234',
                'tipe_data' => 'string',
                'deskripsi' => 'Nomor telepon kantor gampong',
            ],
            [
                'kunci' => 'alamat_kantor',
                'nilai' => 'Jl. Utama Gampong Udeung, Kec. Bandar Baru, Kab. Pidie Jaya, Aceh 24186',
                'tipe_data' => 'string',
                'deskripsi' => 'Alamat lengkap kantor keuchik',
            ],

            // Pejabat Gampong
            [
                'kunci' => 'nama_keuchik',
                'nilai' => 'Tgk. H. Muhammad Yusuf',
                'tipe_data' => 'string',
                'deskripsi' => 'Nama lengkap Keuchik Gampong Udeung',
            ],
            [
                'kunci' => 'nip_keuchik',
                'nilai' => '197503152005011004',
                'tipe_data' => 'string',
                'deskripsi' => 'NIP Keuchik (jika PNS)',
            ],
            [
                'kunci' => 'nama_sekdes',
                'nilai' => 'Cut Nurhaliza, S.E.',
                'tipe_data' => 'string',
                'deskripsi' => 'Nama lengkap Sekretaris Desa',
            ],

            // Profil Gampong
            [
                'kunci' => 'logo_gampong',
                'nilai' => '/images/logo-gampong.png',
                'tipe_data' => 'string',
                'deskripsi' => 'Path logo gampong',
            ],
            [
                'kunci' => 'visi',
                'nilai' => 'Mewujudkan Gampong Udeung yang Maju, Mandiri, dan Sejahtera Berlandaskan Gotong Royong dan Nilai-Nilai Keislaman',
                'tipe_data' => 'string',
                'deskripsi' => 'Visi gampong',
            ],
            [
                'kunci' => 'misi',
                'nilai' => json_encode([
                    'Meningkatkan kualitas pelayanan publik yang transparan dan akuntabel',
                    'Memberdayakan ekonomi masyarakat melalui program pemberdayaan UMKM',
                    'Meningkatkan infrastruktur gampong secara merata dan berkelanjutan',
                    'Menjaga keamanan dan ketertiban masyarakat melalui pola kebersamaan',
                    'Meningkatkan kualitas sumber daya manusia melalui pendidikan dan pelatihan',
                    'Melestarikan budaya dan adat istiadat Aceh di lingkungan gampong',
                ]),
                'tipe_data' => 'json',
                'deskripsi' => 'Misi gampong',
            ],
            [
                'kunci' => 'sejarah_singkat',
                'nilai' => 'Gampong Udeung merupakan salah satu gampong yang terletak di Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh. Nama "Udeung" berasal dari bahasa Aceh yang berarti "tanah tinggi" karena geografis gampong ini yang berada di dataran agak tinggi. Gampong ini dikenal sebagai penghasil padi dan kelapa yang melimpah. Masyarakat Gampong Udeung mayoritas berprofesi sebagai petani dan pedagang kecil.',
                'tipe_data' => 'string',
                'deskripsi' => 'Sejarah singkat gampong',
            ],

            // Administrasi & Pelayanan
            [
                'kunci' => 'jam_pelayanan',
                'nilai' => 'Senin - Jumat: 08.00 - 12.00 WIB',
                'tipe_data' => 'string',
                'deskripsi' => 'Jam pelayanan kantor gampong',
            ],
            [
                'kunci' => 'batas_pengajuan_surat',
                'nilai' => '16:00',
                'tipe_data' => 'string',
                'deskripsi' => 'Batas waktu pengajuan surat per hari',
            ],
            [
                'kunci' => 'nomor_rekening',
                'nilai' => '0123-456-789012',
                'tipe_data' => 'string',
                'deskripsi' => 'Nomor rekening kas gampong',
            ],
            [
                'kunci' => 'nama_bank',
                'nilai' => 'Bank Aceh Syariah',
                'tipe_data' => 'string',
                'deskripsi' => 'Nama bank kas gampong',
            ],
        ];

        foreach ($settings as $setting) {
            PengaturanGampong::create($setting);
        }
    }
}
