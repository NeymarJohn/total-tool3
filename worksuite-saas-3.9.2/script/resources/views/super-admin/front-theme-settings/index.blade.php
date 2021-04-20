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
    <link rel="stylesheet" href="{{ asset('plugins/image-picker/image-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <style>
        .thumbnail{
            color: black;
            font-weight: 600;
            text-align: center;
        }
        .thumbnail.selected{
            background-color: #f8c234 !important;
        }
        a{
            color:yellow;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel">

                <div class="vtabs customvtab p-t-10">
                    @if($global->front_design == 1)
                        @include('sections.front_setting_new_theme_menu')
                    @else
                        @include('sections.front_setting_menu')
                    @endif

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="white-box">
                                    {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <h3 class="box-title m-b-10">@lang('app.selectTheme') </h3>
                                            <div class="form-group" >
                                                <select name="theme" id="theme" class="image-picker image-picker-theme show-labels show-html" style="color: white">
                                                    <option
                                                            data-img-src="{{ asset('img/old-design.jpg') }}"
                                                            @if($global->front_design == 0) selected @endif
                                                            value="0">
                                                        Theme 1
                                                    </option>

                                                    <option data-img-src="{{ asset('img/new-design.jpg') }}"
                                                            data-toggle="tooltip" data-original-title="Edit"
                                                            @if($global->front_design == 1) selected @endif
                                                            value="1">Theme 2
                                                    </option>

                                                </select>

                                            </div>
                                        </div>

                                        @if(!module_enabled('Subdomain'))
                                            <div class="col-md-12 col-xs-12" id="login_ui_box">
                                                <h3 class="box-title m-b-10">@lang('app.login') @lang('app.theme')</h3>
                                                <div class="form-group" >
                                                    <select name="login_ui" id="login_ui" class="image-picker show-labels show-html" style="color: white">
                                                        <option
                                                                data-img-src="{{ asset('img/old-login.jpg') }}"
                                                                @if($global->login_ui == 0) selected @endif
                                                                value="0">
                                                            Theme 1
                                                        </option>

                                                        <option data-img-src="{{ asset('img/new-login.jpg') }}"
                                                                data-toggle="tooltip" data-original-title="Edit"
                                                                @if($global->login_ui == 1) selected @endif
                                                                value="1">Theme 2
                                                        </option>

                                                    </select>

                                                </div>
                                            </div>
                                        @endif

                                      
                                    </div>
                                    <div class="row">

                                          
                                        <div class="col-xs-12 col-md-4">
                                            <label for="">@lang('modules.superadmin.disableFrontendSite')</label>
                                            <div class="form-group">
                                                <div class="switchery-demo">
                                                    <input type="checkbox" id="frontend_disable"
                                                        name="frontend_disable"
                                                           @if($global->frontend_disable) checked
                                                           @endif class="js-switch " data-color="#00c292"
                                                           data-secondary-color="#f96262"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="set-homepage-div">
                                            <div class="col-md-4 col-xs-12" 
                                            @if($global->frontend_disable)
                                            style="display:none"
                                            @endif
                                            >
                                                <div class="form-group">
                                                    <label class="control-label">@lang('modules.superadmin.setupHomepage')</label>
                                                    <select name="setup_homepage" id="setup_homepage" class="form-control selectpicker">
                                                        <option 
                                                        @if ($global->setup_homepage == "default")
                                                            selected
                                                        @endif
                                                        value="default">@lang('modules.superadmin.defaultLanding')</option>
                                                        <option 
                                                        @if ($global->setup_homepage == "signup")
                                                            selected
                                                        @endif
                                                        value="signup">@lang('app.signup')</option>
                                                        <option 
                                                        @if ($global->setup_homepage == "login")
                                                            selected
                                                        @endif
                                                        value="login">@lang('app.login')</option>
                                                        <option 
                                                        @if ($global->setup_homepage == "custom")
                                                            selected
                                                        @endif
                                                        value="custom">@lang('modules.superadmin.loadCustomUrl')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-md-4" id="home_custom_url"
                                                @if(!$global->frontend_disable && $global->setup_homepage != "custom")
                                                style="display:none"
                                                @endif
                                            >
                                                <label for="">@lang('modules.superadmin.customUrl')</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="{{ $global->custom_homepage_url }}" name="custom_homepage_url" id="custom_homepage_url">
                                                </div>
                                            </div>
                                        </div>

                                        
                                        
                                    <div class="col-sm-12">
                                        <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                            @lang('app.update')
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                                </div>
                            </div>
                        </div>    <!-- .row -->
                </div>

            </div>
        </div>


    </div>
{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="seoDetailModel" role="dialog" aria-labelledby="myModalLabel"
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
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <button type="button" class="btn blue">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
<script src="{{ asset('plugins/image-picker/image-picker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

<script>
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function () {
        new Switchery($(this)[0], $(this).data());

    });

    $('#frontend_disable').change(function () {
        $('#set-homepage-div').toggle();
    })

    $('#theme').on('change', function() {
        alert( this.value );
    });

    $('#setup_homepage').change(function () {
        const homepage = $(this).val();

        if (homepage == "custom") {
            $("#home_custom_url").show()
        } else {
            $("#home_custom_url").hide()
        }
    })

    
    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    $(".image-picker").imagepicker({
        show_label: true
    });

    $(".image-picker-theme").imagepicker({
        show_label  : true,
        changed:function (vale,newval) {
            if(newval ==1){
                $('#login_ui_box').show();
            }else{
                $('#login_ui_box').hide();
            }
        },
        initialized:function (vale) {
            if($(".image-picker-theme").val() ==1){
                    $('#login_ui_box').show();
                }else{
                    $('#login_ui_box').hide();
            }

        }
    })
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.theme-update')}}',
            container: '#editSettings',
            type: "POST",
            redirect: true,
            data: $('#editSettings').serialize()
        })
    });

</script>
@endpush
