<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\{
    Group\GroupClient,
    Lesson\Lesson,
    Lesson\LessonStatus
};

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
            $groupId = $this->route('group')->id;
            // группы если у группы есть хотя бы 1 занятие (неважно запланированное или проведенное)
            // группы если в группе есть хотя бы 1 ученик
            if (
                GroupClient::where('group_id', $groupId)->exists() ||
                Lesson::where('status', '<>', LessonStatus::CANCELLED)->where('group_id', $groupId)->exists()
            ) {
                $validator->errors()->add('alert', 'В группе есть ученики или занятия');
            }
        });
    }
}
