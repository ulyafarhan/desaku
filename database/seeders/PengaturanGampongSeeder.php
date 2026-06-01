<?php

namespace Database\Seeders;

use App\Models\PengaturanGampong;
use Illuminate\Database\Seeder;

class PengaturanGampongSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
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
                'kunci' => 'email',
                'nilai' => 'gampong@udeung.desa.id',
                'tipe_data' => 'string',
                'deskripsi' => 'Email resmi gampong',
            ],
            [
                'kunci' => 'telepon',
                'nilai' => '0853-xxxx-xxxx',
                'tipe_data' => 'string',
                'deskripsi' => 'Nomor telepon kantor gampong',
            ],
            [
                'kunci' => 'nama_keuchik',
                'nilai' => 'Nama Keuchik',
                'tipe_data' => 'string',
                'deskripsi' => 'Nama lengkap Keuchik',
            ],
            [
                'kunci' => 'nip_keuchik',
                'nilai' => '',
                'tipe_data' => 'string',
                'deskripsi' => 'NIP Keuchik (jika PNS)',
            ],
            [
                'kunci' => 'logo_gampong',
                'nilai' => '/images/logo-gampong.png',
                'tipe_data' => 'string',
                'deskripsi' => 'Path logo gampong',
            ],
            [
                'kunci' => 'visi',
                'nilai' => 'Mewujudkan Gampong Udeung yang Maju, Mandiri, dan Sejahtera',
                'tipe_data' => 'string',
                'deskripsi' => 'Visi gampong',
            ],
            [
                'kunci' => 'misi',
                'nilai' => json_encode([
                    'Meningkatkan kualitas pelayanan publik',
                    'Memberdayakan ekonomi masyarakat',
                    'Meningkatkan infrastruktur gampong',
                    'Menjaga keamanan dan ketertiban',
                ]),
                'tipe_data' => 'json',
                'deskripsi' => 'Misi gampong',
            ],
        ];

        foreach ($settings as $setting) {
            PengaturanGampong::create($setting);
        }
    }
}
