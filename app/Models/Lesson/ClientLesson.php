<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Client\Client};

class ClientLesson extends Model
{
    protected $fillable = [
        'price', 'late', 'is_absent', 'comment',
    ];

    protected $with = ['client'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
