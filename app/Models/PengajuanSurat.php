<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk merepresentasikan data permohonan pengajuan surat warga.
 */
class PengajuanSurat extends Model
{
    use HasUlids;

    protected $table = 'pengajuan_surat';

    public $incrementing = false;

    protected $keyType = 'string';

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

    /**
     * Accessor untuk memformat nomor registrasi surat resmi secara dinamis.
     */
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

    /**
     * Relasi ke data penduduk yang mengajukan surat ini.
     */
    public function pemohon()
    {
        return $this->belongsTo(Penduduk::class, 'nik_pemohon', 'nik');
    }

    /**
     * Relasi ke kategori/template surat yang diajukan.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_surat_id');
    }

    /**
     * Relasi ke administrator yang menolak/menyetujui pengajuan surat ini.
     */
    public function verifikator()
    {
        return $this->belongsTo(Administrator::class, 'diverifikasi_oleh');
    }

    /**
     * Relasi ke kumpulan log riwayat perubahan status (tracking) pengajuan.
     */
    public function tracking()
    {
        return $this->hasMany(TrackingPengajuanSurat::class, 'pengajuan_surat_id');
    }

    /**
     * Scope query untuk menyaring surat yang masih berstatus pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'Diproses');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_registrasi)) {
                $model->nomor_registrasi = static::generateNomorRegistrasi();
            }
        });
    }

    /**
     * Menghasilkan nomor registrasi pengajuan surat harian secara otomatis.
     */
    public static function generateNomorRegistrasi(): string
    {
        $prefix = date('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return sprintf('%s-%04d', $prefix, $count);
    }
}
