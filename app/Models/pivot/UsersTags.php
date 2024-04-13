<?php

namespace App\Models\pivot;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Email;

class UsersTags extends Model
{
    use HasFactory;

    protected $table = 'users_tags';

    protected $fillable = [
        'uuid'
    ];

    /**
     * Get the tag that owns the UsersTags
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
}
