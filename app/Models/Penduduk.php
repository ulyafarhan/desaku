<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Penduduk extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'penduduk';
    
    protected $primaryKey = 'nik';
    
    public $incrementing = false;
    
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'no_kk',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'status_keluarga',
        'status_mutasi',
        'telegram_chat_id',
    ];

    protected $hidden = [
        'telegram_chat_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relationships
    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'no_kk', 'no_kk');
    }

    public function mutasi()
    {
        return $this->hasMany(MutasiPenduduk::class, 'nik', 'nik');
    }

    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class, 'nik_pemohon', 'nik');
    }

    // Accessor
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir->age;
    }

    // Scope
    public function scopeAktif($query)
    {
        return $query->where('status_mutasi', 'Tetap');
    }
}
