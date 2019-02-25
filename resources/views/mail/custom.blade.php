<!doctype html>
<html>
    <body>
        {!! $html !!}
        <div style='border-top: 1px solid #e5e5e5; margin-top: 30px; padding-top: 10px; font-size: 12px'>
            Внимание: это сообщение отправлено из системы ЕГЭ-Центра и отвечать на него не нужно.
            @if (\User::isTeacher())
                С уважением, {{ user()->names->full }}, преподаватель по
                {{ implode(' и ', array_map(function($subject_id) {
                    return \App\Models\Factory\Subject::getTitle($subject_id, 'dative');
                }, user()->subjects_ec)) }}
            @endif
        </div>
    </body>
</html>
