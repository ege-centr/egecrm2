<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\{
    Contract\Contract,
    Lesson\ClientLesson,
    Payment\Payment,
    Payment\PaymentAdditional,
    Client\Client,
    Group\GroupClient
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
            $clientId = $this->route('client')->id;

            // клиента если у него есть хотя бы 1 версия договора
            if (Contract::where('client_id', $clientId)->exists()) {
                $validator->errors()->add('alert', 'У клиента есть договоры');
            }

            // клиента если у него есть хотя бы 1 проведенное занятие
            if (ClientLesson::where('client_id', $clientId)->exists()) {
                $validator->errors()->add('alert', 'У клиента есть проведённые занятия');
            }

            // клиента если у него есть хотя бы 1 платеж
            if (Payment::whereClient($clientId)->exists()) {
                $validator->errors()->add('alert', 'У клиента есть платежи');
            }

            // клиента если у него есть хотя бы 1 допуслуга
            if (PaymentAdditional::whereClient($clientId)->exists()) {
                $validator->errors()->add('alert', 'У клиента есть допуслуги');
            }

            // клиента если он включен хотя бы в 1 группу
            if (GroupClient::where('client_id', $clientId)->exists()) {
                $validator->errors()->add('alert', 'Клиент состоит в группах');
            }
        });
    }
}
