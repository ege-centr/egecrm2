<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Factory\{Branch, Subject, Grade, Year};
use App\Models\{
    Teacher,
    Cabinet,
    User,
    Admin\Admin,
    Background
};
use App\Http\Resources\Admin\{AdminCollection, AdminLightResource};
use App\Http\Resources\Teacher\TeacherCollection;
use App\Http\Resources\Photo\PhotoResource;

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
                'branches' => Branch::getCached(),
                'subjects' => Subject::getCached(),
                'grades' => Grade::getCached(),
                'years' => Year::all(),
                'admins' => AdminCollection::collection(Admin::with(['email', 'photo'])->get()),
                'teachers' => TeacherCollection::collection(Teacher::getCached()),
                'academic_year' => academicYear(),
                'cabinets' => Cabinet::getCached(),
                'background' => Background::get(),
            ],
            'user' => User::loggedIn() ? User::fromSession() : null,
        ]);
    }
}
