<?php

namespace App\Services;

use App\Services\Contracts\AiProviderInterface;
use App\Services\AiProviders\GeminiProvider;
use App\Services\AiProviders\OpenAiProvider;
use App\Models\PengaturanGampong;
use Illuminate\Support\Facades\Log;

/**
 * Layanan untuk mengelola beberapa provider AI dengan logika prioritas dan fallback.
 */
class FallbackAiService implements AiProviderInterface
{
    /**
     * Mengeksekusi aksi AI menggunakan daftar provider yang diurutkan berdasarkan prioritas.
     *
     * @param callable $callback
     * @return mixed
     * @throws \Exception
     */
    protected function runWithFallback(callable $callback)
    {
        // 1. Ambil daftar provider dari database (pengaturan_gampong -> ai_providers_list)
        $providersListJson = PengaturanGampong::get('ai_providers_list');
        $providers = [];

        if ($providersListJson && is_array($providersListJson)) {
            // Urutkan berdasarkan prioritas (angka lebih kecil = prioritas lebih tinggi)
            usort($providersListJson, function ($a, $b) {
                $pA = isset($a['priority']) ? (int) $a['priority'] : 1;
                $pB = isset($b['priority']) ? (int) $b['priority'] : 1;
                return $pA <=> $pB;
            });

            foreach ($providersListJson as $item) {
                if (isset($item['is_active']) && !$item['is_active']) {
                    continue;
                }
                $providers[] = $item;
            }
        }

        // 2. Jika daftar di database kosong, gunakan konfigurasi lama (kompatibilitas mundur)
        if (empty($providers)) {
            $activeProvider = config('services.ai.active_provider', 'gemini');
            if ($activeProvider === 'openai') {
                $providers[] = [
                    'provider_type' => 'openai',
                    'api_key' => config('services.ai.openai.api_key'),
                    'model' => config('services.ai.openai.model', 'gpt-4o-mini'),
                    'base_url' => config('services.ai.openai.base_url', 'https://api.openai.com/v1'),
                ];
            } else {
                $providers[] = [
                    'provider_type' => 'gemini',
                    'api_key' => config('services.gemini.api_key'),
                    'model' => config('services.gemini.model', 'gemini-pro'),
                ];
            }
        }

        $lastException = null;

        // 3. Iterasi setiap provider dan coba eksekusi
        foreach ($providers as $prov) {
            try {
                // Terapkan konfigurasi ke konfigurasi runtime
                $this->applyProviderConfig($prov);

                // Instansiasi provider yang sesuai
                $providerInstance = $this->resolveProviderInstance($prov['provider_type']);

                // Eksekusi callback
                $response = $callback($providerInstance);

                // Jika respons null, anggap sebagai kegagalan untuk memicu fallback
                if ($response === null) {
                    throw new \Exception("Provider " . $prov['provider_type'] . " mengembalikan respons null.");
                }

                return $response;
            } catch (\Throwable $e) {
                $providerName = $prov['name'] ?? $prov['provider_type'];
                Log::warning("AI Provider Fallback: Provider '{$providerName}' gagal. Error: " . $e->getMessage());
                $lastException = $e;
                continue;
            }
        }

        // Jika semua provider gagal, lemparkan exception
        if ($lastException) {
            throw new \Exception("Semua AI Provider gagal: " . $lastException->getMessage());
        }

        return null;
    }

    /**
     * Menerapkan pengaturan konfigurasi ke konfigurasi runtime Laravel.
     */
    protected function applyProviderConfig(array $prov): void
    {
        if ($prov['provider_type'] === 'openai') {
            config([
                'services.ai.openai.api_key' => $prov['api_key'] ?? '',
                'services.ai.openai.model' => $prov['model'] ?? 'gpt-4o-mini',
                'services.ai.openai.base_url' => $prov['base_url'] ?? 'https://api.openai.com/v1',
            ]);
        } else {
            config([
                'services.gemini.api_key' => $prov['api_key'] ?? '',
                'services.gemini.model' => $prov['model'] ?? 'gemini-pro',
            ]);
        }
    }

    /**
     * Menyelesaikan instance provider konkret yang sebenarnya.
     */
    protected function resolveProviderInstance(string $type): AiProviderInterface
    {
        if ($type === 'openai') {
            return new OpenAiProvider();
        }
        return new GeminiProvider();
    }

    /**
     * Menghasilkan respons untuk pesan pengguna.
     */
    public function generateResponse(string $userMessage, string $chatId, ?string $context = null): ?string
    {
        return $this->runWithFallback(function (AiProviderInterface $provider) use ($userMessage, $chatId, $context) {
            return $provider->generateResponse($userMessage, $chatId, $context);
        });
    }

    /**
     * Memperbaiki dan mengoptimalkan copywriting artikel.
     */
    public function fixCopywriting(string $text, ?string $title = null): ?string
    {
        return $this->runWithFallback(function (AiProviderInterface $provider) use ($text, $title) {
            return $provider->fixCopywriting($text, $title);
        });
    }

    /**
     * Menghasilkan metadata SEO untuk artikel.
     */
    public function generateSeoMetadata(string $title, string $content): ?array
    {
        return $this->runWithFallback(function (AiProviderInterface $provider) use ($title, $content) {
            return $provider->generateSeoMetadata($title, $content);
        });
    }

    /**
     * Memeriksa kesehatan penyedia AI.
     */
    public function checkHealth(): bool
    {
        try {
            $result = $this->runWithFallback(function (AiProviderInterface $provider) {
                return $provider->checkHealth() ? true : null;
            });
            return (bool)$result;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
