<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Photo extends Model
{
    public $timestamps = false;

    protected $fillable = ['entity_id', 'entity_type'];

    const UPLOAD_PATH = '/img/avatar/';

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
        return '/storage' . self::UPLOAD_PATH . $this->filename_original;
    }

    public function getUrlCroppedAttribute()
    {
        if ($this->has_cropped) {
            return '/storage' . self::UPLOAD_PATH . $this->filename_cropped;
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

    public static function bind(int $photo_id, $model)
    {
        self::whereId($photo_id)->update([
            'entity_type' => get_class($model),
            'entity_id' => $model->id
        ]);
    }

    public static function boot()
    {
        parent::boot();

        // @todo: не работает
        // static::deleting(function($model) {
        //     Storage::delete(self::UPLOAD_PATH . $model->filename_original);
        //     Storage::delete(self::UPLOAD_PATH . $model->filename_cropped);
        // });
    }
}
