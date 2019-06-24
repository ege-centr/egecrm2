<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Lesson\{Lesson, LessonStatus};

class MessDestroyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids' => [
                function ($attribute, $value, $fail) {
                    if (
                        Lesson::query()
                            ->whereIn('id', $value)
                            ->where('status', LessonStatus::CONDUCTED)
                            ->exists()
                    ) {
                        $fail('Нельзя удалить проведенное занятие');
                    }
                },
            ]
        ];
    }
}
