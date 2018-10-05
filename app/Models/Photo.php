<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Photo extends Model
{
    public $timestamps = false;

    const UPLOAD_PATH = 'public/img/users/';

    // в этом формате сохраняются все фотки
    const EXTENSION = 'jpg';

    public function getFilenameOriginalAttribute()
    {
        return $this->id . '_original.' . self::EXTENSION;
    }

    public function getFilenameCroppedAttribute()
    {
        return $this->id . '_cropped.' . self::EXTENSION;
    }

    public function getUrlOriginalAttribute()
    {
        return '/storage/img/users/' . $this->filename_original;
    }

    public function getUrlCroppedAttribute()
    {
        if ($this->has_cropped) {
            return '/storage/img/users/' . $this->filename_cropped;
        }
        return null;
    }

    /**
     * Cropped if exists
     */
    public function getUrlAttribute()
    {
        return $this->url_cropped ?? $this->url_original;
    }

    /**
     * Для force reload после изменения
     */
    public function getUrlVersionAttribute()
    {
        return $this->url . '?ver=' . str_random(10);
    }

    public static function boot()
    {
        parent::boot();

        // @todo: не работает
        static::deleting(function($model) {
            Storage::delete(self::UPLOAD_PATH . $model->filename_original);
            Storage::delete(self::UPLOAD_PATH . $model->filename_cropped);
        });
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'url' => $this->url,
            'url_version' => $this->url_version,
            'url_original' => $this->url_original,
            'url_cropped' => $this->url_cropped,
        ]);
    }
}
