<?php

namespace App\Http\Requests\Sms;

use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
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
            'phone' => ['required', 'phone'],
            'text' => ['required', 'max:402'],
        ];
    }

    public function messages()
    {
        return [
            'text.max' => 'Максимум – 6 сообщений',
        ];
    }
}
