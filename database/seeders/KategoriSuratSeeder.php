<?php

/**
 * SEEDER — Kategori Surat Desa
 *
 * Mengisi data master jenis-jenis surat desa yang tersedia untuk
 * diajukan oleh warga. Setiap kategori memiliki template view,
 * skema isian (field yang harus diisi), dan syarat dokumen.
 */

namespace Database\Seeders;

use App\Models\KategoriSurat;
use Illuminate\Database\Seeder;

class KategoriSuratSeeder extends Seeder
{
    /**
     * Buat 5 kategori surat default.
     *
     * 1. SKD  — Surat Keterangan Domisili (keperluan, lama tinggal)
     * 2. SKTM — Surat Keterangan Tidak Mampu (keperluan, tanggungan, penghasilan)
     * 3. SKU  — Surat Keterangan Usaha (nama, jenis, alamat, tahun berdiri)
     * 4. SKP  — Surat Pengantar KTP (jenis permohonan: baru/perpanjangan/hilang)
     * 5. SKL  — Surat Keterangan Kelahiran (data bayi dan orang tua)
     */
    public function run(): void
    {
        $kategoriSurat = [
            [
                'kode_surat' => 'SKD',
                'nama_surat' => 'Surat Keterangan Domisili',
                'template_view' => 'domisili',
                'schema_isian' => [
                    [
                        'field' => 'keperluan',
                        'label' => 'Keperluan',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'field' => 'lama_tinggal',
                        'label' => 'Lama Tinggal (tahun)',
                        'type' => 'number',
                        'required' => true,
                    ],
                ],
                'syarat_dokumen' => [
                    'KTP',
                    'Kartu Keluarga',
                ],
                'is_active' => true,
            ],
            [
                'kode_surat' => 'SKTM',
                'nama_surat' => 'Surat Keterangan Tidak Mampu',
                'template_view' => 'sktm',
                'schema_isian' => [
                    [
                        'field' => 'keperluan',
                        'label' => 'Keperluan',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'field' => 'jumlah_tanggungan',
                        'label' => 'Jumlah Tanggungan',
                        'type' => 'number',
                        'required' => true,
                    ],
                    [
                        'field' => 'penghasilan_perbulan',
                        'label' => 'Penghasilan Per Bulan (Rp)',
                        'type' => 'number',
                        'required' => true,
                    ],
                ],
                'syarat_dokumen' => [
                    'KTP',
                    'Kartu Keluarga',
                    'Foto Rumah',
                ],
                'is_active' => true,
            ],
            [
                'kode_surat' => 'SKU',
                'nama_surat' => 'Surat Keterangan Usaha',
                'template_view' => 'usaha',
                'schema_isian' => [
                    [
                        'field' => 'nama_usaha',
                        'label' => 'Nama Usaha',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'field' => 'jenis_usaha',
                        'label' => 'Jenis Usaha',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'field' => 'alamat_usaha',
                        'label' => 'Alamat Usaha',
                        'type' => 'textarea',
                        'required' => true,
                    ],
                    [
                        'field' => 'tahun_berdiri',
                        'label' => 'Tahun Berdiri',
                        'type' => 'number',
                        'required' => true,
                    ],
                ],
                'syarat_dokumen' => [
                    'KTP',
                    'Foto Usaha',
                ],
                'is_active' => true,
            ],
            [
                'kode_surat' => 'SKP',
                'nama_surat' => 'Surat Pengantar KTP',
                'template_view' => 'pengantar_ktp',
                'schema_isian' => [
                    [
                        'field' => 'jenis_permohonan',
                        'label' => 'Jenis Permohonan',
                        'type' => 'select',
                        'options' => ['Baru', 'Perpanjangan', 'Hilang'],
                        'required' => true,
                    ],
                ],
                'syarat_dokumen' => [
                    'Kartu Keluarga',
                    'Akta Kelahiran',
                    'Pas Foto 4x6',
                ],
                'is_active' => true,
            ],
            [
                'kode_surat' => 'SKL',
                'nama_surat' => 'Surat Keterangan Kelahiran',
                'template_view' => 'kelahiran',
                'schema_isian' => [
                    [
                        'field' => 'nama_bayi',
                        'label' => 'Nama Bayi',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'field' => 'jenis_kelamin_bayi',
                        'label' => 'Jenis Kelamin',
                        'type' => 'select',
                        'options' => ['Laki-laki', 'Perempuan'],
                        'required' => true,
                    ],
                    [
                        'field' => 'tempat_lahir',
                        'label' => 'Tempat Lahir',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'field' => 'tanggal_lahir',
                        'label' => 'Tanggal Lahir',
                        'type' => 'date',
                        'required' => true,
                    ],
                    [
                        'field' => 'nama_ayah',
                        'label' => 'Nama Ayah',
                        'type' => 'text',
                        'required' => true,
                    ],
                    [
                        'field' => 'nama_ibu',
                        'label' => 'Nama Ibu',
                        'type' => 'text',
                        'required' => true,
                    ],
                ],
                'syarat_dokumen' => [
                    'Surat Keterangan Lahir dari Bidan/RS',
                    'KTP Orang Tua',
                    'Kartu Keluarga',
                    'Buku Nikah',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($kategoriSurat as $kategori) {
            KategoriSurat::create($kategori);
        }
    }
}
