<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Contract\Contract, Group\Group, Client\Client};
use App\Http\Resources\Person\PersonResource;


class AbstractGroupsController extends Controller
{
    public function index(Request $request)
    {
        // if (isset($request->year) && isset($request->grade_id) && isset($request->subject_id)) {
        //     return $this->show($request);
        // }
        $abstract_groups = [];

        $query = Contract::active();

        if (isset($request->year) && $request->year) {
            $this->filterMultiple('year', $request->year, $query);
        }

        if (isset($request->grade_id) && $request->grade_id) {
            $grade_ids = explode(',', $request->grade_id);
        }

        if (isset($request->subject_id) && $request->subject_id) {
            $subject_ids = explode(',', $request->subject_id);
        }

        foreach($query->get() as $contract) {
            if (isset($grade_ids) && ! in_array($contract->grade_id, $grade_ids)) {
                continue;
            }
            foreach($contract->subjects as $subject) {
                if (isset($subject_ids) && ! in_array($subject->subject_id, $subject_ids)) {
                    continue;
                }
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
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
            ],
        ];
    }

    public function show($year, $grade_id, $subject_id)
    {
        $clients_ids = [];
        $contracts = Contract::active()->where('year', $year)->where('grade_id', $grade_id)->get();

        foreach($contracts as $contract) {
            foreach($contract->subjects as $subject) {
                if ($subject->status !== 'terminated' && $subject->subject_id == $subject_id) {
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
