@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')
<div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">{{ __($pageTitle) }}</div>
                <div class="vtabs customvtab m-t-10">
                    @include('sections.super_admin_setting_menu')
                    <div class="tab-content" id="google-captcha-settings">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-xs-12">
                                    {!! Form::open(['id' => 'editSlackSettings', 'class' => 'ajax-form', 'method' => 'POST'])
                                    !!}
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4 col-xs-12">
                                                <div class="form-group">
                                                    <label
                                                        class="control-label">@lang('modules.accountSettings.updateEnableDisable')
                                                        <a class="mytooltip" href="javascript:void(0)">
                                                            <i class="fa fa-info-circle"></i>
                                                            <span class="tooltip-content5">
                                                                <span class="tooltip-text3">
                                                                    <span class="tooltip-inner2">
                                                                        @lang('modules.accountSettings.updateEnableDisableTest')
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" id="system_update" name="system_update"
                                                            @if($global->system_update == true) checked
                                                        @endif class="js-switch " data-color="#00c292"
                                                        data-secondary-color="#f96262"/>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <div class="col-sm-12 col-md-4 col-xs-12">
                                                <div class="form-group">
                                                    <label
                                                        class="control-label">@lang('modules.accountSettings.emailVerification')
                                                        <a class="mytooltip" href="javascript:void(0)">
                                                            <i class="fa fa-info-circle"></i>
                                                            <span class="tooltip-content5">
                                                                <span class="tooltip-text3">
                                                                    <span class="tooltip-inner2">
                                                                        @lang('modules.accountSettings.emailVerificationEnableDisable')
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" id="email_verification" name="email_verification"
                                                            @if($global->email_verification == true) checked
                                                        @endif class="js-switch " data-color="#00c292"
                                                        data-secondary-color="#f96262"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">@lang('modules.accountSettings.appDebug')
                                                        <a class="mytooltip" href="javascript:void(0)"> <i
                                                                class="fa fa-info-circle"></i><span
                                                                class="tooltip-content5"><span class="tooltip-text3"><span
                                                                        class="tooltip-inner2">@lang('modules.accountSettings.appDebugInfo')</span></span></span></a></label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" id="app_debug" name="app_debug"
                                                            @if($global->app_debug == true) checked
                                                        @endif class="js-switch " data-color="#00c292"
                                                        data-secondary-color="#f96262"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">@lang('modules.accountSettings.enableRegister')
                                                        <a class="mytooltip" href="javascript:void(0)"> <i
                                                            class="fa fa-info-circle"></i><span
                                                                 class="tooltip-content5"><span class="tooltip-text3"><span
                                                                    class="tooltip-inner2">@lang('modules.accountSettings.enableregisterInfo')</span></span></span></a></label>
                                                        <div class="switchery-demo">
                                                            <input type="checkbox" id="enable_register" name="enable_register"
                                                                @if($global->enable_register == true) checked
                                                            @endif class="js-switch " data-color="#00c292"
                                                            data-secondary-color="#f96262"/>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <br>
                                                <label
                                                    class="control-label">@lang('modules.accountSettings.enableGoogleRecaptcha')
                                                    <a class="mytooltip" href="javascript:void(0)">
                                                        <i class="fa fa-info-circle"></i>
                                                        <span class="tooltip-content5">
                                                            <span class="tooltip-text3">
                                                                <span class="tooltip-inner2">
                                                                    @lang('modules.accountSettings.googleRecaptchaMessage')
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>
                                                </label>
                                                <div class="switchery-demo">
                                                    <input type="checkbox" style ="display:none" id="google_recaptcha_status"
                                                        name="google_recaptcha_status" @if ($global->google_recaptcha_status == true) checked @endif class="js-switch "
                                                        data-color="#00c292" data-secondary-color="#f96262" />
                                                </div>
                                            </div>
                                            
                                            <div id="google-captcha-credentials" style="display: none;">
                                                <div class ="row">
                                                    <div class="col-md-12">
                                                        <label>@lang('app.chooseVersion')</label>
                                                        <div class="form-group">
                                                            <label class="radio-inline"><input value ="v2" type="radio" id="v2" name="version"  @if ($global->google_captcha_version==="v2") checked @endif onclick="captcha();">v2</label>
                                                            <label class="radio-inline m-l-10"><input  value ="v3" id ="v3" type="radio"  data-callback='onClick' name="version" @if ($global->google_captcha_version==="v3") checked @endif onclick="captcha();" >v3</label>
                                                        </div>
                                                    </div>
                                            </div>
                                                <div id="google_captcha_v2">
                                                    <div class="form-group">
                                                        <label class="required">@lang('app.v2SiteKey')</label>
                                                        <input type="text" name="google_recaptcha_key_v2" id="google_captcha_site_key_v2"
                                                        class="form-control form-control-lg"
                                                        value="{{$globalSetting->google_captcha_version == "v2" ? $globalSetting->google_recaptcha_key : '' }}">
                                                        @if ($errors->has('google_recaptcha_key'))
                                                        <span class="text-danger">{{ $errors->first('google_recaptcha_key') }}</span>
                                                    @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="required">@lang('app.v2secretKey')</label>
                                                        <input type="text" name="google_recaptcha_secret_v2" id="google_captcha_secret_v2"
                                                        class="form-control form-control-lg"
                                                        value="{{ $globalSetting->google_captcha_version == "v2" ? $globalSetting->google_recaptcha_secret : '' }}">
                                                    </div>
                                                </div>
                                                <div id="google_captcha_v3">
                                                    <div class="form-group">
                                                        <label class="required">@lang('app.v3SiteKey')</label>
                                                        <input type="text" name="google_recaptcha_key_v3"
                                                            id="google_captcha_site_key_v3"
                                                            class="form-control form-control-lg" value="{{ $globalSetting->google_captcha_version == "v3" ? $globalSetting->google_recaptcha_key : ''}}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="required">@lang('app.v3SecretKey')</label>
                                                        <input type="text" name="google_recaptcha_secret_v3"
                                                            id="google_captcha_secret_v3" class="form-control form-control-lg"
                                                            value="{{$globalSetting->google_captcha_version == "v3" ? $globalSetting->google_recaptcha_secret : ''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" data-callback='onSubmit' id="save-form" style="display: none" class="btn btn-success verify-captcha"><i
                                                            class="fa fa-check"></i>
                                                    @lang('app.verify')
                                                </button>
                                                <button type="submit" data-callback='onSubmit' id="update-captcha" style="display: none" class="btn btn-success  verify-captcha"><i
                                                    ></i>
                                                 @lang('app.update')
                                                </button>

                                                
                                            </div>
                                           
                                        </div>
                                        <!--/span-->
                                    </div>
                                    {!! Form::close() !!}

                                </div>
                                <!-- .row -->

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- .row -->
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">close</button>
                    <button type="button" class="btn blue">save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
       
 var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
     $('.js-switch').each(function() {
     new Switchery($(this)[0], $(this).data());

    });
    $( document ).ready(function() {
        google_recaptcha_status = $('#google_recaptcha_status').is(':checked');
        if(google_recaptcha_status == true){
            $('#save-form').show();
        $('#update-captcha').hide();
        }else{
            $('#update-captcha').show();
            $('#save-form').hide();
        }
        version = $("input[type='radio']:checked").val();
        if(version == 'v2'){
            $('#google_captcha_v2').show();
            $('#google_captcha_v3').hide();
        }else{
            $('#google_captcha_v3').show();
            $('#google_captcha_v2').hide()

        }
    });
    
    var google_captcha_site_key;
    var google_captcha_secret;
    var version;
    var system_update;
    var email_verification;
    var app_debug;
    var enable_register;


    function captcha() {
           version = $("input[type='radio']:checked").val();
           system_update = $('#system_update').is(':checked');
           email_verification = $('#email_verification').is(':checked');
           app_debug = $('#app_debug').is(':checked');
           enable_register = $('#enable_register').is(':checked');

        if(version == 'v2') {
               $('#google_captcha_v2').show();
               $('#google_captcha_v3').hide();
               $('#v3-captcha').hide();
               $('.grecaptcha-badge').hide(); 
                 google_captcha_site_key = $('#google_captcha_site_key_v2').val();
                google_captcha_secret = $('#google_captcha_secret_v2').val();
                return [google_captcha_site_key,google_captcha_secret,google_recaptcha_status,version];
        }
        else {
            $('#google_captcha_v3').show();
            $('#google_captcha_v2').hide();
            $('#v3-captcha').show();
            $('#v3-captcha').html('Please wait verifing key...');  
             google_captcha_site_key = $('#google_captcha_site_key_v3').val();
             google_captcha_secret = $('#google_captcha_secret_v3').val();
             
            return [google_captcha_site_key, google_captcha_secret ,google_recaptcha_status,version];
        }     
           
    }
            //$('#google_recaptcha_status').is(':checked') ? $('#save-form').hide() : $('#update-captcha').show();   //change button name     
           
           $('#google_recaptcha_status').is(':checked') ? $('#google-captcha-credentials').show() : $('#google-captcha-credentials').hide();
            '{{ $globalSetting->google_captcha_version }}' === 'v2' ? $('#google_captcha_v2').show() : $('#google_captcha_v2').hide();
             '{{ $globalSetting->google_captcha_version}}' === 'v3' ? $('#google_captcha_v3').show() : $('#google_captcha_v3').hide();
            //for modal 
            $('.verify-captcha').click( function () {
                 google_recaptcha_status = $('#google_recaptcha_status').is(':checked');
                if(google_recaptcha_status == false){
                    $(this).closest('#editSlackSettings').find("input[type=text]").val("");
                  return save('editSlackSettings', 'PUT');
                }
                $.easyAjax({
                url: '{{ route('super-admin.security-settings.show-modal')}}',
                container: '#editSlackSettings',
                type: 'POST',
                redirect: true,
                data: $('#editSlackSettings').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        captcha(); 
                        var url = '{{ route('super-admin.security-settings.create')}}';
                          url = url+'?google_captcha_site_key=' + google_captcha_site_key.trim() + '&google_captcha_secret=' + google_captcha_secret.trim() + '&version=' + version + '&google_recaptcha_status=' + google_recaptcha_status + '&system_update=' + system_update
                          +'&email_verification=' +email_verification + '&app_debug=' +app_debug + '&enable_register=' +enable_register;
                        $('#modelHeading').html('FAQ');
                        $.ajaxModal('#projectCategoryModal',url );
                    }
                }
            })
            
            })
           
            //enable captcha to change the button name and show form
            $('input[type=checkbox][name=google_recaptcha_status]').change(function() {
                if($('#google_recaptcha_status').is(':checked') == true){
                    $('#save-form').show();
                $('#update-captcha').hide();
                }else{
                    $('#update-captcha').show();
                $('#save-form').hide();
                }
                $('#google-captcha-credentials').toggle();
            });
            function save(formId, methodName){
                console.log(formId);
                    $.easyAjax({
                    url: '{{route('super-admin.security-settings.update', $global->id)}}',
                    container: '#'+formId,
                    type: methodName,
                    redirect: true,
                    data: $('#'+formId).serialize(),
                    success: function (response) {
                        if(response.status == 'success'){
                            $('#projectCategoryModal').modal('hide');
                            $("#v3").attr('checked', 'checked');

                         //   location.reload();
                        }
                    }
                })
            }
</script>
    
@endpush
