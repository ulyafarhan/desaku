<?php

namespace App\Services;

use App\Services\Contracts\AiProviderInterface;

class GeminiAiService
{
    protected AiProviderInterface $provider;

    public function __construct()
    {
        $this->provider = app(AiProviderInterface::class);
    }

    public function generateResponse(string $userMessage, string $chatId, ?string $context = null): ?string
    {
        return $this->provider->generateResponse($userMessage, $chatId, $context);
    }

    public function fixCopywriting(string $text, ?string $title = null): ?string
    {
        return $this->provider->fixCopywriting($text, $title);
    }

    public function generateSeoMetadata(string $title, string $content): ?array
    {
        return $this->provider->generateSeoMetadata($title, $content);
    }

    public function checkHealth(): bool
    {
        return $this->provider->checkHealth();
    }
}
