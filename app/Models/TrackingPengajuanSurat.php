<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk merepresentasikan log riwayat perubahan status (tracking) dari pengajuan surat warga.
 */
class TrackingPengajuanSurat extends Model
{
    use HasUlids;

    protected $table = 'tracking_pengajuan_surat';
    
    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'pengajuan_surat_id',
        'status_sebelumnya',
        'status_baru',
        'keterangan_update',
        'diupdate_oleh',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke data berkas pengajuan surat yang ditracking.
     */
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_surat_id');
    }

    /**
     * Relasi ke data administrator yang melakukan perubahan status.
     */
    public function updater()
    {
        return $this->belongsTo(Administrator::class, 'diupdate_oleh');
    }
}
