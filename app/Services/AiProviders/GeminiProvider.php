<?php

namespace App\Services\AiProviders;

use App\Services\Contracts\AiProviderInterface;
use App\Models\ChatbotLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Provider AI untuk layanan Gemini API Google.
 *
 * Mengimplementasikan AiProviderInterface untuk menghasilkan respons chatbot,
 * memperbaiki copywriting artikel, dan menghasilkan meta data SEO.
 */
class GeminiProvider implements AiProviderInterface
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $model;

    /**
     * Menginisialisasi konfigurasi API Gemini dari konfigurasi aplikasi.
     */
    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-pro');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    /**
     * Menghasilkan respons AI untuk pesan pengguna dengan dukungan cache semantik dan RAG.
     */
    public function generateResponse(string $userMessage, string $chatId, ?string $context = null): ?string
    {
        try {
            $cachedResponse = $this->findSemanticCachedResponse($userMessage);
            if ($cachedResponse) {
                ChatbotLog::create([
                    'telegram_chat_id' => $chatId,
                    'pesan_masuk' => $userMessage,
                    'balasan_ai' => $cachedResponse,
                    'tokens_used' => 0,
                ]);
                return $cachedResponse;
            }

            if (empty($this->apiKey)) {
                Log::warning('Gemini API Key is not configured.');
                return null;
            }

            $systemPrompt = $context ? $this->getRAGSystemPrompt($context) : $this->getSystemPrompt();
            
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
                    'temperature' => 0.5,
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
            Log::error('Gemini Provider Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Memperbaiki dan menyempurnakan copywriting artikel berita desa.
     */
    public function fixCopywriting(string $text, ?string $title = null): ?string
    {
        try {
            $trimmedText = trim(strip_tags($text));
            if (empty($trimmedText)) {
                $prompt = "Buatlah satu artikel berita atau pengumuman desa yang lengkap, natural, mengalir dengan baik, informatif, dan sangat bagus berdasarkan judul berikut: \"" . ($title ?? 'Informasi Desa') . "\". Gunakan bahasa Indonesia yang baik, benar, formal namun tetap ramah dibaca oleh warga gampong/desa. Format artikel menggunakan tag HTML standar (seperti tag p, strong, em, ul, li, br, dll). Jangan berikan penjelasan atau pengantar tambahan apapun, balas HANYA dengan kode HTML artikel tersebut secara langsung.";
            } else {
                $prompt = "Perbaiki dan sempurnakan copywriting tulisan artikel berita desa berikut dari segi ejaan (EYD/PUEBI), tata bahasa, kesantunan, kejelasan, dan alur keterbacaan agar formal, menarik, dan rapi untuk dibaca warga gampong. Pertahankan tag HTML (seperti p, strong, em, ul, li, br, dll) yang sudah ada di dalam teks asli. Jangan berikan penjelasan, komentar, atau pengantar tambahan apapun, balas HANYA dengan teks artikel yang sudah diperbaiki secara langsung.\n\nTeks Asli:\n" . $text;
            }

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 2048,
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '?key=' . $this->apiKey, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            Log::error('Gemini fixCopywriting API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Gemini Provider fixCopywriting Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Menghasilkan meta deskripsi dan kata kunci SEO untuk artikel berita.
     */
    public function generateSeoMetadata(string $title, string $content): ?array
    {
        try {
            $prompt = "Tolong buatkan meta deskripsi (maksimal 150-160 karakter, padat, profesional, ramah SEO, merangkum isi berita, tanpa emoji) dan kata kunci SEO (5-7 kata kunci dipisahkan dengan koma) berdasarkan judul dan konten berita desa berikut. Balas HANYA dengan format JSON valid seperti ini tanpa markdown/formatting tambahan: {\"meta_description\": \"...\", \"kata_kunci\": \"...\"}\n\nJudul: {$title}\nKonten: " . strip_tags($content);

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 1024,
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '?key=' . $this->apiKey, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                $text = trim($text);
                if (str_starts_with($text, '```json')) {
                    $text = substr($text, 7);
                }
                if (str_ends_with($text, '```')) {
                    $text = substr($text, 0, -3);
                }
                $text = trim($text);

                $decoded = json_decode($text, true);
                if (is_array($decoded) && isset($decoded['meta_description']) && isset($decoded['kata_kunci'])) {
                    return $decoded;
                }
            }

            Log::error('Gemini generateSeoMetadata API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Gemini Provider generateSeoMetadata Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Memeriksa ketersediaan layanan API Gemini.
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

    /**
     * Menyusun system prompt dengan konteks RAG dari dokumen referensi.
     */
    protected function getRAGSystemPrompt(string $context): string
    {
        return "Anda adalah asisten virtual resmi Gampong Udeung, Kecamatan Bandar Baru, Pidie Jaya, Aceh.\nJawablah pertanyaan warga secara sopan dan formal HANYA berdasarkan dokumen referensi berikut:\n\n---\nDOKUMEN REFERENSI:\n{$context}\n---\n\nJika informasi tidak ada dalam dokumen referensi di atas, jawab dengan sopan bahwa Anda tidak memiliki informasi detail mengenai hal tersebut dan sarankan untuk menghubungi kantor desa Udeung.";
    }

    /**
     * Menyusun system prompt default untuk percakapan umum dengan warga.
     */
    protected function getSystemPrompt(): string
    {
        return "Anda adalah asisten virtual resmi Gampong Udeung, Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh.\n\nTUGAS ANDA:\n1. Menjawab pertanyaan warga tentang prosedur administrasi desa\n2. Memberikan informasi tentang persyaratan surat-menyurat\n3. Menjelaskan cara menggunakan sistem SIG-Udeung\n4. Memberikan informasi umum tentang Gampong Udeung\n\nJENIS SURAT YANG TERSEDIA:\n- Surat Keterangan Domisili\n- Surat Keterangan Tidak Mampu (SKTM)\n- Surat Keterangan Usaha\n- Surat Pengantar KTP\n- Surat Keterangan Kelahiran\n\nPROSEDUR UMUM:\n1. Login ke PWA menggunakan NIK\n2. Pilih jenis surat yang dibutuhkan\n3. Isi formulir dan upload dokumen persyaratan\n4. Tunggu verifikasi dari perangkat desa\n5. Surat akan dikirim via Telegram jika disetujui\n\nMUTASI KEPENDUDUKAN:\n- Kelahiran: Upload akta kelahiran\n- Kematian: Upload surat kematian dari RS/Puskesmas\n- Kedatangan: Upload surat pindah dari desa asal\n- Kepindahan: Ajukan surat pindah\n\nATURAN KOMUNIKASI:\n- Gunakan bahasa Indonesia yang sopan dan formal\n- Jika tidak tahu jawaban, arahkan untuk menghubungi kantor desa\n- Jangan memberikan informasi pribadi warga\n- Fokus pada informasi prosedural dan administratif\n\nJawab pertanyaan berikut dengan jelas dan membantu:";
    }

    /**
     * Menemukan respons cache berdasarkan kecocokan semantik dengan pertanyaan sebelumnya.
     */
    protected function findSemanticCachedResponse(string $userMessage): ?string
    {
        $normalized = trim(strtolower($userMessage));
        
        $cacheKey = 'ai_exact_' . md5($normalized);
        $cachedValue = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedValue) {
            return $cachedValue;
        }

        $exactLog = ChatbotLog::whereRaw('LOWER(TRIM(pesan_masuk)) = ?', [$normalized])
            ->where('created_at', '>=', now()->subHours(24))
            ->whereNotNull('balasan_ai')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($exactLog) {
            \Illuminate\Support\Facades\Cache::put($cacheKey, $exactLog->balasan_ai, 86400);
            return $exactLog->balasan_ai;
        }

        $recentLogs = \Illuminate\Support\Facades\Cache::remember('ai_recent_logs_semantic', 300, function () {
            return ChatbotLog::where('created_at', '>=', now()->subHours(48))
                ->whereNotNull('balasan_ai')
                ->orderBy('created_at', 'desc')
                ->limit(100)
                ->get(['pesan_masuk', 'balasan_ai'])
                ->unique('pesan_masuk')
                ->values();
        });

        if ($recentLogs->isEmpty()) {
            return null;
        }

        $bestScore = 0.0;
        $bestResponse = null;
        
        $inputTokens = $this->tokenize($normalized);
        if (count($inputTokens) === 0) {
            return null;
        }

        foreach ($recentLogs as $log) {
            $logTokens = $this->tokenize(trim(strtolower($log->pesan_masuk)));
            if (count($logTokens) === 0) {
                continue;
            }

            $intersection = count(array_intersect($inputTokens, $logTokens));
            $union = count(array_unique(array_merge($inputTokens, $logTokens)));
            
            $score = $union > 0 ? ($intersection / $union) : 0;
            
            if (strlen($normalized) < 20) {
                $levDist = levenshtein($normalized, trim(strtolower($log->pesan_masuk)));
                $maxLen = max(strlen($normalized), strlen($log->pesan_masuk));
                $levScore = $maxLen > 0 ? (1 - ($levDist / $maxLen)) : 0;
                $score = max($score, $levScore);
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestResponse = $log->balasan_ai;
            }
        }

        if ($bestScore >= 0.80 && $bestResponse) {
            \Illuminate\Support\Facades\Cache::put($cacheKey, $bestResponse, 86400);
            return $bestResponse;
        }

        return null;
    }

    /**
     * Melakukan tokenisasi dan filtering stopwords untuk pencocokan semantik.
     */
    protected function tokenize(string $text): array
    {
        $clean = preg_replace('/[^\w\s]/u', '', $text);
        $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);
        
        $stopwords = ['yang', 'di', 'dan', 'itu', 'dengan', 'untuk', 'pada', 'ke', 'dari', 'ini', 'adalah', 'akan', 'atau', 'saya', 'anda', 'kami', 'kita'];
        return array_diff($words, $stopwords);
    }
}
