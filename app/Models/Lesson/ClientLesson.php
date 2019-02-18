<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\{Model, Builder};
use App\Models\{Client\Client, Cabinet};

class ClientLesson extends Model
{
    protected $table = 'lessons';
    protected $fillable = [
        'teacher_id', 'cabinet_id', 'date', 'time', 'price',
        'status', 'is_unplanned', 'group_id', 'year', 'entry_id',
        'late', 'is_absent', 'comment', 'entity_id',
    ];
    protected $with = ['client'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'entity_id');
    }

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->entity_type = Client::class;
        });

        static::addGlobalScope('clients', function (Builder $builder) {
            $builder->where('entity_type', Client::class);
        });
    }
}
