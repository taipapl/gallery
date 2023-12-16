<?php

namespace App\Models\pivot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class photo_tag extends Model
{
    use HasFactory;

    protected $table = 'photos_tags';
    protected $timestamps = false;
    protected $fillable = ['photo_id', 'tag_id'];
}
