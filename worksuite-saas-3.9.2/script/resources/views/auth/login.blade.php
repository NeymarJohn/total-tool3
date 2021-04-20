@extends('layouts.auth')

@section('content')

    <form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="POST">
        {{ csrf_field() }}


        @if (session('message'))
            <div class="alert alert-danger m-t-10">
                {{ session('message') }}
            </div>
        @endif

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <div class="col-xs-12">
                <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" autofocus required="" placeholder="@lang('app.email')">
                @if ($errors->has('email'))
                    <div class="help-block with-errors">{{ $errors->first('email') }}</div>
                @endif

            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" id="password" type="password" name="password" required="" placeholder="@lang('modules.client.password')">
                @if ($errors->has('password'))
                    <div class="help-block with-errors">{{ $errors->first('password') }}</div>
                @endif
            </div>
        </div>
        @if($global->google_recaptcha_status  && $global->google_captcha_version=="v2")
        <div class="form-group {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
            <div class="col-xs-12">
                <div class="g-recaptcha"
                     data-sitekey="{{ $global->google_recaptcha_key }}">
                </div>
                @if ($errors->has('g-recaptcha-response'))
                    <div class="help-block with-errors">{{ $errors->first('g-recaptcha-response') }}</div>
                @endif
            </div>
        </div>
        @endif
        <input type='hidden' name='recaptcha_token' id='recaptcha_token'>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox checkbox-primary pull-left p-t-0">
                    <input id="checkbox-signup" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="checkbox-signup" class="text-dark"> @lang('app.rememberMe') </label>
                </div>
                <a href="{{ route('password.request') }}"  class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> @lang('app.forgotPassword')?</a> </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">@lang('app.login')</button>
            </div>
        </div>
        <div class="form-group">
            {{--<div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">--}}
                <script>
                    var facebook = "{{ route('social.login', 'facebook') }}";
                    var google = "{{ route('social.login', 'google') }}";
                    var twitter = "{{ route('social.login', 'twitter') }}";
                    var linkedin = "{{ route('social.login', 'linkedin') }}";
                </script>
                @if(isset($socialAuthSettings) && !module_enabled('Subdomain'))
                    @if($socialAuthSettings && $socialAuthSettings->facebook_status == 'enable')
                        <div class="col-xs-12 col-sm-6 col-md-6 m-t-10 mb-16">
                                <a href="javascript:;" class="btn btn-primary btn-facebook" data-toggle="tooltip" title="@lang('app.loginWithFacebook')" onclick="window.location.href = facebook;" data-original-title="@lang('app.loginWithFacebook')"><i aria-hidden="true" class="fa fa-facebook-f"></i>  &nbsp;@lang('app.loginWithFacebook')</a>
                        </div>
                    @endif

                    @if($socialAuthSettings->google_status == 'enable')
                        <div class="col-xs-12 col-sm-6 col-md-6 m-t-10 mb-16 text-right">
                                <a href="javascript:;" class="btn btn-primary btn-google" data-toggle="tooltip" title="@lang('app.loginWithGoogle')" onclick="window.location.href = google;" data-original-title="@lang('app.loginWithGoogle')"><i aria-hidden="true" class="fa fa-google-plus"></i> &nbsp;@lang('app.loginWithGoogle')</a>
                        </div>
                    @endif
                    @if($socialAuthSettings->twitter_status == 'enable')
                        <div class="col-xs-12 col-sm-12 col-md-4 m-t-10 text-center mb-16">
                                <a href="javascript:;" class="btn btn-primary btn-twitter" data-toggle="tooltip" title="@lang('app.loginWithTwitter')" onclick="window.location.href = twitter;" data-original-title="@lang('app.loginWithTwitter')"><i aria-hidden="true" class="fa fa-twitter"></i> &nbsp;@lang('app.loginWithTwitter')</a>
                        </div>
                    @endif
                    @if($socialAuthSettings->linkedin_status == 'enable')
                        <div class="col-xs-12 col-sm-12 col-md-4 m-t-10 text-center mb-16">
                                <a href="javascript:;" class="btn btn-primary btn-linkedin" data-toggle="tooltip" title="@lang('app.loginWithLinkedin')" onclick="window.location.href = linkedin;" data-original-title="@lang('app.loginWithLinkedin')"><i aria-hidden="true" class="fa fa-linkedin"></i> &nbsp;@lang('app.loginWithLinkedin')</a>
                        </div>
                    @endif
                @endif
            {{--</div>--}}
        </div>
        @if(!module_enabled('Subdomain'))
             @if($global->enable_register == true)
                <div class="form-group m-b-0">
                    <div class="col-sm-12 text-center">
                        <p>@lang('messages.dontHaveAccount') <a href="{{ route('front.signup.index') }}" class="text-primary m-l-5"><b>@lang('app.signup')</b></a></p>
                    </div>
                </div>
             @endif
            <div class="form-group m-b-0">
                <div class="col-sm-12 text-center">
                    <p>@lang('messages.goToWebsite') <a href="{{ route('front.home') }}" class="text-primary m-l-5"><b>@lang('app.home')</b></a></p>
                </div>
            </div>
        @endif
    </form>
@endsection
@if($global->google_recaptcha_status  && $global->google_captcha_version=="v3")
    <script src="https://www.google.com/recaptcha/api.js?render={{ $global->google_recaptcha_key }}"></script>

<script>
     setTimeout(function(){ 
        
        grecaptcha.ready(function() {
          grecaptcha.execute('{{ $global->google_recaptcha_key }}', {action: 'submit'}).then(function(token) {
            document.getElementById("recaptcha_token").value = token;
          });
        });
      
             }, 3000);
     
</script>
@endif
