<?php

namespace Tests\Feature;

use App\Jobs\GenerateSuratPdfJob;
use App\Models\Administrator;
use App\Models\KategoriSurat;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use Database\Seeders\KategoriSuratSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PengajuanSuratTest extends TestCase
{
    protected $warga;

    protected $admin;

    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(KategoriSuratSeeder::class);

        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test',
            'dusun' => 'Test',
            'rt_rw' => '001/002',
        ]);

        $this->warga = Penduduk::create([
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

        $this->admin = Administrator::create([
            'username' => 'testadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);

        $this->kategori = KategoriSurat::first();
    }

    public function test_warga_can_get_kategori_surat()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/surat/kategori');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'kode_surat', 'nama_surat', 'schema_isian', 'syarat_dokumen'],
                ],
            ]);
    }

    public function test_warga_can_submit_pengajuan_surat()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => $this->kategori->id,
                'data_isian' => [
                    'keperluan' => 'Melamar pekerjaan',
                    'lama_tinggal' => 10,
                ],
                'file_syarat' => [
                    'ktp' => '/storage/uploads/ktp.jpg',
                    'kk' => '/storage/uploads/kk.jpg',
                ],
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'nomor_registrasi', 'status'],
            ]);

        $this->assertDatabaseHas('pengajuan_surat', [
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'status' => 'Pending',
        ]);
    }

    public function test_warga_can_get_their_pengajuan()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
        ]);

        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/surat/pengajuan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nomor_registrasi', 'status'],
                ],
            ]);
    }

    public function test_admin_can_get_all_pengajuan()
    {
        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/admin/surat/pengajuan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nomor_registrasi', 'pemohon'],
                ],
            ]);
    }

    public function test_admin_can_approve_pengajuan()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
            'status' => 'Pending',
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $response->assertStatus(200);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Disetujui',
            'diverifikasi_oleh' => $this->admin->id,
        ]);

        Queue::assertPushed(GenerateSuratPdfJob::class);
    }

    public function test_admin_can_reject_pengajuan()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
            'status' => 'Pending',
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/reject", [
                'catatan_penolakan' => 'Dokumen tidak lengkap',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Ditolak',
            'catatan_penolakan' => 'Dokumen tidak lengkap',
        ]);
    }

    public function test_warga_cannot_approve_pengajuan()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
        ]);

        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $response->assertStatus(403);
    }
}
