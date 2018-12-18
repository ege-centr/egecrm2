<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasName, HasPhones, HasEmail};

class Representative extends Model
{
    use HasName, HasPhones, HasEmail;

    public $timestamps = false;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name', 'series', 'number',
        'birthday', 'issued_date', 'issued_by', 'address', 'code'
    ];
}
