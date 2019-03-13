<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    const DISABLE_LOGS = true;
    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';

    protected $fillable = ['table', 'row_id', 'data', 'type'];
    protected $appends = ['user_name'];

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    public function getUserNameAttribute()
    {
        if ($this->email !== null) {
            $class = $this->email->entity_type;
            return $class::whereId($this->email->entity_id)->selectRaw("CONCAT(first_name, ' ', last_name) as `name`")->value('name');
        }
        return 'не установлено';
    }

    public static function add($type, $model)
    {
        switch($type) {
            case self::TYPE_UPDATE:
                $changed = [];
                foreach($model->getDirty() as $field => $new) {
                    $old = $model->getOriginal($field);
                    $changed[] = compact('field', 'old', 'new');
                }
                self::create([
                    'type' => $type,
                    'row_id' => $model->id,
                    'data' => json_encode($changed),
                    'table' => $model->getTable(),
                ]);
                break;
            default:
                self::create([
                    'type' => $type,
                    'row_id' => $model->id,
                    'data' => $model->toJson(),
                    'table' => $model->getTable(),
                ]);
        }
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (User::loggedIn()) {
                // TODO: должен сохраняться правильный из режима просмотра
                // $model->email_id = User::fromSession()->email->id;
            }
        });
    }
}
