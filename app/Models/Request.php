<?php

namespace App\Models;

use Shared\Model;
use App\Traits\{Enumable, HasPhones, Commentable};
use App\Models\Client\Client;

class Request extends Model
{
    use Enumable, HasPhones, Commentable;
    protected $fillable = [
        'name', 'grade', 'comment', 'branches', 'responsible_admin_id', 'subjects',
        'google_id', 'status'
    ];
    protected $hidden = ['updated_at'];
    protected $commaSeparated = ['subjects', 'branches'];

    public function responsibleAdmin()
    {
        return $this->belongsTo(Admin\Admin::class, 'responsible_admin_id');
    }

    public function getClientIds()
    {
        $client_ids = [];
        foreach($this->phones as $phone) {
            $ids = Phone::where('entity_type', Client::class)->where('phone', $phone->phone_clean)->pluck('entity_id')->all();
            if (count($ids)) {
                $client_ids = array_merge($client_ids, $ids);
            }
        }

        return $client_ids;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (User::loggedIn()) {
                $model->created_admin_id = User::id();
            }
        });
    }
}
