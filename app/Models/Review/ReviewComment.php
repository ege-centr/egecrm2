<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedEmail;
use User;

class ReviewComment extends Model
{
    use HasCreatedEmail;

    protected $fillable = ['rating', 'text', 'type'];
    protected $touches = ['review'];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
