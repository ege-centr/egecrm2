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
        $request->merge(['status' => 'conducted']);
        $isTeacher = getModelClass($request->entity_type, true) === Teacher::class;
        $lessons = app()->call('App\Http\Controllers\Api\v1\\' . ($isTeacher ? 'Lessons' : 'ClientLessons') . 'Controller@index');
        $payments = app()->call('App\Http\Controllers\Api\v1\PaymentsController@index')->items();
        // $additionalPayments = app()->call('App\Http\Controllers\Api\v1\PaymentAdditionalsController@index')->items();


        $items = [];

        foreach($lessons as $lesson) {
            $comment = ($lesson->is_unplanned ? 'дополнительное занятие' : 'занятие')
                . ' ' . date("d.m.y", strtotime($lesson->date))
                . " в {$lesson->time}, группа {$lesson->group_id} "
                . '(' . Subject::getTitle($lesson->subject_id, 'three_letters') . '-'
                . Grade::getTitle($lesson->client_grade_id, 'short') . '), кабинет '
                . $lesson->cabinet->title;

            $items[] = [
                'sum' => $isTeacher ? $lesson->price : $lesson->price * -1,
                'comment' => $comment,
                'date' => $lesson->date,
                'year' => $lesson->year,
                'admin_id' => $lesson->conducted_email_id,
                'created_at' => $lesson->created_at,
            ];
        }

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
