<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class TestProblem extends Model
{
    protected $fillable = ['text'];
    protected $with = ['answers'];

    public $timestamps = false;

    public function answers()
    {
        return $this->hasMany(TestProblemAnswer::class);
    }
}
