<?php

namespace App\Models;

use App\Casts\Json;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'label',
        'path',
        'meta',
        'user_id',
        'is_video',
        'video_path',
        'photo_date',
        'is_archived',
        'is_favorite',
        'label',
        'position',
    ];

    protected $casts = [
        'meta' => Json::class,
        'photo_date' => 'datetime',
        'is_archived' => 'boolean',
    ];

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'photos_tags', 'photo_id', 'tag_id');
    }

    protected function videoImage(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->meta['video_image'] ?? null,
        );
    }

    public function archive(): void
    {
        $this->is_archived = $this->is_archived ? false : true;
        $this->save();
    }

    public function favorite(): void
    {
        $this->is_favorite = $this->is_favorite ? false : true;
        $this->save();
    }

    public function publish(): void
    {
        $this->is_blog = $this->is_blog ? false : true;
        $this->save();
    }

    public function rotateLeft(): void
    {
        $img = Image::make(storage_path('app/photos/' . $this->user_id . '/' . $this->path));
        $img->rotate(-90);
        $img->save();
    }
}