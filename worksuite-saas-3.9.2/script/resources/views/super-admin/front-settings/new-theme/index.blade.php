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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.frontCms.updateTitle')</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.front_setting_new_theme_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="white-box">
                                        <h3 class="box-title m-b-0"> @lang("modules.frontSettings.title")</h3>

                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                {!! Form::open(['id'=>'commonSettings','class'=>'ajax-form m-b-30','method'=>'PUT']) !!}
                                                <h4>@lang('modules.frontCms.commonSettings')</h4>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="company_name" class="d-block">@lang('modules.frontCms.primaryColor')</label>
                                                            <input type="text" name="primary_color" class="gradient-colorpicker form-control" autocomplete="off" value="{{ $frontDetail->primary_color }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="address">@lang('modules.frontCms.defaultLanguage')</label>
                                                            <select name="default_language" id="default_language" class="form-control select2">
                                                                <option @if($frontDetail->locale == "en") selected @endif value="en">English
                                                                </option>
                                                                @foreach($languageSettings as $language)
                                                                    <option value="{{ $language->language_code }}" @if($frontDetail->locale == $language->language_code) selected @endif >{{ $language->language_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="custom_css" class="d-block">@lang('modules.frontCms.customCss')</label>
                                                            <textarea name="custom_css" class="my-code-area" rows="20" style="width: 100%">@if(is_null($frontDetail->custom_css_theme_two))/*Enter your auth css after this line*/ @else {!! $frontDetail->custom_css_theme_two !!}  @endif</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <div class="checkbox checkbox-info  col-md-10">
                                                                <input id="get_started_show" name="get_started_show" value="yes"
                                                                       @if($frontDetail->get_started_show == "yes") checked
                                                                       @endif
                                                                       type="checkbox">
                                                                <label for="get_started_show">@lang('modules.frontCms.getStartedButtonShow')</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <div class="checkbox checkbox-info  col-md-10">
                                                                <input id="sign_in_show" name="sign_in_show" value="yes"
                                                                       @if($frontDetail->sign_in_show == "yes") checked
                                                                       @endif
                                                                       type="checkbox">
                                                                <label for="sign_in_show">@lang('modules.frontCms.singInButtonShow')</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <h4 id="social-links">@lang('modules.frontCms.socialLinks')</h4>
                                                <hr>
                                                <span class="text-danger">@lang('modules.frontCms.socialLinksNote')</span><br><br>
                                                <div class="row">
                                                    @foreach(json_decode($frontDetail->social_links) as $link)

                                                        <div class="col-sm-12 col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="{{ $link->name }}">
                                                                    @lang('modules.frontCms.'.$link->name)
                                                                </label>
                                                                <input
                                                                        class="form-control"
                                                                        id="{{ $link->name }}"
                                                                        name="social_links[{{ $link->name }}]"
                                                                        type="url"
                                                                        value="{{ $link->link }}"
                                                                        placeholder="@lang('modules.frontCms.enter'.ucfirst($link->name).'Link')">
                                                            </div>
                                                        </div>

                                                    @endforeach
                                                </div>

                                                <button type="submit" id="save-common-form"
                                                        class="btn btn-success waves-effect waves-light m-r-10">
                                                    @lang('app.update')
                                                </button>

                                                {!! Form::close() !!}

                                                <h4 id="social-links">@lang('modules.frontCms.frontDetail')</h4>
                                                <hr>
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item active">
                                                        <a 
                                                            class="nav-link active"
                                                            id="en-tab"
                                                            data-toggle="tab"
                                                            data-language-id="0"
                                                            href="#en"
                                                            role="tab"
                                                            aria-controls="en"
                                                            aria-selected="true"
                                                        >
                                                            <span class="flag-icon flag-icon-us"></span> English
                                                        </a>
                                                    </li>
                                                    @forelse ($activeLanguages as $language)
                                                        <li class="nav-item">
                                                            <a 
                                                                class="nav-link"
                                                                id="{{$language->language_code}}-tab"
                                                                data-toggle="tab"
                                                                data-language-id="{{$language->id}}"
                                                                href="#{{$language->language_code}}"
                                                                role="tab"
                                                                aria-controls="{{$language->language_code}}"
                                                                aria-selected="true"
                                                            >
                                                                <span class="flag-icon flag-icon-{{ $language->language_code }}"></span> {{ ucfirst($language->language_name) }}
                                                            </a>
                                                        </li>
                                                    @empty
                                                    @endforelse
                                                </ul>
                                                <div class="tab-content" id="edit-form">
                                                    @include('super-admin.front-settings.new-theme.edit-form', ['trFrontDetail' => $trFrontDetail])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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



@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('plugins/ace/ace.js') }}"></script>
<script src="{{ asset('plugins/ace/theme-twilight.js') }}"></script>
<script src="{{ asset('plugins/ace/mode-css.js') }}"></script>
<script src="{{ asset('plugins/ace/jquery-ace.min.js') }}"></script>
<script>
    $('.my-code-area').ace({ theme: 'twilight', lang: 'css' })
    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link',  'hr','video']],
            ['view', ['fullscreen', 'codeview']],
            ['help', ['help']]
        ]
    });
    // Colorpicker
    $(".colorpicker").asColorPicker();
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    $(".gradient-colorpicker").asColorPicker();

    function init() {
        $('.summernote').summernote({
            height: 200,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: false,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link',  'hr']],
                ['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ]
        });
    }

    init();

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        changeForm(e.target);
    })

    function changeForm(target) {
        $.easyAjax({
            url: "{{ route('super-admin.front-settings.changeForm') }}",
            container: '#editSettings',
            data: {
                language_settings_id: $(target).data('language-id')
            },
            type: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    $('#edit-form').html(response.view);
                    init();
                }
            }
        })
    }

    $('#save-common-form').click(function () {
        $.easyAjax({
            url: "{{ route('super-admin.front-settings.update', $frontDetail->id) }}",
            container: '#commonSettings',
            type: 'POST',
            data: $('#commonSettings').serialize()
        })
    })

    $('body').on('click', '#save-form', function () {
        $.easyAjax({
            url: "{{ route('super-admin.front-settings.updateDetail') }}",
            container: '#editSettings',
            type: "POST",
            file: true,
            data: {
                language_settings_id: $('#editSettings').data('language-id'),
                header_description: $('#header_description').summernote('code')
            }
        })
    });

</script>
@endpush
