<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Cache;

class BotKnowledge extends Model
{
    use HasUlids;

    protected $table = 'bot_knowledges';

    protected $fillable = [
        'kunci',
        'tipe',
        'pertanyaan_atau_topik',
        'kata_kunci',
        'jawaban_atau_konten',
        'is_aktif',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('telegram_bot_knowledges'));
        static::deleted(fn () => Cache::forget('telegram_bot_knowledges'));
    }

    protected function casts(): array
    {
        return [
            'kata_kunci' => 'array',
            'is_aktif' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
