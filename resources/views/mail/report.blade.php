<p>
    {{ $report->client->representative->default_name }}, здравствуйте!
</p>

<p>
    Преподаватель по
    {{ \App\Models\Factory\Subject::getTitle($report->subject_id, 'dative') }}
    {{ $report->teacher->default_name }}
    написал новый отчёт об ученике
    {{ $report->client->default_name }}
</p>



@foreach(\App\Models\Report\Report::CATEGORIES as $category => $title)
    <h2 style='margin-top: 40px'>
        {{ $title }}:
    </h2>
    <p>
        {{ $report->{"{$category}_comment"} }}
    </p>
    <p>
        Оценка: <b>{{ $report->{"{$category}_score"} }}/5</b>
    </p>
@endforeach

<div style='margin-top: 40px'>
    <a href="{{ config('app.url') }}" target="_blank">Просмотреть отчёт в личном кабинете</a>
</div>


        <div style='color: grey; font-size: 12px; margin-top: 40px'>С уважинием, Администрация ЕГЭ-Центра</div>
        <div style='color: grey; font-size: 12px'>Это автоматическое письмо, отвечать на него не нужно</div>
