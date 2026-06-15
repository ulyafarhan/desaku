<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function parent()
    {
        return $this->belongsTo(ReferensiWilayah::class, 'parent_kode', 'kode_wilayah');
    }

    public function children()
    {
        return $this->hasMany(ReferensiWilayah::class, 'parent_kode', 'kode_wilayah');
    }
}
