@component('mail::message')
# @lang('email.leave.applied')

@component('mail::text', ['text' => $content])

@endcomponent


@lang('email.regards'),<br>
{{ config('app.name') }}
@endcomponent
