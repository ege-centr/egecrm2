<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    public $timestamps = false;
    protected $fillable = ['lat', 'lng'];
}
