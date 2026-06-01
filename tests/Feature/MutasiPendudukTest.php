<?php

namespace Tests\Feature;

use App\Models\Administrator;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\MutasiPenduduk;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MutasiPendudukTest extends TestCase
{
    protected $warga;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

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
            'status_mutasi' => 'Tetap',
        ]);

        $this->admin = Administrator::create([
            'username' => 'testadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);
    }

    public function test_warga_can_submit_mutasi()
    {
        $token = $this->warga->createToken('test')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'nik' => $this->warga->nik,
                'jenis_mutasi' => 'Kelahiran',
                'tanggal_mutasi' => '2024-01-15',
                'keterangan' => 'Kelahiran anak pertama',
                'dokumen_bukti' => '/storage/uploads/akta.jpg',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'jenis_mutasi', 'status_verifikasi'],
            ]);

        $this->assertDatabaseHas('mutasi_penduduk', [
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'status_verifikasi' => 'Pending',
        ]);
    }

    public function test_warga_can_get_their_mutasi()
    {
        MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Test',
            'dokumen_bukti' => '/path/to/file.jpg',
        ]);

        $token = $this->warga->createToken('test')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/mutasi');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'jenis_mutasi', 'status_verifikasi'],
                ],
            ]);
    }

    public function test_admin_can_approve_mutasi()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Test',
            'dokumen_bukti' => '/path/to/file.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $response->assertStatus(200);

        $this->assertDatabaseHas('mutasi_penduduk', [
            'id' => $mutasi->id,
            'status_verifikasi' => 'Disetujui',
            'diverifikasi_oleh' => $this->admin->id,
        ]);
    }

    public function test_admin_can_reject_mutasi()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Test',
            'dokumen_bukti' => '/path/to/file.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/reject");

        $response->assertStatus(200);

        $this->assertDatabaseHas('mutasi_penduduk', [
            'id' => $mutasi->id,
            'status_verifikasi' => 'Ditolak',
        ]);
    }

    public function test_approve_kematian_updates_penduduk_status()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kematian',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Test',
            'dokumen_bukti' => '/path/to/file.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $this->withToken($token)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'status_mutasi' => 'Meninggal',
        ]);
    }

    public function test_approve_kepindahan_updates_penduduk_status()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kepindahan',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Test',
            'dokumen_bukti' => '/path/to/file.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $this->withToken($token)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'status_mutasi' => 'Pindah',
        ]);
    }
}
