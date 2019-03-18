<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client\Client;
use App\Models\Sms\{SmsTemplate, SmsTemplateCode};

class ClientLesson extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'client_id', 'lesson_id', 'grade_id',
        'price', 'late', 'is_absent', 'comment',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopeJoinLessons($query)
    {
        return $query->join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // if ($model->late > 5) {
            //     SmsTemplate::send(
            //         $model->client->getPhonesArray(),
            //         SmsTemplateCode::CLIENT_LESSON_LATE(),
            //         [
            //             'minutes'       => $model->late,
            //             'client-name'   => $model->client->default_name,
            //         ]
            //     );
            // }
            // if ($model->is_absent) {
            //     SmsTemplate::send(
            //         $model->client->getPhonesArray(),
            //         SmsTemplateCode::CLIENT_LESSON_SKIPPED(),
            //         [
            //             'client-name' => $model->client->default_name,
            //         ]
            //     );
            // }
        });
    }
}
