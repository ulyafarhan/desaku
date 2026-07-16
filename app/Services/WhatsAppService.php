<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $gatewayUrl;
    protected string $apiKey;
    protected string $sessionId;

    public function __construct()
    {
        $this->gatewayUrl = rtrim(config('services.whatsapp.gateway_url', ''), '/');
        $this->apiKey = config('services.whatsapp.api_key', '');
        $this->sessionId = config('services.whatsapp.session_id', 'sig-udeung');
    }

    public function sendMessage(string $target, string $message): bool
    {
        try {
            if (empty($this->apiKey) || empty($this->gatewayUrl)) {
                Log::warning('WhatsApp gateway not configured.');
                return false;
            }

            if (!str_contains($target, '@')) {
                $target = preg_replace('/[^0-9]/', '', $target);
                $target = $target . '@c.us';
            }

            $response = Http::timeout(15)
                ->connectTimeout(10)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post("{$this->gatewayUrl}/api/sessions/{$this->sessionId}/messages/send-text", [
                    'chatId' => $target,
                    'text' => $message,
                ]);

            if (!$response->successful()) {
                Log::error('WhatsApp send failed: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp send error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendImage(string $target, string $imageUrl, string $caption): bool
    {
        try {
            if (empty($this->apiKey) || empty($this->gatewayUrl)) {
                return false;
            }

            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post("{$this->gatewayUrl}/api/sessions/{$this->sessionId}/messages/send-image", [
                    'chatId' => $target,
                    'imageUrl' => $imageUrl,
                    'caption' => $caption,
                ]);

            if (!$response->successful()) {
                Log::error('WhatsApp send image failed: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp send image error: ' . $e->getMessage());
            return false;
        }
    }

    public function broadcast(array $targets, string $message): array
    {
        $results = ['success' => 0, 'failed' => 0];
        foreach ($targets as $target) {
            if ($this->sendMessage($target, $message)) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
            usleep(100000); // 100ms delay antar kirim
        }
        return $results;
    }
}