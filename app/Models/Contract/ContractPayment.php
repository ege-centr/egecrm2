<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Model;

class ContractPayment extends Model
{
    public $timestamps = false;
    protected $fillable = ['date', 'sum', 'lessons'];
}
