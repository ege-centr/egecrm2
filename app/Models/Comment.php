<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedEmail;

class Comment extends Model
{
    use HasCreatedEmail;

    protected $fillable = ['text'];
}
