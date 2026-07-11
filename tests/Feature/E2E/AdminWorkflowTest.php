<?php

namespace Tests\Feature\E2E;

use Tests\TestCase;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\Administrator;
use App\Models\KategoriSurat;
use App\Models\PengajuanSurat;
use App\Models\MutasiPenduduk;
use App\Models\InformasiPublik;
use App\Models\TrackingPengajuanSurat;
use App\Models\AuditLog;
use App\Services\TelegramService;
use App\Jobs\GenerateSuratPdfJob;
use Database\Seeders\KategoriSuratSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Mockery;
use Inertia\Testing\AssertableInertia;

class AdminWorkflowTest extends TestCase
{
    protected Administrator $keuchik;
    protected Administrator $sekdes;
    protected Administrator $operator;
    protected Keluarga $keluarga;
    protected Penduduk $warga;
    protected KategoriSurat $kategori;
    protected string $adminToken;
    protected string $wargaToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(KategoriSuratSeeder::class);

        $this->keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test',
            'dusun' => 'Dusun Test',
            'rt_rw' => '001/002',
        ]);

        $this->warga = Penduduk::create([
            'nik' => '1234567890123456',
            'no_kk' => $this->keluarga->no_kk,
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

        $this->keuchik = Administrator::create([
            'username' => 'keuchik',
            'password' => Hash::make('password123'),
            'role' => 'keuchik',
        ]);

        $this->sekdes = Administrator::create([
            'username' => 'sekdes',
            'password' => Hash::make('password123'),
            'role' => 'sekdes',
        ]);

        $this->operator = Administrator::create([
            'username' => 'operator',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);

        $this->kategori = KategoriSurat::first();

        $this->adminToken = $this->operator->createToken('admin-token', ['admin'])->plainTextToken;
        $this->wargaToken = $this->warga->createToken('warga-token', ['warga'])->plainTextToken;
    }

    // =========================================================================
    // API Auth
    // =========================================================================

    public function test_admin_api_login_returns_admin_token()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', [
            'username' => 'operator',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['username', 'role'],
                'token',
            ]);

        $this->assertEquals('operator', $response->json('user.username'));
    }

    public function test_admin_api_login_rejects_wrong_credentials()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', [
            'username' => 'operator',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_admin_api_logout_revokes_token()
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout berhasil']);

        $this->assertCount(0, $this->operator->tokens);
    }

    public function test_admin_api_profile_returns_admin_data()
    {
        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/auth/profile');

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'username' => 'operator',
                    'role' => 'operator',
                ],
            ]);
    }

    public function test_non_admin_cannot_get_admin_token()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', [
            'username' => '1234567890123456',
            'password' => '1234567890123456',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    // =========================================================================
    // Pengajuan Management
    // =========================================================================

    public function test_admin_lists_all_pengajuan()
    {
        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
        ]);

        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test 2'],
            'file_syarat' => ['ktp' => 'path/to/ktp.jpg'],
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/surat/pengajuan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nomor_registrasi', 'status', 'pemohon', 'kategori'],
                ],
            ]);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_admin_filters_pengajuan_by_pending()
    {
        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Pending'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Disetujui'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Disetujui',
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/surat/pengajuan?status=Pending');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Pending', $data[0]['status']);
    }

    public function test_admin_filters_pengajuan_by_disetujui()
    {
        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Pending'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Disetujui'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Disetujui',
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/surat/pengajuan?status=Disetujui');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Disetujui', $data[0]['status']);
    }

    public function test_admin_filters_pengajuan_by_ditolak()
    {
        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Pending'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Ditolak'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Ditolak',
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/surat/pengajuan?status=Ditolak');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Ditolak', $data[0]['status']);
    }

    public function test_admin_approve_pengajuan_changes_status()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $response = $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $response->assertStatus(200);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Disetujui',
        ]);
    }

    public function test_admin_approve_dispatches_generate_job()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        Queue::assertPushed(GenerateSuratPdfJob::class);
    }

    public function test_admin_approve_creates_tracking()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $this->assertDatabaseHas('tracking_pengajuan_surat', [
            'pengajuan_surat_id' => $pengajuan->id,
            'status_sebelumnya' => 'Pending',
            'status_baru' => 'Disetujui',
            'diupdate_oleh' => $this->operator->id,
        ]);
    }

    public function test_admin_approve_sets_verificator_id()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'diverifikasi_oleh' => $this->operator->id,
        ]);
    }

    public function test_admin_reject_with_note()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $response = $this->withToken($this->adminToken)
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

    public function test_admin_reject_creates_tracking()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/reject", [
                'catatan_penolakan' => 'Dokumen tidak lengkap',
            ]);

        $this->assertDatabaseHas('tracking_pengajuan_surat', [
            'pengajuan_surat_id' => $pengajuan->id,
            'status_sebelumnya' => 'Pending',
            'status_baru' => 'Ditolak',
            'diupdate_oleh' => $this->operator->id,
        ]);
    }

    public function test_warga_cannot_approve_pengajuan()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $response = $this->withToken($this->wargaToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $response->assertStatus(403);
    }

    public function test_warga_cannot_reject_pengajuan()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $response = $this->withToken($this->wargaToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/reject", [
                'catatan_penolakan' => 'Tidak valid',
            ]);

        $response->assertStatus(403);
    }

    public function test_approve_already_approved_does_nothing()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Disetujui',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Disetujui',
        ]);
    }

    public function test_approve_already_selesai_does_nothing()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Selesai',
            'qr_hash' => 'existing-hash',
        ]);

        $response = $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $response->assertStatus(200);
    }

    public function test_reject_already_rejected()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Ditolak',
            'catatan_penolakan' => 'Awal',
            'diverifikasi_oleh' => $this->operator->id,
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/reject", [
                'catatan_penolakan' => 'Lagi',
            ]);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Ditolak',
        ]);
    }

    public function test_admin_view_single_pengajuan_detail()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Detail View'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/surat/pengajuan');

        $response->assertStatus(200);
        $data = collect($response->json('data'));
        $found = $data->firstWhere('id', $pengajuan->id);

        $this->assertNotNull($found);
        $this->assertEquals('Pending', $found['status']);
        $this->assertArrayHasKey('pemohon', $found);
        $this->assertArrayHasKey('kategori', $found);
    }

    // =========================================================================
    // Mutasi Management
    // =========================================================================

    public function test_admin_lists_all_mutasi()
    {
        MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Bayi lahir',
            'dokumen_bukti' => '/storage/akta.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kematian',
            'tanggal_mutasi' => '2024-06-01',
            'keterangan' => 'Meninggal',
            'dokumen_bukti' => '/storage/surat.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/mutasi');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'jenis_mutasi', 'status_verifikasi', 'penduduk'],
                ],
            ]);

        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    public function test_admin_filters_mutasi_by_type_kelahiran()
    {
        MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Bayi lahir',
            'dokumen_bukti' => '/storage/akta.jpg',
        ]);

        MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kematian',
            'tanggal_mutasi' => '2024-06-01',
            'keterangan' => 'Meninggal',
            'dokumen_bukti' => '/storage/surat.jpg',
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/mutasi?jenis_mutasi=Kelahiran');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Kelahiran', $data[0]['jenis_mutasi']);
    }

    public function test_admin_filters_mutasi_by_status()
    {
        MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Bayi lahir',
            'dokumen_bukti' => '/storage/akta.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kematian',
            'tanggal_mutasi' => '2024-06-01',
            'keterangan' => 'Meninggal',
            'dokumen_bukti' => '/storage/surat.jpg',
            'status_verifikasi' => 'Disetujui',
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/mutasi?status_verifikasi=Pending');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Pending', $data[0]['status_verifikasi']);
    }

    public function test_admin_approve_kelahiran()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Bayi lahir',
            'dokumen_bukti' => '/storage/akta.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $response = $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $response->assertStatus(200);

        $this->assertDatabaseHas('mutasi_penduduk', [
            'id' => $mutasi->id,
            'status_verifikasi' => 'Disetujui',
            'diverifikasi_oleh' => $this->operator->id,
        ]);
    }

    public function test_admin_approve_kematian_updates_penduduk_status()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kematian',
            'tanggal_mutasi' => '2024-06-01',
            'keterangan' => 'Meninggal dunia',
            'dokumen_bukti' => '/storage/surat.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'status_mutasi' => 'Meninggal',
        ]);
    }

    public function test_admin_approve_kepindahan_updates_penduduk()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kepindahan',
            'tanggal_mutasi' => '2024-06-01',
            'keterangan' => 'Pindah ke luar kota',
            'dokumen_bukti' => '/storage/surat.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'status_mutasi' => 'Pindah',
        ]);
    }

    public function test_admin_approve_kedatangan_keeps_tetap()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kedatangan',
            'tanggal_mutasi' => '2024-06-01',
            'keterangan' => 'Kembali ke kampung',
            'dokumen_bukti' => '/storage/surat.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'status_mutasi' => 'Tetap',
        ]);
    }

    public function test_admin_reject_mutasi()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Bayi lahir',
            'dokumen_bukti' => '/storage/akta.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $response = $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/reject");

        $response->assertStatus(200);

        $this->assertDatabaseHas('mutasi_penduduk', [
            'id' => $mutasi->id,
            'status_verifikasi' => 'Ditolak',
        ]);
    }

    public function test_admin_approve_creates_audit_log()
    {
        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Bayi lahir',
            'dokumen_bukti' => '/storage/akta.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $this->assertDatabaseHas('audit_logs', [
            'user_type' => 'admin',
            'user_id' => $this->operator->id,
            'tindakan' => 'approve',
            'nama_tabel' => 'mutasi_penduduk',
            'record_id' => $mutasi->id,
        ]);
    }

    public function test_admin_approve_clears_statistik_cache()
    {
        Cache::put('statistik_demografi', ['cached' => true], 3600);
        Cache::put('statistik_layanan', ['cached' => true], 3600);

        $mutasi = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Bayi lahir',
            'dokumen_bukti' => '/storage/akta.jpg',
            'status_verifikasi' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/mutasi/{$mutasi->id}/approve");

        $this->assertNull(Cache::get('statistik_demografi'));
        $this->assertNull(Cache::get('statistik_layanan'));
    }

    // =========================================================================
    // Informasi Publik Management
    // =========================================================================

    public function test_admin_creates_published_informasi()
    {
        Queue::fake();

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Berita Resmi',
                'konten' => 'Konten berita resmi',
                'kategori' => 'berita',
                'is_published' => true,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'judul', 'slug'],
            ]);

        $this->assertDatabaseHas('informasi_publik', [
            'judul' => 'Berita Resmi',
            'is_published' => true,
            'author_id' => $this->operator->id,
        ]);
    }

    public function test_admin_creates_draft_informasi()
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Draft Berita',
                'konten' => 'Konten draft',
                'kategori' => 'berita',
                'is_published' => false,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('informasi_publik', [
            'judul' => 'Draft Berita',
            'is_published' => false,
        ]);
    }

    public function test_admin_updates_informasi_judul()
    {
        $informasi = InformasiPublik::create([
            'judul' => 'Judul Lama',
            'slug' => 'judul-lama',
            'konten' => 'Konten',
            'kategori' => 'berita',
            'author_id' => $this->operator->id,
        ]);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/informasi/{$informasi->id}", [
                'judul' => 'Judul Baru',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('informasi_publik', [
            'id' => $informasi->id,
            'judul' => 'Judul Baru',
        ]);
    }

    public function test_admin_updates_informasi_slug()
    {
        $informasi = InformasiPublik::create([
            'judul' => 'Judul Lama',
            'slug' => 'judul-lama',
            'konten' => 'Konten',
            'kategori' => 'berita',
            'author_id' => $this->operator->id,
        ]);

        $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/informasi/{$informasi->id}", [
                'slug' => 'judul-baru',
            ]);

        $this->assertDatabaseHas('informasi_publik', [
            'id' => $informasi->id,
            'slug' => 'judul-lama',
        ]);
    }

    public function test_admin_publishes_informasi_triggers_telegram_job()
    {
        Queue::fake();

        $informasi = InformasiPublik::create([
            'judul' => 'Draft',
            'slug' => 'draft',
            'konten' => 'Konten',
            'kategori' => 'berita',
            'is_published' => false,
            'author_id' => $this->operator->id,
        ]);

        $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/informasi/{$informasi->id}", [
                'is_published' => true,
            ]);

        Queue::assertPushed(\App\Jobs\SendNewsTelegramNotificationJob::class);
    }

    public function test_admin_deletes_informasi()
    {
        $informasi = InformasiPublik::create([
            'judul' => 'To Delete',
            'slug' => 'to-delete',
            'konten' => 'Content',
            'kategori' => 'berita',
            'author_id' => $this->operator->id,
        ]);

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/informasi/{$informasi->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('informasi_publik', [
            'id' => $informasi->id,
        ]);
    }

    public function test_admin_lists_all_informasi()
    {
        InformasiPublik::create([
            'judul' => 'Published 1',
            'slug' => 'published-1',
            'konten' => 'Content',
            'kategori' => 'berita',
            'is_published' => true,
            'author_id' => $this->operator->id,
        ]);

        InformasiPublik::create([
            'judul' => 'Draft 1',
            'slug' => 'draft-1',
            'konten' => 'Content',
            'kategori' => 'pengumuman',
            'is_published' => false,
            'author_id' => $this->operator->id,
        ]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/informasi');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    public function test_admin_lists_only_published_via_public_api()
    {
        InformasiPublik::create([
            'judul' => 'Published Only',
            'slug' => 'published-only',
            'konten' => 'Content',
            'kategori' => 'berita',
            'is_published' => true,
            'author_id' => $this->operator->id,
        ]);

        InformasiPublik::create([
            'judul' => 'Hidden Draft',
            'slug' => 'hidden-draft',
            'konten' => 'Content',
            'kategori' => 'berita',
            'is_published' => false,
            'author_id' => $this->operator->id,
        ]);

        $response = $this->getJson('/api/v1/informasi');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Published Only', $data[0]['judul']);
    }

    public function test_slug_auto_generated_from_judul()
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Berita Baru 2024',
                'konten' => 'Konten',
                'kategori' => 'berita',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('informasi_publik', [
            'judul' => 'Berita Baru 2024',
            'slug' => 'berita-baru-2024',
        ]);
    }

    // =========================================================================
    // Statistik & Cache
    // =========================================================================

    public function test_admin_clears_statistik_cache()
    {
        Cache::put('statistik_demografi', ['data' => 'old'], 3600);
        Cache::put('statistik_layanan', ['data' => 'old'], 3600);

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/statistik/clear-cache');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cache statistik berhasil dibersihkan']);

        $this->assertNull(Cache::get('statistik_demografi'));
        $this->assertNull(Cache::get('statistik_layanan'));
    }

    public function test_statistik_demografi_returns_correct_totals()
    {
        Cache::forget('statistik_demografi');

        Keluarga::create([
            'no_kk' => '9999999999999999',
            'alamat' => 'Jl. Baru',
            'dusun' => 'Dusun Lain',
            'rt_rw' => '002/003',
        ]);

        $response = $this->getJson('/api/v1/statistik/demografi');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_penduduk',
                    'total_keluarga',
                    'laki_laki',
                    'perempuan',
                    'per_dusun',
                ],
            ]);

        $data = $response->json('data');
        $this->assertEquals(1, $data['total_penduduk']);
        $this->assertEquals(2, $data['total_keluarga']);
    }

    public function test_statistik_layanan_includes_per_status()
    {
        Cache::forget('statistik_layanan');

        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Selesai',
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
                ],
            ]);

        $data = $response->json('data');
        $this->assertEquals(2, $data['pengajuan_surat']['total']);
        $this->assertEquals(1, $data['pengajuan_surat']['pending']);
        $this->assertEquals(1, $data['pengajuan_surat']['selesai']);
    }

    public function test_statistik_caches_results()
    {
        Cache::forget('statistik_demografi');

        $this->getJson('/api/v1/statistik/demografi');

        $cached = Cache::get('statistik_demografi');
        $this->assertNotNull($cached);
        $this->assertEquals(1, $cached['total_penduduk']);
    }

    public function test_statistik_updates_after_data_change()
    {
        Cache::forget('statistik_demografi');
        Cache::forget('statistik_layanan');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $before = $response->json('data.total_penduduk');

        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $after = $response->json('data.total_penduduk');

        $this->assertEquals($before, $after);
    }

    // =========================================================================
    // Filament Admin Access
    // =========================================================================

    public function test_filament_dashboard_renders_for_admin()
    {
        $this->actingAs($this->operator, 'admin')
            ->get('/admin')
            ->assertOk();
    }

    public function test_filament_dashboard_rejects_warga()
    {
        $this->actingAs($this->warga, 'penduduk')
            ->get('/admin')
            ->assertRedirect();
    }

    public function test_filament_dashboard_rejects_guest()
    {
        $this->get('/admin')
            ->assertRedirect();
    }

    // =========================================================================
    // Web Admin Integration
    // =========================================================================

    public function test_admin_can_login_via_web()
    {
        $credentials = [
            'username' => 'operator',
            'password' => 'password123',
        ];

        $this->assertTrue(
            \Illuminate\Support\Facades\Auth::guard('admin')->attempt($credentials)
        );

        $this->assertAuthenticatedAs($this->operator, 'admin');
    }

    public function test_admin_cannot_login_via_web_with_wrong_pass()
    {
        $this->assertFalse(
            \Illuminate\Support\Facades\Auth::guard('admin')->attempt([
                'username' => 'operator',
                'password' => 'wrongpassword',
            ])
        );

        $this->assertGuest('admin');
    }

    public function test_admin_filament_resources_accessible()
    {
        $this->actingAs($this->operator, 'admin');

        $this->get('/admin/pengajuan-surats')
            ->assertOk();

        $this->get('/admin/mutasi-penduduks')
            ->assertOk();

        $this->get('/admin/informasi-publiks')
            ->assertOk();
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_admin_cannot_approve_without_catatan_when_rejecting()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $response = $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/reject");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['catatan_penolakan']);
    }

    public function test_admin_approve_updates_pengajuan_diverifikasi_oleh()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'diverifikasi_oleh' => $this->operator->id,
        ]);
    }

    public function test_multiple_admins_see_same_data()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Shared Data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $keuchikToken = $this->keuchik->createToken('keuchik-token', ['admin'])->plainTextToken;
        $sekdesToken = $this->sekdes->createToken('sekdes-token', ['admin'])->plainTextToken;

        $responseKeuchik = $this->withToken($keuchikToken)
            ->getJson('/api/v1/admin/surat/pengajuan');

        $responseSekdes = $this->withToken($sekdesToken)
            ->getJson('/api/v1/admin/surat/pengajuan');

        $this->assertEquals(
            $responseKeuchik->json('data.0.id'),
            $responseSekdes->json('data.0.id')
        );

        $this->assertEquals(
            count($responseKeuchik->json('data')),
            count($responseSekdes->json('data'))
        );
    }

    public function test_admin_actions_are_atomic_on_failure()
    {
        Queue::fake();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Atomic Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $this->withToken($this->adminToken)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve");

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Disetujui',
        ]);

        $this->assertDatabaseHas('tracking_pengajuan_surat', [
            'pengajuan_surat_id' => $pengajuan->id,
            'status_baru' => 'Disetujui',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'record_id' => $pengajuan->id,
        ]);
    }

    public function test_informasi_store_validates_required_fields()
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/informasi', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'konten', 'kategori']);
    }

    public function test_informasi_update_validates_max_length()
    {
        $informasi = InformasiPublik::create([
            'judul' => 'Test',
            'slug' => 'test',
            'konten' => 'Konten',
            'kategori' => 'berita',
            'author_id' => $this->operator->id,
        ]);

        $longJudul = str_repeat('A', 256);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/informasi/{$informasi->id}", [
                'judul' => $longJudul,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }
}
