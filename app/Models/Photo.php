<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'label',
        'path',
        'meta',
        'user_id',
        'is_video',
        'video_path',
        'photo_date',
        'is_archived',
        'label',
    ];

    protected $casts = [
        'meta' => Json::class,
        'photo_date' => 'datetime',
        'is_archived' => 'boolean',
    ];

    protected function videoImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->meta['video_image'] ?? null,
        );
    }
}
