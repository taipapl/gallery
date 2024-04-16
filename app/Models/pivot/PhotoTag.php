<?php

namespace App\Models\pivot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoTag extends Model
{
    use HasFactory;

    protected $table = 'photos_tags';

    protected $fillable = [
        'uuid',
        'user_id',
        'photo_id',
        'tag_id'
    ];
}
