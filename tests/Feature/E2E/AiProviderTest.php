<?php

namespace Tests\Feature\E2E;

use Tests\TestCase;
use App\Services\FallbackAiService;
use App\Services\AiProviders\GeminiProvider;
use App\Services\AiProviders\OpenAiProvider;
use App\Services\Contracts\AiProviderInterface;
use App\Models\PengaturanGampong;
use App\Models\ChatbotLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiProviderTest extends TestCase
{
    public function test_ai_provider_resolved_from_container()
    {
        $ai = app(AiProviderInterface::class);

        $this->assertInstanceOf(FallbackAiService::class, $ai);
        $this->assertInstanceOf(AiProviderInterface::class, $ai);
    }

    public function test_fallback_service_implements_interface()
    {
        $service = new FallbackAiService();

        $this->assertInstanceOf(AiProviderInterface::class, $service);
        $this->assertTrue(method_exists($service, 'generateResponse'));
        $this->assertTrue(method_exists($service, 'fixCopywriting'));
        $this->assertTrue(method_exists($service, 'generateSeoMetadata'));
        $this->assertTrue(method_exists($service, 'checkHealth'));
    }

    public function test_gemini_provider_instantiated_for_gemini_type()
    {
        $service = new FallbackAiService();
        $reflection = new \ReflectionMethod($service, 'resolveProviderInstance');
        $reflection->setAccessible(true);

        $provider = $reflection->invoke($service, 'gemini');

        $this->assertInstanceOf(GeminiProvider::class, $provider);
        $this->assertInstanceOf(AiProviderInterface::class, $provider);
    }

    public function test_openai_provider_instantiated_for_openai_type()
    {
        $service = new FallbackAiService();
        $reflection = new \ReflectionMethod($service, 'resolveProviderInstance');
        $reflection->setAccessible(true);

        $provider = $reflection->invoke($service, 'openai');

        $this->assertInstanceOf(OpenAiProvider::class, $provider);
        $this->assertInstanceOf(AiProviderInterface::class, $provider);
    }

    public function test_unknown_provider_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown AI provider type: unknown_provider');

        $service = new FallbackAiService();
        $reflection = new \ReflectionMethod($service, 'resolveProviderInstance');
        $reflection->setAccessible(true);

        $reflection->invoke($service, 'unknown_provider');
    }

    public function test_fallback_tries_second_provider_on_first_failure()
    {
        PengaturanGampong::set('ai_providers_list', [
            [
                'name' => 'Provider 1 (Fail)',
                'provider_type' => 'openai',
                'api_key' => 'invalid-key',
                'model' => 'gpt-4o-mini',
                'base_url' => 'https://api.openai.com/v1',
                'priority' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Provider 2 (Success)',
                'provider_type' => 'openai',
                'api_key' => 'valid-key',
                'model' => 'gpt-4o-mini',
                'base_url' => 'https://api.openai.com/v1',
                'priority' => 2,
                'is_active' => true,
            ],
        ], 'json');

        $callCount = 0;
        Http::fake([
            'api.openai.com/*' => function ($request) use (&$callCount) {
                $callCount++;
                if ($callCount === 1) {
                    return Http::response(['error' => 'Unauthorized'], 401);
                }
                return Http::response([
                    'choices' => [['message' => ['content' => 'Success response']]],
                    'usage' => ['total_tokens' => 10],
                ], 200);
            },
        ]);

        $ai = app(AiProviderInterface::class);
        $result = $ai->generateResponse('test message', 'chat-1');

        $this->assertEquals('Success response', $result);
        $this->assertEquals(2, $callCount);
    }

    public function test_fallback_returns_first_successful_response()
    {
        PengaturanGampong::set('ai_providers_list', [
            [
                'name' => 'Provider 1 (Success)',
                'provider_type' => 'openai',
                'api_key' => 'key-1',
                'model' => 'gpt-4o-mini',
                'base_url' => 'https://api.openai.com/v1',
                'priority' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Provider 2 (Should Not Run)',
                'provider_type' => 'openai',
                'api_key' => 'key-2',
                'model' => 'gpt-4o-mini',
                'base_url' => 'https://api.openai.com/v1',
                'priority' => 2,
                'is_active' => true,
            ],
        ], 'json');

        $callCount = 0;
        Http::fake([
            'api.openai.com/*' => function ($request) use (&$callCount) {
                $callCount++;
                return Http::response([
                    'choices' => [['message' => ['content' => "Response from provider {$callCount}"]],
                ]], 200);
            },
        ]);

        $ai = app(AiProviderInterface::class);
        $result = $ai->generateResponse('hello', 'chat-1');

        $this->assertEquals('Response from provider 1', $result);
        $this->assertEquals(1, $callCount);
    }

    public function test_fallback_throws_when_all_providers_fail()
    {
        PengaturanGampong::set('ai_providers_list', [
            [
                'name' => 'Provider 1',
                'provider_type' => 'openai',
                'api_key' => 'key-1',
                'model' => 'gpt-4o-mini',
                'base_url' => 'https://api.openai.com/v1',
                'priority' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Provider 2',
                'provider_type' => 'openai',
                'api_key' => 'key-2',
                'model' => 'gpt-4o-mini',
                'base_url' => 'https://api.openai.com/v1',
                'priority' => 2,
                'is_active' => true,
            ],
        ], 'json');

        Http::fake([
            'api.openai.com/*' => Http::response(['error' => 'Server Error'], 500),
        ]);

        $ai = app(AiProviderInterface::class);
        $result = $ai->generateResponse('hello', 'chat-1');

        $this->assertNull($result);
    }

    public function test_fallback_null_response_triggers_next_provider()
    {
        PengaturanGampong::set('ai_providers_list', [
            [
                'name' => 'Provider 1 (Null)',
                'provider_type' => 'openai',
                'api_key' => 'key-1',
                'model' => 'gpt-4o-mini',
                'base_url' => 'https://api.openai.com/v1',
                'priority' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Provider 2 (Fallback)',
                'provider_type' => 'openai',
                'api_key' => 'key-2',
                'model' => 'gpt-4o-mini',
                'base_url' => 'https://api.openai.com/v1',
                'priority' => 2,
                'is_active' => true,
            ],
        ], 'json');

        $callCount = 0;
        Http::fake([
            'api.openai.com/*' => function ($request) use (&$callCount) {
                $callCount++;
                if ($callCount === 1) {
                    return Http::response([
                        'choices' => [['message' => ['content' => null]]],
                    ], 200);
                }
                return Http::response([
                    'choices' => [['message' => ['content' => 'Fallback response']]],
                    'usage' => ['total_tokens' => 5],
                ], 200);
            },
        ]);

        $ai = app(AiProviderInterface::class);
        $result = $ai->generateResponse('hello', 'chat-1');

        $this->assertEquals('Fallback response', $result);
        $this->assertEquals(2, $callCount);
    }

    public function test_gemini_provider_respects_base_url_config()
    {
        config(['services.gemini.api_key' => 'test-key']);
        config(['services.gemini.model' => 'gemini-pro']);
        config(['services.gemini.base_url' => 'https://custom-gemini.example.com/v1beta']);

        Http::fake([
            'custom-gemini.example.com/*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => 'OK']]]]],
            ], 200),
        ]);

        $provider = new GeminiProvider();
        $result = $provider->generateResponse('test', 'chat-1');

        $this->assertEquals('OK', $result);
    }

    public function test_openai_provider_respects_base_url_config()
    {
        config(['services.ai.openai.api_key' => 'test-key']);
        config(['services.ai.openai.model' => 'gpt-4o-mini']);
        config(['services.ai.openai.base_url' => 'https://custom-openai.example.com']);

        Http::fake([
            'custom-openai.example.com/*' => Http::response([
                'choices' => [['message' => ['content' => 'OK']]],
                'usage' => ['total_tokens' => 5],
            ], 200),
        ]);

        $provider = new OpenAiProvider();
        $result = $provider->generateResponse('test', 'chat-1');

        $this->assertEquals('OK', $result);
    }

    public function test_apply_provider_config_sets_gemini_key()
    {
        $service = new FallbackAiService();
        $reflection = new \ReflectionMethod($service, 'applyProviderConfig');
        $reflection->setAccessible(true);

        $reflection->invoke($service, [
            'provider_type' => 'gemini',
            'api_key' => 'gemini-secret-key',
            'model' => 'gemini-pro',
        ]);

        $this->assertEquals('gemini-secret-key', config('services.gemini.api_key'));
        $this->assertEquals('gemini-pro', config('services.gemini.model'));
    }

    public function test_apply_provider_config_sets_openai_key()
    {
        $service = new FallbackAiService();
        $reflection = new \ReflectionMethod($service, 'applyProviderConfig');
        $reflection->setAccessible(true);

        $reflection->invoke($service, [
            'provider_type' => 'openai',
            'api_key' => 'openai-secret-key',
            'model' => 'gpt-4o',
        ]);

        $this->assertEquals('openai-secret-key', config('services.ai.openai.api_key'));
        $this->assertEquals('gpt-4o', config('services.ai.openai.model'));
    }

    public function test_apply_provider_config_sets_openai_base_url()
    {
        $service = new FallbackAiService();
        $reflection = new \ReflectionMethod($service, 'applyProviderConfig');
        $reflection->setAccessible(true);

        $reflection->invoke($service, [
            'provider_type' => 'openai',
            'base_url' => 'https://custom.example.com/v1',
        ]);

        $this->assertEquals('https://custom.example.com/v1', config('services.ai.openai.base_url'));
    }

    public function test_apply_provider_config_sets_gemini_base_url()
    {
        $service = new FallbackAiService();
        $reflection = new \ReflectionMethod($service, 'applyProviderConfig');
        $reflection->setAccessible(true);

        $reflection->invoke($service, [
            'provider_type' => 'gemini',
            'base_url' => 'https://custom-gemini.example.com/v1',
        ]);

        $this->assertEquals('https://custom-gemini.example.com/v1', config('services.gemini.base_url'));
    }

    public function test_health_check_returns_true_for_valid_provider()
    {
        config(['services.gemini.api_key' => 'test-key']);
        config(['services.gemini.base_url' => 'https://generativelanguage.googleapis.com/v1beta']);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(['state' => 'ACTIVE'], 200),
        ]);

        $provider = new GeminiProvider();
        $this->assertTrue($provider->checkHealth());
    }

    public function test_health_check_returns_false_for_invalid_provider()
    {
        config(['services.gemini.api_key' => 'bad-key']);
        config(['services.gemini.base_url' => 'https://generativelanguage.googleapis.com/v1beta']);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(['error' => 'API key not valid'], 403),
        ]);

        $provider = new GeminiProvider();
        $this->assertFalse($provider->checkHealth());
    }

    public function test_api_key_empty_returns_null()
    {
        config(['services.ai.openai.api_key' => '']);

        $provider = new OpenAiProvider();
        $result = $provider->generateResponse('test', 'chat-1');

        $this->assertNull($result);
    }

    public function test_ai_response_logged_in_chatbot_logs()
    {
        config(['services.gemini.api_key' => 'test-key']);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => 'AI response']]]]],
                'usageMetadata' => ['totalTokenCount' => 42],
            ], 200),
        ]);

        $provider = new GeminiProvider();
        $result = $provider->generateResponse('hello', 'chat-123');

        $this->assertEquals('AI response', $result);
        $this->assertDatabaseHas('chatbot_logs', [
            'telegram_chat_id' => 'chat-123',
            'pesan_masuk' => 'hello',
            'balasan_ai' => 'AI response',
            'tokens_used' => 42,
        ]);
    }

    public function test_ai_semantic_cache_hits_prevent_api_call()
    {
        config(['services.gemini.api_key' => 'test-key']);

        ChatbotLog::create([
            'telegram_chat_id' => 'chat-1',
            'pesan_masuk' => 'Apa itu surat domisili?',
            'balasan_ai' => 'Cached response',
            'tokens_used' => 50,
            'created_at' => now()->subHour(),
        ]);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([], 500),
        ]);

        $provider = new GeminiProvider();
        $result = $provider->generateResponse('Apa itu surat domisili?', 'chat-1');

        $this->assertEquals('Cached response', $result);
    }
}
