<?php

namespace App\Models;

use Shared\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\{HasName, Cacheable, Commentable};
use App\Models\{Payment\Payment, Group\Group, Email};
use App\Utils\Phone;

class Teacher extends Model
{
    use HasName, Cacheable;

    protected $commaSeparated = ['subjects_ec'];

    protected $connection = 'egerep';

    protected $table = 'tutors';

    protected $fillable = ['disable_bonuses'];

    public $timestamps = false;

    public function getPhotoUrlAttribute()
    {
        if (dbEgerep('tutor_data')->where('tutor_id', $this->id)->value('photo_exists')) {
            return 'http://static.a-perspektiva.ru/img/tutors/' . $this->id . '.' . $this->photo_extension;
        }
        return '/img/no-profile-img.jpg';
    }

    public function getDefaultNameAttribute()
    {
        return $this->names->abbreviation;
    }

    public function getGroupsAttribute()
    {
        return Group::where('teacher_id', $this->id)->get();
    }

    public function getPaymentsAttribute()
    {
        return Payment::where('entity_type', self::class)->where('entity_id', $this->id)->get();
    }

    public function getEmailAttribute()
    {
        // return $this->attributes['email'] ? ['email' => $this->attributes['email']] : null;
        return Email::where('entity_type', self::class)->where('entity_id', $this->id)->first();
    }

    public function getPhonesAttribute()
    {
        $phones = [];
        foreach(['phone', 'phone2', 'phone3', 'phone4'] as $field) {
            $phone_clean = $this->{$field};
            if ($phone_clean) {
                $phones[] = [
                    'phone_clean' => $phone_clean,
                    'phone' => Phone::format($phone_clean),
                    'comment' => $this->{"{$field}_comment"},
                ];
            }
        }
        return $phones;
    }

    /**
     * Имитируем фотку по правилам новой системы
     */
    public function getPhotoAttribute()
    {
        return (object)[
            'id' => 1,
            'url' => $this->photo_url,
            'url_version' => $this->photo_url,
            'url_original' => $this->photo_url,
            'url_cropped' => $this->photo_url,
            'has_cropped' => 1,
            'entity_type' => self::class,
            'entity_id' => $this->id,
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('in_egecentr', 2);
    }

    public function isBanned()
    {
        return $this->in_egecentr != 2;
    }

    public function allowedToLogin()
    {
        return true;
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('from_egecentr', function(Builder $builder) {
            $builder->where('in_egecentr', '>', 0)->orderByRaw('IF(in_egecentr = 2, 1, 0) desc')->orderByName();
        });
    }
}
