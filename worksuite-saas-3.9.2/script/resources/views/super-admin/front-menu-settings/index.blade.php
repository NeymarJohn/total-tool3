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
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/iconpicker/css/fontawesome-iconpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel">

                <div class="vtabs customvtab p-t-10">
                    @include('sections.front_setting_new_theme_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="white-box">
                                        <h3 class="box-title m-b-0">@lang('app.menu.menu') @lang('app.menu.settings')</h3>
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
                                            @include('super-admin.front-menu-settings.edit-form')
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                            </div>    <!-- .row -->

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>

@endsection

@push('footer-script')
    <script>
        function changeForm(target) {
            $.easyAjax({
                url: "{{ route('super-admin.front-menu-settings.changeForm') }}",
                container: '#editSettings',
                data: {
                    language_settings_id: $(target).data('language-id')
                },
                type: 'GET',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#edit-form').html(response.view);
                    }
                }
            })
        }

        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            changeForm(e.target);
        })

        $('body').on('click', '#save-form', function () {
            $.easyAjax({
                url: "{{route('super-admin.front-menu-settings.title-update')}}",
                container: '#editSettings',
                type: "POST",
                file: true,
                data: {
                    language_settings_id: $('#editSettings').data('language-id')
                }
            })
        });


    </script>
@endpush
