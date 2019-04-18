<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Contract\Contract,
    Group\Group,
    Group\GroupAct,
    Payment\Payment
};

class PrintController extends Controller
{

    public function index(Request $request)
    {
        return $this->{$request->type}($request->all());
    }

    private function contract(array $params)
    {
        $contract = Contract::find($params['id']);

        return view('print.contract')->with([
            'contract' => $contract,
            'representative' => $contract->client->representative,
            'oneSubjectPrice' => round($contract->discounted_sum / collect($contract->subjects)->sum('lessons')),
            'contractDate' => date('d.m.Y', strtotime($contract->date)),
        ]);
    }

    private function teacher(array $params)
    {
        $group = Group::find($params['id']);
        return view('print.teacher')->with(compact('group'));
    }

    private function payment(array $params)
    {
        $payment = Payment::find($params['id']);
        return view('print.payment')->with(compact('payment'));
    }

    private function act(array $params)
    {
        $act = GroupAct::find($params['id']);
        $group = Group::find($params['group_id']);
        return view('print.act')->with(compact('act', 'group'));
    }
}
