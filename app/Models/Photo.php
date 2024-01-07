<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Photo extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'label',
        'path',
        'meta',
        'user_id',
        'is_video'
    ];


    protected $casts = [
        'meta' => 'array',
    ];


    protected function videoImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->meta['video_image'] ?? null,
        );
    }
}
