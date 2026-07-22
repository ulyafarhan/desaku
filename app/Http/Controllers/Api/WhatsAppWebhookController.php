<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WaWebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? 'unknown';
        $secret = $request->header('X-Webhook-Secret');
        $expected = config('services.whatsapp.webhook_secret', '');

        if ($expected && $secret !== $expected) {
            Log::warning('WA webhook: invalid secret', ['event' => $event]);
            return response()->json(['status' => 'invalid_secret'], 403);
        }

        $sender = match ($event) {
            'message.incoming' => $payload['sender'] ?? null,
            'message.sent', 'message.failed', 'message.status' => $payload['chatId'] ?? null,
            default => null,
        };

        try {
            WaWebhookLog::create([
                'event' => $event,
                'session_id' => $payload['session_id'] ?? 'unknown',
                'sender' => $sender,
                'text' => $payload['text'] ?? null,
                'wa_message_id' => $payload['wa_message_id'] ?? null,
                'raw_payload' => $payload,
                'event_at' => isset($payload['timestamp'])
                    ? date('Y-m-d H:i:s', intval($payload['timestamp'] / 1000))
                    : now(),
            ]);
        } catch (\Exception $e) {
            Log::error('WA webhook: failed to store log', ['error' => $e->getMessage()]);
        }

        return response()->json(['status' => 'ok']);
    }
}
