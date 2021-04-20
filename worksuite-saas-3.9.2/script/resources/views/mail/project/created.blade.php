@component('mail::message')
# Project

@lang('email.newProject.subject')

<h5>@lang('app.project') @lang('app.details')</h5>

@component('mail::text', ['text' => $content])

@endcomponent


@component('mail::button', ['url' => $url])
@lang('app.view') @lang('app.project')
@endcomponent

@lang('email.regards'),<br>
{{ config('app.name') }}
@endcomponent
