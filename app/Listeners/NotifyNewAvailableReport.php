<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ReportBecameAvailable;
use App\Models\Sms\{SmsTemplate, SmsTemplateCode};
use App\Mail\ReportBecameAvailableEmail;
use Mail;

class NotifyNewAvailableReport
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ReportBecameAvailable $event)
    {
        $representative = $event->report->client->representative;

        SmsTemplate::send(
            $representative->getPhonesArray(),
            SmsTemplateCode::REPORT_BECAME_AVAILABLE(),
            [
                'parent_name' => $representative->default_name,
            ]
        );

        Mail::to($representative->email->email)->send(new ReportBecameAvailableEmail($event->report));
    }
}
