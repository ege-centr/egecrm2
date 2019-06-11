<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Payment\Payment, Client\Client, Contract\Contract, Photo};
use App\Http\Resources\Client\{ClientResource, ClientCollection};
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
            return ClientCollection::collection($query->orderByName()->get());
        }

        $this->filter($request, $query);

        if (isset($request->photos) && $request->photos) {
            $query->has('photo');
        }

        if (isset($request->current_grade_id) && $request->current_grade_id) {
            $query->whereRaw("grade_id + (" . academicYear() .  " - `year`) IN ({$request->current_grade_id})");
        }

        return ClientCollection::collection($this->showBy($request, $query));
    }

    public function store(Request $request)
    {
        $new_model = Client::create($request->input());
        $new_model->phones()->createMany(Phone::filter($request->phones));

        $new_model->representative()->create($request->representative);
        $new_model->representative->phones()->createMany(Phone::filter($request->representative['phones']));

        if (isset($request->representative['email']) && $request->representative['email']) {
            $new_model->representative->email()->create(['email' => $request->representative['email']]);
        }

        if (isset($request->email) && $request->email) {
            $new_model->email()->create(['email' => $request->email]);
        }

        if ($request->photo !== null) {
            Photo::bind($request->photo['id'], $new_model);
        }

        return response(new ClientResource($new_model), 201);
    }

    public function show($id)
    {
        return new ClientResource(Client::find($id));
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

        if (isset($request->representative['email']) && $request->representative['email']) {
            if ($model->representative->email === null) {
                $model->representative->email()->create(['email' => $request->representative['email']]);
            } else {
                $model->representative->email->update(['email' => $request->representative['email']]);
            }
        }

        if (isset($request->email) && $request->email) {
            if ($model->email === null) {
                $model->email()->create(['email' => $request->email]);
            } else {
                $model->email->update(['email' => $request->email]);
            }
        }

        return new ClientResource($model);
    }

    public function destroy($id)
    {
        //
    }
}
