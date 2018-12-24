<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contract\Contract;

class PrintController extends Controller
{

    public function index(Request $request)
    {
        switch($request->type) {
            default:
                return $this->contract($request->id);
        }
    }

    private function contract($id)
    {
        $contract = Contract::find($id);
        return view('print.test')->with(compact('contract'));
    }
}
