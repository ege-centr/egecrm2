<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Resources\Admin\{Resource, Collection};

class AdminsController extends Controller
{
    public function index()
    {
        return resourceCollection(Admin::orderBy('id', 'asc')->paginate(20), Collection::class);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return new Resource(Admin::find($id));
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
