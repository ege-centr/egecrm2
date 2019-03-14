<?php

namespace App\Models;

use Shared\Model;
use App\Traits\{Commentable, HasCreatedEmail};

class Task extends Model
{
    use Commentable, HasCreatedEmail;

    protected $commaSeparated = ['attachments'];

    protected $fillable = [
        'text', 'status', 'responsible_admin_id'
    ];
}
