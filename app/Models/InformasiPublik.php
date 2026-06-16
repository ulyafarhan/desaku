<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Str;
use App\Jobs\SendNewsTelegramNotificationJob;

/**
 * Model untuk merepresentasikan artikel berita dan pengumuman gampong.
 */
class InformasiPublik extends Model
{
    use HasUlids;

    protected $table = 'informasi_publik';
    
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'kategori',
        'cover_image',
        'meta_description',
        'kata_kunci',
        'is_published',
        'author_id',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Accessor untuk mendapatkan URL cover image secara dinamis.
     */
    public function getCoverImageAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return \Illuminate\Support\Facades\Storage::url($value);
    }

    /**
     * Relasi ke administrator/perangkat desa yang mempublikasikan berita ini.
     */
    public function author()
    {
        return $this->belongsTo(Administrator::class, 'author_id');
    }

    /**
     * Scope query untuk hanya menyaring artikel yang telah dipublikasikan.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('judul') && empty($model->slug)) {
                $model->slug = Str::slug($model->judul);
            }
        });

        static::created(function ($model) {
            if ($model->is_published) {
                SendNewsTelegramNotificationJob::dispatch($model->id);
            }
        });

        static::updated(function ($model) {
            if ($model->is_published && !$model->getOriginal('is_published')) {
                SendNewsTelegramNotificationJob::dispatch($model->id);
            }
        });
    }
}
