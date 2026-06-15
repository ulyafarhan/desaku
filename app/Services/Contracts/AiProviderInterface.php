<?php

namespace App\Services\Contracts;

interface AiProviderInterface
{
    public function generateResponse(string $userMessage, string $chatId, ?string $context = null): ?string;

    public function fixCopywriting(string $text, ?string $title = null): ?string;

    public function generateSeoMetadata(string $title, string $content): ?array;

    public function checkHealth(): bool;
}
