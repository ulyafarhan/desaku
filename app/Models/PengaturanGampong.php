<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk merepresentasikan konfigurasi/pengaturan sistem (keuchik, visi-misi, kunci API, dsb).
 */
class PengaturanGampong extends Model
{
    use HasUlids;

    protected $table = 'pengaturan_gampong';
    
    public $timestamps = false;

    protected $fillable = [
        'kunci',
        'nilai',
        'tipe_data',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Mengambil nilai pengaturan berdasarkan kunci beserta casting tipe datanya.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('kunci', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match($setting->tipe_data) {
            'integer' => (int) $setting->nilai,
            'boolean' => filter_var($setting->nilai, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($setting->nilai, true),
            default => $setting->nilai,
        };
    }

    /**
     * Menyimpan atau memperbarui nilai pengaturan sistem.
     */
    public static function set(string $key, $value, string $type = 'string'): void
    {
        $nilai = is_array($value) ? json_encode($value) : $value;
        
        static::updateOrCreate(
            ['kunci' => $key],
            [
                'nilai' => $nilai,
                'tipe_data' => $type,
            ]
        );
    }
}
