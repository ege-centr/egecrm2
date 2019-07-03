<?php

namespace App\Http\Requests\Lesson;

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
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required'],
            'cabinet_id' => ['required'],
            'teacher_id' => ['required'],
            'duration' => ['required'],
            'price' => ['required'],
            'topic' => ['sometimes', 'required'],
        ];
    }
}
