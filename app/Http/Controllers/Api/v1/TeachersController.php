<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Http\Resources\Teacher\Collection as TeacherCollection;

class TeachersController extends Controller
{
    public function index()
    {
        return TeacherCollection::collection(Teacher::all());
    }

     public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
