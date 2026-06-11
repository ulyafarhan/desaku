<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PengajuanSurat extends Model
{
    use HasUlids;

    protected $table = 'pengajuan_surat';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $with = ['kategori', 'pemohon'];

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

    protected $appends = ['nomor_surat'];

    public function getNomorSuratAttribute(): string
    {
        $counter = self::where('kategori_surat_id', $this->kategori_surat_id)
            ->where('id', '<=', $this->id)
            ->whereYear('created_at', $this->created_at?->year ?? date('Y'))
            ->count();

        return sprintf(
            '%s/%03d/GAMPONG-UDEUNG/%s/%s',
            $this->kategori?->kode_surat ?? 'SRT',
            $counter,
            strtoupper($this->created_at?->locale('id')->monthName ?? now()->locale('id')->monthName),
            $this->created_at?->format('Y') ?? date('Y')
        );
    }

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
