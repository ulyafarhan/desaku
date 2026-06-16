<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk merepresentasikan template/kategori layanan persuratan gampong.
 */
class KategoriSurat extends Model
{
    use HasUlids;

    protected $table = 'kategori_surat';
    
    public $timestamps = false;

    protected $fillable = [
        'kode_surat',
        'nama_surat',
        'template_view',
        'schema_isian',
        'syarat_dokumen',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'schema_isian' => 'array',
            'syarat_dokumen' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi ke seluruh berkas pengajuan surat yang menggunakan kategori ini.
     */
    public function pengajuan()
    {
        return $this->hasMany(PengajuanSurat::class, 'kategori_surat_id');
    }

    /**
     * Scope query untuk memfilter hanya template surat yang sedang aktif pelayanan.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
