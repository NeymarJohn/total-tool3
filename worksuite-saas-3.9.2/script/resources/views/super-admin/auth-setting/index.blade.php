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
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.authSetting') @lang('app.forTheme') @if($global->login_ui  && $global->front_design == 1) 2 @else 1 @endif</div>

                <div class="vtabs customvtab m-t-10">
                    @if($global->front_design == 1)
                        @include('sections.front_setting_new_theme_menu')
                    @else
                        @include('sections.front_setting_menu')
                    @endif
                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                        <hr>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="company_name">@lang('app.authCss')</label>
                                                @if($global->login_ui == 1 && $global->front_design == 1)
                                                    <textarea name="auth_css" class="my-code-area" rows="20" style="width: 100%">@if(is_null($global->auth_css_theme_two))/*Enter your auth css after this line*/@else {!! $global->auth_css_theme_two !!} @endif</textarea>
                                               @else
                                                    <textarea name="auth_css" class="my-code-area" rows="20" style="width: 100%">@if(is_null($global->auth_css))/*Enter your auth css after this line*/@else {!! $global->auth_css !!} @endif</textarea>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" id="save-form"
                                            class="btn btn-success waves-effect waves-light m-r-10">
                                        @lang('app.update')
                                    </button>

                                    {!! Form::close() !!}
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
<script src="{{ asset('plugins/ace/ace.js') }}"></script>
<script src="{{ asset('plugins/ace/theme-twilight.js') }}"></script>
<script src="{{ asset('plugins/ace/mode-css.js') }}"></script>
<script src="{{ asset('plugins/ace/jquery-ace.min.js') }}"></script>

<script>
    $('.my-code-area').ace({ theme: 'twilight', lang: 'css' })

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.auth-update')}}',
            container: '#editSettings',
            type: "POST",
            redirect: true,
            file: true,
        })
    });

</script>
@endpush
