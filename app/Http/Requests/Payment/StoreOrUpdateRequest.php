<?php

namespace App\Http\Requests\Payment;

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
            //    'category', 'type', 'method', 'date', 'sum', 'year', 'entity_type', 'entity_id', 'card_number', 'is_confirmed', 'bill_number'
            'method' => ['required'],
            'category' => ['required'],
            'type' => ['required'],
            'year' => ['required'],
            'sum' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ];
    }
}
