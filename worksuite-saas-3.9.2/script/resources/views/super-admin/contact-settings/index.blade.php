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
                                        {!! Form::open(['id'=>'editSettings','class'=>'ajax-form']) !!}
                                        <h3>@lang('modules.frontCms.contactDetail')</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="email">@lang('app.email')</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                           value="{{ $frontDetail->email }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="phone">@lang('modules.accountSettings.companyPhone')</label>
                                                    <input type="tel" class="form-control" id="phone" name="phone"
                                                           value="{{ $frontDetail->phone }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="address">@lang('modules.accountSettings.companyAddress')</label>
                                                    <textarea class="form-control" id="address" rows="5"
                                                              name="address">{{ $frontDetail->address }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="address">@lang('modules.accountSettings.htmlOrEmbeded')</label>
                                                    <textarea class="form-control" id="contact_html" rows="10"
                                                              name="contact_html"> {!! $frontDetail->contact_html !!} </textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" id="save-form"
                                                class="btn btn-success waves-effect waves-light m-r-10">
                                            @lang('app.update')
                                        </button>

                                        {!! Form::close() !!}
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
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

  <script>
      $('.summernote').summernote({
          height: 200,                 // set editor height
          minHeight: null,             // set minimum height of editor
          maxHeight: null,             // set maximum height of editor
          focus: false,
          toolbar: [
              // [groupName, [list of button]]
              ['style', ['bold', 'italic', 'underline', 'clear']],
              ['font', ['strikethrough']],
              ['fontsize', ['fontsize']],
              ['para', ['ul', 'ol', 'paragraph']],
              ["view", ["fullscreen"]]
          ]
      });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('super-admin.contactus-setting-update')}}',
            container: '#editSettings',
            type: "POST",
            data: $('#editSettings').serialize()
        })
    });

</script>
@endpush
