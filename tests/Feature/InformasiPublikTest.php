<?php

namespace Tests\Feature;

use App\Models\Administrator;
use App\Models\InformasiPublik;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InformasiPublikTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Administrator::create([
            'username' => 'testadmin',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);
    }

    public function test_can_get_published_informasi()
    {
        InformasiPublik::create([
            'judul' => 'Berita Published',
            'slug' => 'berita-published',
            'konten' => 'Konten berita',
            'kategori' => 'berita',
            'is_published' => true,
            'author_id' => $this->admin->id,
        ]);

        InformasiPublik::create([
            'judul' => 'Berita Draft',
            'slug' => 'berita-draft',
            'konten' => 'Konten berita',
            'kategori' => 'berita',
            'is_published' => false,
            'author_id' => $this->admin->id,
        ]);

        $response = $this->getJson('/api/v1/informasi');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Berita Published', $data[0]['judul']);
    }

    public function test_can_get_informasi_by_slug()
    {
        $informasi = InformasiPublik::create([
            'judul' => 'Test Berita',
            'slug' => 'test-berita',
            'konten' => 'Konten berita',
            'kategori' => 'berita',
            'is_published' => true,
            'author_id' => $this->admin->id,
        ]);

        $response = $this->getJson('/api/v1/informasi/test-berita');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'judul' => 'Test Berita',
                    'slug' => 'test-berita',
                ],
            ]);
    }

    public function test_admin_can_create_informasi()
    {
        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Berita Baru',
                'konten' => 'Konten berita baru',
                'kategori' => 'berita',
                'is_published' => true,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'judul', 'slug'],
            ]);

        $this->assertDatabaseHas('informasi_publik', [
            'judul' => 'Berita Baru',
            'author_id' => $this->admin->id,
        ]);
    }

    public function test_informasi_auto_generates_slug()
    {
        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/informasi', [
                'judul' => 'Berita Dengan Spasi',
                'konten' => 'Konten',
                'kategori' => 'berita',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('informasi_publik', [
            'judul' => 'Berita Dengan Spasi',
            'slug' => 'berita-dengan-spasi',
        ]);
    }

    public function test_admin_can_update_informasi()
    {
        $informasi = InformasiPublik::create([
            'judul' => 'Original Title',
            'slug' => 'original-title',
            'konten' => 'Original content',
            'kategori' => 'berita',
            'is_published' => false,
            'author_id' => $this->admin->id,
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->putJson("/api/v1/admin/informasi/{$informasi->id}", [
                'judul' => 'Updated Title',
                'is_published' => true,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('informasi_publik', [
            'id' => $informasi->id,
            'judul' => 'Updated Title',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_delete_informasi()
    {
        $informasi = InformasiPublik::create([
            'judul' => 'To Delete',
            'slug' => 'to-delete',
            'konten' => 'Content',
            'kategori' => 'berita',
            'author_id' => $this->admin->id,
        ]);

        $token = $this->admin->createToken('test', ['admin'])->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson("/api/v1/admin/informasi/{$informasi->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('informasi_publik', [
            'id' => $informasi->id,
        ]);
    }

    public function test_can_filter_informasi_by_kategori()
    {
        InformasiPublik::create([
            'judul' => 'Berita 1',
            'slug' => 'berita-1',
            'konten' => 'Content',
            'kategori' => 'berita',
            'is_published' => true,
            'author_id' => $this->admin->id,
        ]);

        InformasiPublik::create([
            'judul' => 'Pengumuman 1',
            'slug' => 'pengumuman-1',
            'konten' => 'Content',
            'kategori' => 'pengumuman',
            'is_published' => true,
            'author_id' => $this->admin->id,
        ]);

        $response = $this->getJson('/api/v1/informasi?kategori=berita');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('berita', $data[0]['kategori']);
    }
}
