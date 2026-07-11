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
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\KategoriSuratSeeder;

class SecurityTest extends TestCase
{
    protected Penduduk $warga;

    protected Administrator $admin;

    protected Keluarga $keluarga;

    protected function setUp(): void
    {
        parent::setUp();

        $this->keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test',
            'dusun' => 'Test',
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
            'username' => 'testadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);
    }

    // ─── API Token Security ────────────────────────────────────

    public function test_protected_route_requires_token()
    {
        $response = $this->getJson('/api/v1/auth/profile');

        $response->assertStatus(401);
    }

    public function test_wrong_ability_token_rejected()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/admin/surat/pengajuan');

        $response->assertStatus(403);
    }

    public function test_expired_token_rejected()
    {
        $token = $this->warga->createToken('test', ['warga']);

        $token->accessToken->forceDelete();

        $response = $this->withToken($token->plainTextToken)
            ->getJson('/api/v1/auth/profile');

        $response->assertStatus(401);
    }

    public function test_token_revoked_after_logout()
    {
        $token = $this->warga->createToken('test', ['warga']);
        $plainTextToken = $token->plainTextToken;

        $this->withToken($plainTextToken)
            ->postJson('/api/v1/auth/logout')
            ->assertStatus(200);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);

        $this->app->forgetInstance('auth');

        $response = $this->withToken($plainTextToken)
            ->getJson('/api/v1/auth/profile');

        $response->assertStatus(401);
    }

    public function test_token_from_one_user_rejected_on_another()
    {
        $warga2 = Penduduk::create([
            'nik' => '2234567890123456',
            'no_kk' => $this->keluarga->no_kk,
            'nama_lengkap' => 'Jane Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1992-01-01',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Designer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);

        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();

        PengajuanSurat::create([
            'nik_pemohon' => $warga2->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $tokenWarga1 = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($tokenWarga1)
            ->getJson('/api/v1/surat/pengajuan');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertIsArray($data);
        $this->assertCount(0, $data);
    }

    // ─── Route Authorization ───────────────────────────────────

    public function test_warga_cannot_access_admin_api_routes()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/admin/surat/pengajuan')->assertStatus(403);
        $this->withToken($token)->getJson('/api/v1/admin/mutasi')->assertStatus(403);
        $this->withToken($token)->getJson('/api/v1/admin/informasi')->assertStatus(403);
    }

    public function test_admin_cannot_access_warga_api_routes()
    {
        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/surat/pengajuan', [
            'kategori_surat_id' => 'some-id',
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['test' => 'path'],
        ])->assertStatus(403);

        $this->withToken($token)->postJson('/api/v1/mutasi', [
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-15',
            'keterangan' => 'Test',
            'dokumen_bukti' => '/path/to/file.jpg',
        ])->assertStatus(403);

        $this->withToken($token)->postJson('/api/v1/auth/bind-telegram', [
            'telegram_chat_id' => '123456',
        ])->assertStatus(403);
    }

    public function test_guest_cannot_access_protected_web_routes()
    {
        $this->get('/warga/dashboard')->assertRedirect('/login');
        $this->get('/warga/profil')->assertRedirect('/login');
        $this->get('/warga/keluarga')->assertRedirect('/login');
    }

    public function test_warga_cannot_approve_pengajuan()
    {
        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $this->withToken($token)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/approve")
            ->assertStatus(403);
    }

    public function test_warga_cannot_reject_pengajuan()
    {
        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $this->withToken($token)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/reject", [
                'catatan_penolakan' => 'Tidak lengkap',
            ])
            ->assertStatus(403);
    }

    public function test_warga_cannot_manage_mutasi_admin_routes()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/admin/mutasi/some-id/approve')
            ->assertStatus(403);

        $this->withToken($token)
            ->postJson('/api/v1/admin/mutasi/some-id/reject')
            ->assertStatus(403);
    }

    public function test_warga_cannot_manage_informasi_admin_routes()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Test',
                'konten' => 'Content',
                'kategori' => 'berita',
            ])
            ->assertStatus(403);

        $informasi = InformasiPublik::create([
            'judul' => 'Test',
            'konten' => 'Content',
            'kategori' => 'berita',
            'author_id' => $this->admin->id,
        ]);

        $this->withToken($token)
            ->putJson("/api/v1/admin/informasi/{$informasi->id}", ['judul' => 'Updated'])
            ->assertStatus(403);

        $this->withToken($token)
            ->deleteJson("/api/v1/admin/informasi/{$informasi->id}")
            ->assertStatus(403);
    }

    // ─── Input Validation ──────────────────────────────────────

    public function test_nik_must_be_16_chars()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '12345',
            'no_kk' => '1234567890123456',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_nik_rejects_non_numeric()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => 'ABCDEFGHIJKLMNOP',
            'no_kk' => '1234567890123456',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_no_kk_must_be_16_chars()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '1234567890123456',
            'no_kk' => '12345',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['no_kk']);
    }

    public function test_missing_kategori_surat_id_rejected()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'data_isian' => ['keperluan' => 'Test'],
                'file_syarat' => ['ktp' => 'path.jpg'],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kategori_surat_id']);
    }

    public function test_invalid_kategori_surat_id_rejected()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => 'non-existent-id',
                'data_isian' => ['keperluan' => 'Test'],
                'file_syarat' => ['ktp' => 'path.jpg'],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kategori_surat_id']);
    }

    public function test_missing_data_isian_rejected()
    {
        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => $kategori->id,
                'file_syarat' => ['ktp' => 'path.jpg'],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data_isian']);
    }

    public function test_missing_file_syarat_rejected()
    {
        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => $kategori->id,
                'data_isian' => ['keperluan' => 'Test'],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file_syarat']);
    }

    public function test_empty_data_isian_values_rejected()
    {
        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => $kategori->id,
                'data_isian' => [],
                'file_syarat' => ['ktp' => 'path.jpg'],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data_isian']);
    }

    // ─── Rate Limiting ─────────────────────────────────────────

    public function test_login_rate_limit_enforced()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/login/warga', [
                'nik' => '1234567890123456',
                'no_kk' => $this->keluarga->no_kk,
            ]);
        }

        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '1234567890123456',
            'no_kk' => $this->keluarga->no_kk,
        ]);

        $response->assertStatus(429);
    }

    public function test_telegram_webhook_rate_limit()
    {
        for ($i = 0; $i < 60; $i++) {
            $this->postJson('/api/v1/telegram/webhook', ['message' => ['text' => 'test']]);
        }

        $response = $this->postJson('/api/v1/telegram/webhook', ['message' => ['text' => 'test']]);

        $response->assertStatus(429);
    }

    // ─── Data Exposure ─────────────────────────────────────────

    public function test_api_profile_hides_sensitive_fields()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/auth/profile');

        $response->assertStatus(200);
        $user = $response->json('user');

        $this->assertArrayNotHasKey('telegram_chat_id', $user);
    }

    public function test_api_warga_profile_hides_token_secrets()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/auth/profile');

        $response->assertStatus(200);
        $response->assertJsonMissingPath('user.token');
        $response->assertJsonMissingPath('user.tokenable');
    }

    public function test_admin_token_not_in_response_body()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', [
            'username' => 'testadmin',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response->json());
    }

    // ─── XSS Prevention ────────────────────────────────────────

    public function test_profile_name_escaped_in_response()
    {
        $xssName = '<script>alert("xss")</script>';
        $this->warga->update(['nama_lengkap' => $xssName]);

        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/auth/profile');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $user = $response->json('user');
        $this->assertEquals($xssName, $user['nama_lengkap']);
    }

    public function test_informasi_judul_escaped_in_api()
    {
        $adminToken = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $xssTitle = '<script>alert("xss")</script>';

        $this->withToken($adminToken)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => $xssTitle,
                'konten' => 'Safe content',
                'kategori' => 'berita',
                'is_published' => true,
            ]);

        $response = $this->getJson('/api/v1/informasi');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals($xssTitle, $data[0]['judul']);
    }

    public function test_submission_data_isian_safe()
    {
        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();

        $xssValue = '<img src=x onerror=alert(1)>';

        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => $kategori->id,
                'data_isian' => ['keperluan' => $xssValue],
                'file_syarat' => ['ktp' => 'path.jpg'],
            ]);

        $response->assertStatus(201);

        $pengajuanId = $response->json('data.id');
        $pengajuan = PengajuanSurat::find($pengajuanId);
        $this->assertEquals($xssValue, $pengajuan->data_isian['keperluan']);
    }

    // ─── SQL Injection ─────────────────────────────────────────

    public function test_nik_sql_injection_rejected()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => "' OR '1'='1",
            'no_kk' => '1234567890123456',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_search_sql_injection_safe()
    {
        $response = $this->getJson('/api/v1/informasi?kategori=\' OR 1=1 --');

        $response->assertStatus(200);
    }

    // ─── Session Security ──────────────────────────────────────

    public function test_web_login_regenerates_session()
    {
        $this->get('/login');

        $sessionId = session()->getId();

        $this->post('/login', [
            '_token' => csrf_token(),
            'nik' => $this->warga->nik,
            'no_kk' => $this->keluarga->no_kk,
        ]);

        $this->assertNotEquals($sessionId, session()->getId());
    }

    public function test_web_logout_invalidates_session()
    {
        $this->actingAs($this->warga, 'penduduk');

        $this->post('/logout');

        $this->assertGuest('penduduk');
    }

    public function test_guest_cannot_access_citizen_dashboard()
    {
        $this->get('/warga/dashboard')->assertRedirect('/login');
        $this->get('/warga/profil')->assertRedirect('/login');
    }

    // ─── File Upload Security ──────────────────────────────────

    public function test_rejects_php_file_upload()
    {
        Storage::fake('public');

        $this->actingAs($this->warga, 'penduduk');

        $response = $this->post('/warga/profil', [
            'foto_profil' => UploadedFile::fake()->create('malicious.php', 100, 'text/php'),
        ]);

        $response->assertSessionHasErrors(['foto_profil']);
    }

    public function test_rejects_oversized_file()
    {
        Storage::fake('public');

        $this->actingAs($this->warga, 'penduduk');

        $response = $this->post('/warga/profil', [
            'foto_profil' => UploadedFile::fake()->create('huge.jpg', 3000, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors(['foto_profil']);
    }
}
