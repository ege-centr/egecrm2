<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client\Client;
use App\Traits\HasAbstract;

class Review extends Model
{
    use HasAbstract;

    public $timestamps = false;

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
}
