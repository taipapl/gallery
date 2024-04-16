<?php

namespace App\Models\pivot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersEmails extends Model
{
    use HasFactory;

    protected $table = 'users_emails';

    protected $casts = [
        'share_blog' => 'boolean',
    ];

    protected $fillable = [
        'uuid',
        'user_id',
        'email',
        'share_blog',
    ];
}