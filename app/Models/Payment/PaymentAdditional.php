<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasCreatedEmail, HasEntity};

class PaymentAdditional extends Model
{
    use HasCreatedEmail, HasEntity;

    protected $fillable = ['date', 'sum', 'year', 'entity_type', 'entity_id', 'purpose'];
}
