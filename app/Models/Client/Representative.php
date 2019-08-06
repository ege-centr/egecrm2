<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasName, HasPhones, HasEmail};
use App\Interfaces\UserInterface;

class Representative extends Model implements UserInterface
{
    use HasName, HasPhones, HasEmail;

    public $timestamps = false;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name', 'series', 'number',
        'birthday', 'issued_date', 'issued_by', 'address', 'code', 'fact_address'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function isBanned()
    {
        return $this->client->isBanned();
    }

    public function allowedToLogin()
    {
        return true;
    }

    public function photo()
    {
        return $this->client->photo();
    }
}
