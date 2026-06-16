<?php

namespace App\Services;

use App\Services\Contracts\AiProviderInterface;

/**
 * Service yang membungkus AiProviderInterface untuk berbagai kebutuhan AI.
 *
 * Menyediakan akses terpusat ke fungsionalitas AI (respons chatbot,
 * perbaikan copywriting, dan meta data SEO) tanpa tergantung provider spesifik.
 */
class GeminiAiService
{
    /**
     * Instance provider AI aktif (Gemini/OpenAI).
     */
    protected AiProviderInterface $provider;

    /**
     * Menginisialisasi dengan provider AI dari service container.
     */
    public function __construct()
    {
        $this->provider = app(AiProviderInterface::class);
    }

    /**
     * Menghasilkan respons AI untuk pesan pengguna.
     */
    public function generateResponse(string $userMessage, string $chatId, ?string $context = null): ?string
    {
        return $this->provider->generateResponse($userMessage, $chatId, $context);
    }

    /**
     * Memperbaiki dan menyempurnakan copywriting artikel berita desa.
     */
    public function fixCopywriting(string $text, ?string $title = null): ?string
    {
        return $this->provider->fixCopywriting($text, $title);
    }

    /**
     * Menghasilkan meta deskripsi dan kata kunci SEO untuk artikel berita.
     */
    public function generateSeoMetadata(string $title, string $content): ?array
    {
        return $this->provider->generateSeoMetadata($title, $content);
    }

    /**
     * Memeriksa ketersediaan layanan provider AI.
     */
    public function checkHealth(): bool
    {
        return $this->provider->checkHealth();
    }
}
