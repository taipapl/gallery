<?php

namespace App\Models\pivot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPhoto extends Model
{
    use HasFactory;

    protected $table = 'posts_photos';

    protected $fillable = [
        'uuid'
    ];
}
