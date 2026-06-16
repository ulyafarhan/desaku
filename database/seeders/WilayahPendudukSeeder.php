<?php

/**
 * SEEDER — Wilayah, Keluarga, Penduduk & Informasi Publik
 *
 * Mengisi data autentik Gampong Udeung meliputi:
 * 1. Referensi wilayah (Provinsi Aceh → Gampong Udeung)
 * 2. 5 keluarga contoh dengan total 16 penduduk
 * 3. 3 informasi publik (berita & pengumuman)
 *
 * @see \App\Models\ReferensiWilayah
 * @see \App\Models\Keluarga
 * @see \App\Models\Penduduk
 * @see \App\Models\InformasiPublik
 */

namespace Database\Seeders;

use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\ReferensiWilayah;
use App\Models\InformasiPublik;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WilayahPendudukSeeder extends Seeder
{
    /**
     * Jalankan proses seeding data awal.
     *
     * Tahapan:
     * 1. Wilayah     — 6 referensi: 1 provinsi, 1 kabupaten, 1 kecamatan, 3 gampong
     * 2. Penduduk    — 5 keluarga (KK) dengan total 16 anggota tersebar di 3 dusun
     * 3. Informasi   — 3 konten publik (2 pengumuman, 1 berita)
     */
    public function run(): void
    {
        // 1. Referensi Wilayah
        $wilayah = [
            [
                'kode_wilayah' => '11',
                'nama_wilayah' => 'Provinsi Aceh',
                'level' => 'provinsi',
                'parent_kode' => null,
            ],
            [
                'kode_wilayah' => '11.18',
                'nama_wilayah' => 'Kab. Pidie Jaya',
                'level' => 'kabupaten',
                'parent_kode' => '11',
            ],
            [
                'kode_wilayah' => '11.18.06',
                'nama_wilayah' => 'Kec. Bandar Baru',
                'level' => 'kecamatan',
                'parent_kode' => '11.18',
            ],
            [
                'kode_wilayah' => '11.18.06.2001',
                'nama_wilayah' => 'Gampong Udeung',
                'level' => 'desa',
                'parent_kode' => '11.18.06',
            ],
            [
                'kode_wilayah' => '11.18.06.2002',
                'nama_wilayah' => 'Gampong Paru',
                'level' => 'desa',
                'parent_kode' => '11.18.06',
            ],
            [
                'kode_wilayah' => '11.18.06.2003',
                'nama_wilayah' => 'Gampong Nyong',
                'level' => 'desa',
                'parent_kode' => '11.18.06',
            ],
        ];

        foreach ($wilayah as $w) {
            ReferensiWilayah::updateOrCreate(['kode_wilayah' => $w['kode_wilayah']], $w);
        }

        // 2. Keluarga & Penduduk (5 Keluarga Realistis)
        $keluargaData = [
            [
                'no_kk' => '1118061010800001',
                'alamat' => 'Jl. Tgk. Chik di Phoroh No. 12, Gampong Udeung',
                'dusun' => 'Dusun Phoroh',
                'rt_rw' => 'RT 01 / RW 01',
                'anggota' => [
                    [
                        'nik' => '1118061005800001',
                        'nama_lengkap' => 'Teuku Faisal',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Pidie',
                        'tanggal_lahir' => '1980-05-10',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Wiraswasta',
                        'pendidikan' => 'S1',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Kepala Keluarga',
                    ],
                    [
                        'nik' => '1118064508820002',
                        'nama_lengkap' => 'Cut Nyak Fatimah',
                        'jenis_kelamin' => 'P',
                        'tempat_lahir' => 'Banda Aceh',
                        'tanggal_lahir' => '1982-08-05',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                        'pendidikan' => 'SMA',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Istri',
                    ],
                    [
                        'nik' => '1118060101100003',
                        'nama_lengkap' => 'Muhammad Aulia',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Pidie Jaya',
                        'tanggal_lahir' => '2010-01-01',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Pelajar/Mahasiswa',
                        'pendidikan' => 'SMP',
                        'status_perkawinan' => 'Belum Kawin',
                        'status_keluarga' => 'Anak',
                    ],
                ]
            ],
            [
                'no_kk' => '1118061210820002',
                'alamat' => 'Jl. Neulop Indah No. 5, Gampong Udeung',
                'dusun' => 'Dusun Neulop',
                'rt_rw' => 'RT 02 / RW 01',
                'anggota' => [
                    [
                        'nik' => '1118061507750001',
                        'nama_lengkap' => 'Zulkifli',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Sigli',
                        'tanggal_lahir' => '1975-07-15',
                        'agama' => 'Islam',
                        'pekerjaan' => 'PNS',
                        'pendidikan' => 'S1',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Kepala Keluarga',
                    ],
                    [
                        'nik' => '1118065010780002',
                        'nama_lengkap' => 'Mariani',
                        'jenis_kelamin' => 'P',
                        'tempat_lahir' => 'Meureudu',
                        'tanggal_lahir' => '1978-10-10',
                        'agama' => 'Islam',
                        'pekerjaan' => 'PNS',
                        'pendidikan' => 'S1',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Istri',
                    ],
                    [
                        'nik' => '1118060205050003',
                        'nama_lengkap' => 'Nadia Safira',
                        'jenis_kelamin' => 'P',
                        'tempat_lahir' => 'Pidie Jaya',
                        'tanggal_lahir' => '2005-05-02',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Pelajar/Mahasiswa',
                        'pendidikan' => 'SMA',
                        'status_perkawinan' => 'Belum Kawin',
                        'status_keluarga' => 'Anak',
                    ],
                ]
            ],
            [
                'no_kk' => '1118061510850003',
                'alamat' => 'Jl. Garuda Mas No. 22, Gampong Udeung',
                'dusun' => 'Dusun Garuda',
                'rt_rw' => 'RT 03 / RW 02',
                'anggota' => [
                    [
                        'nik' => '1118062011700001',
                        'nama_lengkap' => 'Nurdin',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Pidie Jaya',
                        'tanggal_lahir' => '1970-11-20',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Petani',
                        'pendidikan' => 'SD',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Kepala Keluarga',
                    ],
                    [
                        'nik' => '1118066212720002',
                        'nama_lengkap' => 'Zainab',
                        'jenis_kelamin' => 'P',
                        'tempat_lahir' => 'Pidie',
                        'tanggal_lahir' => '1972-12-22',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Tani',
                        'pendidikan' => 'SD',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Istri',
                    ],
                    [
                        'nik' => '1118061809020003',
                        'nama_lengkap' => 'Rahmad Saputra',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Pidie Jaya',
                        'tanggal_lahir' => '2002-09-18',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Wiraswasta',
                        'pendidikan' => 'SMA',
                        'status_perkawinan' => 'Belum Kawin',
                        'status_keluarga' => 'Anak',
                    ],
                ]
            ],
            [
                'no_kk' => '1118062010880004',
                'alamat' => 'Jl. Bukit Cot No. 45, Gampong Udeung',
                'dusun' => 'Dusun Cot',
                'rt_rw' => 'RT 04 / RW 02',
                'anggota' => [
                    [
                        'nik' => '1118060404850001',
                        'nama_lengkap' => 'Yusuf',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Lhokseumawe',
                        'tanggal_lahir' => '1985-04-04',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Pedagang',
                        'pendidikan' => 'SMA',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Kepala Keluarga',
                    ],
                    [
                        'nik' => '1118064802870002',
                        'nama_lengkap' => 'Sri Yuliana',
                        'jenis_kelamin' => 'P',
                        'tempat_lahir' => 'Bireuen',
                        'tanggal_lahir' => '1987-02-08',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Wiraswasta',
                        'pendidikan' => 'SMA',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Istri',
                    ],
                ]
            ],
            [
                'no_kk' => '1118062510900005',
                'alamat' => 'Jl. Tgk. Chik di Phoroh No. 19, Gampong Udeung',
                'dusun' => 'Dusun Phoroh',
                'rt_rw' => 'RT 01 / RW 01',
                'anggota' => [
                    [
                        'nik' => '1118060909650001',
                        'nama_lengkap' => 'Iskandar',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Sabang',
                        'tanggal_lahir' => '1965-09-09',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Wiraswasta',
                        'pendidikan' => 'SMA',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Kepala Keluarga',
                    ],
                    [
                        'nik' => '1118065505700002',
                        'nama_lengkap' => 'Siti Aminah',
                        'jenis_kelamin' => 'P',
                        'tempat_lahir' => 'Banda Aceh',
                        'tanggal_lahir' => '1970-05-15',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                        'pendidikan' => 'SMP',
                        'status_perkawinan' => 'Kawin',
                        'status_keluarga' => 'Istri',
                    ],
                    [
                        'nik' => '1118062210950003',
                        'nama_lengkap' => 'Hendra Wijaya',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Pidie Jaya',
                        'tanggal_lahir' => '1995-10-22',
                        'agama' => 'Islam',
                        'pekerjaan' => 'Wiraswasta',
                        'pendidikan' => 'S1',
                        'status_perkawinan' => 'Belum Kawin',
                        'status_keluarga' => 'Anak',
                    ],
                ]
            ]
        ];

        foreach ($keluargaData as $data) {
            $keluarga = Keluarga::updateOrCreate(
                ['no_kk' => $data['no_kk']],
                [
                    'alamat' => $data['alamat'],
                    'dusun' => $data['dusun'],
                    'rt_rw' => $data['rt_rw'],
                ]
            );

            $kepalaKeluargaNik = null;

            foreach ($data['anggota'] as $ang) {
                Penduduk::updateOrCreate(
                    ['nik' => $ang['nik']],
                    [
                        'no_kk' => $data['no_kk'],
                        'nama_lengkap' => $ang['nama_lengkap'],
                        'jenis_kelamin' => $ang['jenis_kelamin'],
                        'tempat_lahir' => $ang['tempat_lahir'],
                        'tanggal_lahir' => $ang['tanggal_lahir'],
                        'agama' => $ang['agama'],
                        'pekerjaan' => $ang['pekerjaan'],
                        'pendidikan' => $ang['pendidikan'],
                        'status_perkawinan' => $ang['status_perkawinan'],
                        'status_keluarga' => $ang['status_keluarga'],
                        'status_mutasi' => 'Tetap',
                    ]
                );

                if ($ang['status_keluarga'] === 'Kepala Keluarga') {
                    $kepalaKeluargaNik = $ang['nik'];
                }
            }

            if ($kepalaKeluargaNik) {
                $keluarga->update(['kepala_keluarga_nik' => $kepalaKeluargaNik]);
            }
        }

        // 3. Informasi Publik (Berita & Pengumuman Nyata)
        $informasi = [
            [
                'kategori' => 'Pengumuman',
                'judul' => 'Rapat Koordinasi Pembangunan Saluran Irigasi Dusun Phoroh',
                'slug' => 'rapat-koordinasi-pembangunan-saluran-irigasi-dusun-phoroh',
                'konten' => 'Sehubungan dengan program pengembangan sektor pertanian gampong, Keuchik Gampong Udeung mengundang seluruh pemilik lahan dan warga di lingkungan Dusun Phoroh untuk menghadiri rapat koordinasi terkait rencana pembangunan saluran irigasi baru. Rapat akan dilaksanakan pada hari Minggu ini pukul 09.00 WIB bertempat di Kantor Keuchik.',
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'kategori' => 'Pengumuman',
                'judul' => 'Penyaluran Bantuan Langsung Tunai (BLT) Tahap II Gampong Udeung',
                'slug' => 'penyaluran-blt-tahap-ii-gampong-udeung',
                'konten' => 'Pemerintah Gampong Udeung mengumumkan bahwa penyaluran Bantuan Langsung Tunai (BLT) Dana Desa untuk Tahap II akan diselenggarakan pada hari Kamis mendatang. Penyaluran dimulai pukul 09.00 WIB hingga selesai di Aula Kantor Keuchik. Warga penerima manfaat diwajibkan membawa Kartu Keluarga (KK) dan KTP asli serta mematuhi nomor antrean yang telah dibagikan.',
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'kategori' => 'Berita',
                'judul' => 'Kegiatan Gotong Royong Kebersihan Lingkungan Menyambut Hari Kemerdekaan',
                'slug' => 'kegiatan-gotong-royong-kebersihan-lingkungan-menyambut-hari-kemerdekaan',
                'konten' => 'Dalam rangka menyambut hari kemerdekaan, warga Gampong Udeung bersama unsur perangkat desa melaksanakan aksi gotong royong massal. Fokus kebersihan difokuskan pada pembersihan parit saluran pembuangan utama gampong, pemangkasan rumput di pinggir jalan utama, serta pemasangan bendera merah putih di sepanjang perbatasan wilayah dusun.',
                'is_published' => true,
                'created_at' => Carbon::now()->subDays(7),
            ],
        ];

        foreach ($informasi as $inf) {
            InformasiPublik::updateOrCreate(
                ['slug' => $inf['slug']],
                $inf
            );
        }
    }
}
