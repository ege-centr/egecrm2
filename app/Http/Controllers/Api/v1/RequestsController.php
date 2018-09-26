<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Request as ClientRequest;

class RequestsController extends Controller
{
    public function index()
    {
        return response()->json(
            ClientRequest::orderBy('id', 'desc')->get()->all()
        );
    }

    public function store(Request $request)
    {
        $new_model = ClientRequest::create($request->input())->fresh();
        return response()->json($new_model, 201);
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
