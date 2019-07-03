<?php

namespace App\Models;

use Shared\Model;
use App\Traits\{Enumable, HasPhones, HasCreatedEmail, Commentable};
use App\Models\Client\{Client, Representative};
use App\Models\Phone;
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
            $ids = Phone::entity(Client::class)->where('phone', $phone->phone_clean)->pluck('entity_id')->all();
            if (count($ids)) {
                $client_ids = array_merge($client_ids, $ids);
            }
            $ids = Phone::entity(Representative::class)->where('phone', $phone->phone_clean)->pluck('entity_id')->all();
            if (count($ids)) {
                $client_ids = array_merge($client_ids, Representative::whereIn('id', $ids)->pluck('client_id')->all());
            }
        }

        return array_unique($client_ids);
    }

    /**
     * Получить ассоциативные заявки
     */
    public function getRelativeIds()
    {
        $relativeIds = collect();

        $requestPhones = $this->phones->pluck('phone_clean')->all();

        // находим номера телефонов клиентов
        $clientIds = Phone::entity(Client::class)
            ->whereIn('phone', $requestPhones)
            ->pluck('entity_id')
            ->all();

        // находим все заявки по номерам телефона клиентов
        $clients = Client::whereIn('id', $clientIds)->get();
        foreach($clients as $client) {
            $relativeIds = $relativeIds->merge($client->requests(true));
        }

        // все заявки по телефонам самой заявки
        $requestIds = Phone::entity(self::class)
            ->whereIn('phone', $requestPhones)
            ->pluck('entity_id')
            ->all();

        return $relativeIds
            ->merge($requestIds)
            ->diff($this->id)
            ->values()
            ->unique()
            ->all();
    }
}
