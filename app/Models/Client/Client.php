<?php

namespace App\Models\Client;

use Shared\Model;
use App\Interfaces\UserInterface;
use App\Traits\{HasPhones, HasEmail, HasPhoto, HasName, Commentable};
use App\Http\Resources\Request\Collection as RequestCollection;
use App\Http\Resources\Teacher\Collection as TeacherResource;
use App\Models\{Request, Phone, Payment, Teacher, Contract\Contract, Group\Group, Group\GroupClient};

class Client extends Model implements UserInterface
{
    use HasPhones, HasEmail, HasPhoto, HasName, Commentable;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name',
        'grade_id', 'year', 'branches', 'school'
    ];

    protected $commaSeparated = ['branches'];

    public function representative()
    {
        return $this->hasOne(Representative::class);
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

    public function isBanned()
    {
        return false;
    }

    public function allowedToLogin()
    {
        return true;
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

    public function getHeadTeacher()
    {
        if ($this->head_teacher_id) {
            return new TeacherResource(Teacher::find($this->head_teacher_id));
        }
        return null;
    }

    public function getBars()
    {
        $client_bars = null;
        foreach($this->groups as $group) {
            $group_bars = $group->getSchedule()['bars'];
            if ($client_bars === null) {
                $client_bars = $group_bars;
            } else {
                foreach($group_bars as $i => $bars) {
                    foreach($bars as $j => $bar) {
                        if ($bar !== null && $client_bars[$i][$j] === null) {
                            $client_bars[$i][$j] = $bar;
                        }
                    }
                }
            }
        }
        return $client_bars;
    }
}
