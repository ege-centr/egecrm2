<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    const DISABLE_LOGS = true;
    public $timestamps = false;
    protected $fillable = ['name', 'original_name'];
}
