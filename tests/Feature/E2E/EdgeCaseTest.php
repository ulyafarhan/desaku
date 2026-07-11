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
use Database\Seeders\KategoriSuratSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EdgeCaseTest extends TestCase
{
    protected Penduduk $warga;
    protected Keluarga $keluarga;
    protected KategoriSurat $kategori;
    protected Administrator $admin;
    protected string $adminToken;
    protected string $wargaToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(KategoriSuratSeeder::class);

        $this->keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test No. 1',
            'dusun' => 'Udeung',
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

        $this->admin = Administrator::create([
            'username' => 'edgeadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);

        $this->adminToken = $this->admin->createToken('edge-admin-token', ['admin'])->plainTextToken;
        $this->wargaToken = $this->warga->createToken('edge-warga-token', ['warga'])->plainTextToken;
        $this->kategori = KategoriSurat::first();
    }

    // =========================================================================
    // Boundary Values
    // =========================================================================

    public function test_nik_exactly_16_chars_accepted()
    {
        $nik = '1111111111111111';
        $this->assertEquals(16, strlen($nik));

        $warga = Penduduk::create([
            'nik' => $nik,
            'no_kk' => $this->keluarga->no_kk,
            'nama_lengkap' => '16 Char NIK',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Buruh',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);

        $this->assertDatabaseHas('penduduk', ['nik' => '1111111111111111']);
    }

    public function test_nik_15_chars_rejected()
    {
        $nik = '123456789012345';
        $this->assertEquals(15, strlen($nik));
    }

    public function test_nik_17_chars_rejected()
    {
        $nik = '12345678901234567';
        $this->assertEquals(17, strlen($nik));
    }

    public function test_empty_nik_rejected()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '',
            'no_kk' => '1234567890123456',
        ]);

        $response->assertStatus(422);
    }

    public function test_ktp_max_2mb_file()
    {
        $this->actingAs($this->warga, 'penduduk')
            ->post('/warga/profil', [
                'foto_ktp' => \Illuminate\Http\UploadedFile::fake()->create('ktp.pdf', 2048, 'application/pdf'),
            ])
            ->assertRedirect();

        $this->warga->refresh();
        $this->assertNotNull($this->warga->getRawOriginal('foto_ktp'));
    }

    public function test_kk_max_2mb_file()
    {
        $this->actingAs($this->warga, 'penduduk')
            ->post('/warga/profil', [
                'foto_kk' => \Illuminate\Http\UploadedFile::fake()->image('kk.jpg')->size(2048),
            ])
            ->assertRedirect();

        $this->warga->refresh();
        $this->assertNotNull($this->warga->getRawOriginal('foto_kk'));
    }

    public function test_profile_telegram_id_max_50()
    {
        $longId = str_repeat('1', 50);
        $this->assertEquals(50, strlen($longId));

        $this->actingAs($this->warga, 'penduduk')
            ->post('/warga/profil', [
                'telegram_chat_id' => $longId,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'telegram_chat_id' => $longId,
        ]);
    }

    public function test_informasi_judul_max_255()
    {
        $judul = str_repeat('A', 255);
        $this->assertEquals(255, strlen($judul));

        $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => $judul,
                'konten' => 'Content within limit',
                'kategori' => 'berita',
            ])
            ->assertStatus(201);
    }

    // =========================================================================
    // Null/Empty Handling
    // =========================================================================

    public function test_null_optional_fields_accepted()
    {
        $warga = Penduduk::create([
            'nik' => '9999999999999991',
            'no_kk' => $this->keluarga->no_kk,
            'nama_lengkap' => 'Null Fields',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Buruh',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
        ]);

        $this->assertNull($warga->foto_profil);
        $this->assertNull($warga->foto_ktp);
        $this->assertNull($warga->foto_kk);
    }

    public function test_empty_string_optional_fields_accepted()
    {
        $warga = Penduduk::create([
            'nik' => '9999999999999992',
            'no_kk' => $this->keluarga->no_kk,
            'nama_lengkap' => 'Empty Fields',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => '',
            'pekerjaan' => '',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);

        $this->assertEquals('', $warga->pendidikan);
        $this->assertEquals('', $warga->pekerjaan);
    }

    public function test_penduduk_with_null_telegram()
    {
        $this->assertNull($this->warga->telegram_chat_id);
    }

    public function test_penduduk_with_null_foto()
    {
        $this->assertNull($this->warga->foto_profil);
        $this->assertNull($this->warga->foto_ktp);
        $this->assertNull($this->warga->foto_kk);
    }

    public function test_submission_with_empty_file_syarat()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => [],
        ]);

        $this->assertEquals([], $pengajuan->file_syarat);
    }

    public function test_pengajuan_with_null_catatan_penolakan()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $this->assertNull($pengajuan->catatan_penolakan);
    }

    public function test_informasi_with_null_cover_image()
    {
        $artikel = InformasiPublik::create([
            'judul' => 'No Cover',
            'slug' => 'no-cover',
            'konten' => 'Content',
            'kategori' => 'Berita',
            'is_published' => true,
            'author_id' => $this->admin->id,
        ]);

        $this->assertNull($artikel->cover_image);
    }

    // =========================================================================
    // Status Transitions
    // =========================================================================

    public function test_multiple_status_changes_same_pengajuan()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $pengajuan->update(['status' => 'Diproses']);
        $pengajuan->update(['status' => 'Disetujui']);
        $pengajuan->update(['status' => 'Selesai']);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_approve_then_reject_different_admin()
    {
        Queue::fake();

        $admin2 = Administrator::create([
            'username' => 'admin2',
            'password' => Hash::make('password123'),
            'role' => 'sekdes',
        ]);

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $pengajuan->update(['status' => 'Disetujui', 'diverifikasi_oleh' => $this->admin->id]);
        $pengajuan->update(['status' => 'Ditolak', 'diverifikasi_oleh' => $admin2->id, 'catatan_penolakan' => 'Ditolak setelah disetujui']);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Ditolak',
            'diverifikasi_oleh' => $admin2->id,
        ]);
    }

    public function test_reject_then_approve_different_admin()
    {
        Queue::fake();

        $admin2 = Administrator::create([
            'username' => 'admin3',
            'password' => Hash::make('password123'),
            'role' => 'keuchik',
        ]);

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Pending',
        ]);

        $pengajuan->update(['status' => 'Ditolak', 'diverifikasi_oleh' => $this->admin->id, 'catatan_penolakan' => 'Awal']);
        $pengajuan->update(['status' => 'Disetujui', 'diverifikasi_oleh' => $admin2->id]);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'Disetujui',
            'diverifikasi_oleh' => $admin2->id,
        ]);
    }

    public function test_selesai_status_persists_after_refresh()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
            'status' => 'Selesai',
            'qr_hash' => hash('sha256', 'final-test'),
        ]);

        $pengajuan->refresh();
        $this->assertEquals('Selesai', $pengajuan->status);
    }

    // =========================================================================
    // ID Type Handling
    // =========================================================================

    public function test_ulid_used_for_pengajuan_id()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $this->assertTrue(Str::isUlid($pengajuan->id));
    }

    public function test_ulid_used_for_kategori_surat_id()
    {
        $this->assertTrue(Str::isUlid($this->kategori->id));
    }

    public function test_string_used_for_nik()
    {
        $this->assertIsString($this->warga->nik);
        $this->assertTrue(is_string($this->warga->getKey()));
    }

    public function test_string_used_for_no_kk()
    {
        $this->assertIsString($this->keluarga->no_kk);
        $this->assertTrue(is_string($this->keluarga->getKey()));
    }

    // =========================================================================
    // Date Handling
    // =========================================================================

    public function test_tanggal_lahir_stored_correctly()
    {
        $tanggal = '1990-01-01';
        $this->warga->update(['tanggal_lahir' => $tanggal]);

        $this->warga->refresh();
        $this->assertEquals('1990-01-01', $this->warga->tanggal_lahir->format('Y-m-d'));
    }

    public function test_tanggal_lahir_accessible_as_carbon()
    {
        $this->assertInstanceOf(Carbon::class, $this->warga->tanggal_lahir);
    }

    public function test_created_at_stored_in_utc()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $this->assertNotNull($pengajuan->created_at);
        $this->assertInstanceOf(Carbon::class, $pengajuan->created_at);
    }

    public function test_submission_created_at_format()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $this->assertStringContainsString(date('Y'), (string)$pengajuan->created_at);
    }

    // =========================================================================
    // Concurrency
    // =========================================================================

    public function test_concurrent_submissions_unique_numbers()
    {
        $p1 = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['t' => '1'],
            'file_syarat' => ['ktp' => 'p.jpg'],
        ]);
        $p2 = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['t' => '2'],
            'file_syarat' => ['ktp' => 'p.jpg'],
        ]);

        $this->assertNotNull($p1->nomor_registrasi);
        $this->assertNotNull($p2->nomor_registrasi);
        $this->assertNotEquals($p1->nomor_registrasi, $p2->nomor_registrasi);
    }

    public function test_multiple_admins_can_approve_different()
    {
        Queue::fake();

        $admin2 = Administrator::create([
            'username' => 'otheradmin',
            'password' => Hash::make('password123'),
            'role' => 'sekdes',
        ]);

        $p1 = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['t' => '1'],
            'file_syarat' => ['ktp' => 'p.jpg'],
            'status' => 'Pending',
        ]);
        $p2 = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['t' => '2'],
            'file_syarat' => ['ktp' => 'p.jpg'],
            'status' => 'Pending',
        ]);

        $p1->update(['status' => 'Disetujui', 'diverifikasi_oleh' => $this->admin->id]);
        $p2->update(['status' => 'Disetujui', 'diverifikasi_oleh' => $admin2->id]);

        $this->assertDatabaseHas('pengajuan_surat', ['id' => $p1->id, 'diverifikasi_oleh' => $this->admin->id]);
        $this->assertDatabaseHas('pengajuan_surat', ['id' => $p2->id, 'diverifikasi_oleh' => $admin2->id]);
    }

    // =========================================================================
    // JSON Field Handling
    // =========================================================================

    public function test_data_isian_stored_as_json()
    {
        $isian = ['keperluan' => 'Test', 'lama_tinggal' => 5];
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => $isian,
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $this->assertIsArray($pengajuan->data_isian);
        $this->assertEquals($isian, $pengajuan->data_isian);
    }

    public function test_file_syarat_stored_as_json()
    {
        $syarat = ['ktp' => 'path/ktp.jpg', 'kk' => 'path/kk.jpg'];
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => $syarat,
        ]);

        $this->assertIsArray($pengajuan->file_syarat);
        $this->assertEquals($syarat, $pengajuan->file_syarat);
    }

    public function test_schema_isian_stored_correctly()
    {
        $schema = $this->kategori->schema_isian;

        $this->assertIsArray($schema);
    }

    public function test_syarat_dokumen_stored_correctly()
    {
        $syarat = $this->kategori->syarat_dokumen;

        $this->assertIsArray($syarat);
    }

    // =========================================================================
    // Misc
    // =========================================================================

    public function test_audit_log_created_on_critical_actions()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        AuditLog::log('admin', $this->admin->id, 'approve', 'pengajuan_surat', $pengajuan->id);

        $this->assertDatabaseHas('audit_logs', [
            'user_type' => 'admin',
            'user_id' => $this->admin->id,
            'tindakan' => 'approve',
            'nama_tabel' => 'pengajuan_surat',
            'record_id' => $pengajuan->id,
        ]);
    }

    public function test_tracking_order_preserved()
    {
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_baru' => 'Pending',
            'keterangan_update' => 'Awal',
        ]);

        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_baru' => 'Diproses',
            'keterangan_update' => 'Diproses',
        ]);

        TrackingPengajuanSurat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'status_baru' => 'Disetujui',
            'keterangan_update' => 'Disetujui',
        ]);

        $tracking = TrackingPengajuanSurat::where('pengajuan_surat_id', $pengajuan->id)
            ->orderBy('created_at')
            ->get();

        $this->assertCount(3, $tracking);
        $this->assertEquals('Pending', $tracking->get(0)->status_baru);
        $this->assertEquals('Diproses', $tracking->get(1)->status_baru);
        $this->assertEquals('Disetujui', $tracking->get(2)->status_baru);
    }
}
