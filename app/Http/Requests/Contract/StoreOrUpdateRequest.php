<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // ['year', 'grade_id', 'sum', 'date', 'discount', 'number', 'client_id']

        return [
            'year' => ['required'],
            'grade_id' => ['required'],
            'sum' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'payments.*.date' => ['required', 'date'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator){
            if (isset($this->payments)) {
                // если платежи есть, то
                // * сумма уроков должны быть = сумме уроков в предметах
                $payments = collect($this->payments);
                $subjects = collect($this->subjects);

                if ($payments->sum('lessons') != $subjects->sum('lessons')) {
                    $validator->errors()->add('alert', 'Сумма уроков в платежах должна быть равна сумме уроков в предметах');
                }

                // * сумма рублей должна быть = сумме рублей договора * скидка
                $sum = $this->sum;
                if (isset($this->discount) && $this->discount) {
                    $sum = round($this->sum - ($this->sum * ($this->discount / 100)));
                }
                if ($payments->sum('sum') != $sum) {
                    $validator->errors()->add('alert', 'Сумма платежей должна быть равна сумме по договору');
                }
            }
        });
    }
}
