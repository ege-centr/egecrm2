<?php

namespace App\Http\Requests\Report;

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
            'homework_score' => ['required'],
            'knowledge_score' => ['required'],
            'learning_ability_score' => ['required'],

            'homework_comment' => ['required'],
            'learning_ability_comment' => ['required'],
            'knowledge_comment' => ['required'],
            'recommendation' => ['required'],

            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
