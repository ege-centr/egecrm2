<?php

namespace App\Models\Client;

use Shared\Model;
use App\Traits\{HasPhones, HasEmail, HasPhoto, HasName, Commentable};
use App\Http\Resources\Request\Collection as RequestCollection;
use App\Models\{Request, Phone, Payment, Contract\Contract, Group\Group, Group\GroupClient};

class Client extends Model
{
    use HasPhones, HasEmail, HasPhoto, HasName, Commentable;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name',
        'grade', 'year', 'branches'
    ];

    protected $commaSeparated = ['branches'];

    public function passport()
    {
        return $this->hasOne(ClientPassport::class);
    }

    public function markers()
    {
        return $this->hasMany(ClientMarker::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function groups()
    {
        return $this->hasManyThrough(Group::class, GroupClient::class, 'client_id', 'id', 'id', 'group_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'entity');
    }

    public function getRequests()
    {
        $request_ids = [];
        foreach($this->phones as $phone) {
            $ids = Phone::where('entity_type', Request::class)->where('phone', $phone->phone_clean)->pluck('entity_id')->all();
            if (count($ids)) {
                $request_ids = array_merge($request_ids, $ids);
            }
        }
        $requests = Request::with('responsibleAdmin')
            ->whereIn('id', $request_ids)
            ->orderBy('id', 'desc')
            ->get();
        return resourceCollection($requests, RequestCollection::class);
    }
}
