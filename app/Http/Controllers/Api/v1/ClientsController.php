<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Payment\Payment, Client\Client, Contract\Contract, Photo};
use App\Http\Resources\Client\{Resource, Collection};
use App\Utils\Phone;

class ClientsController extends Controller
{
    protected $filters = [
        // FIXME: убрать client_id?
        'equals' => ['id', 'client_id'],
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
        $new_model->phones()->createMany(Phone::filter($request->phones));

        $new_model->representative()->create($request->representative);
        $new_model->representative->phones()->createMany(Phone::filter($request->representative['phones']));
        $new_model->representative->email()->create($request->representative['email']);

        $new_model->email()->create($request->email);

        if ($request->photo !== null) {
            Photo::bind($request->photo['id'], $new_model);
        }

        return response(new Resource($new_model), 201);
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
        $model->phones()->createMany(Phone::filter($request->phones));

        $model->representative->update($request->representative);
        $model->representative->phones()->delete();
        $model->representative->phones()->createMany(Phone::filter($request->representative['phones']));

        if ($request->representative['email']['email']) {
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
