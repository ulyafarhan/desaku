<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model untuk merepresentasikan Administrator (perangkat desa) dalam sistem SIG-Udeung.
 */
class Administrator extends Authenticatable implements FilamentUser
{
    use HasApiTokens, Notifiable, HasUlids;

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

    /**
     * Relasi ke data pengajuan surat yang diverifikasi oleh administrator ini.
     */
    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class, 'diverifikasi_oleh');
    }

    /**
     * Relasi ke data mutasi penduduk yang diverifikasi oleh administrator ini.
     */
    public function mutasiPenduduk()
    {
        return $this->hasMany(MutasiPenduduk::class, 'diverifikasi_oleh');
    }

    /**
     * Relasi ke data artikel informasi publik yang ditulis oleh administrator ini.
     */
    public function informasiPublik()
    {
        return $this->hasMany(InformasiPublik::class, 'author_id');
    }

    /**
     * Relasi ke antrean pesan siaran Telegram yang dibuat oleh administrator ini.
     */
    public function telegramBroadcasts()
    {
        return $this->hasMany(TelegramBroadcastQueue::class, 'created_by');
    }

    /**
     * Menentukan hak akses administrator untuk masuk ke panel admin Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['keuchik', 'sekdes', 'operator'], true);
    }

    public function getNameAttribute(): string
    {
        return $this->username;
    }
}
