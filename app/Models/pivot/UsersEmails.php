<?php

namespace App\Models\pivot;

use App\Models\Email;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersEmails extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'users_emails';
}
