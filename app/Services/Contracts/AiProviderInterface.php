<?php

namespace App\Services\Contracts;

/**
 * Kontrak untuk provider AI yang digunakan sistem.
 *
 * Mendefinisikan method yang harus diimplementasikan oleh setiap
 * provider AI (Gemini, OpenAI, dll) untuk respons chatbot,
 * perbaikan copywriting, meta data SEO, dan pengecekan kesehatan.
 */
interface AiProviderInterface
{
    /**
     * Menghasilkan respons AI untuk pesan pengguna.
     */
    public function generateResponse(string $userMessage, string $chatId, ?string $context = null): ?string;

    /**
     * Memperbaiki dan menyempurnakan copywriting artikel berita desa.
     */
    public function fixCopywriting(string $text, ?string $title = null): ?string;

    /**
     * Menghasilkan meta deskripsi dan kata kunci SEO untuk artikel berita.
     */
    public function generateSeoMetadata(string $title, string $content): ?array;

    /**
     * Memeriksa ketersediaan layanan provider AI.
     */
    public function checkHealth(): bool;
}
