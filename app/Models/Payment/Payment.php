<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedEmail;
use App\Models\User;

class Payment extends Model
{
    use HasCreatedEmail;

    protected $fillable = [
        'category', 'type', 'method', 'date', 'sum', 'year', 'entity_type', 'entity_id', 'card_number', 'is_confirmed'
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    public function getClassNameAttribute($value)
    {
        return trim(self::class, 'App\\Models\\');
    }
}
