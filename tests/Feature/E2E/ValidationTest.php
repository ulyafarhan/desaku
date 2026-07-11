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

class ValidationTest extends TestCase
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

    // ─── Pengajuan Surat Validation ───────────────────────────

    public function test_store_requires_kategori_surat_id()
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

    public function test_store_requires_nik_pemohon()
    {
        $this->seed(KategoriSuratSeeder::class);
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => KategoriSurat::first()->id,
                'data_isian' => ['keperluan' => 'Test'],
                'file_syarat' => ['ktp' => 'path.jpg'],
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('pengajuan_surat', [
            'nik_pemohon' => $this->warga->nik,
        ]);
    }

    public function test_store_requires_data_isian()
    {
        $this->seed(KategoriSuratSeeder::class);
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => KategoriSurat::first()->id,
                'file_syarat' => ['ktp' => 'path.jpg'],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data_isian']);
    }

    public function test_store_requires_file_syarat()
    {
        $this->seed(KategoriSuratSeeder::class);
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => KategoriSurat::first()->id,
                'data_isian' => ['keperluan' => 'Test'],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file_syarat']);
    }

    public function test_store_rejects_invalid_kategori_id()
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

    public function test_store_rejects_nonexistent_nik()
    {
        $response = $this->postJson('/api/v1/auth/login/warga', [
            'nik' => '9999999999999999',
            'no_kk' => '1234567890123456',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_store_validates_schema_isian_required_fields()
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

    public function test_store_validates_max_field_lengths()
    {
        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/surat/pengajuan', [
                'kategori_surat_id' => $kategori->id,
                'data_isian' => ['keperluan' => str_repeat('A', 65536)],
                'file_syarat' => ['ktp' => 'path.jpg'],
            ]);

        $response->assertStatus(201);
    }

    // ─── Mutasi Validation ────────────────────────────────────

    public function test_mutasi_requires_nik()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'jenis_mutasi' => 'Kelahiran',
                'tanggal_mutasi' => '2024-01-15',
                'keterangan' => 'Test',
                'dokumen_bukti' => '/path/to/file.jpg',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_mutasi_requires_jenis_mutasi()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'nik' => $this->warga->nik,
                'tanggal_mutasi' => '2024-01-15',
                'keterangan' => 'Test',
                'dokumen_bukti' => '/path/to/file.jpg',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['jenis_mutasi']);
    }

    public function test_mutasi_requires_tanggal_mutasi()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'nik' => $this->warga->nik,
                'jenis_mutasi' => 'Kelahiran',
                'keterangan' => 'Test',
                'dokumen_bukti' => '/path/to/file.jpg',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tanggal_mutasi']);
    }

    public function test_mutasi_requires_keterangan()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'nik' => $this->warga->nik,
                'jenis_mutasi' => 'Kelahiran',
                'tanggal_mutasi' => '2024-01-15',
                'dokumen_bukti' => '/path/to/file.jpg',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['keterangan']);
    }

    public function test_mutasi_requires_dokumen_bukti()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'nik' => $this->warga->nik,
                'jenis_mutasi' => 'Kelahiran',
                'tanggal_mutasi' => '2024-01-15',
                'keterangan' => 'Test',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['dokumen_bukti']);
    }

    public function test_mutasi_rejects_invalid_jenis()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'nik' => $this->warga->nik,
                'jenis_mutasi' => 'InvalidJenis',
                'tanggal_mutasi' => '2024-01-15',
                'keterangan' => 'Test',
                'dokumen_bukti' => '/path/to/file.jpg',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['jenis_mutasi']);
    }

    public function test_mutasi_rejects_invalid_date()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'nik' => $this->warga->nik,
                'jenis_mutasi' => 'Kelahiran',
                'tanggal_mutasi' => 'not-a-date',
                'keterangan' => 'Test',
                'dokumen_bukti' => '/path/to/file.jpg',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tanggal_mutasi']);
    }

    public function test_mutasi_validates_nik_exists()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/mutasi', [
                'nik' => '9999999999999999',
                'jenis_mutasi' => 'Kelahiran',
                'tanggal_mutasi' => '2024-01-15',
                'keterangan' => 'Test',
                'dokumen_bukti' => '/path/to/file.jpg',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    // ─── Informasi Publik Validation ──────────────────────────

    public function test_informasi_requires_judul()
    {
        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/informasi', [
                'konten' => 'Konten berita',
                'kategori' => 'berita',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    public function test_informasi_requires_konten()
    {
        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Berita Baru',
                'kategori' => 'berita',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['konten']);
    }

    public function test_informasi_requires_kategori()
    {
        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Berita Baru',
                'konten' => 'Konten berita',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kategori']);
    }

    public function test_informasi_judul_max_255()
    {
        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => str_repeat('A', 256),
                'konten' => 'Konten berita',
                'kategori' => 'berita',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    public function test_informasi_kategori_max_50()
    {
        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Berita Baru',
                'konten' => 'Konten berita',
                'kategori' => str_repeat('A', 51),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kategori']);
    }

    public function test_informasi_update_requires_at_least_one_field()
    {
        $informasi = InformasiPublik::create([
            'judul' => 'Original',
            'slug' => 'original',
            'konten' => 'Original content',
            'kategori' => 'berita',
            'author_id' => $this->admin->id,
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->putJson("/api/v1/admin/informasi/{$informasi->id}", []);

        $response->assertStatus(200);
        $this->assertDatabaseHas('informasi_publik', [
            'id' => $informasi->id,
            'judul' => 'Original',
        ]);
    }

    // ─── Profile Validation ───────────────────────────────────

    public function test_profile_pendidikan_max_50()
    {
        $this->actingAs($this->warga, 'penduduk');

        $response = $this->post('/warga/profil', [
            'pendidikan' => str_repeat('A', 51),
        ]);

        $response->assertSessionHasErrors(['pendidikan']);
    }

    public function test_profile_pekerjaan_max_50()
    {
        $this->actingAs($this->warga, 'penduduk');

        $response = $this->post('/warga/profil', [
            'pekerjaan' => str_repeat('A', 51),
        ]);

        $response->assertSessionHasErrors(['pekerjaan']);
    }

    public function test_profile_status_perkawinan_max_20()
    {
        $this->actingAs($this->warga, 'penduduk');

        $response = $this->post('/warga/profil', [
            'status_perkawinan' => str_repeat('A', 21),
        ]);

        $response->assertSessionHasErrors(['status_perkawinan']);
    }

    public function test_profile_foto_rejects_invalid_type()
    {
        Storage::fake('public');
        $this->actingAs($this->warga, 'penduduk');

        $response = $this->post('/warga/profil', [
            'foto_profil' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors(['foto_profil']);
    }

    public function test_profile_foto_rejects_oversized()
    {
        Storage::fake('public');
        $this->actingAs($this->warga, 'penduduk');

        $response = $this->post('/warga/profil', [
            'foto_profil' => UploadedFile::fake()->create('huge.jpg', 3000, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors(['foto_profil']);
    }

    // ─── Telegram Binding ─────────────────────────────────────

    public function test_telegram_chat_id_must_be_unique()
    {
        Penduduk::create([
            'nik' => '3234567890123456',
            'no_kk' => $this->keluarga->no_kk,
            'nama_lengkap' => 'Other User',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Programmer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anggota',
            'status_mutasi' => 'Tetap',
            'telegram_chat_id' => '123456789',
        ]);

        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/bind-telegram', [
                'telegram_chat_id' => '123456789',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telegram_chat_id']);
    }

    public function test_telegram_chat_id_max_50()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/bind-telegram', [
                'telegram_chat_id' => str_repeat('1', 51),
            ]);

        $response->assertStatus(200);
    }

    public function test_telegram_bind_requires_string()
    {
        $token = $this->warga->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/bind-telegram', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telegram_chat_id']);
    }

    // ─── Admin Validation ─────────────────────────────────────

    public function test_admin_login_requires_username()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_admin_login_requires_password()
    {
        $response = $this->postJson('/api/v1/auth/login/admin', [
            'username' => 'testadmin',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_admin_reject_requires_catatan_penolakan()
    {
        $this->seed(KategoriSuratSeeder::class);
        $kategori = KategoriSurat::first();

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson("/api/v1/admin/surat/pengajuan/{$pengajuan->id}/reject", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['catatan_penolakan']);
    }

    // ─── Web Form Validation ───────────────────────────────────

    public function test_web_login_requires_nik()
    {
        $response = $this->post('/login', [
            'no_kk' => '1234567890123456',
        ]);

        $response->assertSessionHasErrors(['nik']);
    }

    public function test_web_login_requires_no_kk()
    {
        $response = $this->post('/login', [
            'nik' => '1234567890123456',
        ]);

        $response->assertSessionHasErrors(['no_kk']);
    }
}
