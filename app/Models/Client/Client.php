<?php

namespace App\Models\Client;

use Shared\Model;
use App\Interfaces\UserInterface;
use App\Traits\{HasPhones, HasEmail, HasPhoto, HasName, HasCreatedEmail, Commentable};
use App\Http\Resources\Teacher\TeacherCollection;
use App\Models\{Request, Phone, Payment\Payment, Teacher, Contract\Contract, Group\Group, Group\GroupClient};

class Client extends Model implements UserInterface
{
    use HasPhones, HasEmail, HasPhoto, HasName, HasCreatedEmail, Commentable;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name',
        'grade_id', 'year', 'branches', 'school',
        'reviewer_admin_id', 'birthdate'
    ];

    protected $commaSeparated = ['branches'];

    public function representative()
    {
        return $this->hasOne(Representative::class);
    }

    public function getDefaultNameAttribute()
    {
        $name = $this->names->lastF;
        if (empty(trim($name))) {
            return 'пусто';
        }
        return $name;
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function groups()
    {
        return $this->hasManyThrough(Group::class, GroupClient::class, 'client_id', 'id', 'id', 'group_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'entity');
    }

    public function tests()
    {
        return $this->hasMany(ClientTest::class);
    }

    /**
     * Есть договоры на текущий или будущий учебный год
     */
    public function isBanned()
    {
        return !$this
            ->contracts()
            ->whereIn('year', [academicYear(), academicYear() + 1])
            ->exists();
    }

    public function allowedToLogin()
    {
        return true;
    }

    public function requests()
    {
        $request_ids = [];
        foreach($this->phones as $phone) {
            $ids = Phone::where('entity_type', Request::class)->where('phone', $phone->phone_clean)->pluck('entity_id')->all();
            if (count($ids)) {
                $request_ids = array_merge($request_ids, $ids);
            }
        }
        return Request::with('responsibleAdmin')
            ->whereIn('id', $request_ids)
            ->orderBy('id', 'desc');
    }

    public function getHeadTeacher()
    {
        if ($this->head_teacher_id) {
            return new TeacherCollection(Teacher::find($this->head_teacher_id));
        }
        return null;
    }

    public function getSubjectStatus($year, $grade_id, $subject_id)
    {
        $contract = $this->contracts()->active()->where([
            ['year', $year],
            ['grade_id', $grade_id]
        ])->first();

        if ($contract !== null) {
            foreach($contract->subjects as $subject) {
                if ($subject->subject_id === $subject_id) {
                    return $subject->status;
                }
            }
        }

        return null;
    }
}
