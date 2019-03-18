<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedEmail;

class SmsMessage extends Model
{
    use HasCreatedEmail;

    const DISABLE_LOGS = true;

    protected $fillable = ['id', 'is_secret'];
    public $timestamps = false;
}
