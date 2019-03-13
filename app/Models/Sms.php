<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;

class Sms extends Model
{
    use HasCreatedAdmin;

    const DISABLE_LOGS = true;

    protected $fillable = ['id', 'is_secret'];
    public $timestamps = false;
}
