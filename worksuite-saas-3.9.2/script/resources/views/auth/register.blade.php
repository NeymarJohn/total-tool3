@extends('layouts.auth-register')

@push('head-script')
    <link href="{{ asset('front/plugin/froiden-helper/helper.css') }}" rel="stylesheet">
@endpush

@section('content')

{!! Form::open(['id'=>'register', 'method'=>'POST', 'class' => 'form-horizontal' ]) !!}
    <div class="form-section">
        <div class="col-xs-12">
            <div class="form-group mb-2">
                <h3>@lang('app.signup')</h3>
            </div>
        </div>

              
        <div class="col-xs-12" id="alert">
            
        </div>
  
        <div class="col-xs-12" id="form-box">
            <div class="form-group mb-2">
                    <label for="company_name">{{ __('modules.client.companyName') }}</label>
                    <input type="text" name="company_name" id="company_name" class="form-control">            
            </div>
            
            @if(module_enabled('Subdomain'))
            <div class="form-group mb-2">
                    <label for="company_name">{{ __('app.chooseSubDomain') }}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="spacex" name="sub_domain" id="sub_domain" aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2">.{{ get_domain() }}</span>
                    </div>           
            </div>
            @endif
            <div class="form-group mb-2">
                    <label for="email">{{ __('app.yourEmailAddress') }}</label>
                    <input type="email" name="email" id="email" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label for="password">{{__('modules.client.password')}}</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" laceholder="{{__('modules.client.password')}}" aria-describedby="basic-addon3">
                    <span class="input-group-addon toggle-password" id="basic-addon3"><i class="fa fa-fw fa-eye-slash field-icon toggle-password"></i></span>
                </div>           
            </div>
            
            @if($global->google_recaptcha_status)
                <div class="form-group mb-2">
                    <div class="g-recaptcha" data-sitekey="{{ $global->google_recaptcha_key }}"></div>
                </div>
            @endif
            <div class="form-group mb-2">
                <button type="button" class="btn-success btn btn-block btn-lg btn-rounded waves-effect waves-light" id="save-form">
                    @lang('app.signup')
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('footer-script')
<script src="{{ asset('saas/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('front/plugin/froiden-helper/helper.js') }}"></script>

<script>
    $('body').on('click', '.toggle-password', function() {
        var $selector = $(this).parent().find('input.form-control');
        $(this).find('i').toggleClass("fa-eye fa-eye-slash");
        var $type = $selector.attr("type") === "password" ? "text" : "password";
        $selector.attr("type", $type);
    });

    $('#save-form').click(function () {


        $.easyAjax({
            url: '{{route('front.signup.store')}}',
            container: '.form-section',
            type: "POST",
            data: $('#register').serialize(),
            messagePosition: "inline",
            success: function (response) {
                if (response.status == 'success') {
                    $('#form-box').remove();
                } else if (response.status == 'fail') {
                    @if($global->google_recaptcha_status)
                    grecaptcha.reset();
                    @endif

                }
            }
        })
    });
</script>
@endpush