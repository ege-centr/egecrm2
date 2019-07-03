<?php

namespace App\Http\Resources\Photo;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'url', 'url_version', 'url_original', 'url_cropped', 'has_cropped',
            'entity_type', 'entity_id'
        ],
        // временные поля на время обрезки фоток клиента
        [
            'filename_original' => $this->filename_original,
            'filename_cropped' => $this->filename_cropped,
            'original_size' => self::getSize($this->filename_original),
            'cropped_size' => ($this->has_cropped ? self::getSize($this->filename_cropped) : null),
        ]);
    }

    // временно на время обрезки фоток клиента
    public static function getSize($filename)
    {
        $size = filesize(storage_path() . '/app/public/img/avatar/' . $filename);
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}
