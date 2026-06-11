<?php

namespace Tests\Feature;

use App\Models\Administrator;
use App\Models\KategoriSurat;
use App\Models\Keluarga;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class WebFrontendTest extends TestCase
{
    public function test_public_home_renders_inertia_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Home')
                ->has('demografi')
                ->has('layanan'));
    }

    public function test_guest_is_redirected_from_citizen_dashboard(): void
    {
        $this->get('/warga/dashboard')
            ->assertRedirect('/login');
    }

    public function test_warga_can_login_with_session_guard(): void
    {
        $this->makePenduduk();

        $this->post('/login', [
            'nik' => '1234567890123456',
            'no_kk' => '1234567890123456',
        ])->assertRedirect('/warga/dashboard');

        $this->assertAuthenticatedAs(Penduduk::find('1234567890123456'), 'penduduk');
    }

    public function test_authenticated_warga_can_submit_pengajuan(): void
    {
        $penduduk = $this->makePenduduk();
        $kategori = KategoriSurat::create([
            'kode_surat' => 'SKD',
            'nama_surat' => 'Surat Keterangan Domisili',
            'template_view' => 'domisili',
            'schema_isian' => [
                ['field' => 'keperluan', 'label' => 'Keperluan', 'type' => 'text', 'required' => true],
            ],
            'syarat_dokumen' => ['KTP'],
            'is_active' => true,
        ]);

        $this->actingAs($penduduk, 'penduduk')
            ->post('/warga/surat/pengajuan', [
                'kategori_surat_id' => $kategori->id,
                'nik_pemohon' => $penduduk->nik,
                'data_isian' => ['keperluan' => 'Administrasi'],
                'file_syarat' => ['ktp' => 'https://example.test/ktp.jpg'],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('pengajuan_surat', [
            'nik_pemohon' => $penduduk->nik,
            'kategori_surat_id' => $kategori->id,
            'status' => 'Pending',
        ]);
    }

    public function test_admin_can_access_filament_dashboard_with_admin_guard(): void
    {
        $admin = Administrator::create([
            'username' => 'operator',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);

        $this->actingAs($admin, 'admin')
            ->get('/admin')
            ->assertOk();
    }

    private function makePenduduk(): Penduduk
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test',
            'dusun' => 'Test',
            'rt_rw' => '001/002',
        ]);

        return Penduduk::create([
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
    }

    public function test_warga_can_upload_files_to_profile(): void
    {
        $penduduk = $this->makePenduduk();
        \Illuminate\Support\Facades\Storage::fake('public');

        $this->actingAs($penduduk, 'penduduk')
            ->post('/warga/profil', [
                'pendidikan' => 'SLTA/Sederajat',
                'pekerjaan' => 'Wiraswasta',
                'status_perkawinan' => 'Kawin',
                'foto_profil' => \Illuminate\Http\UploadedFile::fake()->image('profile.jpg'),
                'foto_ktp' => \Illuminate\Http\UploadedFile::fake()->create('ktp.pdf', 500, 'application/pdf'),
                'foto_kk' => \Illuminate\Http\UploadedFile::fake()->image('kk.png'),
            ])
            ->assertRedirect();

        $penduduk->refresh();
        $this->assertEquals('SLTA/Sederajat', $penduduk->pendidikan);
        $this->assertEquals('Wiraswasta', $penduduk->pekerjaan);
        
        $this->assertNotNull($penduduk->getRawOriginal('foto_profil'));
        $this->assertNotNull($penduduk->getRawOriginal('foto_ktp'));
        $this->assertNotNull($penduduk->getRawOriginal('foto_kk'));
        
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($penduduk->getRawOriginal('foto_profil'));
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($penduduk->getRawOriginal('foto_ktp'));
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($penduduk->getRawOriginal('foto_kk'));
    }
}
