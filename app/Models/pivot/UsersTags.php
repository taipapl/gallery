<?php

namespace App\Models\pivot;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UsersTags extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'users_tags';




    /**
     * Get the tag that owns the UsersTags
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }


    public function email(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Email::class);
    }
}
