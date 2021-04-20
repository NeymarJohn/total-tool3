<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $global->favicon_url }}">
    {{--<link rel="manifest" href="{{ asset('favicon/manifest.json') }}">--}}
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $global->favicon_url }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $setting->company_name }}</title>

    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('less/icons/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    @stack('head-script')

    <style>
        #background-section {
            background: url("{{ $setting->login_background_url }}") center center/cover no-repeat !important;
        }
        #auth-logo {
            background: {{ $setting->logo_background_color }};
        }
        {!! $setting->auth_css !!}
    </style>

</head>
<body>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-lg-5" id="form-section">
        <div id="register-auth-logo" class="row">
            <div class="col-xs-6">
                <img src="{{ $setting->logo_front_url }}" style="max-height: 35px" alt="Logo"/>
            </div>
            <div class="col-xs-6 text-right">
                <span class="text-muted">@lang('messages.alreadyHaveAnAccount')</span>
                <a href="{{ module_enabled('Subdomain')?route('front.workspace'):route('login') }}"
                    class="btn login-url shadow-none">@lang('app.login')</a>
            </div>
        </div>

        <div class="row" id="register-auth-form">


            @yield('content')

        </div>
    </div>

    <div class="col-lg-7 visible-lg" id="background-section">

    </div>
  </div>
</div>

<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

@stack('footer-script')

</body>
</html>
