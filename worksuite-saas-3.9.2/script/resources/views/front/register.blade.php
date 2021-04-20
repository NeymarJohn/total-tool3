@extends('layouts.front-app')
@section('content')
    <section class="section bg-img" id="section-contact" style="background-image: url({{ asset('front/img/bg-cup.jpg') }})" data-overlay="8">
        <div class="container">
            <div class="row gap-y">

                <div class="col-12 col-md-8 offset-md-3 form-section">

                    {!! Form::open(['id'=>'register','class'=>'row', 'method'=>'POST']) !!}
                        <div class="col-12 col-md-10 bg-white px-30 py-45 rounded">
                            <h2 class="text-center m-b-15">@lang('app.signup')</h2>
                            <p id="alert"></p>
                            <div id="form-box">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="{{ __('modules.client.companyName') }}">
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-lg" type="email" id="email" name="email" placeholder="{{ __('app.yourEmailAddress') }}">
                                </div>
                                @if(module_enabled('Subdomain'))
                                    <div class="form-group">
                                        <div class="sub-domain">
                                            <input type="text" class="form-control" placeholder="your-login-url" name="sub_domain">
                                            @if(function_exists('get_domain'))
                                                <span class="domain-text">.{{ get_domain() }}</span>
                                            @else
                                                <span class="domain-text">.{{ $_SERVER['SERVER_NAME'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="{{__('modules.client.password')}}">
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="{{__('app.confirmPassword')}}">
                                </div>

                                @if($global->google_recaptcha_status)
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="{{ $global->google_recaptcha_key }}"></div>
                                </div>
                                @endif
                            <br>

                                <button class="btn btn-lg btn-block btn-primary" type="button" id="save-form">@lang('app.signup')</button>

                            </div>

                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection
@push('footer-script')
    <script>
        $('#save-form').click(function () {


            $.easyAjax({
                url: '{{route('front.signup.store')}}',
                container: '.form-section',
                type: "POST",
                data: $('#register').serialize(),
                messagePosition: "inline",
                success: function (response) {
                    if(response.status == 'success'){
                        $('#form-box').remove();
                    }else if (response.status == 'fail')
                    {
                        @if($global->google_recaptcha_status)
                            grecaptcha.reset();
                        @endif

                    }
                }
            })
        });
    </script>
@endpush
