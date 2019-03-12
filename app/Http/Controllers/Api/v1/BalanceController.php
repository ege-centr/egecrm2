<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Teacher};
use App\Models\Factory\{Grade, Subject, Branch};
use Illuminate\Database\Eloquent\Collection;

class BalanceController extends Controller
{
    public function index(Request $request)
    {
        $isTeacher = getModelClass($request->entity_type, true) === Teacher::class;
        $request->merge([
            'status' => 'conducted',
            ($isTeacher ? 'teacher_id' : 'client_id') => $request->entity_id,
        ]);


        $items = [];

        if ($isTeacher) {
            $lessons = app()->call('App\Http\Controllers\Api\v1\LessonsController@index');
            foreach($lessons as $lesson) {
                $comment = ($lesson->is_unplanned ? 'дополнительное занятие' : 'занятие')
                    . ' ' . date("d.m.y", strtotime($lesson->date))
                    . " в {$lesson->time}, группа {$lesson->group_id} "
                    . '(' . Subject::getTitle($lesson->subject_id, 'three_letters') . '-'
                    . Grade::getTitle($lesson->grade_id, 'short') . ')'
                    . ($lesson->cabinet ? ', кабинет ' . $lesson->cabinet->title : '');

                $items[] = [
                    'sum' => $lesson->price,
                    'comment' => $comment,
                    'date' => $lesson->date,
                    'year' => $lesson->group->year,
                    'admin_id' => $lesson->conducted_email_id,
                    'created_at' => $lesson->created_at,
                ];
            }
        } else {
            $clientLessons = app()->call('App\Http\Controllers\Api\v1\ClientLessonsController@index');
            foreach($clientLessons as $clientLesson) {
                $comment = ($clientLesson->lesson->is_unplanned ? 'дополнительное занятие' : 'занятие')
                    . ' ' . date("d.m.y", strtotime($clientLesson->lesson->date))
                    . " в {$clientLesson->lesson->time}, группа {$clientLesson->lesson->group_id} "
                    . '(' . Subject::getTitle($clientLesson->lesson->group->subject_id, 'three_letters') . '-'
                    . Grade::getTitle($clientLesson->grade_id, 'short') . ')'
                    . ($clientLesson->cabinet ? ', кабинет ' . $clientLesson->lesson->cabinet->title : '');

                $items[] = [
                    'sum' => $clientLesson->price * -1,
                    'comment' => $comment,
                    'date' => $clientLesson->lesson->date,
                    'year' => $clientLesson->lesson->group->year,
                    'admin_id' => $clientLesson->lesson->conducted_email_id,
                    'created_at' => $clientLesson->lesson->created_at,
                ];
            }
        }

        $payments = app()->call('App\Http\Controllers\Api\v1\PaymentsController@index')->items();
        foreach($payments as $payment) {
            if ($payment->type === 'payment') {
                $sum = $isTeacher ? $payment->sum * -1 : $payment->sum;
                $comment =  $this->getPaymentMethodTitle($payment->method) . ' (' . $this->getPaymentCategoryTitle($payment->category) . ')';
            } else {
                $sum = $payment->sum * -1;
                $comment = 'возврат';
            }
            $items[] = [
                'sum' => $sum,
                'comment' => $comment,
                'date' => $payment->date,
                'year' => $payment->year,
                'admin_id' => $payment->created_admin_id,
                'created_at' => $payment->created_at,
            ];
        }

        $additionalPayments = app()->call('App\Http\Controllers\Api\v1\PaymentAdditionalsController@index')->items();
        foreach($additionalPayments as $additionalPayment) {
            $items[] = [
                'sum' => $isTeacher ? $additionalPayment->sum : $additionalPayment->sum * -1,
                'comment' => $additionalPayment->purpose,
                'date' => $additionalPayment->date,
                'year' => $additionalPayment->year,
                'admin_id' => $additionalPayment->created_admin_id,
                'created_at' => $additionalPayment->created_at,
            ];
        }

        $items = collect($items)->sortByDesc(function($item) {
            return [$item['year'], $item['date']];
        })->groupBy(['year', 'date']);

        return $items->toArray();
    }

    private function getPaymentCategoryTitle($category)
    {
        switch($category) {
            case 'study': return 'обучение';
            case 'career_guidance': return 'профориентация';
            case 'ege_trial': return 'пробный ЕГЭ';
        }
    }

    private function getPaymentMethodTitle($method)
    {
        switch($method) {
            case 'card': return 'карта';
            case 'cash': return 'наличные';
            case 'bill': return 'счет';
            case 'card_online': return 'карта онлайн';
        }
    }
}
