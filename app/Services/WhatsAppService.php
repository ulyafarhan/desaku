<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $provider;
    protected string $gatewayUrl;
    protected string $apiKey;
    protected string $sessionId;
    protected string $fonnteToken;

    public function __construct()
    {
        $this->provider = config('services.whatsapp.provider', 'wa-gateway');
        $this->gatewayUrl = rtrim(config('services.whatsapp.gateway_url', ''), '/');
        $this->apiKey = config('services.whatsapp.api_key', '');
        $this->sessionId = config('services.whatsapp.session_id', 'sig-udeung');
        $this->fonnteToken = config('services.whatsapp.fonnte_token', '');
    }

    public function sendMessage(string $target, string $message): bool
    {
        return $this->provider === 'fonnte'
            ? $this->sendViaFonnte($target, $message)
            : $this->sendViaGateway($target, $message);
    }

    public function sendImage(string $target, string $imageUrl, string $caption = ''): bool
    {
        return $this->provider === 'fonnte'
            ? $this->sendViaFonnte($target, $caption, $imageUrl)
            : $this->sendImageViaGateway($target, $imageUrl, $caption);
    }

    public function broadcast(array $targets, string $message): array
    {
        $results = ['success' => 0, 'failed' => 0];
        foreach ($targets as $target) {
            if ($this->sendMessage($target, $message)) $results['success']++;
            else $results['failed']++;
            usleep(100000);
        }
        return $results;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function isConfigured(): bool
    {
        if ($this->provider === 'fonnte') return !empty($this->fonnteToken);
        return !empty($this->apiKey) && !empty($this->gatewayUrl);
    }

    // ── WA Gateway Provider ─────────────────────────────────────────────

    protected function sendViaGateway(string $target, string $message): bool
    {
        try {
            if (!$this->isConfigured()) {
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

    protected function sendImageViaGateway(string $target, string $imageUrl, string $caption): bool
    {
        try {
            if (!$this->isConfigured()) return false;

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

    // ── Fonnte Provider ─────────────────────────────────────────────────

    protected function sendViaFonnte(string $target, string $message, string $url = ''): bool
    {
        try {
            if (empty($this->fonnteToken)) {
                Log::warning('Fonnte token not configured.');
                return false;
            }

            $target = preg_replace('/[^0-9]/', '', $target);
            if (strlen($target) > 11 && $target[0] === '0') $target = '62' . substr($target, 1);
            elseif (strlen($target) <= 11 && $target[0] === '0') $target = '62' . substr($target, 1);

            $payload = ['target' => $target, 'message' => $message];
            if (!empty($url)) $payload['url'] = $url;

            $response = Http::timeout(15)
                ->withHeaders(['Authorization' => $this->fonnteToken])
                ->post('https://api.fonnte.com/send', $payload);

            $body = $response->json();
            if (!($body['status'] ?? false)) {
                Log::error('Fonnte send failed: ' . ($body['reason'] ?? $response->body()));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Fonnte send error: ' . $e->getMessage());
            return false;
        }
    }
}