<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasPhones, HasPassport};

class Representative extends Model
{
    use HasPhones, HasPassport;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name', 'status'
    ];

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'phones' => $this->phones,
            'passport' => $this->passport
        ]);
    }
}
