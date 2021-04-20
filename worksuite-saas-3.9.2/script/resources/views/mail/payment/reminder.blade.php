@component('mail::message')
# Payment Reminder

@lang('email.paymentReminder.subject')

<h5>@lang('app.invoice') @lang('app.details')</h5>

@component('mail::text', ['text' => $content])

@endcomponent


@component('mail::paymentbutton', ['paymenturl' => $paymentUrl])
    @lang('app.view') @lang('app.invoice')
@endcomponent
@component('mail::button', ['url' => $url])
@lang('app.view') @lang('app.invoice')
@endcomponent

@lang('email.regards'),<br>
{{ config('app.name') }}
@endcomponent
