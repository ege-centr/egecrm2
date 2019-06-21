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

    // 'subject_id', 'homework_score',
    // 'learning_ability_score', 'knowledge_score', 'homework_comment',
    // 'learning_ability_comment', 'knowledge_comment', 'is_available_for_parents',
    // 'date', 'expected_score_from', 'expected_score_to', 'expected_score_max', 'recommendation',
    // 'client_id', 'teacher_id', 'year', 'is_not_moderated', 'price'


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

            'date' => ['required', 'date'],
        ];
    }
}
