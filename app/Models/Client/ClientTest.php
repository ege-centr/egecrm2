<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Models\Test\Test;

class ClientTest extends Model
{
    public $timestamps = false;
    protected $fillable = ['client_id', 'test_id'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    // public function getResultsAttribute()
    // {
    //     if ()
    // }
}
