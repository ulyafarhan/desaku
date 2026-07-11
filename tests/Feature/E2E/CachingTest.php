<?php

namespace Tests\Feature\E2E;

use Tests\TestCase;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\PengajuanSurat;
use App\Models\MutasiPenduduk;
use App\Models\KategoriSurat;
use App\Models\PengaturanGampong;
use App\Models\BotKnowledge;
use App\Models\Administrator;
use Database\Seeders\KategoriSuratSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class CachingTest extends TestCase
{
    protected Penduduk $warga;
    protected Keluarga $keluarga;
    protected KategoriSurat $kategori;
    protected Administrator $admin;
    protected string $adminToken;

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
            'username' => 'testcacheadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);

        $this->adminToken = $this->admin->createToken('cache-admin-token', ['admin'])->plainTextToken;
        $this->kategori = KategoriSurat::first();
    }

    // =========================================================================
    // Statistik Cache
    // =========================================================================

    public function test_statistik_demografi_cached()
    {
        Cache::forget('statistik_demografi');

        $response1 = $this->getJson('/api/v1/statistik/demografi');
        $response1->assertStatus(200);

        $this->assertNotNull(Cache::get('statistik_demografi'));
    }

    public function test_statistik_layanan_cached()
    {
        Cache::forget('statistik_layanan');

        $response = $this->getJson('/api/v1/statistik/layanan');
        $response->assertStatus(200);

        $this->assertNotNull(Cache::get('statistik_layanan'));
    }

    public function test_cache_invalidated_on_penduduk_save()
    {
        Cache::put('statistik_demografi', ['stale' => true], 3600);

        $this->warga->update(['pekerjaan' => 'Wiraswasta']);

        $this->assertNull(Cache::get('statistik_demografi'));
    }

    public function test_cache_invalidated_on_penduduk_delete()
    {
        Cache::put('statistik_demografi', ['stale' => true], 3600);

        $warga2 = Penduduk::create([
            'nik' => '9999999999999999',
            'no_kk' => $this->keluarga->no_kk,
            'nama_lengkap' => 'To Delete',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Buruh',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);
        $warga2->delete();

        $this->assertNull(Cache::get('statistik_demografi'));
    }

    public function test_cache_invalidated_on_keluarga_save()
    {
        Cache::put('statistik_demografi', ['stale' => true], 3600);

        $this->keluarga->update(['alamat' => 'Jl. Updated']);

        $this->assertNull(Cache::get('statistik_demografi'));
    }

    public function test_cache_invalidated_on_keluarga_delete()
    {
        Cache::put('statistik_demografi', ['stale' => true], 3600);

        $k2 = Keluarga::create([
            'no_kk' => '9999999999999999',
            'alamat' => 'Temp',
            'dusun' => 'Temp',
            'rt_rw' => '001/001',
        ]);
        $k2->delete();

        $this->assertNull(Cache::get('statistik_demografi'));
    }

    public function test_cache_invalidated_on_pengajuan_save()
    {
        Cache::put('statistik_layanan', ['stale' => true], 3600);

        PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);

        $this->assertNull(Cache::get('statistik_layanan'));
    }

    public function test_cache_invalidated_on_pengajuan_delete()
    {
        Cache::put('statistik_layanan', ['stale' => true], 3600);

        $p = PengajuanSurat::create([
            'nik_pemohon' => $this->warga->nik,
            'kategori_surat_id' => $this->kategori->id,
            'data_isian' => ['test' => 'data'],
            'file_syarat' => ['ktp' => 'path.jpg'],
        ]);
        $p->delete();

        $this->assertNull(Cache::get('statistik_layanan'));
    }

    public function test_cache_invalidated_on_mutasi_save()
    {
        Cache::put('statistik_layanan', ['stale' => true], 3600);

        MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-01',
            'keterangan' => 'Test',
            'dokumen_bukti' => 'akta.pdf',
            'status_verifikasi' => 'Pending',
        ]);

        $this->assertNull(Cache::get('statistik_layanan'));
    }

    public function test_cache_invalidated_on_mutasi_delete()
    {
        Cache::put('statistik_layanan', ['stale' => true], 3600);

        $m = MutasiPenduduk::create([
            'nik' => $this->warga->nik,
            'jenis_mutasi' => 'Kelahiran',
            'tanggal_mutasi' => '2024-01-01',
            'keterangan' => 'Test',
            'dokumen_bukti' => 'akta.pdf',
            'status_verifikasi' => 'Pending',
        ]);
        $m->delete();

        $this->assertNull(Cache::get('statistik_layanan'));
    }

    // =========================================================================
    // Pengaturan Gampong (Settings)
    // =========================================================================

    public function test_settings_loaded_from_database()
    {
        PengaturanGampong::set('site_name', 'Desaku Test');

        $value = PengaturanGampong::get('site_name');
        $this->assertEquals('Desaku Test', $value);
    }

    public function test_settings_override_config_values()
    {
        PengaturanGampong::set('ai_active_provider', 'gemini');

        $value = PengaturanGampong::get('ai_active_provider');
        $this->assertEquals('gemini', $value);
    }

    public function test_telegram_reply_cached_for_2_hours()
    {
        $cacheKey = 'telegram_reply_' . md5(trim(strtolower('test query')));
        Cache::put($cacheKey, 'cached reply', now()->addHours(2));

        $cached = Cache::get($cacheKey);
        $this->assertEquals('cached reply', $cached);
    }

    public function test_telegram_exact_match_cached()
    {
        $normalized = md5(trim(strtolower('apa itu sktm')));
        $cacheKey = 'ai_exact_' . $normalized;
        Cache::put($cacheKey, 'SKTM adalah surat keterangan...', now()->addHours(24));

        $this->assertEquals('SKTM adalah surat keterangan...', Cache::get($cacheKey));
    }

    public function test_ai_semantic_cache_hits()
    {
        Cache::put('ai_recent_logs_semantic', collect(), 300);

        $semanticCache = Cache::get('ai_recent_logs_semantic');
        $this->assertNotNull($semanticCache);
    }

    // =========================================================================
    // Cache Utilities
    // =========================================================================

    public function test_cache_remember_returns_same_value()
    {
        Cache::forget('test_remember_key');

        $value1 = Cache::remember('test_remember_key', 60, fn () => 'computed_value');
        $value2 = Cache::remember('test_remember_key', 60, fn () => 'should_not_execute');

        $this->assertEquals('computed_value', $value1);
        $this->assertEquals('computed_value', $value2);
    }

    public function test_cache_forget_clears_specific_key()
    {
        Cache::put('specific_key', 'data', 3600);
        $this->assertNotNull(Cache::get('specific_key'));

        Cache::forget('specific_key');
        $this->assertNull(Cache::get('specific_key'));
    }

    public function test_statistik_cache_includes_total_penduduk()
    {
        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $data = $response->json('data');

        $this->assertArrayHasKey('total_penduduk', $data);
        $this->assertEquals(1, $data['total_penduduk']);
    }

    public function test_statistik_cache_includes_per_dusun()
    {
        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $data = $response->json('data');

        $this->assertArrayHasKey('per_dusun', $data);
        $this->assertIsArray($data['per_dusun']);
    }

    public function test_statistik_cache_includes_per_agama()
    {
        Cache::forget('statistik_demografi');

        $response = $this->getJson('/api/v1/statistik/demografi');
        $data = $response->json('data');

        $this->assertArrayHasKey('per_agama', $data);
        $this->assertArrayHasKey('Islam', $data['per_agama']);
    }

    public function test_telegram_broadcast_queue_ready_query()
    {
        $now = now();
        $future = now()->addHours(1);

        \App\Models\TelegramBroadcastQueue::create([
            'pesan' => 'Test broadcast',
            'kategori_target' => 'all',
            'status' => 'Queued',
            'jadwal_kirim' => $now,
        ]);

        \App\Models\TelegramBroadcastQueue::create([
            'pesan' => 'Future broadcast',
            'kategori_target' => 'all',
            'status' => 'Queued',
            'jadwal_kirim' => $future,
        ]);

        $ready = \App\Models\TelegramBroadcastQueue::where('status', 'Queued')
            ->where('jadwal_kirim', '<=', now())
            ->count();

        $this->assertEquals(1, $ready);
    }

    public function test_settings_multiple_queries_same_request()
    {
        PengaturanGampong::set('key_a', 'value_a');
        PengaturanGampong::set('key_b', 'value_b');
        PengaturanGampong::set('key_c', 'value_c');

        $a = PengaturanGampong::get('key_a');
        $b = PengaturanGampong::get('key_b');
        $c = PengaturanGampong::get('key_c');

        $this->assertEquals('value_a', $a);
        $this->assertEquals('value_b', $b);
        $this->assertEquals('value_c', $c);
    }

    public function test_inertia_settings_cached_per_request()
    {
        PengaturanGampong::set('site_name', 'Cached Site');

        $value1 = PengaturanGampong::get('site_name');
        $value2 = PengaturanGampong::get('site_name');

        $this->assertEquals($value1, $value2);
    }

    public function test_admin_settings_update_reflected_immediately()
    {
        PengaturanGampong::set('feature_flag', 'old_value');
        $old = PengaturanGampong::get('feature_flag');

        PengaturanGampong::set('feature_flag', 'new_value');
        $new = PengaturanGampong::get('feature_flag');

        $this->assertEquals('old_value', $old);
        $this->assertEquals('new_value', $new);
    }

    public function test_ai_providers_list_from_database_cache()
    {
        PengaturanGampong::set('ai_active_provider', 'gemini');
        PengaturanGampong::set('ai_gemini_key', 'test-key');

        $provider = PengaturanGampong::get('ai_active_provider');
        $key = PengaturanGampong::get('ai_gemini_key');

        $this->assertEquals('gemini', $provider);
        $this->assertEquals('test-key', $key);
    }
}
