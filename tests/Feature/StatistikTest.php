<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\PengajuanSurat;
use App\Models\KategoriSurat;
use Tests\TestCase;

class StatistikTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\KategoriSuratSeeder::class);

        // Create sample data
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test',
            'dusun' => 'Dusun A',
            'rt_rw' => '001/002',
        ]);

        Penduduk::create([
            'nik' => '1111111111111111',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'John Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Programmer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);

        Penduduk::create([
            'nik' => '2222222222222222',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'Jane Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Designer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);
    }

    public function test_can_get_statistik_demografi()
    {
        \Illuminate\Support\Facades\Cache::forget('statistik_demografi');
        $response = $this->getJson('/api/v1/statistik/demografi');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_penduduk',
                    'total_keluarga',
                    'laki_laki',
                    'perempuan',
                    'per_dusun',
                    'per_agama',
                    'per_pendidikan',
                    'per_pekerjaan',
                    'per_usia',
                ],
            ]);

        $data = $response->json('data');
        $this->assertEquals(2, $data['total_penduduk']);
        $this->assertEquals(1, $data['laki_laki']);
        $this->assertEquals(1, $data['perempuan']);
    }

    public function test_can_get_statistik_layanan()
    {
        $penduduk = Penduduk::first();
        $kategori = KategoriSurat::first();

        PengajuanSurat::create([
            'nik_pemohon' => $penduduk->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
            'status' => 'Pending',
        ]);

        $response = $this->getJson('/api/v1/statistik/layanan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'pengajuan_surat' => [
                        'total',
                        'pending',
                        'diproses',
                        'selesai',
                        'ditolak',
                    ],
                    'mutasi_penduduk',
                    'per_jenis_surat',
                ],
            ]);

        $data = $response->json('data');
        $this->assertEquals(1, $data['pengajuan_surat']['total']);
        $this->assertEquals(1, $data['pengajuan_surat']['pending']);
    }

    public function test_statistik_demografi_uses_cache()
    {
        // First call
        $response1 = $this->getJson('/api/v1/statistik/demografi');
        $data1 = $response1->json('data');

        // Add new penduduk
        $keluarga = Keluarga::first();
        Penduduk::withoutEvents(function () use ($keluarga) {
            Penduduk::create([
                'nik' => '3333333333333333',
                'no_kk' => $keluarga->no_kk,
                'nama_lengkap' => 'New Person',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2000-01-01',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'pendidikan' => 'SMA',
                'pekerjaan' => 'Pelajar',
                'status_perkawinan' => 'Belum Kawin',
                'status_keluarga' => 'Anak',
                'status_mutasi' => 'Tetap',
            ]);
        });

        // Second call should return cached data
        $response2 = $this->getJson('/api/v1/statistik/demografi');
        $data2 = $response2->json('data');

        // Should be same because of cache
        $this->assertEquals($data1['total_penduduk'], $data2['total_penduduk']);
    }
}
