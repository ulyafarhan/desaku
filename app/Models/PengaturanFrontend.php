<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk merepresentasikan konfigurasi visual dan konten spesifik halaman frontend publik.
 */
class PengaturanFrontend extends Model
{
    protected $table = 'pengaturan_frontend';
    
    public $timestamps = false;

    protected $primaryKey = 'kunci';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kunci',
        'nilai',
        'tipe_data',
        'deskripsi',
    ];

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
