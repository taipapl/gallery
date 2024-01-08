<?php

namespace App\Models\Pivot;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersTags extends Model
{
    use HasFactory;

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
