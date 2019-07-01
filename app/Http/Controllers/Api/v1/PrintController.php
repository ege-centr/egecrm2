<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Contract\Contract,
    Group\Group,
    Group\GroupAct,
    Payment\Payment,
    Lesson\Lesson,
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

        // $lastLessonDate = Lesson::query()
        //     ->join('client_lessons', 'client_lessons.lesson_id', '=', 'lessons.id')
        //     ->where('client_id', $contract->client->id)
        //     ->orderBy('date', 'desc')
        //     ->value('date');

        return view('print.contract-' . $params['option'])->with([
            'contract' => $contract,
            // 'lastLessonDate' => $lastLessonDate,
            'firstVersion' => Contract::where('version', 1)->where('number', $contract->number)->first(),
            'representative' => $contract->client->representative,
            'oneSubjectPrice' => round($contract->discounted_sum / collect($contract->subjects)->sum('lessons')),
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
        $contract = Contract::query()
            ->lastInYear($payment->year)
            ->where('client_id', $payment->entity_id)
            ->first();
        return view('print.payment-' . $params['method'])->with(compact('payment', 'contract'));
    }

    private function act(array $params)
    {
        $act = GroupAct::find($params['id']);
        $group = Group::find($params['group_id']);
        return view('print.act')->with(compact('act', 'group'));
    }
}
