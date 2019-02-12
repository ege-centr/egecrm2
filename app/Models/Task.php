<?php

namespace App\Models;

use Shared\Model;
use App\Traits\{Commentable, HasCreatedAdmin};

class Task extends Model
{
    use Commentable, HasCreatedAdmin;

    protected $commaSeparated = ['attachments'];

    protected $fillable = [
        'text', 'status', 'responsible_admin_id'
    ];
}
