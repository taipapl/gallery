<?php

namespace App\Models;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'posts_photos', 'post_id', 'photo_id')->withPivot('uuid')->orderBy('first', 'desc');
    }
}
