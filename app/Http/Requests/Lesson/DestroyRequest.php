<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Lesson\LessonStatus;

class DestroyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->route('lesson')->status === LessonStatus::CONDUCTED) {
                $validator->errors()->add('alert', 'Нельзя удалить проведенное занятие');
            }
        });
    }
}
