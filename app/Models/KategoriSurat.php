<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSurat extends Model
{
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

    // Relationships
    public function pengajuan()
    {
        return $this->hasMany(PengajuanSurat::class, 'kategori_surat_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
