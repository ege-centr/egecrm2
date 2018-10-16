<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasName;

class Teacher extends Model
{
    use HasName;

    protected $connection = 'egerep';
    protected $table = 'tutors';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('egecentr_active', function(Builder $builder) {
            $builder->where('in_egecentr', 2)->orderByName();
        });
    }
}
