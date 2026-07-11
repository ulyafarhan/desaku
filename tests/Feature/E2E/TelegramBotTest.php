<?php

namespace Tests\Feature\E2E;

use Tests\TestCase;
use App\Services\TelegramService;
use App\Services\TelegramKnowledgeService;
use App\Jobs\ProcessTelegramMessageJob;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\ChatbotLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Mockery;

class TelegramBotTest extends TestCase
{
    protected $telegramMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->telegramMock = Mockery::mock(TelegramService::class);
        $this->app->instance(TelegramService::class, $this->telegramMock);

        Cache::flush();
    }

    // === Command Handling ===

    public function test_start_command_returns_welcome()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => '/start']
        ]);

        $response->assertOk()->assertJson(['ok' => true]);
    }

    public function test_start_command_includes_bot_name()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'asisten virtual Gampong Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => '/start']
        ]);

        $response->assertOk();
    }

    public function test_bind_without_nik_shows_format()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Format: /bind [NIK]');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => '/bind']
        ]);

        $response->assertOk();
    }

    public function test_bind_with_nik_shows_pwa_instruction()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Untuk menghubungkan akun, silakan buka PWA');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => '/bind 1234567890123456']
        ]);

        $response->assertOk();
    }

    public function test_bind_with_too_many_args_shows_format()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Format: /bind [NIK]');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => '/bind 123 456']
        ]);

        $response->assertOk();
    }

    public function test_empty_message_ignored()
    {
        $this->telegramMock->shouldNotReceive('sendMessage');

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => '   ']
        ]);

        $response->assertOk();
    }

    // === Knowledge Base - Greetings ===

    public function test_halo_greeting_responds()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'Halo']
        ]);

        $response->assertOk();
    }

    public function test_hai_greeting_responds()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'Hai']
        ]);

        $response->assertOk();
    }

    public function test_assalamualaikum_responds()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'Assalamualaikum']
        ]);

        $response->assertOk();
    }

    public function test_pagi_responds()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'pagi']
        ]);

        $response->assertOk();
    }

    public function test_siang_responds()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'Siang']
        ]);

        $response->assertOk();
    }

    public function test_malam_responds()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'Malam']
        ]);

        $response->assertOk();
    }

    // === Knowledge Base - FAQs ===

    public function test_sktm_question_returns_requirements()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Surat Keterangan Tidak Mampu')
                    && str_contains($message, 'KK');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'bagaimana cara buat sktm?']
        ]);

        $response->assertOk();
    }

    public function test_domisili_question_returns_info()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Surat Keterangan Domisili');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'butuh surat domisili']
        ]);

        $response->assertOk();
    }

    public function test_syarat_question_returns_info()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Surat Pengantar KTP');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'syarat ktp baru']
        ]);

        $response->assertOk();
    }

    public function test_surat_question_returns_category_list()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Surat Keterangan Usaha');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'buat usaha']
        ]);

        $response->assertOk();
    }

    public function test_ktp_question_returns_info()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Surat Pengantar KTP');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'syarat buat ktp']
        ]);

        $response->assertOk();
    }

    public function test_login_question_returns_info()
    {
        Queue::fake();

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'bagaimana cara login']
        ]);

        $response->assertOk();
        Queue::assertPushed(ProcessTelegramMessageJob::class, function ($job) {
            return $job->text === 'bagaimana cara login';
        });
    }

    public function test_profil_question_returns_info()
    {
        Queue::fake();

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'profil gampong udeung']
        ]);

        $response->assertOk();
        Queue::assertPushed(ProcessTelegramMessageJob::class, function ($job) {
            return $job->text === 'profil gampong udeung';
        });
    }

    public function test_keuchik_question_returns_info()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Keuchik') || str_contains($message, 'kontak');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'nomor keuchik']
        ]);

        $response->assertOk();
    }

    public function test_ambil_surat_returns_info()
    {
        Queue::fake();

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'ambil surat']
        ]);

        $response->assertOk();
        Queue::assertPushed(ProcessTelegramMessageJob::class, function ($job) {
            return $job->text === 'ambil surat';
        });
    }

    public function test_ditolak_question_returns_info()
    {
        Queue::fake();

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'kenapa ditolak']
        ]);

        $response->assertOk();
        Queue::assertPushed(ProcessTelegramMessageJob::class, function ($job) {
            return $job->text === 'kenapa ditolak';
        });
    }

    public function test_pindaah_returns_info()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Mutasi') || str_contains($message, 'Pindah');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'cara mutasi keluar']
        ]);

        $response->assertOk();
    }

    // === AI Fallback ===

    public function test_unknown_question_dispatches_ai_job()
    {
        Queue::fake();

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'apa ibukota indonesia?']
        ]);

        $response->assertOk();
        Queue::assertPushed(ProcessTelegramMessageJob::class, function ($job) {
            return $job->chatId === '123456789' && $job->text === 'apa ibukota indonesia?';
        });
    }

    public function test_ai_job_includes_chat_id()
    {
        Queue::fake();

        $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '987654321'], 'text' => 'siapa nama presiden?']
        ]);

        Queue::assertPushed(ProcessTelegramMessageJob::class, function ($job) {
            return $job->chatId === '987654321';
        });
    }

    public function test_ai_job_includes_message_text()
    {
        Queue::fake();

        $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'pertanyaan tersembunyi']
        ]);

        Queue::assertPushed(ProcessTelegramMessageJob::class, function ($job) {
            return $job->text === 'pertanyaan tersembunyi';
        });
    }

    public function test_ai_response_cached_for_2_hours()
    {
        $cacheKey = 'telegram_reply_' . md5('pertanyaan cache');
        Cache::put($cacheKey, 'Jawaban ter-cache', now()->addHours(2));

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', 'Jawaban ter-cache')
            ->andReturn(true);

        Queue::fake();

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => 'pertanyaan cache']
        ]);

        $response->assertOk();
        Queue::assertNotPushed(ProcessTelegramMessageJob::class);
    }

    public function test_cached_response_returned_immediately()
    {
        $cacheKey = 'telegram_reply_' . md5('duplikat');
        Cache::put($cacheKey, 'Balasan dari cache', now()->addHours(2));

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('111', 'Balasan dari cache')
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '111'], 'text' => 'duplikat']
        ]);

        $response->assertOk();
    }

    public function test_same_question_from_different_users_hits_rate_limit_independently()
    {
        Queue::fake();

        $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => 'user_a'], 'text' => 'cuaca hari ini?']
        ]);
        $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => 'user_b'], 'text' => 'cuaca hari ini?']
        ]);

        Queue::assertPushed(ProcessTelegramMessageJob::class, 2);
    }

    // === Rate Limiting ===

    public function test_rate_limit_10_per_day_per_chat()
    {
        Queue::fake();

        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/v1/telegram/webhook', [
                'message' => ['chat' => ['id' => 'rate123'], 'text' => "pertanyaan ke-$i"]
            ]);
        }

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('rate123', Mockery::on(function ($message) {
                return str_contains($message, 'Batas Pertanyaan AI Habis');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => 'rate123'], 'text' => 'pertanyaan ke-11']
        ]);

        $response->assertOk();
        Queue::assertPushed(ProcessTelegramMessageJob::class, 10);
    }

    public function test_rate_limit_resets_after_24_hours()
    {
        $rateLimitKey = 'telegram_ai_limit_rate_reset_' . date('Y-m-d');
        Cache::put($rateLimitKey, 10, now()->addDay());

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('rate_reset', Mockery::on(function ($message) {
                return str_contains($message, 'Batas Pertanyaan AI Habis');
            }))
            ->andReturn(true);

        Queue::fake();

        $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => 'rate_reset'], 'text' => 'test question']
        ]);

        Queue::assertNotPushed(ProcessTelegramMessageJob::class);
    }

    public function test_rate_limit_message_sent_when_exceeded()
    {
        $rateLimitKey = 'telegram_ai_limit_exceed_chat_' . date('Y-m-d');
        Cache::put($rateLimitKey, 10, now()->addDay());

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('exceed_chat', Mockery::on(function ($message) {
                return str_contains($message, 'Batas Pertanyaan AI Habis');
            }))
            ->andReturn(true);

        Queue::fake();

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => 'exceed_chat'], 'text' => 'apakah saya bisa bertanya?']
        ]);

        $response->assertOk();
        Queue::assertNotPushed(ProcessTelegramMessageJob::class);
    }

    public function test_rate_limit_prevents_ai_job_dispatch()
    {
        $rateLimitKey = 'telegram_ai_limit_no_dispatch_' . date('Y-m-d');
        Cache::put($rateLimitKey, 10, now()->addDay());

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->andReturn(true);

        Queue::fake();

        $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => 'no_dispatch'], 'text' => 'cuaca bagaimana?']
        ]);

        Queue::assertNotPushed(ProcessTelegramMessageJob::class);
    }

    // === Error Handling ===

    public function test_callback_query_returns_ok()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Callback received');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'callback_query' => [
                'id' => 'callback_id',
                'message' => ['chat' => ['id' => '123456789']],
                'data' => 'button_click'
            ]
        ]);

        $response->assertOk()->assertJson(['ok' => true]);
    }

    public function test_webhook_returns_ok_json()
    {
        $this->telegramMock->shouldNotReceive('sendMessage');

        $response = $this->postJson('/api/v1/telegram/webhook', []);

        $response->assertOk()->assertJson(['ok' => true]);
    }

    public function test_non_message_update_handled()
    {
        $this->telegramMock->shouldNotReceive('sendMessage');

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'my_chat_member' => ['chat' => ['id' => '123'], 'new_chat_member' => ['status' => 'member']]
        ]);

        $response->assertOk();
    }

    public function test_telegram_api_error_handled_gracefully()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::any())
            ->andReturn(false);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => '123456789'], 'text' => '/start']
        ]);

        $response->assertOk();
    }

    // === User Context ===

    public function test_bound_user_message_matched_to_nik()
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test',
            'dusun' => 'Test',
            'rt_rw' => '001/002',
        ]);

        Penduduk::create([
            'nik' => '1234567890123456',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'Bond User',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Petani',
            'status_perkawinan' => 'Kawin',
            'status_keluarga' => 'Kepala Keluarga',
            'telegram_chat_id' => 'bound_chat',
        ]);

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('bound_chat', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => 'bound_chat'], 'text' => '/start']
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('penduduk', [
            'nik' => '1234567890123456',
            'telegram_chat_id' => 'bound_chat',
        ]);
    }

    public function test_unbound_user_can_still_ask_questions()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('unbound_chat', Mockery::on(function ($message) {
                return str_contains($message, 'Surat Keterangan Tidak Mampu');
            }))
            ->andReturn(true);

        $response = $this->postJson('/api/v1/telegram/webhook', [
            'message' => ['chat' => ['id' => 'unbound_chat'], 'text' => 'sktm']
        ]);

        $response->assertOk();
    }

    public function test_same_user_multiple_messages_within_rate_limit()
    {
        Queue::fake();

        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/v1/telegram/webhook', [
                'message' => ['chat' => ['id' => 'multi_msg'], 'text' => "random question $i"]
            ]);
        }

        Queue::assertPushed(ProcessTelegramMessageJob::class, 3);
    }
}
