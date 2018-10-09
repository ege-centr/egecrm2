<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientMarker extends Model
{
    public $timestamps = false;
    protected $fillable = ['lat', 'lng'];
}
