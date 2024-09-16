<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'cover'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'photos_tags', 'tag_id', 'photo_id')->withPivot('uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(Email::class, 'users_tags', 'tag_id', 'email_id')->withPivot('uuid');
    }

    public function shared(): HasMany
    {
        return $this->hasMany(pivot\UsersTags::class, 'tag_id');
    }
}