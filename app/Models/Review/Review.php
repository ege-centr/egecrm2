<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client\Client;

class Review extends Model
{
    protected $fillable = [
        'teacher_id', 'client_id', 'subject_id', 'grade_id',
        'signature', 'expressive_title', 'score', 'max_score',
        'is_approved', 'is_published', 'year'
    ];

    public function comments()
    {
        return $this->hasMany(ReviewComment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    private static function syncSearchable($id, bool $delete = false)
    {
        $model = AbstractReview::search()->where('review_id', $id)->first();
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
            self::syncSearchable($model->id);
        });

        static::created(function ($model) {
            AbstractReview::search()->where([
                'client_id', $model->client_id,
                'teacher_id', $model->teacher_id,
                'subject_id', $model->subject_id,
                'year', $model->year,
            ])->first()->unsearchable();
            self::syncSearchable($model->id);
        });

        static::deleting(function ($model) {
            self::syncSearchable($model->id, true);
        });
    }
}
