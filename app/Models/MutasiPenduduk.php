<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk merepresentasikan data pelaporan mutasi warga (kelahiran, kematian, datang, pindah).
 */
class MutasiPenduduk extends Model
{
    use HasUlids;

    protected $table = 'mutasi_penduduk';

    public $incrementing = false;

    protected $keyType = 'string';

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

    /**
     * Relasi ke data penduduk yang bersangkutan dengan laporan mutasi.
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }

    /**
     * Relasi ke administrator yang memproses verifikasi laporan mutasi ini.
     */
    public function verifikator()
    {
        return $this->belongsTo(Administrator::class, 'diverifikasi_oleh');
    }

    /**
     * Scope query untuk menyaring laporan mutasi yang masih berstatus pending.
     */
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'Pending');
    }
}
