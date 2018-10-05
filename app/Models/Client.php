<?php

namespace App\Models;

use Shared\Model;
use App\Traits\{HasPhones, HasEmail, HasPhoto};
use App\Http\Resources\Request\Collection as RequestCollection;

class Client extends Model
{
    use HasPhones, HasEmail, HasPhoto;

    protected $fillable = [
        'student_first_name', 'student_last_name', 'student_middle_name',
        'representative_first_name', 'representative_last_name', 'representative_middle_name',
        'grade', 'year', 'branches'
    ];

    protected $commaSeparated = ['branches'];

    public function passport()
    {
        return $this->hasOne(ClientPassport::class);
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
