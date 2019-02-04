<?php

namespace App\Models;

use Shared\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasName;
use App\Models\{Payment\Payment, Group\Group};

class Teacher extends Model
{
    use HasName;

    protected $commaSeparated = ['subjects_ec'];

    protected $connection = 'egerep';
    protected $table = 'tutors';

    public function getPhotoUrlAttribute()
    {
        if (dbEgerep('tutor_data')->where('tutor_id', $this->id)->value('photo_exists')) {
            return 'http://static.a-perspektiva.ru/img/tutors/' . $this->id . '.' . $this->photo_extension;
        }
        return '/img/no-profile-img.jpg';
    }

    public function getGroupsAttribute()
    {
        return Group::where('teacher_id', $this->id)->get();
    }

    public function getPaymentsAttribute()
    {
        return Payment::where('entity_type', self::class)->where('entity_id', $this->id)->get();
    }

    public function scopeActive($query)
    {
        return $query->where('in_egecentr', 2);
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('from_egecentr', function(Builder $builder) {
            $builder->where('in_egecentr', '>', 0)->orderByRaw('IF(in_egecentr = 2, 1, 0) desc')->orderByName();
        });
    }
}
