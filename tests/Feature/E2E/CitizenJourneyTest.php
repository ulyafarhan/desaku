<?php

namespace Tests\Feature\E2E;

use App\Models\Administrator;
use App\Models\AuditLog;
use App\Models\KategoriSurat;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\TrackingPengajuanSurat;
use Database\Seeders\KategoriSuratSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CitizenJourneyTest extends TestCase
{
    protected $kepalaKeluarga;
    protected $anggotaKeluarga;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriSuratSeeder::class);

        $keluarga = Keluarga::create([
            'no_kk' => '1118061010800001',
            'alamat' => 'Dusun Phoroh',
            'dusun' => 'Phoroh',
            'rt_rw' => 'RT 01 / RW 01',
        ]);

        $this->kepalaKeluarga = Penduduk::create([
            'nik' => '1118061010800001',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'Teuku Umar',
            'tempat_lahir' => 'Phoroh',
            'tanggal_lahir' => '1980-10-10',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Petani',
            'status_perkawinan' => 'Kawin',
            'status_keluarga' => 'Kepala Keluarga',
            'status_mutasi' => 'Tetap',
        ]);

        $keluarga->update(['kepala_keluarga_nik' => $this->kepalaKeluarga->nik]);

        $this->anggotaKeluarga = Penduduk::create([
            'nik' => '1118061010800002',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'Cut Nyak Dhien',
            'tempat_lahir' => 'Phoroh',
            'tanggal_lahir' => '1985-05-15',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Ibu Rumah Tangga',
            'status_perkawinan' => 'Kawin',
            'status_keluarga' => 'Istri',
            'status_mutasi' => 'Tetap',
        ]);

        $this->admin = Administrator::create([
            'username' => 'testadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);
    }

    // ─── API Login Tests ─────────────────────────────────────────────

    public function test_api_warga_login_returns_token()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '1118061010800001',
            'no_kk' => '1118061010800001',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['nik', 'nama_lengkap'],
                'token',
            ])
            ->assertJson([
                'user' => ['nik' => '1118061010800001', 'nama_lengkap' => 'Teuku Umar'],
            ]);
    }

    public function test_api_warga_login_fails_with_wrong_nik()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '9999999999999999',
            'no_kk' => '1118061010800001',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_api_warga_login_fails_with_wrong_no_kk()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '1118061010800001',
            'no_kk' => '9999999999999999',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_api_warga_login_fails_inactive_citizen()
    {
        Penduduk::create([
            'nik' => '1118061010800003',
            'no_kk' => '1118061010800001',
            'nama_lengkap' => 'Pindah Warga',
            'tempat_lahir' => 'Phoroh',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Buruh',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Pindah',
        ]);

        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '1118061010800003',
            'no_kk' => '1118061010800001',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_api_warga_login_fails_missing_fields()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik', 'no_kk']);
    }

    public function test_api_warga_login_fails_nik_not_16_chars()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '12345',
            'no_kk' => '1118061010800001',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_api_warga_login_creates_audit_log()
    {
        $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '1118061010800001',
            'no_kk' => '1118061010800001',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_type' => 'warga',
            'user_id' => '1118061010800001',
            'tindakan' => 'login',
            'nama_tabel' => 'penduduk',
        ]);
    }

    public function test_api_admin_login_returns_token()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', [
            'username' => 'testadmin',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['username', 'role'],
                'token',
            ])
            ->assertJson([
                'user' => ['username' => 'testadmin', 'role' => 'operator'],
            ]);
    }

    public function test_api_admin_login_fails_wrong_password()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', [
            'username' => 'testadmin',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_api_admin_login_fails_missing_fields()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'password']);
    }

    public function test_api_logout_revokes_token()
    {
        $token = $this->kepalaKeluarga->createToken('test-token', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout berhasil']);
    }

    public function test_api_profile_returns_user_data()
    {
        $token = $this->kepalaKeluarga->createToken('test-token', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/auth/profile');

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'nik' => '1118061010800001',
                    'nama_lengkap' => 'Teuku Umar',
                ],
            ]);
    }

    public function test_api_bind_telegram_success()
    {
        $token = $this->kepalaKeluarga->createToken('test-token', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/bind-telegram', [
                'telegram_chat_id' => '123456789',
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Telegram berhasil terhubung']);

        $this->assertDatabaseHas('penduduk', [
            'nik' => '1118061010800001',
            'telegram_chat_id' => '123456789',
        ]);
    }

    public function test_api_bind_telegram_fails_duplicate()
    {
        $this->kepalaKeluarga->update(['telegram_chat_id' => '999999999']);
        $token = $this->kepalaKeluarga->createToken('test-token', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/bind-telegram', [
                'telegram_chat_id' => '999999999',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telegram_chat_id']);
    }

    public function test_api_bind_telegram_rejects_admin()
    {
        $token = $this->admin->createToken('test-token', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/bind-telegram', [
                'telegram_chat_id' => '123456789',
            ]);

        $response->assertStatus(403);
    }

    // ─── Web Login Tests ─────────────────────────────────────────────

    public function test_web_login_page_renders()
    {
        $this->get('/login')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page->component('Auth/Login'));
    }

    public function test_web_login_with_valid_creds()
    {
        $this->get('/login');
        $this->post('/login', [
            '_token' => csrf_token(),
            'nik' => '1118061010800001',
            'no_kk' => '1118061010800001',
        ])->assertRedirect(route('warga.dashboard'));

        $this->assertAuthenticated('penduduk');
    }

    public function test_web_login_with_invalid_nik()
    {
        $this->get('/login');
        $this->post('/login', [
            '_token' => csrf_token(),
            'nik' => '9999999999999999',
            'no_kk' => '1118061010800001',
        ])->assertSessionHasErrors(['nik']);
    }

    public function test_web_login_with_inactive_citizen()
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1118061010800003',
            'alamat' => 'Dusun Phoroh',
            'dusun' => 'Phoroh',
            'rt_rw' => 'RT 01 / RW 01',
        ]);

        Penduduk::create([
            'nik' => '1118061010800007',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'Meninggal Warga',
            'tempat_lahir' => 'Phoroh',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Buruh',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Meninggal',
        ]);

        $this->get('/login');
        $this->post('/login', [
            '_token' => csrf_token(),
            'nik' => '1118061010800007',
            'no_kk' => $keluarga->no_kk,
        ])->assertSessionHasErrors(['nik']);
    }

    public function test_web_logout_invalidates_session()
    {
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->post('/logout')
            ->assertRedirect(route('home'));

        $this->assertGuest('penduduk');
    }

    // ─── Dashboard Tests ─────────────────────────────────────────────

    public function test_dashboard_renders_for_warga()
    {
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get('/warga/dashboard')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Dashboard')
                ->has('warga')
                ->has('kategoriSurat')
                ->has('pengajuan')
                ->has('summary')
                ->has('biodataCompleteness')
                ->has('isKepalaKeluarga')
                ->has('anggotaKeluarga'));
    }

    public function test_dashboard_redirects_guest()
    {
        $this->get('/warga/dashboard')
            ->assertRedirect('/login');
    }

    public function test_dashboard_shows_summary_stats()
    {
        $kategori = KategoriSurat::first();
        PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
            'status' => 'Pending',
        ]);
        PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 3],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
            'status' => 'Selesai',
        ]);

        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get('/warga/dashboard')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('summary.pending', 1)
                ->where('summary.selesai', 1));
    }

    public function test_dashboard_shows_recent_submissions()
    {
        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
            'status' => 'Pending',
        ]);

        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get('/warga/dashboard')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('pengajuan.data', 1)
                ->where('pengajuan.data.0.id', $pengajuan->id));
    }

    public function test_dashboard_kk_head_sees_family_submissions()
    {
        $kategori = KategoriSurat::first();
        PengajuanSurat::create([
            'nik_pemohon' => $this->anggotaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
            'status' => 'Pending',
        ]);

        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get('/warga/dashboard')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('summary.pending', 1)
                ->where('isKepalaKeluarga', true));
    }

    // ─── Submission Tests ────────────────────────────────────────────

    public function test_create_submission_page_renders()
    {
        $kategori = KategoriSurat::first();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get("/warga/surat/ajukan/{$kategori->id}")
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Submission/Create')
                ->has('kategori')
                ->has('wargaData')
                ->where('isKepalaKeluarga', true));
    }

    public function test_create_submission_requires_active_kategori()
    {
        $kategori = KategoriSurat::first();
        $kategori->update(['is_active' => false]);

        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get("/warga/surat/ajukan/{$kategori->id}")
            ->assertStatus(404);
    }

    public function test_create_submission_requires_auth()
    {
        $kategori = KategoriSurat::first();

        $this->get("/warga/surat/ajukan/{$kategori->id}")
            ->assertRedirect('/login');
    }

    public function test_submit_letter_successfully()
    {
        $kategori = KategoriSurat::where('kode_surat', 'SKBM')->first();
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $response = $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'data_isian' => ['pekerjaan' => 'Petani', 'keperluan' => 'Keperluan Administrasi'],
            'file_syarat' => [
                'ktp_asli_dan_fotokopi' => 'https://example.test/ktp.jpg',
                'kartu_keluarga' => 'https://example.test/kk.jpg',
                'surat_pernyataan_dari_orang_tua' => 'https://example.test/surat.jpg',
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pengajuan_surat', [
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'status' => 'Pending',
        ]);
    }

    public function test_submit_letter_fails_without_ktp_file()
    {
        $kategori = KategoriSurat::first();
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $response = $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => [],
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_submit_letter_fails_with_empty_required_field()
    {
        $kategori = KategoriSurat::first();
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $response = $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'data_isian' => ['keperluan' => '', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'https://example.test/ktp.jpg', 'kartu_keluarga' => 'https://example.test/kk.jpg'],
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_submit_letter_for_family_member_as_kk()
    {
        $kategori = KategoriSurat::where('kode_surat', 'SKBM')->first();
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $response = $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->anggotaKeluarga->nik,
            'data_isian' => ['pekerjaan' => 'Petani', 'keperluan' => 'Keperluan Administrasi'],
            'file_syarat' => [
                'ktp_asli_dan_fotokopi' => 'https://example.test/ktp.jpg',
                'kartu_keluarga' => 'https://example.test/kk.jpg',
                'surat_pernyataan_dari_orang_tua' => 'https://example.test/surat.jpg',
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pengajuan_surat', [
            'nik_pemohon' => $this->anggotaKeluarga->nik,
            'status' => 'Pending',
        ]);
    }

    public function test_submit_letter_rejects_non_family_member()
    {
        $keluargaLain = Keluarga::create([
            'no_kk' => '1118061010800099',
            'alamat' => 'Dusun Lain',
            'dusun' => 'Lain',
            'rt_rw' => 'RT 02 / RW 01',
        ]);
        $wargaLain = Penduduk::create([
            'nik' => '1118061010800099',
            'no_kk' => $keluargaLain->no_kk,
            'nama_lengkap' => 'Warga Lain',
            'tempat_lahir' => 'Phoroh',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Buruh',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);

        $kategori = KategoriSurat::first();
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $response = $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $wargaLain->nik,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'https://example.test/ktp.jpg', 'kartu_keluarga' => 'https://example.test/kk.jpg'],
        ]);

        $response->assertStatus(403);
    }

    public function test_show_submission_page_renders()
    {
        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
            'status' => 'Pending',
        ]);

        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get("/warga/pengajuan/{$pengajuan->id}")
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Submission/Show')
                ->has('pengajuan'));
    }

    public function test_show_submission_forbids_other_users()
    {
        $wargaLain = Penduduk::create([
            'nik' => '1118061010800010',
            'no_kk' => '1118061010800001',
            'nama_lengkap' => 'Orang Lain',
            'tempat_lahir' => 'Phoroh',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Pelajar',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);

        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/ktp.jpg'],
            'status' => 'Pending',
        ]);

        $this->actingAs($wargaLain, 'penduduk');

        $this->get("/warga/pengajuan/{$pengajuan->id}")
            ->assertStatus(403);
    }

    public function test_show_submission_allows_kk_head_for_family()
    {
        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->anggotaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/ktp.jpg'],
            'status' => 'Pending',
        ]);

        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get("/warga/pengajuan/{$pengajuan->id}")
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Submission/Show'));
    }

    public function test_print_requires_selesai_status()
    {
        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path/ktp.jpg'],
            'status' => 'Pending',
        ]);

        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get("/warga/pengajuan/{$pengajuan->id}/print")
            ->assertStatus(404);
    }

    public function test_print_renders_for_completed_letter()
    {
        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
            'status' => 'Selesai',
            'qr_hash' => 'test_qr_hash_print',
            'diverifikasi_oleh' => $this->admin->id,
        ]);

        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get("/warga/pengajuan/{$pengajuan->id}/print")
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Submission/Print')
                ->has('pengajuan')
                ->has('qrCodeSvg')
                ->has('tanggalSurat')
                ->has('settings'));
    }

    public function test_print_generates_qr_code()
    {
        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
            'status' => 'Selesai',
            'qr_hash' => 'unique_qr_print_test',
            'diverifikasi_oleh' => $this->admin->id,
        ]);

        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get("/warga/pengajuan/{$pengajuan->id}/print")
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('pengajuan.qr_hash', 'unique_qr_print_test')
                ->has('qrCodeSvg'));
    }

    // ─── Profile Tests ───────────────────────────────────────────────

    public function test_profile_page_renders()
    {
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get('/warga/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Profile')
                ->has('warga')
                ->has('completeness'));
    }

    public function test_update_profile_successfully()
    {
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->post('/warga/profil', [
            'pendidikan' => 'SLTA/Sederajat',
            'pekerjaan' => 'Wiraswasta',
            'status_perkawinan' => 'Kawin',
        ])->assertRedirect();

        $this->kepalaKeluarga->refresh();
        $this->assertEquals('SLTA/Sederajat', $this->kepalaKeluarga->pendidikan);
        $this->assertEquals('Wiraswasta', $this->kepalaKeluarga->pekerjaan);
    }

    public function test_upload_foto_profil()
    {
        Storage::fake('public');
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->post('/warga/profil', [
            'foto_profil' => UploadedFile::fake()->image('profile.jpg'),
        ])->assertRedirect();

        $this->kepalaKeluarga->refresh();
        $this->assertNotNull($this->kepalaKeluarga->getRawOriginal('foto_profil'));
        Storage::disk('public')->assertExists($this->kepalaKeluarga->getRawOriginal('foto_profil'));
    }

    public function test_upload_foto_ktp()
    {
        Storage::fake('public');
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->post('/warga/profil', [
            'foto_ktp' => UploadedFile::fake()->create('ktp.pdf', 500, 'application/pdf'),
        ])->assertRedirect();

        $this->kepalaKeluarga->refresh();
        $this->assertNotNull($this->kepalaKeluarga->getRawOriginal('foto_ktp'));
        Storage::disk('public')->assertExists($this->kepalaKeluarga->getRawOriginal('foto_ktp'));
    }

    public function test_upload_foto_kk()
    {
        Storage::fake('public');
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->post('/warga/profil', [
            'foto_kk' => UploadedFile::fake()->image('kk.png'),
        ])->assertRedirect();

        $this->kepalaKeluarga->refresh();
        $this->assertNotNull($this->kepalaKeluarga->getRawOriginal('foto_kk'));
        Storage::disk('public')->assertExists($this->kepalaKeluarga->getRawOriginal('foto_kk'));
    }

    public function test_profile_completeness_calculation()
    {
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get('/warga/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.nama_lengkap', 'Teuku Umar')
                ->has('completeness'));
    }

    public function test_profile_shows_telegram_id()
    {
        $this->kepalaKeluarga->update(['telegram_chat_id' => '9988776655']);

        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get('/warga/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.telegram_chat_id', '9988776655'));
    }

    // ─── Family Tests ────────────────────────────────────────────────

    public function test_family_index_page_renders()
    {
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->get('/warga/keluarga')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Family')
                ->has('keluarga')
                ->has('anggota')
                ->where('isKepalaKeluarga', true));
    }

    public function test_kk_head_can_update_member()
    {
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->put("/warga/keluarga/{$this->anggotaKeluarga->nik}", [
            'pendidikan' => 'S1',
            'pekerjaan' => 'Guru',
        ])->assertRedirect();

        $this->anggotaKeluarga->refresh();
        $this->assertEquals('S1', $this->anggotaKeluarga->pendidikan);
        $this->assertEquals('Guru', $this->anggotaKeluarga->pekerjaan);
    }

    public function test_non_kk_head_cannot_update_member()
    {
        $this->actingAs($this->anggotaKeluarga, 'penduduk');

        $this->put("/warga/keluarga/{$this->kepalaKeluarga->nik}", [
            'pendidikan' => 'S2',
        ])->assertStatus(403);
    }

    // ─── Submission Lifecycle Tests ───────────────────────────────────

    public function test_full_lifecycle_create_to_show()
    {
        $kategori = KategoriSurat::where('kode_surat', 'SKBM')->first();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'data_isian' => ['pekerjaan' => 'Petani', 'keperluan' => 'Keperluan Administrasi'],
            'file_syarat' => [
                'ktp_asli_dan_fotokopi' => 'https://example.test/ktp.jpg',
                'kartu_keluarga' => 'https://example.test/kk.jpg',
                'surat_pernyataan_dari_orang_tua' => 'https://example.test/surat.jpg',
            ],
        ])->assertRedirect();

        $pengajuan = PengajuanSurat::where('nik_pemohon', $this->kepalaKeluarga->nik)->first();
        $this->assertNotNull($pengajuan);

        $this->get("/warga/pengajuan/{$pengajuan->id}")
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Submission/Show')
                ->where('pengajuan.id', $pengajuan->id));
    }

    public function test_nomor_registrasi_auto_generated()
    {
        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
        ]);

        $this->assertNotNull($pengajuan->nomor_registrasi);
        $this->assertStringMatchesFormat('%d-%d', $pengajuan->nomor_registrasi);
    }

    public function test_status_starts_as_pending()
    {
        $kategori = KategoriSurat::first();
        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
            'status' => 'Pending',
        ]);

        $this->assertEquals('Pending', $pengajuan->status);
    }

    public function test_tracking_record_created_on_submit()
    {
        $kategori = KategoriSurat::where('kode_surat', 'SKBM')->first();
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'data_isian' => ['pekerjaan' => 'Petani', 'keperluan' => 'Keperluan Administrasi'],
            'file_syarat' => [
                'ktp_asli_dan_fotokopi' => 'https://example.test/ktp.jpg',
                'kartu_keluarga' => 'https://example.test/kk.jpg',
                'surat_pernyataan_dari_orang_tua' => 'https://example.test/surat.jpg',
            ],
        ]);

        $pengajuan = PengajuanSurat::where('nik_pemohon', $this->kepalaKeluarga->nik)
            ->where('status', 'Pending')
            ->first();

        $this->assertDatabaseHas('tracking_pengajuan_surat', [
            'pengajuan_surat_id' => $pengajuan->id,
            'status_baru' => 'Pending',
            'keterangan_update' => 'Pengajuan surat dibuat',
        ]);
    }

    public function test_multiple_submissions_unique_numbers()
    {
        $kategori = KategoriSurat::first();

        $p1 = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test 1', 'lama_tinggal' => 1],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
        ]);

        $p2 = PengajuanSurat::create([
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test 2', 'lama_tinggal' => 2],
            'file_syarat' => ['ktp' => 'path/ktp.jpg', 'kartu_keluarga' => 'path/kk.jpg'],
        ]);

        $this->assertNotNull($p1->nomor_registrasi);
        $this->assertNotNull($p2->nomor_registrasi);
        $this->assertNotEquals($p1->nomor_registrasi, $p2->nomor_registrasi);
    }

    public function test_submission_with_all_document_types()
    {
        $kategori = KategoriSurat::where('kode_surat', 'SKU')->first();
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $response = $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'data_isian' => [
                'nama_usaha' => 'Warung Kopi',
                'jenis_usaha' => 'Perdagangan',
                'alamat_usaha' => 'Jl. Utama No. 1',
                'tahun_berdiri' => '2020',
                'keperluan' => 'Keperluan Pinjaman Modal',
            ],
            'file_syarat' => [
                'ktp_asli_dan_fotokopi' => 'https://example.test/ktp.jpg',
                'foto_tempat_usaha' => 'https://example.test/usaha.jpg',
                'kartu_keluarga' => 'https://example.test/kk.jpg',
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pengajuan_surat', [
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'kategori_surat_id' => $kategori->id,
            'status' => 'Pending',
        ]);
    }

    public function test_submission_validates_max_file_size()
    {
        $kategori = KategoriSurat::first();
        Storage::fake('public');
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $oversizedFile = UploadedFile::fake()->create('ktp.jpg', 3000, 'image/jpeg');

        $response = $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => $oversizedFile, 'kartu_keluarga' => 'https://example.test/kk.jpg'],
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_submission_validates_file_mimes()
    {
        $kategori = KategoriSurat::first();
        Storage::fake('public');
        $this->withoutMiddleware();
        $this->actingAs($this->kepalaKeluarga, 'penduduk');

        $invalidFile = UploadedFile::fake()->create('ktp.exe', 100, 'application/x-msdownload');

        $response = $this->post('/warga/surat/pengajuan', [
            'kategori_surat_id' => $kategori->id,
            'nik_pemohon' => $this->kepalaKeluarga->nik,
            'data_isian' => ['keperluan' => 'Test', 'lama_tinggal' => 5],
            'file_syarat' => ['ktp' => $invalidFile, 'kartu_keluarga' => 'https://example.test/kk.jpg'],
        ]);

        $response->assertSessionHasErrors();
    }
}
