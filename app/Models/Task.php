<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{Commentable, HasCreatedAdmin};

class Task extends Model
{
    use Commentable, HasCreatedAdmin;

    protected $fillable = [
        'text', 'status', 'responsible_admin_id'
    ];
}
