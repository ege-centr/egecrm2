<?php

namespace App\Http\Requests\Test;

use App\Models\Lesson\LessonStatus;

class DestroyRequest extends StoreOrUpdateRequest
{
    public function rules()
    {
        return [];
    }
}
