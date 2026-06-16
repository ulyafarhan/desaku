<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk mencatat log kunjungan/traffic website.
 */
class TrafficLog extends Model
{
    use HasUlids;

    protected $table = 'traffic_logs';

    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'path',
        'method',
        'referer',
        'is_bot',
    ];

    protected function casts(): array
    {
        return [
            'is_bot' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
