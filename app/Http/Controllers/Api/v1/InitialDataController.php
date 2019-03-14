<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Factory\{Branch, Subject, Grade, Year};
use App\Models\{Teacher, Cabinet, User, Admin\Admin};
use App\Http\Resources\Admin\AdminCollection;
use App\Http\Resources\Teacher\Collection as TeacherCollection;

/**
 * Загрузка изначальных данных,
 * необходимых для работы приложения
 */
class InitialDataController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                'branches' => Branch::all(),
                'subjects' => Subject::all(),
                'grades' => Grade::all(),
                'years' => Year::all(),
                'admins' => AdminCollection::collection(Admin::all()),
                'teachers' => TeacherCollection::collection(Teacher::all()),
                'academic_year' => academicYear(),
                'cabinets' => Cabinet::all(),
            ],
            'user' => User::fromSession()
        ]);
    }
}
