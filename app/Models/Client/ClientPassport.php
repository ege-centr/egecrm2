<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientPassport extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name', 'series', 'number',
        'birthday', 'issued_date', 'issued_by', 'address', 'code'
    ];
}