<?php

namespace App\Models\pivot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersEmails extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'users_emails';

    protected $casts = [
        'send_public' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'email',
        'send_public',
    ];
}
