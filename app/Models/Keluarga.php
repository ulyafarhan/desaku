<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // Relationships
    public function anggota()
    {
        return $this->hasMany(Penduduk::class, 'no_kk', 'no_kk');
    }

    public function kepalaKeluarga()
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_nik', 'nik');
    }
}
