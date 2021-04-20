@component('mail::message')
# Task Reminder

@lang('email.reminder.subject')

<h5>@lang('app.task') @lang('app.details')</h5>

@component('mail::text', ['text' => $content])

@endcomponent


@component('mail::button', ['url' => $url])
@lang('app.view') @lang('app.task')
@endcomponent

@lang('email.regards'),<br>
{{ config('app.name') }}
@endcomponent
