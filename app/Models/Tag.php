<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'photo_id',
        'user_id',
        'is_public',
        'is_album',
        'public_url',
        'is_archived',
        'count',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'photos_tags', 'tag_id', 'photo_id')->withPivot('uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emails()
    {
        return $this->belongsToMany(Email::class, 'users_tags', 'tag_id', 'email_id');
    }

    public function shared()
    {
        return $this->hasMany(pivot\UsersTags::class, 'tag_id');
    }
}
