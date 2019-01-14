<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Contract\Contract,
    Group\Group,
    Group\GroupAct
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
        return view('print.contract')->with(compact('contract'));
    }

    private function act(array $params)
    {
        $act = GroupAct::find($params['id']);
        $group = Group::find($params['group_id']);
        return view('print.act')->with(compact('act', 'group'));
    }
}
