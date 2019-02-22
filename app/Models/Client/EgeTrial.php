<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;

class EgeTrial extends Model
{
    use HasCreatedAdmin;

    protected $fillable = [
        'sum', 'date', 'subject_id', 'grade_id', 'score', 'max_score',
        'year', 'description', 'client_id'
    ];
}
