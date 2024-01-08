<?php

namespace App\Models;

use App\Casts\Json;
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
        'is_video',
        'video_path',
        'photo_date'
    ];


    protected $casts = [
        'meta' => Json::class,
    ];


    protected function videoImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->meta['video_image'] ?? null,
        );
    }
}
