<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Report\Report;
use App\Models\Factory\Subject;

class ReportBecameAvailableEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Доступен отчёт по ' . Subject::getTitle($this->report->subject_id, 'dative') . ' №' . $this->report->id)
            ->view('mail.report')->with([
                'report' => $this->report,
            ]);
    }
}
