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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
@endpush

@section('content')


    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">{{ __($pageTitle) }}</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.super_admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">


                                        <div class="form-group">
                                            <div class="radio-list">
                                                <label class="radio-inline p-0">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="active_theme" @if($global->active_theme == 'default') checked @endif id="default_theme" value="default">
                                                        <label for="default_theme">@lang('modules.themeSettings.useDefaultTheme')</label>
                                                    </div>
                                                </label>
                                                <label class="radio-inline">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="active_theme" id="custom_theme" @if($global->active_theme == 'custom') checked @endif value="custom">
                                                        <label for="custom_theme">@lang('modules.themeSettings.useCustomTheme')</label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>


                                        <div id="custom-theme-options" @if($global->active_theme == 'default') style="display: none" @endif>
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                            <h3 class="box-title m-b-0"><b>@lang('modules.themeSettings.superAdminPanelTheme')</b></h3>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="example">
                                                        <p class="box-title m-t-30">@lang('modules.themeSettings.headerColor')</p>
                                                        <input type="text" class="colorpicker form-control header_color" required name="theme_settings[1][header_color]" value="{{ $adminTheme->header_color }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="example">
                                                        <p class="box-title m-t-30">@lang('modules.themeSettings.sidebarColor')</p>
                                                        <input type="text" class="complex-colorpicker sidebar_color form-control" required name="theme_settings[1][sidebar_color]" value="{{ $adminTheme->sidebar_color }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="example">
                                                        <p class="box-title m-t-30">@lang('modules.themeSettings.sidebarTextColor')</p>
                                                        <input type="text" class="complex-colorpicker sidebar_text_color form-control" required name="theme_settings[1][sidebar_text_color]" value="{{ $adminTheme->sidebar_text_color }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="example">
                                                        <p class="box-title m-t-30">@lang('modules.themeSettings.linkColor')</p>
                                                        <input type="text" class="complex-colorpicker link_color form-control" required name="theme_settings[1][link_color]" value="{{ $adminTheme->link_color }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        

                                            <div class="row">
                                                <div class="col-md-12 m-t-30">
                                                    <button class="btn btn-success" id="save-form" type="submit"><i class="fa fa-check"></i> @lang('app.save')</button>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>

                                </div>
                            </div>
                            <!-- /.row -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->



@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>

<script src="{{ asset('plugins/ace/ace.js') }}"></script>
<script src="{{ asset('plugins/ace/theme-twilight.js') }}"></script>
<script src="{{ asset('plugins/ace/mode-css.js') }}"></script>
<script src="{{ asset('plugins/ace/jquery-ace.min.js') }}"></script>

<script>
    // Colorpicker

    $(".colorpicker").asColorPicker();
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    $(".gradient-colorpicker").asColorPicker({
        mode: 'gradient'
    });

    $('.header_color').on('asColorPicker::change', function (e) {
        document.documentElement.style.setProperty('--header_color', e.target.value);
    });

    $('.sidebar_color').on('asColorPicker::change', function (e) {
        document.documentElement.style.setProperty('--sidebar_color', e.target.value);
    });

    $('.sidebar_text_color').on('asColorPicker::change', function (e) {
        document.documentElement.style.setProperty('--sidebar_text_color', e.target.value);
    });

    $('.link_color').on('asColorPicker::change', function (e) {
        document.documentElement.style.setProperty('--link_color', e.target.value);
    });

    $('.my-code-area').ace({ theme: 'twilight', lang: 'css' })

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.theme-settings.store')}}',
            container: '#editSettings',
            data: $('#editSettings').serialize(),
            type: "POST",
            redirect: true
        })
    });

    $('input[name=active_theme]').click(function () {
        var theme = $('input[name=active_theme]:checked').val();

        $.easyAjax({
            url: '{{route('super-admin.theme-settings.activeTheme')}}',
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', 'active_theme': theme}
        })
    });

</script>
@endpush

