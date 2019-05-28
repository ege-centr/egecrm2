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

<h2 style='margin-top: 40px'>
    Данные для отчета
</h2>

<p>
Составитель отчета: {{ $report->teacher->names->full }},
преподаватель по
{{
    implode(' и ',  array_map(function ($subject_id) {
        return \App\Models\Factory\Subject::getTitle($subject_id, 'dative');
    }, $report->teacher->subjects_ec))
 }}
</p>

<p>
    Ученик: {{ $report->client->names->short }}
</p>

<p style='margin-bottom: 40px'>
    Предмет: {{ \App\Models\Factory\Subject::getTitle($report->subject_id, 'name') }}
</p>

@foreach($clientLessons as $clientLesson)
<div>
    {{ date('d.m.Y', strtotime($clientLesson->date)) }} –
    @if($clientLesson->is_absent)
        <span style='color: red'>не был</span>
    @else
        @if($clientLesson->late > 0)
            опоздал на {{ $clientLesson->late }} мин.
        @else
            был
        @endif
    @endif
    @if($clientLesson->lesson->topic)
        ({{ $clientLesson->lesson->topic }})
    @endif
</div>
@endforeach


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
