<?php

namespace App\Models;

use Shared\Model;
use App\Traits\{Enumable, HasPhones, HasCreatedEmail, Commentable};
use App\Models\Client\{Client, Representative};
use Laravel\Scout\Searchable;

class Request extends Model
{
    use Enumable, HasPhones, HasCreatedEmail, Commentable, Searchable;

    protected $fillable = [
        'name', 'grade_id', 'comment', 'responsible_admin_id',
        'subjects', 'google_id', 'status', 'branches',
    ];

    protected $hidden = ['updated_at'];

    protected $commaSeparated = ['subjects', 'branches'];

    protected $casts = ['grade_id' => 'integer'];

    protected $attributes = ['status' => 'new'];

    public function responsibleAdmin()
    {
        return $this->belongsTo(Admin\Admin::class, 'responsible_admin_id');
    }

    public function toSearchableArray()
    {
        return extractFields($this, [
            'id', 'status', 'grade_id', 'responsible_admin_id', 'created_email_id'
        ], [
            'created_at_timestamp' => strtotime($this->created_at)
        ]);
    }

    public function getClientIds()
    {
        $client_ids = [];
        foreach($this->phones as $phone) {
            $ids = Phone::where('entity_type', Client::class)->where('phone', $phone->phone_clean)->pluck('entity_id')->all();
            if (count($ids)) {
                $client_ids = array_merge($client_ids, $ids);
            }
            $ids = Phone::where('entity_type', Representative::class)->where('phone', $phone->phone_clean)->pluck('entity_id')->all();
            if (count($ids)) {
                $client_ids = array_merge($client_ids, Representative::whereIn('id', $ids)->pluck('client_id')->all());
            }
        }

        return array_unique($client_ids);
    }
}
