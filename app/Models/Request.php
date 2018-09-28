<?php

namespace App\Models;

use Shared\Model;
use App\Traits\HasPhones;

class Request extends Model
{
    use HasPhones;

    protected $fillable = [
        'name', 'grade', 'comment', 'branches', 'responsible_user_id', 'subjects',
        'google_id', 'status'
    ];

    protected $hidden = ['updated_at'];

    protected $commaSeparated = ['subjects', 'branches'];

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (User::loggedIn()) {
                $model->created_user_id = User::id();
            }
        });
    }
}
