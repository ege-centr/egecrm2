<?php

namespace App\Models;

use Shared\Model;
use App\Traits\{Commentable, HasCreatedEmail, HasFiles};

class Task extends Model
{
    use Commentable, HasCreatedEmail, HasFiles;

    protected $fillable = [
        'text', 'status', 'responsible_admin_id', 'priority'
    ];
}
