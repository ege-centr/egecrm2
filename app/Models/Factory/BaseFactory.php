<?php

namespace App\Models\Factory;

use Illuminate\Database\Eloquent\Model;

class BaseFactory extends Model
{
    const DISABLE_LOGS = true;
    protected $connection = 'factory';
}
