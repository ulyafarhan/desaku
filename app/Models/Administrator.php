<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Administrator extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class, 'diverifikasi_oleh');
    }

    public function mutasiPenduduk()
    {
        return $this->hasMany(MutasiPenduduk::class, 'diverifikasi_oleh');
    }

    public function informasiPublik()
    {
        return $this->hasMany(InformasiPublik::class, 'author_id');
    }

    public function telegramBroadcasts()
    {
        return $this->hasMany(TelegramBroadcastQueue::class, 'created_by');
    }
}
