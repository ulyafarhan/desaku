<?php

namespace Tests\Feature\E2E;

use Tests\TestCase;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\KategoriSurat;
use Database\Seeders\KategoriSuratSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia;

class ProfileManagementTest extends TestCase
{
    protected Penduduk $warga;
    protected Penduduk $kepalaKeluarga;
    protected Keluarga $keluarga;

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

        $this->kepalaKeluarga = Penduduk::create([
            'nik' => '1111111111111111',
            'no_kk' => $this->keluarga->no_kk,
            'nama_lengkap' => 'Kepala Keluarga',
            'tempat_lahir' => 'Banda Aceh',
            'tanggal_lahir' => '1980-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'PNS',
            'status_perkawinan' => 'Kawin',
            'status_keluarga' => 'Kepala Keluarga',
            'status_mutasi' => 'Tetap',
        ]);

        $this->keluarga->update(['kepala_keluarga_nik' => $this->kepalaKeluarga->nik]);

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
    }

    private function actingAsWarga()
    {
        return $this->actingAs($this->warga, 'penduduk');
    }

    private function actingAsKepalaKeluarga()
    {
        return $this->actingAs($this->kepalaKeluarga, 'penduduk');
    }

    public function test_profile_page_inertia_renders()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Profile')
                ->has('warga')
                ->has('completeness'));
    }

    public function test_profile_shows_nik()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.nik', '1234567890123456'));
    }

    public function test_profile_shows_no_kk()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.no_kk', '1234567890123456'));
    }

    public function test_profile_shows_name()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.nama_lengkap', 'John Doe'));
    }

    public function test_profile_shows_gender()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.jenis_kelamin', 'L'));
    }

    public function test_profile_shows_birthplace()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.tempat_lahir', 'Jakarta'));
    }

    public function test_profile_shows_birthdate()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.tanggal_lahir', '1990-01-01T00:00:00.000000Z'));
    }

    public function test_profile_shows_agama()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.agama', 'Islam'));
    }

    public function test_profile_shows_status_keluarga()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('warga.status_keluarga', 'Anak'));
    }

    public function test_update_pendidikan()
    {
        $this->actingAsWarga()
            ->post('/warga/profil', [
                'pendidikan' => 'SLTA/Sederajat',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'pendidikan' => 'SLTA/Sederajat',
        ]);
    }

    public function test_update_pekerjaan()
    {
        $this->actingAsWarga()
            ->post('/warga/profil', [
                'pekerjaan' => 'Wiraswasta',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'pekerjaan' => 'Wiraswasta',
        ]);
    }

    public function test_update_status_perkawinan()
    {
        $this->actingAsWarga()
            ->post('/warga/profil', [
                'status_perkawinan' => 'Kawin',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'status_perkawinan' => 'Kawin',
        ]);
    }

    public function test_update_telegram_chat_id()
    {
        $this->actingAsWarga()
            ->post('/warga/profil', [
                'telegram_chat_id' => '123456789',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'telegram_chat_id' => '123456789',
        ]);
    }

    public function test_upload_foto_profil_stores_correctly()
    {
        Storage::fake('public');

        $this->actingAsWarga()
            ->post('/warga/profil', [
                'foto_profil' => UploadedFile::fake()->image('profile.jpg'),
            ])
            ->assertRedirect();

        $this->warga->refresh();
        $this->assertNotNull($this->warga->getRawOriginal('foto_profil'));
        Storage::disk('public')->assertExists($this->warga->getRawOriginal('foto_profil'));
    }

    public function test_upload_foto_ktp_stores_correctly()
    {
        Storage::fake('public');

        $this->actingAsWarga()
            ->post('/warga/profil', [
                'foto_ktp' => UploadedFile::fake()->create('ktp.pdf', 500, 'application/pdf'),
            ])
            ->assertRedirect();

        $this->warga->refresh();
        $this->assertNotNull($this->warga->getRawOriginal('foto_ktp'));
        Storage::disk('public')->assertExists($this->warga->getRawOriginal('foto_ktp'));
    }

    public function test_upload_foto_kk_stores_correctly()
    {
        Storage::fake('public');

        $this->actingAsWarga()
            ->post('/warga/profil', [
                'foto_kk' => UploadedFile::fake()->image('kk.png'),
            ])
            ->assertRedirect();

        $this->warga->refresh();
        $this->assertNotNull($this->warga->getRawOriginal('foto_kk'));
        Storage::disk('public')->assertExists($this->warga->getRawOriginal('foto_kk'));
    }

    public function test_upload_webp_accepted()
    {
        Storage::fake('public');

        $this->actingAsWarga()
            ->post('/warga/profil', [
                'foto_ktp' => UploadedFile::fake()->image('ktp.webp'),
            ])
            ->assertRedirect();

        $this->warga->refresh();
        $this->assertNotNull($this->warga->getRawOriginal('foto_ktp'));
    }

    public function test_upload_png_accepted()
    {
        Storage::fake('public');

        $this->actingAsWarga()
            ->post('/warga/profil', [
                'foto_profil' => UploadedFile::fake()->image('profile.png'),
            ])
            ->assertRedirect();

        $this->warga->refresh();
        $this->assertNotNull($this->warga->getRawOriginal('foto_profil'));
    }

    public function test_upload_rejects_invalid_type()
    {
        $this->actingAsWarga()
            ->post('/warga/profil', [
                'foto_profil' => UploadedFile::fake()->create('malware.exe', 100),
            ])
            ->assertSessionHasErrors('foto_profil');
    }

    public function test_upload_rejects_oversized()
    {
        $this->actingAsWarga()
            ->post('/warga/profil', [
                'foto_profil' => UploadedFile::fake()->image('large.jpg')->size(3072),
            ])
            ->assertSessionHasErrors('foto_profil');
    }

    public function test_profile_completeness_full_fields()
    {
        $this->actingAsWarga()
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('completeness', 100));
    }

    public function test_profile_completeness_partial_fields()
    {
        Penduduk::where('nik', $this->warga->nik)->update([
            'pendidikan' => '',
            'pekerjaan' => '',
        ]);

        $freshWarga = Penduduk::where('nik', $this->warga->nik)->first();

        $this->actingAs($freshWarga, 'penduduk')
            ->get('/warga/profil')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('completeness', 75));
    }

    public function test_family_index_renders()
    {
        $this->actingAsWarga()
            ->get('/warga/keluarga')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Citizen/Family')
                ->has('keluarga')
                ->has('anggota')
                ->has('isKepalaKeluarga'));
    }

    public function test_family_index_shows_all_members()
    {
        $this->actingAsWarga()
            ->get('/warga/keluarga')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('anggota', 2));
    }

    public function test_kk_head_can_update_member()
    {
        $this->actingAsKepalaKeluarga()
            ->put("/warga/keluarga/{$this->warga->nik}", [
                'pendidikan' => 'SLTA/Sederajat',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'pendidikan' => 'SLTA/Sederajat',
        ]);
    }

    public function test_non_kk_cannot_update_member()
    {
        $this->actingAsWarga()
            ->put("/warga/keluarga/{$this->kepalaKeluarga->nik}", [
                'pendidikan' => 'S2',
            ])
            ->assertStatus(403);
    }

    public function test_update_member_pendidikan()
    {
        $this->actingAsKepalaKeluarga()
            ->put("/warga/keluarga/{$this->warga->nik}", [
                'pendidikan' => 'D3',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'pendidikan' => 'D3',
        ]);
    }

    public function test_update_member_pekerjaan()
    {
        $this->actingAsKepalaKeluarga()
            ->put("/warga/keluarga/{$this->warga->nik}", [
                'pekerjaan' => 'Mahasiswa',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('penduduk', [
            'nik' => $this->warga->nik,
            'pekerjaan' => 'Mahasiswa',
        ]);
    }

    public function test_family_member_sorted_by_role()
    {
        $this->actingAsWarga()
            ->get('/warga/keluarga')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('anggota', 2)
                ->where('anggota.0.status_keluarga', 'Kepala Keluarga')
                ->where('anggota.1.status_keluarga', 'Anak'));
    }
}
