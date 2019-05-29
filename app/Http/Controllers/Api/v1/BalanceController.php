<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Client\Client, Teacher, Email};
use App\Models\Factory\{Grade, Subject, Branch};
use Illuminate\Database\Eloquent\Collection;
use PersonResource;

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
                    . '(' . Subject::getTitle($lesson->group->subject_id, 'three_letters') . '-'
                    . Grade::getTitle($lesson->group->grade_id, 'short') . ')'
                    . ($lesson->cabinet ? ', кабинет ' . $lesson->cabinet->title : '');

                $items[] = [
                    'sum' => $lesson->price,
                    'bonus' => $lesson->bonus,
                    'comment' => $comment,
                    'date' => $lesson->date,
                    'year' => $lesson->group->year,
                    'user' => new PersonResource($lesson->createdUser),
                    'created_at' => $lesson->created_at,
                ];
            }

            $reports = app()->call('App\Http\Controllers\Api\v1\ReportsController@index');
            $reports = jsonRedecode($reports)->data;
            foreach($reports as $report) {
                if ($report->price > 0) {
                    $comment = sprintf(
                        'Отчет по %s по ученику %s',
                        Subject::getTitle($report->subject_id, 'dative'),
                        Client::find($report->client_id)->default_name
                    );

                    $items[] = [
                        'sum' => $report->price,
                        'bonus' => 0,
                        'comment' => $comment,
                        'date' => $report->report_date,
                        'year' => $report->year,
                        'user' => $report->created_email_id ? new PersonResource(Email::getUser($report->created_email_id)) : null,
                        'created_at' => $report->created_at,
                    ];
                }
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
                    'user' => new PersonResource($clientLesson->lesson->createdUser),
                    'created_at' => $clientLesson->lesson->created_at,
                    'bonus' => 0,
                ];
            }
        }

        $additionalPayments = app()->call('App\Http\Controllers\Api\v1\PaymentAdditionalsController@index')->items();
        foreach($additionalPayments as $additionalPayment) {
            $items[] = [
                'sum' => $isTeacher ? $additionalPayment->sum : $additionalPayment->sum * -1,
                'comment' => $additionalPayment->purpose,
                'date' => $additionalPayment->date,
                'year' => $additionalPayment->year,
                'user' => new PersonResource($additionalPayment->createdUser),
                'created_at' => $additionalPayment->created_at,
                'bonus' => 0,
            ];
        }


        $payments = app()->call('App\Http\Controllers\Api\v1\PaymentsController@index');
        $payments = jsonRedecode($payments)->data;
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
                'user' => new PersonResource(Email::getUser($payment->created_email_id)),
                'created_at' => $payment->created_at,
                'bonus' => 0,
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
