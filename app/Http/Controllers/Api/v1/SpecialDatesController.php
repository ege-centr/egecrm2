<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SpecialDate;

class SpecialDatesController extends Controller
{
    public function index()
    {
        return SpecialDate::all();
    }

    public function store(Request $request)
    {
        return SpecialDate::create($request->all());
    }

    public function update(Request $request, $id)
    {
        SpecialDate::find($id)->update($request->all());
    }

    public function destroy($id)
    {
        SpecialDate::find($id)->delete();
    }
}
