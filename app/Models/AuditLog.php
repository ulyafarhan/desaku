<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * Model untuk mencatat log riwayat audit aktivitas pengguna/sistem (audit trails).
 */
class AuditLog extends Model
{
    use HasUlids;

    protected $table = 'audit_logs';
    
    public $timestamps = false;

    protected $fillable = [
        'user_type',
        'user_id',
        'tindakan',
        'nama_tabel',
        'record_id',
        'data_lama',
        'data_baru',
        'ip_address',
        'user_agent',
    ];

    /**
     * Casting atribut ke tipe data native.
     */
    protected function casts(): array
    {
        return [
            'data_lama' => 'array',
            'data_baru' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Membuat catatan log aktivitas audit baru.
     *
     * @param string $userType Tipe user ('admin', 'warga', atau 'system').
     * @param string|null $userId ID pengidentifikasi user.
     * @param string $tindakan Jenis tindakan (misal: 'create', 'update', 'delete').
     * @param string $namaTabel Nama tabel database terkait.
     * @param string|null $recordId ID record tabel yang dimanipulasi.
     * @param array|null $dataLama Payload data sebelum perubahan.
     * @param array|null $dataBaru Payload data setelah perubahan.
     */
    public static function log(
        string $userType,
        ?string $userId,
        string $tindakan,
        string $namaTabel,
        ?string $recordId = null,
        ?array $dataLama = null,
        ?array $dataBaru = null
    ): void {
        static::create([
            'user_type' => $userType,
            'user_id' => $userId,
            'tindakan' => $tindakan,
            'nama_tabel' => $namaTabel,
            'record_id' => $recordId,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
