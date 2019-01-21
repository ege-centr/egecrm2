<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Payment, Client\Client, Contract\Contract};
use App\Http\Resources\Client\{Resource, Collection};

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();
        if (isset($request->get_all) && $request->get_all) {
            return resourceCollection($query->orderByName()->get(), Collection::class);
        }

        if (isset($request->grade_id) && $request->grade_id) {
            $query->whereIn('grade_id', explode(',', $request->grade_id));
        }

        if (isset($request->current_grade_id) && $request->current_grade_id) {
            $query->whereIn('grade_id', explode(',', $request->current_grade_id));
        }

        // TODO: current_grade_id
        //  if (isset($request->current_grade_id) && $request->current_grade_id) {
        //     $grade_id = $request->current_grade_id + (academicYear())
        //     $query->where('client_id', $request->client_id);
        //     $query->whereRaw("IF(" . academicYear() . ")")
        // }


         if (isset($request->client_id) && $request->client_id) {
            $query->where('client_id', $request->client_id);
        }
        // if (isset($request->name)) {
        //     $query->where('first_name', 'like', '%' . $request->name . '%')
        //         ->orWhere('last_name', 'like', '%' . $request->name . '%')
        //         ->orWhere('middle_name', 'like', '%' . $request->name . '%');
        // }
        return resourceCollection($query->orderBy('id', 'desc')->paginate($request->show_by ?: 9999), Collection::class);
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
        if (! $model->representative->email) {
            $model->representative->email()->create($request->representative['email']);
        } else {
            $model->representative->email->update($request->representative['email']);
        }

        $model->email->update($request->email);


        return new Resource($model);
    }

    public function destroy($id)
    {
        //
    }
}
