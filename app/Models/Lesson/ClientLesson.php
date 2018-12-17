<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\{Model, Builder};
use App\Models\{Client\Client};

class ClientLesson extends Model
{
    protected $table = 'lessons';
    protected $fillable = ['late', 'is_absent', 'comment'];
    protected $with = ['client'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'entity_id');
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('clients', function (Builder $builder) {
            $builder->where('entity_type', Client::class);
        });
    }
}
