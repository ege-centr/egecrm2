<?php

namespace App\Http\Requests\Test;

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
            'minutes' => ['required', 'numeric'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $test = $this->route('test');
            if ($test !== null && $test->clientTests()->exists()) {
                $validator->errors()->add('alert', 'Нельзя менять тест, который уже присвоен ученикам');
            }
        });
    }
}
