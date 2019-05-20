<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedEmail;
use App\Models\User;
use Laravel\Scout\Searchable;

class Payment extends Model
{
    use HasCreatedEmail, Searchable;

    protected $fillable = [
        'category', 'type', 'method', 'date', 'sum', 'year', 'entity_type', 'entity_id', 'card_number', 'is_confirmed', 'bill_number'
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    public function toSearchableArray()
    {
        $array = extractFields($this, [
            'id', 'category', 'type', 'method', 'date', 'sum', 'year',
            'is_confirmed', 'bill_number', 'entity_type', 'entity_id'
        ]);

        $array = $this->transform($array);

        return $array;
    }

    public function getClassNameAttribute($value)
    {
        return trim($this->entity_type, 'App\\Models\\');
    }
}
