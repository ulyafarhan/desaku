<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class TelegramBroadcastQueue extends Model
{
    use HasUlids;

    protected $table = 'telegram_broadcast_queue';
    
    public $timestamps = false;

    protected $fillable = [
        'pesan',
        'kategori_target',
        'status',
        'jadwal_kirim',
        'waktu_selesai',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'jadwal_kirim' => 'datetime',
            'waktu_selesai' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(Administrator::class, 'created_by');
    }

    public function scopeQueued($query)
    {
        return $query->where('status', 'Queued');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'Queued')
                    ->where('jadwal_kirim', '<=', now());
    }
}
