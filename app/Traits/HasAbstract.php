<?php

namespace App\Traits;

/**
 * Синхронизация с Scout и абстрактных записей
 */
trait HasAbstract
{
    public static function getAbstractClass()
    {
        $parts = explode('\\', static::class);
        $lastIndex = count($parts) - 1;
        $parts[$lastIndex] = 'Abstract' . $parts[$lastIndex];
        return implode('\\', $parts);
    }

    public static function syncModel($model, bool $delete = false)
    {
        $model = static::getAbstractClass()::where((new static)->getTable() . '.id', $model->id)->first();
        if ($delete) {
            $model->unsearchable();
        } else {
            $model->searchable();
        }
    }

    public static function syncAbstract($model, bool $delete = false)
    {
        // TODO: не находится по
        // App\Models\Review\AbstractReview::search()->where('client_id', 35019)->get();
        // а через paginateRaw находится
        $model = static::getAbstractClass()::where('client_lessons.client_id', $model->client_id)
            ->where('lessons.teacher_id', $model->teacher_id)
            ->where('groups.subject_id', $model->subject_id)
            ->where('groups.year', $model->year);
        if ($delete) {
            $model->unsearchable();
        } else {
            $model->searchable();
        }
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            self::syncModel($model);
        });

        static::creating(function ($model) {
            self::syncAbstract($model, true);
        });

        static::deleting(function ($model) {
            self::syncModel($model, true);
        });

        static::deleted(function ($model) {
            self::syncAbstract($model);
        });
    }
}
