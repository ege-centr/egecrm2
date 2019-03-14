<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Http\Resources\Admin\{Resource, AdminCollection};

class AdminsController extends Controller
{
    protected $filters = [
        'likeMultiple' => ['name' => ['first_name', 'last_name', 'middle_name', 'nickname']]
    ];

    public function index(Request $request)
    {
        $query = Admin::query();
        $this->filter($request, $query);
        return AdminCollection::collection($this->showBy($request, $query));
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
        $model = Admin::find($id);
        $model->update($request->all());
        return $model;
    }

    public function destroy($id)
    {

    }
}
