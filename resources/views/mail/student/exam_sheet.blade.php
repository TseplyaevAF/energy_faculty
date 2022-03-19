@component('mail::message')
    Здравствуйте, {{$exam_sheet['studentFIO']}}!
    Срок действия вашего допуска по дисциплине {{$exam_sheet['discipline']}} истёк!
@endcomponent
