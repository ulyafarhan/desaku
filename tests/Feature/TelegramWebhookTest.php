<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\TelegramService;
use App\Jobs\ProcessTelegramMessageJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Mockery;

class TelegramWebhookTest extends TestCase
{
    protected $telegramMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->telegramMock = Mockery::mock(TelegramService::class);
        $this->app->instance(TelegramService::class, $this->telegramMock);
        
        Cache::flush();
    }

    public function test_it_handles_start_command_correctly()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $payload = [
            'message' => [
                'chat' => ['id' => '123456789'],
                'text' => '/start'
            ]
        ];

        $response = $this->postJson('/api/v1/telegram/webhook', $payload);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
    }

    public function test_it_handles_bind_command_correctly()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Format: /bind [NIK]');
            }))
            ->andReturn(true);

        $payload = [
            'message' => [
                'chat' => ['id' => '123456789'],
                'text' => '/bind'
            ]
        ];

        $response = $this->postJson('/api/v1/telegram/webhook', $payload);
        $response->assertStatus(200);

        // Bind with correct NIK format
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Untuk menghubungkan akun, silakan buka PWA');
            }))
            ->andReturn(true);

        $payloadCorrect = [
            'message' => [
                'chat' => ['id' => '123456789'],
                'text' => '/bind 1234567890123456'
            ]
        ];

        $responseCorrect = $this->postJson('/api/v1/telegram/webhook', $payloadCorrect);
        $responseCorrect->assertStatus(200);
    }

    public function test_it_matches_local_knowledge_base_greetings()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Selamat datang di Bot SIG-Udeung');
            }))
            ->andReturn(true);

        $payload = [
            'message' => [
                'chat' => ['id' => '123456789'],
                'text' => 'Halo'
            ]
        ];

        $response = $this->postJson('/api/v1/telegram/webhook', $payload);
        $response->assertStatus(200);
    }

    public function test_it_matches_local_sktm_keywords_and_sends_requirements()
    {
        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Surat Keterangan Tidak Mampu') && str_contains($message, 'KK');
            }))
            ->andReturn(true);

        $payload = [
            'message' => [
                'chat' => ['id' => '123456789'],
                'text' => 'bagaimana syarat sktm'
            ]
        ];

        $response = $this->postJson('/api/v1/telegram/webhook', $payload);
        $response->assertStatus(200);
    }

    public function test_it_dispatches_gemini_job_for_unknown_questions_within_rate_limit()
    {
        Queue::fake();

        $payload = [
            'message' => [
                'chat' => ['id' => '123456789'],
                'text' => 'bagaimana cara menanam padi yang baik?'
            ]
        ];

        $response = $this->postJson('/api/v1/telegram/webhook', $payload);
        $response->assertStatus(200);

        Queue::assertPushed(ProcessTelegramMessageJob::class, function ($job) {
            return $job->chatId === '123456789' && $job->text === 'bagaimana cara menanam padi yang baik?';
        });
    }

    public function test_it_enforces_rate_limits_for_ai_questions()
    {
        Queue::fake();

        // Simulate 10 usage counts
        $rateLimitKey = 'telegram_ai_limit_123456789_' . date('Y-m-d');
        Cache::put($rateLimitKey, 10, now()->addDay());

        $this->telegramMock->shouldReceive('sendMessage')
            ->once()
            ->with('123456789', Mockery::on(function ($message) {
                return str_contains($message, 'Batas Pertanyaan AI Habis') || str_contains($message, 'limit');
            }))
            ->andReturn(true);

        $payload = [
            'message' => [
                'chat' => ['id' => '123456789'],
                'text' => 'bagaimana cara menanam padi yang baik?'
            ]
        ];

        $response = $this->postJson('/api/v1/telegram/webhook', $payload);
        $response->assertStatus(200);

        Queue::assertNotPushed(ProcessTelegramMessageJob::class);
    }
}
