<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasCreatedEmail, HasEntity};
use App\Models\{User, Client\Client};
use Laravel\Scout\Searchable;

class Payment extends Model
{
    use HasCreatedEmail, Searchable, HasEntity;

    protected $casts = [
        'entity_id' => 'integer',
    ];

    protected $fillable = [
        'category', 'type', 'method', 'date', 'sum', 'year', 'entity_type', 'entity_id', 'card_number', 'is_confirmed', 'bill_number'
    ];

    public function toSearchableArray()
    {
        return extractFields($this, [
            'id', 'category', 'type', 'method', 'date', 'sum', 'year', 'created_email_id',
            'is_confirmed', 'bill_number', 'entity_type', 'entity_id', 'created_at'
        ], [
            'date_timestamp' => strtotime($this->date)
        ]);
    }

    public function getClassNameAttribute($value)
    {
        return trim($this->entity_type, 'App\\Models\\');
    }
}
