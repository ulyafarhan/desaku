<?php

namespace Tests\Feature\E2E;

use Tests\TestCase;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\KategoriSurat;
use App\Models\PengajuanSurat;
use App\Models\Administrator;
use App\Models\InformasiPublik;
use App\Models\PengaturanGampong;
use App\Models\PengaturanFrontend;
use App\Models\AuditLog;
use Database\Seeders\KategoriSuratSeeder;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia;

class PublicPortalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriSuratSeeder::class);
    }

    protected function makePenduduk(): Penduduk
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

    // === Home Page ===

    public function test_home_renders()
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Home')
                ->has('demografi')
                ->has('layanan')
                ->has('berita')
                ->has('kategoriSurat'));
    }

    public function test_home_shows_demografi_data()
    {
        $this->makePenduduk();

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Home')
                ->has('demografi.total_penduduk')
                ->has('demografi.total_keluarga')
                ->has('demografi.laki_laki')
                ->has('demografi.perempuan'));
    }

    public function test_home_shows_layanan_data()
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Home')
                ->has('layanan.pengajuan_surat')
                ->has('layanan.mutasi_penduduk'));
    }

    public function test_home_shows_berita()
    {
        $admin = Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        InformasiPublik::create([
            'judul' => 'Berita Terbaru',
            'slug' => 'berita-terbaru',
            'konten' => 'Konten berita',
            'kategori' => 'berita',
            'is_published' => true,
            'author_id' => $admin->id,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Home')
                ->has('berita', 1)
                ->where('berita.0.judul', 'Berita Terbaru'));
    }

    public function test_home_shows_kategori_surat()
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Home')
                ->has('kategoriSurat', 6)
                ->where('kategoriSurat.0.kode_surat', 'SKBM'));
    }

    public function test_home_limits_berita_to_3()
    {
        $admin = Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        for ($i = 0; $i < 5; $i++) {
            InformasiPublik::create([
                'judul' => "Berita Ke-$i",
                'slug' => "berita-ke-$i",
                'konten' => "Konten $i",
                'kategori' => 'berita',
                'is_published' => true,
                'author_id' => $admin->id,
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Home')
                ->has('berita', 3));
    }

    public function test_home_limits_kategori_to_6()
    {
        for ($i = 0; $i < 3; $i++) {
            KategoriSurat::create([
                'kode_surat' => "EXTRA$i",
                'nama_surat' => "Extra Surat $i",
                'template_view' => 'extra',
                'schema_isian' => [],
                'syarat_dokumen' => [],
                'is_active' => true,
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Home')
                ->has('kategoriSurat', 6));
    }

    // === Profile Page ===

    public function test_profile_renders_with_perangkat()
    {
        $this->get('/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Profile')
                ->has('perangkat', 3));
    }

    public function test_profile_shows_keuchik()
    {
        PengaturanGampong::set('nama_keuchik', 'Teuku Udeung', 'string');

        $this->get('/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Profile')
                ->where('perangkat.0.jabatan', 'Keuchik')
                ->where('perangkat.0.nama', 'Teuku Udeung'));
    }

    public function test_profile_shows_sekdes()
    {
        PengaturanFrontend::set('nama_sekdes', 'Sekretaris Udeung', 'string');

        $this->get('/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Profile')
                ->where('perangkat.1.jabatan', 'Sekretaris Desa')
                ->where('perangkat.1.nama', 'Sekretaris Udeung'));
    }

    public function test_profile_shows_operator()
    {
        PengaturanFrontend::set('nama_operator', 'Operator Gampong', 'string');

        $this->get('/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Profile')
                ->where('perangkat.2.jabatan', 'Operator Layanan')
                ->where('perangkat.2.nama', 'Operator Gampong'));
    }

    public function test_profile_uses_fallback_avatar()
    {
        $this->get('/profil')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Profile')
                ->where('perangkat.0.foto', '/images/default-avatar.png')
                ->where('perangkat.1.foto', '/images/default-avatar.png')
                ->where('perangkat.2.foto', '/images/default-avatar.png'));
    }

    // === Information Pages ===

    public function test_information_index_renders()
    {
        $this->get('/informasi')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Information/Index')
                ->has('informasi')
                ->has('kategori')
                ->has('filters'));
    }

    public function test_information_index_filters_by_kategori()
    {
        $admin = Administrator::create(['username' => 'admin', 'password' => bcrypt('p'), 'role' => 'operator']);

        InformasiPublik::create(['judul' => 'Berita A', 'slug' => 'berita-a', 'konten' => 'x', 'kategori' => 'berita', 'is_published' => true, 'author_id' => $admin->id]);
        InformasiPublik::create(['judul' => 'Pengumuman A', 'slug' => 'pengumuman-a', 'konten' => 'y', 'kategori' => 'pengumuman', 'is_published' => true, 'author_id' => $admin->id]);

        $this->get('/informasi?kategori=pengumuman')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Information/Index')
                ->where('filters.kategori', 'pengumuman'));
    }

    public function test_information_index_searches_by_judul()
    {
        $admin = Administrator::create(['username' => 'admin', 'password' => bcrypt('p'), 'role' => 'operator']);

        InformasiPublik::create(['judul' => 'Khusus Dicari', 'slug' => 'khusus-dicari', 'konten' => 'rahasia', 'kategori' => 'berita', 'is_published' => true, 'author_id' => $admin->id]);
        InformasiPublik::create(['judul' => 'Lain', 'slug' => 'lain', 'konten' => 'biasa', 'kategori' => 'berita', 'is_published' => true, 'author_id' => $admin->id]);

        $this->get('/informasi?search=Khusus')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Information/Index')
                ->where('filters.search', 'Khusus'));
    }

    public function test_information_index_searches_by_konten()
    {
        $admin = Administrator::create(['username' => 'admin', 'password' => bcrypt('p'), 'role' => 'operator']);

        InformasiPublik::create(['judul' => 'Judul Biasa', 'slug' => 'judul-biasa', 'konten' => 'konten rahasia spesial', 'kategori' => 'berita', 'is_published' => true, 'author_id' => $admin->id]);
        InformasiPublik::create(['judul' => 'Judul Lain', 'slug' => 'judul-lain', 'konten' => 'biasa', 'kategori' => 'berita', 'is_published' => true, 'author_id' => $admin->id]);

        $this->get('/informasi?search=spesial')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Information/Index')
                ->where('filters.search', 'spesial'));
    }

    public function test_information_index_paginates()
    {
        $admin = Administrator::create(['username' => 'admin', 'password' => bcrypt('p'), 'role' => 'operator']);

        for ($i = 0; $i < 15; $i++) {
            InformasiPublik::create([
                'judul' => "Article $i",
                'slug' => "article-$i",
                'konten' => "Content $i",
                'kategori' => 'berita',
                'is_published' => true,
                'author_id' => $admin->id,
            ]);
        }

        $this->get('/informasi')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Information/Index')
                ->has('informasi.data', 9));
    }

    public function test_information_show_renders_article()
    {
        $admin = Administrator::create(['username' => 'admin', 'password' => bcrypt('p'), 'role' => 'operator']);

        InformasiPublik::create([
            'judul' => 'Artikel Saya',
            'slug' => 'artikel-saya',
            'konten' => 'Isi artikel lengkap',
            'kategori' => 'berita',
            'is_published' => true,
            'author_id' => $admin->id,
        ]);

        $this->get('/informasi/artikel-saya')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Information/Show')
                ->where('informasi.judul', 'Artikel Saya')
                ->where('informasi.konten', 'Isi artikel lengkap'));
    }

    public function test_information_show_404_for_draft()
    {
        $admin = Administrator::create(['username' => 'admin', 'password' => bcrypt('p'), 'role' => 'operator']);

        InformasiPublik::create([
            'judul' => 'Draft Article',
            'slug' => 'draft-article',
            'konten' => 'Not published',
            'kategori' => 'berita',
            'is_published' => false,
            'author_id' => $admin->id,
        ]);

        $this->get('/informasi/draft-article')->assertNotFound();
    }

    public function test_information_show_404_for_nonexistent()
    {
        $this->get('/informasi/tidak-ada')->assertNotFound();
    }

    // === Verification ===

    public function test_verify_page_renders()
    {
        $this->get('/verifikasi')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Verify')
                ->where('result', null));
    }

    public function test_verify_valid_hash()
    {
        $keluarga = Keluarga::create(['no_kk' => '1234567890123456', 'alamat' => 'Jl. Test', 'dusun' => 'Test', 'rt_rw' => '001/002']);
        $penduduk = Penduduk::create(['nik' => '1234567890123456', 'no_kk' => $keluarga->no_kk, 'nama_lengkap' => 'John Doe', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '1990-01-01', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'pendidikan' => 'S1', 'pekerjaan' => 'Petani', 'status_perkawinan' => 'Kawin', 'status_keluarga' => 'Kepala Keluarga']);
        $admin = Administrator::create(['username' => 'verifikator', 'password' => bcrypt('p'), 'role' => 'operator']);
        $kategori = KategoriSurat::first();

        PengajuanSurat::create([
            'nik_pemohon' => $penduduk->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path'],
            'status' => 'Selesai',
            'qr_hash' => 'hash_valid_123',
            'diverifikasi_oleh' => $admin->id,
        ]);

        $this->get('/verifikasi/hash_valid_123')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Verify')
                ->has('result')
                ->where('result.valid', true)
                ->where('result.message', 'Dokumen terverifikasi.')
                ->where('result.nama_pemohon', 'John Doe'));
    }

    public function test_verify_invalid_hash()
    {
        $this->get('/verifikasi/hash_tidak_ada')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Verify')
                ->has('result')
                ->where('result.valid', false)
                ->where('result.message', 'Dokumen tidak ditemukan atau tidak valid.'));
    }

    public function test_verify_unfinished_document()
    {
        $keluarga = Keluarga::create(['no_kk' => '1234567890123456', 'alamat' => 'Jl. Test', 'dusun' => 'Test', 'rt_rw' => '001/002']);
        $penduduk = Penduduk::create(['nik' => '1234567890123456', 'no_kk' => $keluarga->no_kk, 'nama_lengkap' => 'Jane Doe', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '1992-05-05', 'jenis_kelamin' => 'P', 'agama' => 'Islam', 'pendidikan' => 'S1', 'pekerjaan' => 'Guru', 'status_perkawinan' => 'Kawin', 'status_keluarga' => 'Istri']);
        $kategori = KategoriSurat::first();

        PengajuanSurat::create([
            'nik_pemohon' => $penduduk->nik,
            'kategori_surat_id' => $kategori->id,
            'data_isian' => ['keperluan' => 'Test'],
            'file_syarat' => ['ktp' => 'path'],
            'status' => 'Pending',
            'qr_hash' => 'pending_hash_456',
        ]);

        $this->get('/verifikasi/pending_hash_456')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Verify')
                ->where('result.valid', false)
                ->where('result.message', 'Dokumen belum selesai diproses.'));
    }

    public function test_verify_nonexistent_hash()
    {
        $this->get('/verifikasi/tak_kenal')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Verify')
                ->where('result.valid', false));
    }

    // === Public API ===

    public function test_api_informasi_returns_published_only()
    {
        $admin = Administrator::create(['username' => 'admin', 'password' => bcrypt('p'), 'role' => 'operator']);

        InformasiPublik::create(['judul' => 'Published', 'slug' => 'published', 'konten' => 'x', 'kategori' => 'berita', 'is_published' => true, 'author_id' => $admin->id]);
        InformasiPublik::create(['judul' => 'Draft', 'slug' => 'draft', 'konten' => 'y', 'kategori' => 'berita', 'is_published' => false, 'author_id' => $admin->id]);

        $response = $this->getJson('/api/v1/informasi');

        $response->assertOk();
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Published', $data[0]['judul']);
    }

    public function test_api_informasi_filter_by_kategori()
    {
        $admin = Administrator::create(['username' => 'admin', 'password' => bcrypt('p'), 'role' => 'operator']);

        InformasiPublik::create(['judul' => 'Berita', 'slug' => 'berita', 'konten' => 'x', 'kategori' => 'berita', 'is_published' => true, 'author_id' => $admin->id]);
        InformasiPublik::create(['judul' => 'Pengumuman', 'slug' => 'pengumuman', 'konten' => 'y', 'kategori' => 'pengumuman', 'is_published' => true, 'author_id' => $admin->id]);

        $response = $this->getJson('/api/v1/informasi?kategori=pengumuman');

        $response->assertOk();
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('pengumuman', $data[0]['kategori']);
    }

    public function test_api_statistik_demografi()
    {
        $this->makePenduduk();

        $response = $this->getJson('/api/v1/statistik/demografi');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['total_penduduk', 'total_keluarga', 'laki_laki', 'perempuan'],
        ]);
        $this->assertEquals(1, $response->json('data.total_penduduk'));
    }

    public function test_api_statistik_layanan()
    {
        $response = $this->getJson('/api/v1/statistik/layanan');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['pengajuan_surat', 'mutasi_penduduk'],
        ]);
    }

    public function test_api_kategori_surat_list()
    {
        $penduduk = $this->makePenduduk();
        $token = $penduduk->createToken('test', ['warga'])->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/surat/kategori');

        $response->assertOk();
        $response->assertJsonCount(10, 'data');
    }

    // === Aspirasi ===

    public function test_aspirasi_submit_stores_log()
    {
        $this->post('/aspirasi', ['pesan' => 'Tolong perbaiki jalan'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('audit_logs', [
            'user_type' => 'public',
            'tindakan' => 'aspirasi',
        ]);
    }

    public function test_aspirasi_requires_pesan()
    {
        $response = $this->post('/aspirasi', []);

        $response->assertSessionHasErrors('pesan');
    }

    public function test_aspirasi_max_length()
    {
        $response = $this->post('/aspirasi', ['pesan' => str_repeat('a', 1001)]);

        $response->assertSessionHasErrors('pesan');
    }

    public function test_aspirasi_rate_limited()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/aspirasi', ['pesan' => "Pesan ke-$i"])->assertRedirect();
        }

        $response = $this->post('/aspirasi', ['pesan' => 'Pesan ke-6']);
        $response->assertStatus(429);
    }

    public function test_aspirasi_redirects_back()
    {
        $response = $this->from('/statistik')->post('/aspirasi', ['pesan' => 'Aspirasi test']);

        $response->assertRedirect('/statistik');
        $response->assertSessionHas('success');
    }

    // === Statistik ===

    public function test_statistik_page_renders()
    {
        $this->get('/statistik')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Statistik')
                ->has('demografi')
                ->has('layanan'));
    }

    public function test_statistik_shows_demografi()
    {
        $this->makePenduduk();

        $this->get('/statistik')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Statistik')
                ->has('demografi.total_penduduk')
                ->has('demografi.per_dusun')
                ->has('demografi.per_agama'));
    }

    public function test_statistik_shows_layanan()
    {
        $this->get('/statistik')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Public/Statistik')
                ->has('layanan.pengajuan_surat')
                ->has('layanan.mutasi_penduduk')
                ->has('layanan.per_jenis_surat'));
    }
}
