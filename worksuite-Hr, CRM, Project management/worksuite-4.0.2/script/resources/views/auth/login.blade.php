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
        @if($setting->google_recaptcha)
        <div class="form-group {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
            <div class="col-xs-12">
                <div class="g-recaptcha"
                     data-sitekey="{{ $setting->google_recaptcha_key }}">
                </div>
                @if ($errors->has('g-recaptcha-response'))
                    <div class="help-block with-errors">{{ $errors->first('g-recaptcha-response') }}</div>
                @endif
            </div>
        </div>
        @endif
        <div class="form-group">
            <div class="col-md-12">
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

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                <script>
                    var facebook = "{{ route('social.login', 'facebook') }}";
                    var google = "{{ route('social.login', 'google') }}";
                    var twitter = "{{ route('social.login', 'twitter') }}";
                    var linkedin = "{{ route('social.login', 'linkedin') }}";
                </script>
                <div class="social mb-16">
                    @if($socialAuthSettings->facebook_status == 'enable')
                        <a href="javascript:;" class="btn btn-facebook" data-toggle="tooltip" title="Login with Facebook" onclick="window.location.href = facebook;" data-original-title="Login with Facebook"> <i aria-hidden="true" class="fa fa-facebook-f"></i> </a>
                    @endif
                    @if($socialAuthSettings->google_status == 'enable')
                        <a href="javascript:;" class="btn btn-google" data-toggle="tooltip" title="Login with Google" onclick="window.location.href = google;" data-original-title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a>
                    @endif
                    @if($socialAuthSettings->twitter_status == 'enable')
                        <a href="javascript:;" class="btn btn-twitter" data-toggle="tooltip" title="Login with twitter" onclick="window.location.href = twitter;" data-original-title="Login with Google"> <i aria-hidden="true" class="fa fa-twitter"></i> </a>
                    @endif
                    @if($socialAuthSettings->linkedin_status == 'enable')
                        <a href="javascript:;" class="btn btn-linkedin" data-toggle="tooltip" title="Login with linkedin" onclick="window.location.href = linkedin;" data-original-title="Login with Linkedin"> <i aria-hidden="true" class="fa fa-linkedin"></i> </a>
                    @endif
                </div>
            </div>
        </div>

        {{--<div class="form-group m-b-0">--}}
            {{--<div class="col-sm-12 text-center">--}}
                {{--<p>Don't have an account? <a href="{{ route('register') }}" class="text-primary m-l-5"><b>Sign Up</b></a></p>--}}
            {{--</div>--}}
        {{--</div>--}}
    </form>
@endsection
