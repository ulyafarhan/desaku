<?php

namespace App\Services;

use App\Models\ChatbotLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-pro');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    /**
     * Generate response dari Gemini AI
     */
    public function generateResponse(string $userMessage, string $chatId): ?string
    {
        try {
            $systemPrompt = $this->getSystemPrompt();
            
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt],
                            ['text' => "User: {$userMessage}"],
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1024,
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '?key=' . $this->apiKey, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                $tokensUsed = $data['usageMetadata']['totalTokenCount'] ?? 0;

                // Log conversation
                ChatbotLog::create([
                    'telegram_chat_id' => $chatId,
                    'pesan_masuk' => $userMessage,
                    'balasan_ai' => $aiResponse,
                    'tokens_used' => $tokensUsed,
                ]);

                return $aiResponse;
            }

            Log::error('Gemini API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Gemini AI Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * System prompt untuk konteks Gampong Udeung
     */
    protected function getSystemPrompt(): string
    {
        return <<<PROMPT
Anda adalah asisten virtual resmi Gampong Udeung, Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh.

TUGAS ANDA:
1. Menjawab pertanyaan warga tentang prosedur administrasi desa
2. Memberikan informasi tentang persyaratan surat-menyurat
3. Menjelaskan cara menggunakan sistem SIG-Udeung
4. Memberikan informasi umum tentang Gampong Udeung

JENIS SURAT YANG TERSEDIA:
- Surat Keterangan Domisili
- Surat Keterangan Tidak Mampu (SKTM)
- Surat Keterangan Usaha
- Surat Pengantar KTP
- Surat Keterangan Kelahiran

PROSEDUR UMUM:
1. Login ke PWA menggunakan NIK
2. Pilih jenis surat yang dibutuhkan
3. Isi formulir dan upload dokumen persyaratan
4. Tunggu verifikasi dari perangkat desa
5. Surat akan dikirim via Telegram jika disetujui

MUTASI KEPENDUDUKAN:
- Kelahiran: Upload akta kelahiran
- Kematian: Upload surat kematian dari RS/Puskesmas
- Kedatangan: Upload surat pindah dari desa asal
- Kepindahan: Ajukan surat pindah

ATURAN KOMUNIKASI:
- Gunakan bahasa Indonesia yang sopan dan formal
- Jika tidak tahu jawaban, arahkan untuk menghubungi kantor desa
- Jangan memberikan informasi pribadi warga
- Fokus pada informasi prosedural dan administratif

Jawab pertanyaan berikut dengan jelas dan membantu:
PROMPT;
    }

    /**
     * Check API health
     */
    public function checkHealth(): bool
    {
        try {
            $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}?key={$this->apiKey}");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
