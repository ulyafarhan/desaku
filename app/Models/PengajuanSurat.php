<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'nomor_registrasi',
        'nik_pemohon',
        'kategori_surat_id',
        'data_isian',
        'file_syarat',
        'status',
        'catatan_penolakan',
        'qr_hash',
        'file_pdf_url',
        'diverifikasi_oleh',
    ];

    protected function casts(): array
    {
        return [
            'data_isian' => 'array',
            'file_syarat' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relationships
    public function pemohon()
    {
        return $this->belongsTo(Penduduk::class, 'nik_pemohon', 'nik');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_surat_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(Administrator::class, 'diverifikasi_oleh');
    }

    public function tracking()
    {
        return $this->hasMany(TrackingPengajuanSurat::class, 'pengajuan_surat_id');
    }

    // Scope
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'Diproses');
    }

    // Boot method untuk auto-generate nomor registrasi
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_registrasi)) {
                $model->nomor_registrasi = static::generateNomorRegistrasi();
            }
        });
    }

    public static function generateNomorRegistrasi(): string
    {
        $prefix = date('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return sprintf('%s-%04d', $prefix, $count);
    }
}
