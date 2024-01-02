<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersEmails extends Model
{
    use HasFactory;

    protected $table = 'users_emails';
}
