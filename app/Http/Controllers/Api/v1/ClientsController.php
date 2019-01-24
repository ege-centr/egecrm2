<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Payment, Client\Client, Contract\Contract};
use App\Http\Resources\Client\{Resource, Collection};

class ClientsController extends Controller
{
    protected $filters = [
        'equals' => ['client_id'],
        'multiple' => ['grade_id'],
    ];

    public function index(Request $request)
    {
        $query = Client::orderBy('id', 'desc');

        if (isset($request->get_all) && $request->get_all) {
            return Collection::collection($query->orderByName()->get());
        }

        $this->filter($request, $query);

        if (isset($request->current_grade_id) && $request->current_grade_id) {
            $query->whereRaw("grade_id + (" . academicYear() .  " - `year`) IN ({$request->current_grade_id})");
        }

        return Collection::collection($this->showBy($request, $query));
    }

    public function store(Request $request)
    {
        $new_model = Client::create($request->input());
        $new_model->phones()->createMany($request->phones);

        $new_model->representative()->create($request->representative);
        $new_model->representative->phones()->createMany($request->representative['phones']);
        $new_model->representative->email()->create($request->representative['email']);

        $new_model->email()->create($request->email);

        return response($new_model->id, 201);
    }

    public function show($id)
    {
        return new Resource(Client::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = Client::find($id);
        $model->update($request->input());

        $model->phones()->delete();
        $model->phones()->createMany($request->phones);

        $model->representative->update($request->representative);
        $model->representative->phones()->delete();
        $model->representative->phones()->createMany($request->representative['phones']);

        if ($request->representatives['email']['email']) {
            if ($model->representative->email === null) {
                $model->representative->email()->create($request->representative['email']);
            } else {
                $model->representative->email->update($request->representative['email']);
            }
        }

        $model->email->update($request->email);


        return new Resource($model);
    }

    public function destroy($id)
    {
        //
    }
}
