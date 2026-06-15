<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Contracts\AiProviderInterface;
use App\Services\AiProviders\GeminiProvider;
use App\Services\AiProviders\OpenAiProvider;
use Illuminate\Support\Facades\Http;

class AiServiceTest extends TestCase
{
    public function test_it_resolves_gemini_provider_by_default()
    {
        config(['services.ai.active_provider' => 'gemini']);
        
        $ai = app(AiProviderInterface::class);
        
        $this->assertInstanceOf(GeminiProvider::class, $ai);
    }

    public function test_it_resolves_openai_provider_when_configured()
    {
        config(['services.ai.active_provider' => 'openai']);
        
        $ai = app(AiProviderInterface::class);
        
        $this->assertInstanceOf(OpenAiProvider::class, $ai);
    }

    public function test_gemini_provider_can_generate_seo_metadata_successfully()
    {
        config(['services.gemini.api_key' => 'test-key']);
        config(['services.gemini.model' => 'gemini-pro']);
        
        $provider = new GeminiProvider();

        $responsePayload = [
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => '{"meta_description": "Deskripsi meta untuk artikel gampong.", "kata_kunci": "gampong, desa, pembangunan"}']
                        ]
                    ]
                ]
            ]
        ];

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response($responsePayload, 200)
        ]);

        $result = $provider->generateSeoMetadata('Judul Artikel', 'Konten artikel panjang.');

        $this->assertIsArray($result);
        $this->assertEquals('Deskripsi meta untuk artikel gampong.', $result['meta_description']);
        $this->assertEquals('gampong, desa, pembangunan', $result['kata_kunci']);
    }

    public function test_openai_provider_can_generate_seo_metadata_successfully()
    {
        config(['services.ai.openai.api_key' => 'test-key']);
        config(['services.ai.openai.model' => 'gpt-4o-mini']);
        config(['services.ai.openai.base_url' => 'https://api.openai.com/v1']);
        
        $provider = new OpenAiProvider();

        $responsePayload = [
            'choices' => [
                [
                    'message' => [
                        'content' => '{"meta_description": "Deskripsi meta OpenAI.", "kata_kunci": "gampong, desa"}'
                    ]
                ]
            ]
        ];

        Http::fake([
            'api.openai.com/*' => Http::response($responsePayload, 200)
        ]);

        $result = $provider->generateSeoMetadata('Judul Artikel', 'Konten artikel.');

        $this->assertIsArray($result);
        $this->assertEquals('Deskripsi meta OpenAI.', $result['meta_description']);
        $this->assertEquals('gampong, desa', $result['kata_kunci']);
    }

    public function test_openai_provider_generates_copywriting_when_empty()
    {
        config(['services.ai.openai.api_key' => 'test-key']);
        config(['services.ai.openai.base_url' => 'https://api.openai.com/v1']);
        $provider = new OpenAiProvider();

        $responsePayload = [
            'choices' => [
                [
                    'message' => [
                        'content' => '<p>Ini artikel hasil generate dari judul.</p>'
                    ]
                ]
            ]
        ];

        Http::fake([
            'api.openai.com/*' => Http::response($responsePayload, 200)
        ]);

        $result = $provider->fixCopywriting('', 'Judul Baru Gampong');

        $this->assertEquals('<p>Ini artikel hasil generate dari judul.</p>', $result);
    }

    public function test_openai_provider_fixes_copywriting_when_not_empty()
    {
        config(['services.ai.openai.api_key' => 'test-key']);
        config(['services.ai.openai.base_url' => 'https://api.openai.com/v1']);
        $provider = new OpenAiProvider();

        $responsePayload = [
            'choices' => [
                [
                    'message' => [
                        'content' => '<p>Hasil perbaikan teks asli.</p>'
                    ]
                ]
            ]
        ];

        Http::fake([
            'api.openai.com/*' => Http::response($responsePayload, 200)
        ]);

        $result = $provider->fixCopywriting('<p>Teks asli yang salah ejaan.</p>', 'Judul');

        $this->assertEquals('<p>Hasil perbaikan teks asli.</p>', $result);
    }
}

