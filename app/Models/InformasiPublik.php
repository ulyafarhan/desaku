<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InformasiPublik extends Model
{
    protected $table = 'informasi_publik';
    
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'kategori',
        'cover_image',
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

    // Accessor for cover image
    public function getCoverImageAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset('storage/' . $value);
    }

    // Relationships
    public function author()
    {
        return $this->belongsTo(Administrator::class, 'author_id');
    }

    // Scope
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Boot method untuk auto-generate slug
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
    }
}
