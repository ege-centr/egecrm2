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
            'email' => ['nullable', 'email', 'unique:emails,email'],

            'representative.first_name' => ['required'],
            'representative.phones.*.phone' => ['required', 'phone'],
            'representative.email' => ['nullable', 'email', 'unique:emails,email'],

            'grade_id' => ['required'],
            'year' => ['required'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->email && $this->representative['email']) {
                if ($this->email === $this->representative['email']) {
                    $validator->errors()->add('email', 'Одинаковый email у ученика и представителя');
                    $validator->errors()->add('representative.email', 'Одинаковый email у ученика и представителя');
                }
            }
        });
    }
}
