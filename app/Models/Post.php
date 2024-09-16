<?php

namespace App\Models;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'post',
        'likes',
        'active',
        'created_at',
        'user_id'
    ];

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'posts_photos', 'post_id', 'photo_id')->withPivot('uuid')->orderBy('first', 'desc');
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}