<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;

class PaymentAdditional extends Model
{
    use HasCreatedAdmin;

    protected $fillable = ['date', 'sum', 'year', 'entity_type', 'entity_id', 'purpose'];
}
