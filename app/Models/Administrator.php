<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Administrator extends Authenticatable implements FilamentUser
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

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['keuchik', 'sekdes', 'operator'], true);
    }

    public function getNameAttribute(): string
    {
        return $this->username;
    }
}
