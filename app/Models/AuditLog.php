<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
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

    protected function casts(): array
    {
        return [
            'data_lama' => 'array',
            'data_baru' => 'array',
            'created_at' => 'datetime',
        ];
    }

    // Helper method untuk log audit
    public static function log(
        string $userType,
        ?int $userId,
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
