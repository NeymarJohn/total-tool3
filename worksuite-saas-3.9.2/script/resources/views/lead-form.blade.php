<!DOCTYPE html>

<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ $global->favicon_url }}">
    {{--<link rel="manifest" href="{{ asset('favicon/manifest.json') }}">--}}
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $global->favicon_url }}">
    <meta name="theme-color" content="#ffffff">

    <title>@lang($pageTitle)</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

    @stack('head-script')
    <!-- This is a Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (default.css) for this starter
       page. However, you can choose any other skin from folder css / colors .
       -->
    <link href="{{ asset('css/colors/default.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-new.css') }}" rel="stylesheet">

    @if($global->rounded_theme)
    <link href="{{ asset('css/rounded.css') }}" rel="stylesheet">
    @endif
    <style>
        html {
            background: #ffffff;
        }
    </style>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<body>
    <div class="row">
        <div class="col-md-12">
            <div class="white-box p-t-20">
                {!! Form::open(['id'=>'createLead','class'=>'ajax-form','method'=>'POST']) !!}
                <div class="form-body">
                    <div class="row">
                        <input type="hidden" name="company_id" value="{{ $leadFormFields[0]->company_id }}">

                        @foreach ($leadFormFields as $item)
                            @if($item->field_name != 'message')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.lead.'.$item->field_name)</label>
                                        <input type="text" id="{{ $item->field_name }}" name="{{ $item->field_name }}"
                                            class="form-control">
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.lead.'.$item->field_name) </label>
                                        <textarea class="form-control"   id="{{ $item->field_name }}" name="{{ $item->field_name }}"  ></textarea>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @if($global->lead_form_google_captcha == 1  && $superadmin->google_captcha_version=="v2" && $superadmin->google_recaptcha_status)
                            <div class="form-group {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                                <div class="col-xs-12 m-b-20">
                                    <div class="g-recaptcha"
                                         data-sitekey="{{ $superadmin->google_recaptcha_key }}">
                                    </div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <div class="help-block with-errors">{{ $errors->first('g-recaptcha-response') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        @endif
                        <input type='hidden' name='recaptcha_token' id='recaptcha_token'>
                    </div>

                </div>
                <div class="form-actions">
                    <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i>
                        @lang('app.save')</button>
                    <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                </div>
                {!! Form::close() !!}

                <div class="row">
                    <div class="col-xs-12">
                        <div class="alert alert-success" id="success-message" style="display:none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

<script src='https://www.google.com/recaptcha/api.js'></script>

@if($superadmin->google_recaptcha_status  && $superadmin->google_captcha_version=="v3" && $global->lead_form_google_captcha == 1)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $superadmin->google_recaptcha_key }}"></script>

    <script>
        setTimeout(function(){

            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $superadmin->google_recaptcha_key }}', {action: 'submit'}).then(function(token) {
                    document.getElementById("recaptcha_token").value = token;
                });
            });

        }, 3000);

    </script>
@endif
<script>

$('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('front.leadStore')}}',
                container: '#createLead',
                type: "POST",
                redirect: true,
                disableButton: true,
                data: $('#createLead').serialize(),
                success: function (response) {
                    if (response.status == "success") {
                        $('#createLead')[0].reset();
                        $('#createLead').hide();
                        $('#success-message').html(response.message);
                        $('#success-message').show();
                    }
                }
            })
        });
</script>

</head>