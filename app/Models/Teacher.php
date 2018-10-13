<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Teacher extends Model
{
    protected $connection = 'egerep';
    protected $table = 'tutors';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('egecentr_active', function(Builder $builder) {
            $builder->where('in_egecentr', 2);
        });
    }
}
