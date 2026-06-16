<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk merepresentasikan antrean pengiriman pesan massal (broadcast) Telegram warga.
 */
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

    /**
     * Relasi ke administrator pembuat draf pesan broadcast ini.
     */
    public function creator()
    {
        return $this->belongsTo(Administrator::class, 'created_by');
    }

    /**
     * Scope query untuk menyaring draf pesan siaran yang masih masuk antrean.
     */
    public function scopeQueued($query)
    {
        return $query->where('status', 'Queued');
    }

    /**
     * Scope query untuk menyaring draf pesan siaran yang siap dikirim saat ini.
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'Queued')
                    ->where('jadwal_kirim', '<=', now());
    }
}
