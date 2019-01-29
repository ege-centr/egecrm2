<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Contract\Contract, Group\Group, Client\Client};
use App\Http\Resources\Person\Resource as PersonResource;


class AbstractGroupsController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->year) && isset($request->grade_id) && isset($request->subject_id)) {
            return $this->show($request);
        }
        $abstract_groups = [];
        $contracts = Contract::active()->where('year', academicYear())->get();

        foreach($contracts as $contract) {
            foreach($contract->subjects as $subject) {
                if ($subject->status !== 'terminated') {
                    // проверяем, находится ли ученик в группе по этому предмету в этом договоре
                    $exists = Group::whereExists(function ($query) use ($contract, $subject) {
                        $query->selectRaw(1)->from('group_clients')
                            ->whereRaw('group_clients.group_id = groups.id AND group_clients.client_id=' . $contract->client_id);
                    })->where([
                        ['year', $contract->year],
                        ['subject_id', $subject->subject_id],
                        ['grade_id', $contract->grade_id]
                    ])->exists();

                    if (! $exists) {
                        $key = implode('-', [$contract->year, $subject->subject_id, $contract->grade_id]);
                        if (! isset($abstract_groups[$key])) {
                            $abstract_groups[$key] = 0;
                        }
                        $abstract_groups[$key]++;
                    }
                }
            }
        }

        $data = [];
        foreach ($abstract_groups as $key => $clients_count) {
            list($year, $subject_id, $grade_id) = explode('-', $key);
            $data[] = compact('year', 'subject_id', 'grade_id', 'clients_count');
        }

        // $abstract_groups = array_values($abstract_groups);
        // $return = [];
        // foreach($abstract_groups as $v) {
        //     $return = array_merge($return, array_values($v));
        // }
        return [
            'data' => $data,
        ];
    }

    public function show(Request $request)
    {
        $clients_ids = [];
        $contracts = Contract::active()->where('year', $request->year)->where('grade_id', $request->grade_id)->get();

        foreach($contracts as $contract) {
            foreach($contract->subjects as $subject) {
                if ($subject->status !== 'terminated' && $subject->subject_id == $request->subject_id) {
                    // проверяем, находится ли ученик в группе по этому предмету в этом договоре
                    $exists = Group::whereExists(function ($query) use ($contract, $subject) {
                        $query->selectRaw(1)->from('group_clients')
                            ->whereRaw('group_clients.group_id = groups.id AND group_clients.client_id=' . $contract->client_id);
                    })->where([
                        ['year', $contract->year],
                        ['subject_id', $subject->subject_id],
                        ['grade_id', $contract->grade_id]
                    ])->exists();

                    if (! $exists) {
                        $clients_ids[] = $contract->client_id;
                    }
                }
            }
        }

        return PersonResource::collection(Client::whereIn('id', $clients_ids)->get());
    }
}
