<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\KategoriSurat;
use App\Models\PengajuanSurat;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VerifikasiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\KategoriSuratSeeder::class);
    }

    public function test_can_verify_valid_qr_hash()
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test',
            'dusun' => 'Test',
            'rt_rw' => '001/002',
        ]);

        $penduduk = Penduduk::create([
            'nik' => '1234567890123456',
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
        ]);

        $admin = Administrator::create([
            'username' => 'testadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);

        $kategori = KategoriSurat::first();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $penduduk->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
            'status' => 'Selesai',
            'qr_hash' => 'test_hash_123',
            'diverifikasi_oleh' => $admin->id,
        ]);

        $response = $this->getJson('/api/v1/verifikasi/test_hash_123');

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'message' => 'Dokumen valid',
            ])
            ->assertJsonStructure([
                'data' => [
                    'nomor_registrasi',
                    'jenis_surat',
                    'nama_pemohon',
                    'nik_pemohon',
                    'tanggal_terbit',
                    'diverifikasi_oleh',
                ],
            ]);
    }

    public function test_returns_invalid_for_non_existent_hash()
    {
        $response = $this->getJson('/api/v1/verifikasi/invalid_hash');

        $response->assertStatus(404)
            ->assertJson([
                'valid' => false,
                'message' => 'Dokumen tidak ditemukan atau tidak valid',
            ]);
    }

    public function test_returns_invalid_for_unfinished_document()
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test',
            'dusun' => 'Test',
            'rt_rw' => '001/002',
        ]);

        $penduduk = Penduduk::create([
            'nik' => '1234567890123456',
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
        ]);

        $kategori = KategoriSurat::first();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $penduduk->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
            'status' => 'Pending',
            'qr_hash' => 'pending_hash_123',
        ]);

        $response = $this->getJson('/api/v1/verifikasi/pending_hash_123');

        $response->assertStatus(200)
            ->assertJson([
                'valid' => false,
                'message' => 'Dokumen belum selesai diproses',
            ]);
    }
}
