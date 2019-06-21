<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required'],
            'phones.*.phone' => ['required', 'phone'],
            'email' => ['required', 'email'],

            'representative.first_name' => ['required'],
            'representative.phones.*.phone' => ['required', 'phone'],
            'representative.email' => ['required', 'email'],

            'grade_id' => ['required'],
            'year' => ['required'],
        ];
    }
}
