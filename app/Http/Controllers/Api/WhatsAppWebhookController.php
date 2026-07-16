<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('WhatsApp Webhook Received', $request->all());

        if ($message = $request->input('data.message')) {
            $from = $message['from'] ?? '';
            $text = $message['text']['body'] ?? '';
            // Proses auto-reply sederhana
        }

        return response()->json(['ok' => true]);
    }
}