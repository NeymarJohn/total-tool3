<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>@lang('app.login') | {{ ucwords($setting->company_name)}}</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $global->favicon_url }}">
    {{--<link rel="manifest" href="{{ asset('favicon/manifest.json') }}">--}}
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $global->favicon_url }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Bootstrap CSS -->
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('saas/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('saas/vendor/animate-css/animate.min.css') }}">
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('saas/vendor/slick/slick.css') }}">
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('saas/vendor/slick/slick-theme.css') }}">
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('saas/fonts/flaticon/flaticon.css') }}">
    <link href="{{ asset('front/plugin/froiden-helper/helper.css') }}" rel="stylesheet">
    <!-- Template CSS -->
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('saas/css/main.css') }}">
    <!-- Template Font Family  -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&family=Rubik:wght@400;500&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" media="all"
          href="{{ asset('saas/vendor/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">

    <script src="https://www.google.com/recaptcha/api.js"></script>

    @if(isset($socialAuthSettings) && ($socialAuthSettings->facebook_status == 'enable' || $socialAuthSettings->google_status == 'enable' || $socialAuthSettings->twitter_status == 'enable' || $socialAuthSettings->linkedin_status == 'enable'))
        <style>
             .login-box {
                max-width: 900px
            }
        </style>
    @endif
    <style>
        {!! $setting->auth_css_theme_two !!}

        :root {
            --main-color: {{ $frontDetail->primary_color }};
        }
        .help-block {
            color: #8a1f11 !important;
        }

        @media (max-width: 767px) {
            .login-box form {
                padding: 10px;
            }

            .input-group-text {
                font-size: 13px;
            }

            .login-box h5 {
                padding: 35px 15px 15px;
                font-size: 21px;
                text-align: center;
                font-weight: 600;
            }
        }

    </style>

    @if(isset($socialAuthSettings) && ($socialAuthSettings->facebook_status == 'enable' || $socialAuthSettings->google_status == 'enable' || $socialAuthSettings->twitter_status == 'enable' || $socialAuthSettings->linkedin_status == 'enable'))
        @php
            $socialLogin = true;
        @endphp
    @else
        @php
            $socialLogin = false;
        @endphp
    @endif

    @if($socialLogin)
    <style>
        .login-box {
            max-width: 900px
        }
    </style>
    @endif
    
</head>

<body id="home">


<!-- Topbar -->
@include('sections.saas.saas_header')
<!-- END Topbar -->

<!-- Header -->
<!-- END Header -->


<section class="sp-100 login-section" id="section-contact">
    <div class="container">
        <div class="login-box mt-5 shadow bg-white form-section row align-items-center">

            <div class="@if($socialLogin)
            col-lg-6 
           @else col-lg-12 @endif order-lg-1 " id="form-box">
                <h4 class="mb-0">
                    @lang('app.login')
                </h4>
    
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
                    @if($global->google_recaptcha_status)
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
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="checkbox checkbox-primary float-left p-t-0">
                                <input id="checkbox-signup" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="checkbox-signup" class="text-dark"> @lang('app.rememberMe') </label>
                            </div>
                            <a href="{{ route('password.request') }}"  class="text-dark float-right"><i class="fa fa-lock m-r-5"></i> @lang('app.forgotPassword')?</a> </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">@lang('app.login')</button>
                        </div>
                    </div>
                    @if($global->enable_register == true)
                        <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                <p>@lang('messages.dontHaveAccount')<br><a href="{{ route('front.signup.index') }}"
                                                            class="text-primary m-l-5"><b>@lang('app.signup')</b></a></p>
                            </div>
                    @endif
                    </div>
                </form>
            </div>

            <script>
                var facebook = "{{ route('social.login', 'facebook') }}";
                var google = "{{ route('social.login', 'google') }}";
                var twitter = "{{ route('social.login', 'twitter') }}";
                var linkedin = "{{ route('social.login', 'linkedin') }}";
            </script>
            @if(isset($socialAuthSettings) && ($socialAuthSettings->facebook_status == 'enable' || $socialAuthSettings->google_status == 'enable' || $socialAuthSettings->twitter_status == 'enable' || $socialAuthSettings->linkedin_status == 'enable'))
            <div class="col-lg-6 order-lg-2 ">
                <div class="row ">
                        
                            <div class="col-xs-12 col-sm-12 m-t-10 text-center mb-2">
                                @if($socialAuthSettings->facebook_status == 'enable')
                                    <a href="javascript:;" class="btn btn-primary btn-facebook" data-toggle="tooltip" title="@lang('app.loginWithFacebook')" onclick="window.location.href = facebook;" data-original-title="@lang('app.loginWithFacebook')">@lang('app.loginWithFacebook') <i aria-hidden="true" class="zmdi zmdi-facebook"></i> </a>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-12 m-t-10 text-center mb-2">
                                @if($socialAuthSettings->google_status == 'enable')
                                    <a href="javascript:;" class="btn btn-primary btn-google" data-toggle="tooltip" title="@lang('app.loginWithGoogle')" onclick="window.location.href = google;" data-original-title="@lang('app.loginWithGoogle')">@lang('app.loginWithGoogle') <i aria-hidden="true" class="zmdi zmdi-google"></i> </a>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-12  m-t-10 text-center mb-2">
                                    @if($socialAuthSettings->twitter_status == 'enable')
                                        <a href="javascript:;" class="btn btn-primary btn-twitter" data-toggle="tooltip" title="@lang('app.loginWithTwitter')" onclick="window.location.href = twitter;" data-original-title="@lang('app.loginWithTwitter')">@lang('app.loginWithTwitter') <i aria-hidden="true" class="zmdi zmdi-twitter"></i> </a>
                                    @endif
                            </div>
                            <div class="col-xs-12 col-sm-12 m-t-10 text-center">
                                    @if($socialAuthSettings->linkedin_status == 'enable')
                                        <a href="javascript:;" class="btn btn-primary btn-linkedin" data-toggle="tooltip" title="@lang('app.loginWithLinkedin')" onclick="window.location.href = linkedin;" data-original-title="@lang('app.loginWithLinkedin')">@lang('app.loginWithLinkedin') <i aria-hidden="true" class="zmdi zmdi-linkedin"></i> </a>
                                    @endif
                            </div>
                </div>

            </div>
            @endif
        </div>
    </div>
</section>

<!-- END Main container -->

<!-- Cta -->
{{--@include('saas.sections.cta')--}}
<!-- End Cta -->

<!-- Footer -->
@include('sections.saas.saas_footer')
<!-- END Footer -->



<!-- Scripts -->
<script src="{{ asset('saas/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('saas/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('saas/vendor/slick/slick.min.js') }}"></script>
<script src="{{ asset('saas/vendor/wowjs/wow.min.js') }}"></script>
<script src="{{ asset('front/plugin/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('saas/js/main.js') }}"></script>
<script src="{{ asset('front/plugin/froiden-helper/helper.js') }}"></script>
<!-- Global Required JS -->

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

</body>
</html>
