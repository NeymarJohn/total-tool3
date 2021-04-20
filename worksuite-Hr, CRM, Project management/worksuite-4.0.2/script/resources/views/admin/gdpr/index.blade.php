@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang($pageTitle)</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.gdpr_settings_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="box-title m-b-0">General GDPR Settings</h3>
                                    <div class="row b-t m-t-20 p-10">
                                        <div class="col-md-12">
                                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                                            <label for="">Enable GDPR</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                           class="checkbox"
                                                           @if($gdprSetting->enable_gdpr) checked @endif
                                                           value="1" name="enable_gdpr">Yes
                                                </label>
                                                <label class="radio-inline m-l-10">
                                                    <input type="radio"
                                                           @if($gdprSetting->enable_gdpr==0) checked @endif
                                                           value="0" name="enable_gdpr">No
                                                </label>


                                            </div>
                                            <hr>
                                            <label for="">Show GDPR link in customers area navigation</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                           class="checkbox"
                                                           @if($gdprSetting->show_customer_area==1) checked @endif
                                                           value="1" name="show_customer_area">Yes
                                                </label>
                                                <label class="radio-inline m-l-10">
                                                    <input type="radio"
                                                           @if($gdprSetting->show_customer_area==0) checked @endif
                                                           value="0" name="show_customer_area">No
                                                </label>


                                            </div> <hr>
                                            <label for="">Show GDPR link in customers area footer</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                           class="checkbox"
                                                           @if($gdprSetting->show_customer_footer==1) checked @endif
                                                           value="1" name="show_customer_footer">Yes
                                                </label>
                                                <label class="radio-inline m-l-10">
                                                    <input type="radio"
                                                           @if($gdprSetting->show_customer_footer==0) checked @endif
                                                           value="0" name="show_customer_footer">No
                                                </label>


                                            </div>
                                            <hr>
                                            <label for="">GDPR page top information block</label>
                                            <div class="form-group">
                                                <textarea name="top_information_block" id="" cols="30" rows="10" class="summernote">
                                                    {{$gdprSetting->top_information_block}}
                                                </textarea>

                                            </div>

                                            <button type="button" onclick="submitForm();" class="btn btn-primary">Submit</button>
                                            {!! Form::close() !!}
                                        </div>
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
        function submitForm(){

            $.easyAjax({
                url: '{{route('admin.gdpr.store')}}',
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize(),
            })
        }


    </script>
@endpush

