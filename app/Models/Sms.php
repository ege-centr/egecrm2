<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedEmail;

class Sms extends Model
{
    use HasCreatedEmail;

    const DISABLE_LOGS = true;

    protected $fillable = ['id', 'is_secret'];
    public $timestamps = false;
}
