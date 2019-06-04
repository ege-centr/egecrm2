<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Contract\Contract,
    Contract\SubjectStatus,
    Payment\Payment,
    Payment\PaymentType
};
use Illuminate\Support\Carbon;
use DateTime;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        switch($request->mode) {
            case 'week': {
                $paginate = 30;
                $lastPage = 8;
                break;
            }
            case 'month': {
                $paginate = 20;
                $lastPage = 3;
                break;
            }
            case 'year': {
                $paginate = 4;
                $lastPage = 1;
                break;
            }
            default: {
                $paginate = 50;
                $lastPage = 50;
            }
        }

        $date = (new DateTime())->modify(sprintf(
            '-%d %s',
            ($request->page - 1) * $paginate,
            $request->mode
        ));

        $result = [];
        foreach(range(1, $paginate) as $i) {
            $dateFormatted = $date->format(DATE_FORMAT);
            if ($request->mode === 'day') {
                $dateStart = null;
            } else {
                $dateStart = (new Carbon($dateFormatted))
                    ->{"startOf" . ucfirst($request->mode)}()
                    ->{"sub" . ucfirst($request->mode)}()
                    ->{"endOf" . ucfirst($request->mode)}()
                    ->format(DATE_FORMAT);
            }

            if (isset($request->entity_type)) {
                $result[] = array_merge([
                    'date' => $dateFormatted,
                ], $this->payments(
                    $request,
                    $dateFormatted,
                    $dateStart,
                    $request->mode === 'year' ? (academicYear() - $i + 1) : null
                ));
            } else {
                $result[] = array_merge([
                    'date' => $dateFormatted,
                    'requests' => $this->requests($request, $dateFormatted, $dateStart),
                ],
                    $this->contracts(
                        $request,
                        $dateFormatted,
                        $dateStart,
                        $request->mode === 'year' ? (academicYear() - $i + 1) : null
                    )
                );
            }

            if ($dateStart === null) {
                $date->modify('-1 day');
            } else {
                $date = new DateTime($dateStart);
            }
        }

        return imitatePagination($result, $request->page, $lastPage);
    }

    private function requests(Request $request, $date, $dateStart = null)
    {
        $query = \App\Models\Request::query();
        if ($dateStart !== null) {
            $query->whereRaw("DATE(created_at) BETWEEN '{$dateStart}' AND '{$date}'");
        } else {
            $query->whereRaw("DATE(created_at) = '{$date}'");
        }

        if (isset($request->grade_id)) {
            $query->whereIn('grade_id', explode(',', $request->grade_id));
        }

        return $query->count();
    }

    private function payments(Request $request, $date, $dateStart = null, $year = null)
    {
        $query = Payment::where('entity_type', $request->entity_type);
        if ($dateStart !== null) {
            if ($year === null) {
                $query->whereBetween('date', [$dateStart, $date]);
            } else {
                $query->where('year', $year);
            }
        } else {
            $query->where('date', $date);
        }

        $payments = $query
            ->selectRaw('method, type, sum(`sum`) as `sum`')
            ->groupBy('method', 'type')
            ->get();

        $result = [
            'payment_sum' => 0,
            'return_sum' => 0,
        ];

        foreach($payments as $payment) {
            if (! isset($result[$payment->method])) {
                $result[$payment->method] = 0;
            }
            if ($payment->type === PaymentType::PAYMENT) {
                $result[$payment->method] += $payment->sum;
                $result['payment_sum'] += $payment->sum;
            } else {
                $result[$payment->method] -= $payment->sum;
                $result['return_sum'] += $payment->sum;
            }
        }
        return $result;
    }

    private function contracts(Request $request, $date, $dateStart = null, $year = null)
    {
        $query = Contract::query();
        if ($dateStart !== null) {
            if ($year === null) {
                $query->whereBetween('date', [$dateStart, $date]);
            } else {
                $query->where('year', $year);
            }
        } else {
            $query->where('date', $date);
        }

        if (isset($request->grade_id)) {
            $query->whereIn('grade_id', explode(',', $request->grade_id));
        }

        $contracts = $query->get();

        $result = [
            'contracts' => 0,
            'subjects' => 0,
            'contracts_sum' => 0,
            'subjects_added' => 0,
            'subjects_removed' => 0,
            'contracts_sum_change' => 0
        ];

        foreach($contracts as $contract) {
            if ($contract->version === 1) {
                $result['contracts']++;
                $result['subjects'] += $contract->subjects->count();
                $result['contracts_sum'] += $contract->discounted_sum;
            } else {
                $removedSubjectIds = array_diff(
                    $contract->previous->subjects->where('status', '<>', SubjectStatus::TERMINATED)->pluck('subject_id')->all(),
                    $contract->subjects->where('status', '<>', SubjectStatus::TERMINATED)->pluck('subject_id')->all()
                );
                $addedSubjectIds = array_diff(
                    $contract->subjects->where('status', '<>', SubjectStatus::TERMINATED)->pluck('subject_id')->all(),
                    $contract->previous->where('status', '<>', SubjectStatus::TERMINATED)->subjects->pluck('subject_id')->all()
                );
                $result['subjects_added'] += count($addedSubjectIds);
                $result['subjects_removed'] += count($removedSubjectIds);
                $result['contracts_sum_change'] += ($contract->discounted_sum - $contract->previous->discounted_sum);
            }
        }

        return $result;
    }
}
