<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use User;
use App\Traits\HasCreatedEmail;
use App\Models\{Email, Admin\Admin};

class Log extends Model
{
    use HasCreatedEmail;

    const DISABLE_LOGS = true;

    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';
    const TYPE_AUTH = 'auth';
    const TYPE_URL = 'url';

    protected $fillable = ['table', 'row_id', 'data', 'type'];


    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function previewModeEmail()
    {
        return $this->belongsTo(Email::class, 'preview_mode_email_id');
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
                    'data' => $changed,
                    'table' => $model->getTable(),
                ]);
                break;
            default:
                self::create([
                    'type' => $type,
                    'row_id' => $model->id,
                    'data' => $model,
                    'table' => $model->getTable(),
                ]);
        }
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (User::loggedIn()) {
                if (User::isInPreviewMode()) {
                    $model->created_email_id = Admin::find($_SESSION['real_user']['entity_id'])->email->id;
                    $model->preview_mode_email_id = User::emailId();
                } else {
                    $model->created_email_id = User::fromSession()->email->id;
                }
            }
        });
    }
}
