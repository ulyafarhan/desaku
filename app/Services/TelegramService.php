<?php

namespace App\Services;

use App\Models\Penduduk;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected ?string $botToken = null;

    protected ?string $apiUrl = null;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        if ($this->botToken) {
            $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
        }
    }

    public function sendMessage(string $chatId, string $message, ?array $keyboard = null): bool
    {
        try {
            if (! $this->botToken || ! $this->apiUrl) {
                Log::warning('Telegram Bot Token or API URL is not configured.');
                return false;
            }

            $message = $this->convertMarkdownToHtml($message);

            $payload = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ];

            if ($keyboard) {
                $payload['reply_markup'] = json_encode($keyboard);
            }

            $response = Http::post("{$this->apiUrl}/sendMessage", $payload);

            if (!$response->successful()) {
                Log::error('Telegram Send Message Failed: ' . $response->body() . ' | Payload: ' . json_encode($payload));
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram Send Message Error: '.$e->getMessage());

            return false;
        }
    }

    public function sendPhoto(string $chatId, string $photoUrl, string $caption): bool
    {
        try {
            if (! $this->botToken || ! $this->apiUrl) {
                Log::warning('Telegram Bot Token or API URL is not configured.');
                return false;
            }

            $payload = [
                'chat_id' => $chatId,
                'photo' => $photoUrl,
                'caption' => $caption,
                'parse_mode' => 'HTML',
            ];

            $response = Http::post("{$this->apiUrl}/sendPhoto", $payload);

            if (!$response->successful()) {
                Log::error('Telegram Send Photo Failed: ' . $response->body() . ' | Photo: ' . $photoUrl);
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram Send Photo Error: '.$e->getMessage());

            return false;
        }
    }

    public function sendDocument(string $chatId, string $filePath, string $caption = ''): bool
    {
        try {
            if (! $this->botToken || ! $this->apiUrl) {
                Log::warning('Telegram Bot Token or API URL is not configured.');
                return false;
            }

            $response = Http::attach(
                'document',
                file_get_contents($filePath),
                basename($filePath)
            )->post("{$this->apiUrl}/sendDocument", [
                'chat_id' => $chatId,
                'caption' => $caption,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram Send Document Error: '.$e->getMessage());

            return false;
        }
    }

    public function broadcast(array $chatIds, string $message): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
        ];

        foreach ($chatIds as $chatId) {
            if ($this->sendMessage($chatId, $message)) {
                $results['success']++;
            } else {
                $results['failed']++;
            }

            usleep(50000);
        }

        return $results;
    }

    public function notifyPengajuanStatus(string $nik, string $status, string $nomorRegistrasi, ?string $catatan = null): void
    {
        $penduduk = Penduduk::find($nik);

        if (! $penduduk || ! $penduduk->telegram_chat_id) {
            return;
        }

        $statusMessages = [
            'Pending' => 'Pengajuan surat Anda sedang menunggu verifikasi',
            'Diproses' => 'Pengajuan surat Anda sedang diproses',
            'Disetujui' => 'Pengajuan surat Anda telah disetujui',
            'Ditolak' => 'Pengajuan surat Anda ditolak',
            'Selesai' => 'Surat Anda telah selesai dan siap diunduh',
        ];

        $message = "<b>Status Pengajuan Surat</b>\n\n";
        $message .= 'Nomor Registrasi: <code>'.$this->escapeHtml($nomorRegistrasi)."</code>\n";
        $message .= 'Status: '.$this->escapeHtml($statusMessages[$status] ?? $status)."\n";

        if ($catatan) {
            $message .= "\nCatatan:\n".$this->escapeHtml($catatan);
        }

        $this->sendMessage($penduduk->telegram_chat_id, $message);
    }

    public function notifyMutasiStatus(string $nik, string $jenisMutasi, string $status): void
    {
        $penduduk = Penduduk::find($nik);

        if (! $penduduk || ! $penduduk->telegram_chat_id) {
            return;
        }

        $statusPrefix = $status === 'Disetujui' ? '[Disetujui]' : '[Ditolak]';

        $message = "<b>Status Mutasi Kependudukan</b>\n\n";
        $message .= 'Jenis: '.$this->escapeHtml($jenisMutasi)."\n";
        $message .= 'Status: '.$this->escapeHtml("{$statusPrefix} {$status}")."\n";

        $this->sendMessage($penduduk->telegram_chat_id, $message);
    }

    protected function escapeHtml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function setWebhook(string $url): bool
    {
        try {
            $response = Http::post("{$this->apiUrl}/setWebhook", [
                'url' => $url,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram Set Webhook Error: '.$e->getMessage());

            return false;
        }
    }

    public function getMe(): ?array
    {
        try {
            $response = Http::get("{$this->apiUrl}/getMe");

            if ($response->successful()) {
                return $response->json('result');
            }
        } catch (\Exception $e) {
            Log::error('Telegram Get Me Error: '.$e->getMessage());
        }

        return null;
    }

    protected function convertMarkdownToHtml(string $text): string
    {
        $text = preg_replace('/^###\s+(.+)$/m', '<b>$1</b>', $text);
        $text = preg_replace('/^##\s+(.+)$/m', '<b>$1</b>', $text);
        $text = preg_replace('/^#\s+(.+)$/m', '<b>$1</b>', $text);

        $text = preg_replace('/\*\*(.*?)\*\*/s', '<b>$1</b>', $text);

        $text = preg_replace('/\*([^*]+)\*/', '<i>$1</i>', $text);
        
        $text = preg_replace('/^\s*[\*\-]\s+/m', '• ', $text);

        return $text;
    }
}
