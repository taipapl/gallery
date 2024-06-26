<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = ['email'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_emails', 'email_id', 'user_id')->withPivot('uuid');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'users_tags', 'email_id', 'tag_id')->withPivot('uuid');
    }
}
