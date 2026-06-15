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
        'foto_profil',
        'foto_ktp',
        'foto_kk',
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

    public function getFotoProfilAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return \Illuminate\Support\Facades\Storage::url($value);
    }

    public function getFotoKtpAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return \Illuminate\Support\Facades\Storage::url($value);
    }

    public function getFotoKkAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return \Illuminate\Support\Facades\Storage::url($value);
    }

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

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir->age;
    }

    public function scopeAktif($query)
    {
        return $query->where('status_mutasi', 'Tetap');
    }

    public function scopeLakiLaki($query)
    {
        return $query->where('jenis_kelamin', 'L');
    }

    public function scopePerempuan($query)
    {
        return $query->where('jenis_kelamin', 'P');
    }

    public function scopeByDusun($query, string $dusun)
    {
        return $query->whereHas('keluarga', function ($q) use ($dusun) {
            $q->where('dusun', $dusun);
        });
    }
}
