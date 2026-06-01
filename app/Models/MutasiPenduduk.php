<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiPenduduk extends Model
{
    protected $table = 'mutasi_penduduk';
    
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
