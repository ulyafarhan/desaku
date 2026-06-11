<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class MutasiPenduduk extends Model
{
    use HasUlids;

    protected $table = 'mutasi_penduduk';
    
    public $incrementing = false;

    protected $keyType = 'string';

    protected $with = ['penduduk'];

    public $timestamps = false;

    protected $fillable = [
        'nik',
        'jenis_mutasi',
        'tanggal_mutasi',
        'keterangan',
        'dokumen_bukti',
        'status_verifikasi',
        'diverifikasi_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mutasi' => 'date',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }

    public function verifikator()
    {
        return $this->belongsTo(Administrator::class, 'diverifikasi_oleh');
    }

    // Scope
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'Pending');
    }
}
