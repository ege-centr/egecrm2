<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Contract\Contract, Contract\SubjectStatus, Group\Group, Client\Client};
use App\Http\Resources\Person\PersonResource;


class AbstractGroupsController extends Controller
{
    protected $filters = [
        'equals' => ['client_id'],
        'multiple' => ['year'],
    ];

    public function index(Request $request)
    {
        $abstract_groups = [];

        $query = Contract::active();

        $this->filter($request, $query);

        if (isset($request->subject_id) && $request->subject_id) {
            $subject_ids = explode(',', $request->subject_id);
        }

        foreach($query->get() as $contract) {
            foreach($contract->subjects as $subject) {
                if (isset($subject_ids) && ! in_array($subject->subject_id, $subject_ids)) {
                    continue;
                }
                if ($subject->status !== SubjectStatus::TERMINATED) {
                    // проверяем, находится ли ученик в группе по этому предмету в этом договоре
                    $exists = Group::whereExists(function ($query) use ($contract, $subject) {
                        $query->selectRaw(1)->from('group_clients')
                            ->whereRaw('group_clients.group_id = groups.id AND group_clients.client_id=' . $contract->client_id);
                    })->where([
                        ['year', $contract->year],
                        ['subject_id', $subject->subject_id],
                    ])->exists();

                    if (! $exists) {
                        $key = implode('-', [$contract->year, $subject->subject_id]);
                        if (! isset($abstract_groups[$key])) {
                            $abstract_groups[$key] = 0;
                        }
                        $abstract_groups[$key]++;
                    }
                }
            }
        }

        $data = collect([]);
        foreach ($abstract_groups as $key => $clients_count) {
            [$year, $subject_id] = explode('-', $key);
            $data->push([
                'year' => (int) $year,
                'subject_id' => (int) $subject_id,
                'clients_count' => $clients_count,
            ]);
        }

        // $abstract_groups = array_values($abstract_groups);
        // $return = [];
        // foreach($abstract_groups as $v) {
        //     $return = array_merge($return, array_values($v));
        // }
        return [
            'data' => $data->sortBy('year')->values()->all(),
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
            ],
        ];
    }

    public function show($year, $subject_id)
    {
        $clients_ids = [];
        $contracts = Contract::active()->where('year', $year)->get();

        foreach($contracts as $contract) {
            foreach($contract->subjects as $subject) {
                if ($subject->status !== SubjectStatus::TERMINATED && $subject->subject_id == $subject_id) {
                    // проверяем, находится ли ученик в группе по этому предмету в этом договоре
                    $exists = Group::whereExists(function ($query) use ($contract, $subject) {
                        $query->selectRaw(1)->from('group_clients')
                            ->whereRaw('group_clients.group_id = groups.id AND group_clients.client_id=' . $contract->client_id);
                    })->where([
                        ['year', $contract->year],
                        ['subject_id', $subject->subject_id],
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
