<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk merepresentasikan konfigurasi/pengaturan sistem gampong.
 *
 * Tabel: pengaturan_gampong
 * Menyimpan pasangan kunci-nilai untuk pengaturan internal sistem
 * (nama desa, visi-misi, kunci API, konfigurasi Telegram, dsb.).
 *
 * @property  string  $id  ULID unik pengaturan
 * @property  string  $kunci  Kunci unik pengaturan
 * @property  string  $nilai  Nilai pengaturan (string)
 * @property  string  $tipe_data  Tipe data nilai (string, integer, boolean, json)
 * @property  string|null  $deskripsi  Deskripsi fungsi pengaturan
 * @property  \Carbon\Carbon|null  $updated_at  Waktu pembaruan pengaturan terakhir
 */
class PengaturanGampong extends Model
{
    use HasUlids;

    /**
     * Nama tabel database yang terhubung dengan model ini.
     *
     * @var  string
     */
    protected $table = 'pengaturan_gampong';

    /**
     * Nonaktifkan timestamps otomatis (updated_at di-set manual).
     *
     * @var  bool
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var  array<int, string>
     */
    protected $fillable = [
        'kunci',
        'nilai',
        'tipe_data',
        'deskripsi',
    ];

    /**
     * Casting atribut ke tipe data native PHP.
     *
     * @return  array<string, string>
     */
    protected function casts(): array
    {
        return [
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Mengambil nilai pengaturan gampong berdasarkan kunci beserta casting tipe datanya.
     *
     * @param  string       $key     Kunci pengaturan yang dicari.
     * @param  mixed        $default Nilai default jika kunci tidak ditemukan.
     * @return  mixed       Nilai pengaturan dengan tipe data yang sesuai.
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
     * Menyimpan atau memperbarui nilai pengaturan gampong.
     *
     * Jika kunci sudah ada, nilainya diperbarui. Jika belum ada, record baru dibuat.
     * Nilai array akan di-encode otomatis ke JSON. Nilai null dikonversi ke string kosong.
     *
     * @param  string  $key    Kunci pengaturan.
     * @param  mixed   $value  Nilai pengaturan.
     * @param  string  $type   Tipe data nilai (default: string).
     * @return  void
     */
    public static function set(string $key, $value, string $type = 'string'): void
    {
        $nilai = is_array($value) ? json_encode($value) : ($value ?? '');
        
        static::updateOrCreate(
            ['kunci' => $key],
            [
                'nilai' => $nilai,
                'tipe_data' => $type,
            ]
        );
    }
}
