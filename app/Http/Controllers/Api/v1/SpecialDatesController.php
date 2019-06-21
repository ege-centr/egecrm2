<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SpecialDate;
use App\Http\Requests\SpecialDate\StoreOrUpdateRequest;

class SpecialDatesController extends Controller
{
    // тут нельзя использовать фильтры
    // protected $filters = [
    //     'equals' => ['year', 'subject_id', 'grade_id']
    // ];

    public function index(Request $request)
    {
        $query = SpecialDate::query();

        if (isset($request->year) && $request->year) {
            $query->where('year', $request->year);
        }

        if (isset($request->subject_id) && $request->subject_id) {
            $query->whereRaw("(type = 'vacation' OR subject_id={$request->subject_id})");
        }

        if (isset($request->grade_id) && $request->grade_id) {
            $query->whereRaw("(type = 'vacation' OR grade_id={$request->grade_id})");
        }

        return $query->get();
    }

    public function store(StoreOrUpdateRequest $request)
    {
        return SpecialDate::create($request->all());
    }

    public function update(StoreOrUpdateRequest $request, $id)
    {
        SpecialDate::find($id)->update($request->all());
    }

    public function destroy($id)
    {
        SpecialDate::find($id)->delete();
    }
}
