<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientTest extends Model
{
    public $timestamps = false;
    protected $fillable = ['client_id', 'test_id'];
}
