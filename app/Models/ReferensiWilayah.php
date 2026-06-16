<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk merepresentasikan master data referensi wilayah administrasi negara (provinsi hingga desa).
 */
class ReferensiWilayah extends Model
{
    protected $table = 'referensi_wilayah';
    
    protected $primaryKey = 'kode_wilayah';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    public $timestamps = false;

    protected $fillable = [
        'kode_wilayah',
        'nama_wilayah',
        'level',
        'parent_kode',
    ];

    /**
     * Relasi ke wilayah atasan (parent wilayah).
     */
    public function parent()
    {
        return $this->belongsTo(ReferensiWilayah::class, 'parent_kode', 'kode_wilayah');
    }

    /**
     * Relasi ke seluruh sub-wilayah bawahan (children wilayah).
     */
    public function children()
    {
        return $this->hasMany(ReferensiWilayah::class, 'parent_kode', 'kode_wilayah');
    }
}
