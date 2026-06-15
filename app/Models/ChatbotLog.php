<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class ChatbotLog extends Model
{
    use HasUlids;

    protected $table = 'chatbot_logs';
    
    public $timestamps = false;

    protected $fillable = [
        'telegram_chat_id',
        'pesan_masuk',
        'balasan_ai',
        'tokens_used',
    ];

    protected function casts(): array
    {
        return [
            'tokens_used' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
