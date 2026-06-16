<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk merepresentasikan data Kartu Keluarga (KK).
 */
class Keluarga extends Model
{
    protected $table = 'keluarga';
    
    protected $primaryKey = 'no_kk';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    public $timestamps = false;

    protected $fillable = [
        'no_kk',
        'alamat',
        'dusun',
        'rt_rw',
        'kepala_keluarga_nik',
    ];

    /**
     * Relasi ke seluruh anggota keluarga (warga) yang terdaftar dalam KK ini.
     */
    public function anggota()
    {
        return $this->hasMany(Penduduk::class, 'no_kk', 'no_kk');
    }

    /**
     * Relasi ke penduduk yang bertindak sebagai Kepala Keluarga.
     */
    public function kepalaKeluarga()
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_nik', 'nik');
    }

    /**
     * Scope query untuk memfilter keluarga berdasarkan wilayah dusun.
     */
    public function scopeByDusun($query, string $dusun)
    {
        return $query->where('dusun', $dusun);
    }
}
