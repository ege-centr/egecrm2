<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasName;

class Teacher extends Model
{
    use HasName;

    protected $connection = 'egerep';
    protected $table = 'tutors';

    public function getPhotoUrlAttribute()
    {
        if (dbEgerep('tutor_data')->where('tutor_id', $this->id)->value('photo_exists')) {
            return 'http://static.a-perspektiva.ru/img/tutors/' . $this->id . '.' . $this->photo_extension;
        }
        return '/img/no-profile-img.jpg';
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('egecentr_active', function(Builder $builder) {
            $builder->where('in_egecentr', 2)->orderByName();
        });
    }
}
