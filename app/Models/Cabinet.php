<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cabinet extends Model
{
    public $timestamps = false;

    public function toArray()
    {
        $title = Factory\Branch::whereId($this->branch_id)->value('short') . '–' . $this->number;
        return [
            'id' => $this->id,
            'title' => $title,
            'text' => $title,
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function(Builder $builder) {
            $builder->orderByRaw('branch_id asc, number asc');
        });
    }
}
