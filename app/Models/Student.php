<?php

namespace App\Models;

use Shared\Model;
use App\Traits\HasPhones;
use App\Http\Resources\Request\Collection as RequestCollection;

class Student extends Model
{
    use HasPhones;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name', 'grade', 'year', 'branches'
    ];
    protected $commaSeparated = ['branches'];

    public function getRequests()
    {
        $request_ids = [];
        foreach($this->phones as $phone) {
            $ids = Phone::where('entity_type', Request::class)->where('phone', $phone->phone_clean)->pluck('entity_id')->all();
            if (count($ids)) {
                $request_ids = array_merge($request_ids, $ids);
            }
        }
        $requests = Request::with('responsibleUser')
            ->whereIn('id', $request_ids)
            ->orderBy('id', 'desc')
            ->get();
        return resourceCollection($requests, RequestCollection::class);
    }
}
