<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminIp extends Model
{
    public $timestamps = false;
    protected $fillable = ['ip_from', 'ip_to', 'confirm_by_sms'];
}
