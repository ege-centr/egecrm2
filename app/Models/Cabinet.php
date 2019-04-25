<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Cacheable;

class Cabinet extends Model
{
    use Cacheable;

    public $timestamps = false;

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->title,
            'color' => optional($this->branch)->color,
        ];
    }

    public function getTitleAttribute()
    {
        return optional($this->branch)->short . '–' . $this->number;
    }

    public function getBranchAttribute()
    {
        return Factory\Branch::whereId($this->branch_id)->first();
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function(Builder $builder) {
            $builder->orderByRaw('branch_id asc, number asc');
        });

        static::addGlobalScope('unused', function (Builder $builder) {
            $builder->whereIn('branch_id', [1, 2]);
        });
    }
}
