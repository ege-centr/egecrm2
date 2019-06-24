<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => ['required'],
            'phones.*.phone' => ['required', 'phone'],
            'email' => ['required', 'email', 'different:representative.email'],

            'representative.first_name' => ['required'],
            'representative.phones.*.phone' => ['required', 'phone'],
            'representative.email' => ['required', 'email', 'different:email'],

            'grade_id' => ['required'],
            'year' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'email.different' => 'Одинаковый email у ученика и представителя',
            'representative.email.different' => 'Одинаковый email у ученика и представителя',
        ];
    }
}
