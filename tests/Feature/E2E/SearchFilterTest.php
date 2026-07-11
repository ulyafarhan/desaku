<?php

namespace Tests\Feature\E2E;

use Tests\TestCase;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\Administrator;
use App\Models\KategoriSurat;
use App\Models\InformasiPublik;
use App\Models\PengajuanSurat;
use App\Models\MutasiPenduduk;
use Database\Seeders\KategoriSuratSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class SearchFilterTest extends TestCase
{
    protected Penduduk $warga;
    protected Keluarga $keluarga;
    protected Administrator $admin;
    protected KategoriSurat $kategori;
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
            'username' => 'searchadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);

        $this->adminToken = $this->admin->createToken('search-admin-token', ['admin'])->plainTextToken;
        $this->wargaToken = $this->warga->createToken('search-warga-token', ['warga'])->plainTextToken;
        $this->kategori = KategoriSurat::first();
    }

    // =========================================================================
    // Information Search/Filter
    // =========================================================================

    public function test_information_filter_by_berita()
    {
        InformasiPublik::create(['judul' => 'Berita A', 'slug' => 'berita-a', 'konten' => 'Konten berita', 'kategori' => 'Berita', 'is_published' => true, 'author_id' => $this->admin->id]);
        InformasiPublik::create(['judul' => 'Pengumuman A', 'slug' => 'pengumuman-a', 'konten' => 'Konten pengumuman', 'kategori' => 'Pengumuman', 'is_published' => true, 'author_id' => $this->admin->id]);

        $response = $this->getJson('/api/v1/informasi?kategori=Berita');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Berita A', $data[0]['judul']);
    }

    public function test_information_filter_by_pengumuman()
    {
        InformasiPublik::create(['judul' => 'Berita B', 'slug' => 'berita-b', 'konten' => 'Konten berita', 'kategori' => 'Berita', 'is_published' => true, 'author_id' => $this->admin->id]);
        InformasiPublik::create(['judul' => 'Pengumuman B', 'slug' => 'pengumuman-b', 'konten' => 'Konten pengumuman', 'kategori' => 'Pengumuman', 'is_published' => true, 'author_id' => $this->admin->id]);

        $response = $this->getJson('/api/v1/informasi?kategori=Pengumuman');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Pengumuman B', $data[0]['judul']);
    }

    public function test_information_search_judul_exact()
    {
        InformasiPublik::create(['judul' => 'Exact Title', 'slug' => 'exact-title', 'konten' => 'Content', 'kategori' => 'Berita', 'is_published' => true, 'author_id' => $this->admin->id]);
        InformasiPublik::create(['judul' => 'Other Title', 'slug' => 'other-title', 'konten' => 'Content', 'kategori' => 'Berita', 'is_published' => true, 'author_id' => $this->admin->id]);

        $response = $this->getJson('/api/v1/informasi');
        $titles = collect($response->json('data'))->pluck('judul');
        $this->assertTrue($titles->contains('Exact Title'));
    }

    public function test_information_search_judul_partial()
    {
        InformasiPublik::create(['judul' => 'Pengumuman Penting 2024', 'slug' => 'pengumuman-penting-2024', 'konten' => 'Isi penting', 'kategori' => 'Pengumuman', 'is_published' => true, 'author_id' => $this->admin->id]);
        InformasiPublik::create(['judul' => 'Berita Biasa', 'slug' => 'berita-biasa', 'konten' => 'Isi biasa', 'kategori' => 'Berita', 'is_published' => true, 'author_id' => $this->admin->id]);

        $response = $this->getJson('/api/v1/informasi');
        $titles = collect($response->json('data'))->pluck('judul');
        $this->assertTrue($titles->contains('Pengumuman Penting 2024'));
        $this->assertTrue($titles->contains('Berita Biasa'));
    }

    public function test_information_search_konten()
    {
        InformasiPublik::create(['judul' => 'Info Layanan', 'slug' => 'info-layanan', 'konten' => 'Persyaratan membuat surat keterangan', 'kategori' => 'Berita', 'is_published' => true, 'author_id' => $this->admin->id]);

        $response = $this->getJson('/api/v1/informasi');
        $kontens = collect($response->json('data'))->pluck('konten');
        $this->assertTrue($kontens->contains(fn ($k) => str_contains($k, 'surat keterangan')));
    }

    public function test_information_search_case_insensitive()
    {
        $article = InformasiPublik::create(['judul' => 'BERITA RESMI', 'slug' => 'berita-resmi', 'konten' => 'Isi resmi', 'kategori' => 'Berita', 'is_published' => true, 'author_id' => $this->admin->id]);

        $this->assertDatabaseHas('informasi_publik', ['id' => $article->id, 'judul' => 'BERITA RESMI']);
        $response = $this->getJson('/api/v1/informasi');
        $titles = collect($response->json('data'))->pluck('judul');
        $this->assertTrue($titles->contains('BERITA RESMI'));
    }

    public function test_information_pagination()
    {
        for ($i = 1; $i <= 12; $i++) {
            InformasiPublik::create([
                'judul' => "Article $i",
                'slug' => "article-$i",
                'konten' => "Content $i",
                'kategori' => 'Berita',
                'is_published' => true,
                'author_id' => $this->admin->id,
            ]);
        }

        $response = $this->getJson('/api/v1/informasi');
        $response->assertStatus(200);
        $this->assertCount(10, $response->json('data'));
        $this->assertEquals(12, $response->json('total'));
    }

    public function test_information_per_page_limit()
    {
        for ($i = 1; $i <= 5; $i++) {
            InformasiPublik::create([
                'judul' => "PageItem $i",
                'slug' => "pageitem-$i",
                'konten' => "Content $i",
                'kategori' => 'Berita',
                'is_published' => true,
                'author_id' => $this->admin->id,
            ]);
        }

        $response = $this->getJson('/api/v1/informasi');
        $this->assertCount(5, $response->json('data'));
    }

    // =========================================================================
    // Pengajuan Filter (Admin)
    // =========================================================================

    public function test_admin_filter_pending()
    {
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '2'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Selesai']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/surat/pengajuan?status=Pending');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Pending', $data[0]['status']);
    }

    public function test_admin_filter_diproses()
    {
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '2'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Diproses']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/surat/pengajuan?status=Diproses');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Diproses', $data[0]['status']);
    }

    public function test_admin_filter_disetujui()
    {
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '2'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Disetujui']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/surat/pengajuan?status=Disetujui');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Disetujui', $data[0]['status']);
    }

    public function test_admin_filter_ditolak()
    {
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '2'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Ditolak']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/surat/pengajuan?status=Ditolak');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Ditolak', $data[0]['status']);
    }

    public function test_admin_filter_selesai()
    {
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '2'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Selesai']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/surat/pengajuan?status=Selesai');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Selesai', $data[0]['status']);
    }

    public function test_admin_no_filter_returns_all()
    {
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '2'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Selesai']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/surat/pengajuan');
        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_admin_filter_combined_with_sort()
    {
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '2'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/surat/pengajuan?status=Pending');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
        foreach ($data as $item) {
            $this->assertEquals('Pending', $item['status']);
        }
    }

    // =========================================================================
    // Mutasi Filter (Admin)
    // =========================================================================

    public function test_admin_filter_mutasi_by_kelahiran()
    {
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kelahiran', 'tanggal_mutasi' => '2024-01-01', 'keterangan' => 'Lahir', 'dokumen_bukti' => 'akta.pdf']);
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kematian', 'tanggal_mutasi' => '2024-06-01', 'keterangan' => 'Meninggal', 'dokumen_bukti' => 'surat.pdf']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/mutasi?jenis_mutasi=Kelahiran');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Kelahiran', $data[0]['jenis_mutasi']);
    }

    public function test_admin_filter_mutasi_by_kematian()
    {
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kelahiran', 'tanggal_mutasi' => '2024-01-01', 'keterangan' => 'Lahir', 'dokumen_bukti' => 'akta.pdf']);
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kematian', 'tanggal_mutasi' => '2024-06-01', 'keterangan' => 'Meninggal', 'dokumen_bukti' => 'surat.pdf']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/mutasi?jenis_mutasi=Kematian');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Kematian', $data[0]['jenis_mutasi']);
    }

    public function test_admin_filter_mutasi_by_kedatangan()
    {
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kelahiran', 'tanggal_mutasi' => '2024-01-01', 'keterangan' => 'Lahir', 'dokumen_bukti' => 'akta.pdf']);
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kedatangan', 'tanggal_mutasi' => '2024-03-01', 'keterangan' => 'Datang', 'dokumen_bukti' => 'surat.pdf']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/mutasi?jenis_mutasi=Kedatangan');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Kedatangan', $data[0]['jenis_mutasi']);
    }

    public function test_admin_filter_mutasi_by_kepindahan()
    {
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kelahiran', 'tanggal_mutasi' => '2024-01-01', 'keterangan' => 'Lahir', 'dokumen_bukti' => 'akta.pdf']);
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kepindahan', 'tanggal_mutasi' => '2024-05-01', 'keterangan' => 'Pindah', 'dokumen_bukti' => 'surat.pdf']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/mutasi?jenis_mutasi=Kepindahan');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Kepindahan', $data[0]['jenis_mutasi']);
    }

    public function test_admin_filter_mutasi_by_status_pending()
    {
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kelahiran', 'tanggal_mutasi' => '2024-01-01', 'keterangan' => 'Lahir', 'dokumen_bukti' => 'akta.pdf', 'status_verifikasi' => 'Pending']);
        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kematian', 'tanggal_mutasi' => '2024-06-01', 'keterangan' => 'Meninggal', 'dokumen_bukti' => 'surat.pdf', 'status_verifikasi' => 'Disetujui']);

        $response = $this->withToken($this->adminToken)->getJson('/api/v1/admin/mutasi?status_verifikasi=Pending');
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Pending', $data[0]['status_verifikasi']);
    }

    // =========================================================================
    // Statistik
    // =========================================================================

    public function test_statistik_total_penduduk_count()
    {
        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $data = $response->json('data');
        $this->assertEquals(1, $data['total_penduduk']);
    }

    public function test_statistik_laki_laki_count()
    {
        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $data = $response->json('data');
        $this->assertEquals(1, $data['laki_laki']);
    }

    public function test_statistik_perempuan_count()
    {
        Cache::forget('statistik_demografi');

        Penduduk::create([
            'nik' => '9999999999999998',
            'no_kk' => $this->keluarga->no_kk,
            'nama_lengkap' => 'Jane Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Guru',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);
        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $data = $response->json('data');
        $this->assertEquals(1, $data['perempuan']);
    }

    public function test_statistik_total_keluarga_count()
    {
        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $data = $response->json('data');
        $this->assertEquals(1, $data['total_keluarga']);
    }

    public function test_statistik_per_dusun_count()
    {
        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $data = $response->json('data');
        $this->assertArrayHasKey('Udeung', $data['per_dusun']);
        $this->assertEquals(1, $data['per_dusun']['Udeung']);
    }

    public function test_statistik_pengajuan_pending_count()
    {
        Cache::forget('statistik_layanan');

        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '2'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Selesai']);

        $response = $this->getJson('/api/v1/statistik/layanan');
        $data = $response->json('data');
        $this->assertEquals(1, $data['pengajuan_surat']['pending']);
    }

    public function test_statistik_pengajuan_selesai_count()
    {
        Cache::forget('statistik_layanan');

        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Selesai']);

        $response = $this->getJson('/api/v1/statistik/layanan');
        $data = $response->json('data');
        $this->assertEquals(1, $data['pengajuan_surat']['selesai']);
    }

    public function test_statistik_per_jenis_surat()
    {
        Cache::forget('statistik_layanan');

        $namaSurat = $this->kategori->nama_surat;
        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);

        $response = $this->getJson('/api/v1/statistik/layanan');
        $data = $response->json('data');
        $this->assertArrayHasKey('per_jenis_surat', $data);
        $this->assertArrayHasKey($namaSurat, $data['per_jenis_surat']);
        $this->assertEquals(1, $data['per_jenis_surat'][$namaSurat]);
    }

    public function test_statistik_mutasi_count()
    {
        Cache::forget('statistik_layanan');

        MutasiPenduduk::create(['nik' => $this->warga->nik, 'jenis_mutasi' => 'Kelahiran', 'tanggal_mutasi' => '2024-01-01', 'keterangan' => 'Test', 'dokumen_bukti' => 'akta.pdf']);

        $response = $this->getJson('/api/v1/statistik/layanan');
        $data = $response->json('data');
        $this->assertEquals(1, $data['mutasi_penduduk']['total']);
    }

    public function test_statistik_layanan_total()
    {
        Cache::forget('statistik_layanan');

        PengajuanSurat::create(['nik_pemohon' => $this->warga->nik, 'kategori_surat_id' => $this->kategori->id, 'data_isian' => ['t' => '1'], 'file_syarat' => ['ktp' => 'p.jpg'], 'status' => 'Pending']);

        $response = $this->getJson('/api/v1/statistik/layanan');
        $data = $response->json('data');
        $this->assertEquals(1, $data['pengajuan_surat']['total']);
    }
}
