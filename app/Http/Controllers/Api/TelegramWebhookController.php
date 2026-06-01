<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiAiService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @group Telegram Webhook
 * 
 * API untuk menerima webhook dari Telegram Bot (internal use only)
 */
class TelegramWebhookController extends Controller
{
    public function __construct(
        protected TelegramService $telegram,
        protected GeminiAiService $gemini
    ) {}

    /**
     * Handle Telegram Webhook
     * 
     * Endpoint untuk menerima webhook dari Telegram Bot.
     * Menangani pesan masuk dan callback query dari user.
     * Terintegrasi dengan Gemini AI untuk chatbot otomatis.
     * 
     * @bodyParam update_id integer ID update dari Telegram. Example: 123456789
     * @bodyParam message object Objek message dari Telegram. Example: {"message_id": 1, "chat": {"id": 123456}, "text": "/start"}
     * @bodyParam callback_query object Objek callback query dari button. Example: {"id": "123", "data": "action_data"}
     * 
     * @response 200 {
     *   "ok": true
     * }
     */
    public function handle(Request $request)
    {
        $update = $request->all();
        
        Log::info('Telegram Webhook Received', $update);

        // Handle message
        if (isset($update['message'])) {
            $this->handleMessage($update['message']);
        }

        // Handle callback query (button press)
        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
        }

        return response()->json(['ok' => true]);
    }

    protected function handleMessage(array $message)
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        // Handle /start command
        if ($text === '/start') {
            $welcomeMessage = "Selamat datang di Bot SIG-Udeung! 🏘️\n\n";
            $welcomeMessage .= "Saya adalah asisten virtual Gampong Udeung.\n\n";
            $welcomeMessage .= "Anda dapat bertanya tentang:\n";
            $welcomeMessage .= "• Prosedur pembuatan surat\n";
            $welcomeMessage .= "• Persyaratan dokumen\n";
            $welcomeMessage .= "• Informasi umum gampong\n\n";
            $welcomeMessage .= "Silakan kirim pertanyaan Anda!";

            $this->telegram->sendMessage($chatId, $welcomeMessage);
            return;
        }

        // Handle /bind command untuk menghubungkan akun
        if (str_starts_with($text, '/bind')) {
            $parts = explode(' ', $text);
            if (count($parts) === 2) {
                $nik = $parts[1];
                // Logic untuk bind NIK dengan chat_id
                // Ini bisa dilakukan dengan generate token di PWA
                $this->telegram->sendMessage(
                    $chatId,
                    "Untuk menghubungkan akun, silakan buka PWA dan masukkan kode: {$chatId}"
                );
            } else {
                $this->telegram->sendMessage(
                    $chatId,
                    "Format: /bind [NIK]\nContoh: /bind 1234567890123456"
                );
            }
            return;
        }

        // Handle regular message dengan AI
        if (!empty($text)) {
            $aiResponse = $this->gemini->generateResponse($text, $chatId);

            if ($aiResponse) {
                $this->telegram->sendMessage($chatId, $aiResponse);
            } else {
                $this->telegram->sendMessage(
                    $chatId,
                    "Maaf, saya sedang mengalami gangguan. Silakan coba lagi nanti atau hubungi kantor desa."
                );
            }
        }
    }

    protected function handleCallbackQuery(array $callbackQuery)
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $data = $callbackQuery['data'];

        // Handle callback data
        // Implementasi sesuai kebutuhan button interaktif

        $this->telegram->sendMessage($chatId, "Callback received: {$data}");
    }
}
