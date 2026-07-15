<?php

namespace App\Services\AiProviders;

use App\Services\Contracts\AiProviderInterface;
use App\Models\ChatbotLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Provider AI untuk layanan OpenAI API.
 *
 * Mengimplementasikan AiProviderInterface untuk menghasilkan respons chatbot,
 * memperbaiki copywriting artikel, dan menghasilkan meta data SEO.
 */
class OpenAiProvider implements AiProviderInterface
{
    /**
     * API Key untuk autentikasi ke OpenAI API.
     */
    protected string $apiKey;

    /**
     * Model OpenAI yang digunakan (default: gpt-4o-mini).
     */
    protected string $model;

    /**
     * Base URL endpoint OpenAI API.
     */
    protected string $baseUrl;

    /**
     * Menginisialisasi konfigurasi API OpenAI dari konfigurasi aplikasi.
     */
    public function __construct()
    {
        $this->apiKey = config('services.ai.openai.api_key') ?? '';
        $this->model = config('services.ai.openai.model', 'gpt-4o-mini');
        $this->baseUrl = config('services.ai.openai.base_url', 'https://api.openai.com/v1');
    }

    /**
     * Menghasilkan respons AI untuk pesan pengguna dengan dukungan cache semantik dan RAG.
     *
     * Fitur:
     * - Pencarian cache semantik untuk pertanyaan yang pernah dijawab sebelumnya
     * - Dukungan RAG (Retrieval-Augmented Generation) dengan konteks dari knowledge base
     * - Logging percakapan ke tabel chatbot_logs
     *
     * @param  string  $userMessage  Pesan dari pengguna
     * @param  string  $chatId  ID chat Telegram untuk logging
     * @param  string|null  $context  Konteks dokumen dari knowledge base untuk RAG (opsional)
     * @return string|null  Respons AI yang dihasilkan, atau null jika gagal
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
                Log::warning('OpenAI API Key is not configured.');
                return null;
            }

            $systemPrompt = $context ? $this->getRAGSystemPrompt($context) : $this->getSystemPrompt();

            $payload = [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.5,
                'max_tokens' => 1024,
            ];

            $response = Http::timeout(30)->connectTimeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['choices'][0]['message']['content'] ?? null;
                $tokensUsed = $data['usage']['total_tokens'] ?? 0;

                ChatbotLog::create([
                    'telegram_chat_id' => $chatId,
                    'pesan_masuk' => $userMessage,
                    'balasan_ai' => $aiResponse,
                    'tokens_used' => $tokensUsed,
                ]);

                return $aiResponse;
            }

            Log::error('OpenAI API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('OpenAi Provider Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Memperbaiki dan menyempurnakan copywriting artikel berita desa.
     *
     * Jika teks kosong, akan membuat artikel baru berdasarkan judul.
     * Jika teks tersedia, akan memperbaiki ejaan, tata bahasa, dan alur keterbacaan.
     *
     * @param  string  $text  Teks artikel yang akan diperbaiki (bisa kosong untuk buat baru)
     * @param  string|null  $title  Judul artikel untuk konteks (wajib jika $text kosong)
     * @return string|null  Teks artikel yang sudah diperbaiki, atau null jika gagal
     */
    public function fixCopywriting(string $text, ?string $title = null): ?string
    {
        try {
            if (empty($this->apiKey)) {
                Log::warning('OpenAI API Key is not configured.');
                return null;
            }

            $trimmedText = trim(strip_tags($text));
            if (empty($trimmedText)) {
                $prompt = "Buatlah satu artikel berita atau pengumuman desa yang lengkap, natural, mengalir dengan baik, informatif, dan sangat bagus berdasarkan judul berikut: \"" . ($title ?? 'Informasi Desa') . "\". Gunakan bahasa Indonesia yang baik, benar, formal namun tetap ramah dibaca oleh warga gampong/desa. Format artikel menggunakan tag HTML standar (seperti tag p, strong, em, ul, li, br, dll). Jangan berikan penjelasan atau pengantar tambahan apapun, balas HANYA dengan kode HTML artikel tersebut secara langsung.";
            } else {
                $prompt = "Perbaiki dan sempurnakan copywriting tulisan artikel berita desa berikut dari segi ejaan (EYD/PUEBI), tata bahasa, kesantunan, kejelasan, dan alur keterbacaan agar formal, menarik, dan rapi untuk dibaca warga gampong. Pertahankan tag HTML (seperti p, strong, em, ul, li, br, dll) yang sudah ada di dalam teks asli. Jangan berikan penjelasan, komentar, atau pengantar tambahan apapun, balas HANYA dengan teks artikel yang sudah diperbaiki secara langsung.\n\nTeks Asli:\n" . $text;
            }

            $payload = [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
                'max_tokens' => 2048,
            ];

            $response = Http::timeout(30)->connectTimeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('OpenAI fixCopywriting API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('OpenAi Provider fixCopywriting Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Menghasilkan meta deskripsi dan kata kunci SEO untuk artikel berita.
     *
     * Meta deskripsi dibatasi maksimal 150 karakter untuk optimasi SEO.
     * Response dikembalikan dalam format JSON yang sudah di-parse.
     *
     * @param  string  $title  Judul artikel berita
     * @param  string  $content  Konten artikel berita (HTML akan di-strip)
     * @return array|null  Assoc array dengan 'meta_description' dan 'kata_kunci', atau null jika gagal
     */
    public function generateSeoMetadata(string $title, string $content): ?array
    {
        try {
            if (empty($this->apiKey)) {
                Log::warning('OpenAI API Key is not configured.');
                return null;
            }

            $prompt = "Tolong buatkan meta deskripsi (sangat penting: HARUS kurang dari 150 karakter terhitung spasi, jangan sampai melebihi 150 karakter, padat, profesional, ramah SEO, merangkum isi berita, tanpa emoji) dan kata kunci SEO (5-7 kata kunci dipisahkan dengan koma) berdasarkan judul dan konten berita desa berikut. Balas HANYA dengan format JSON valid seperti ini tanpa markdown/formatting tambahan: {\"meta_description\": \"...\", \"kata_kunci\": \"...\"}\n\nJudul: {$title}\nKonten: " . strip_tags($content);

            $payload = [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
                'max_tokens' => 1024,
            ];

            $response = Http::timeout(30)->connectTimeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? '';
                
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
                    $metaDesc = trim($decoded['meta_description']);
                    if (mb_strlen($metaDesc) > 160) {
                        $metaDesc = mb_substr($metaDesc, 0, 157) . '...';
                    }
                    $decoded['meta_description'] = $metaDesc;
                    return $decoded;
                }
            }

            Log::error('OpenAI generateSeoMetadata API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('OpenAi Provider generateSeoMetadata Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Memeriksa ketersediaan layanan API OpenAI.
     *
     * Melakukan request ke endpoint /models untuk memverifikasi koneksi.
     *
     * @return bool  true jika API merespons dengan sukses
     */
    public function checkHealth(): bool
    {
        try {
            if (empty($this->apiKey)) {
                return false;
            }
            $response = Http::timeout(10)->connectTimeout(5)->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/models');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Menyusun system prompt dengan konteks RAG dari dokumen referensi.
     *
     * @param  string  $context  Konteks dokumen dari knowledge base
     * @return string  System prompt lengkap dengan instruksi dan dokumen referensi
     */
    protected function getRAGSystemPrompt(string $context): string
    {
        return "Anda adalah asisten virtual resmi Gampong Udeung, Kecamatan Bandar Baru, Pidie Jaya, Aceh.\nJawablah pertanyaan warga secara sopan dan formal HANYA berdasarkan dokumen referensi berikut:\n\n---\nDOKUMEN REFERENSI:\n{$context}\n---\n\nJika informasi tidak ada dalam dokumen referensi di atas, jawab dengan sopan bahwa Anda tidak memiliki informasi detail mengenai hal tersebut dan sarankan untuk menghubungi kantor desa Udeung.";
    }

    /**
     * Menyusun system prompt default untuk percakapan umum dengan warga.
     *
     * Berisi informasi tentang Gampong Udeung, jenis surat, prosedur administrasi,
     * dan aturan komunikasi untuk asisten virtual.
     *
     * @return string  System prompt default untuk chatbot
     */
    protected function getSystemPrompt(): string
    {
        return "Anda adalah asisten virtual resmi Gampong Udeung, Kecamatan Bandar Baru, Kabupaten Pidie Jaya, Provinsi Aceh.\n\nTUGAS ANDA:\n1. Menjawab pertanyaan warga tentang prosedur administrasi desa\n2. Memberikan informasi tentang persyaratan surat-menyurat\n3. Menjelaskan cara menggunakan sistem SIG-Udeung\n4. Memberikan informasi umum tentang Gampong Udeung\n\nJENIS SURAT YANG TERSEDIA:\n- Surat Keterangan Domisili\n- Surat Keterangan Tidak Mampu (SKTM)\n- Surat Keterangan Usaha\n- Surat Pengantar KTP\n- Surat Keterangan Kelahiran\n\nPROSEDUR UMUM:\n1. Login ke PWA menggunakan NIK\n2. Pilih jenis surat yang dibutuhkan\n3. Isi formulir dan upload dokumen persyaratan\n4. Tunggu verifikasi dari perangkat desa\n5. Surat akan dikirim via Telegram jika disetujui\n\nMUTASI KEPENDUDUKAN:\n- Kelahiran: Upload akta kelahiran\n- Kematian: Upload surat kematian dari RS/Puskesmas\n- Kedatangan: Upload surat pindah dari desa asal\n- Kepindahan: Ajukan surat pindah\n\nATURAN KOMUNIKASI:\n- Gunakan bahasa Indonesia yang sopan dan formal\n- Jika tidak tahu jawaban, arahkan untuk menghubungi kantor desa\n- Jangan memberikan informasi pribadi warga\n- Fokus pada informasi prosedural dan administratif\n\nJawab pertanyaan berikut dengan jelas dan membantu:";
    }

    /**
     * Menemukan respons cache berdasarkan kecocokan semantik dengan pertanyaan sebelumnya.
     *
     * Menggunakan kombinasi pencocokan tepat (exact match) dan Jaccard similarity
     * untuk menemukan pertanyaan serupa dari log chatbot sebelumnya.
     *
     * @param  string  $userMessage  Pesan pengguna yang akan dicocokkan
     * @return string|null  Respons dari cache jika ditemukan kecocokan (score >= 0.80), atau null
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
     *
     * Menghapus tanda baca, memecah teks menjadi kata-kata, dan menghapus stopwords Bahasa Indonesia.
     *
     * @param  string  $text  Teks yang akan ditokenisasi
     * @return array  Array kata-kata setelah filtering stopwords
     */
    protected function tokenize(string $text): array
    {
        $clean = preg_replace('/[^\w\s]/u', '', $text);
        $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);
        
        $stopwords = ['yang', 'di', 'dan', 'itu', 'dengan', 'untuk', 'pada', 'ke', 'dari', 'ini', 'adalah', 'akan', 'atau', 'saya', 'anda', 'kami', 'kita'];
        return array_diff($words, $stopwords);
    }
}
