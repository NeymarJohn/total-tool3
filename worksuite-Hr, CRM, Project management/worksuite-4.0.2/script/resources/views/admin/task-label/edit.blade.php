@extends('layouts.app')
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
<style>
    .suggest-colors a {
        border-radius: 4px;
        width: 30px;
        height: 30px;
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
        text-decoration: none;
    }
</style>
@endpush
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
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.task-label.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="panel panel-inverse">
            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('app.edit') @lang('app.menu.taskLabel')</div>

                <p class="text-muted m-b-10 font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createContract','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="company_name" class="required">@lang('app.label') @lang('app.name')</label>
                                    <input type="text" class="form-control" name="label_name" value="{{ $taskLabel->label_name }}" />
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">@lang('app.description') </label>
                                    <textarea class="form-control" name="description">{{ $taskLabel->description }} </textarea>
                                </div>

                            </div>
                        </div>
                        <div class="row m-b-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required">@lang('modules.sticky.colors')</label>
                                    <div class="example m-b-10">
                                        <input type="text" class="complex-colorpicker form-control" name="color" id="color" value="{{ $taskLabel->color }}"  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <p>
                                    @lang('messages.taskLabel.labelColorSuggestion')
                                </p>
                            </div>
                            <div class="col-md-12">
                                <div class="suggest-colors">
                                    <a style="background-color: #0033CC" data-color="#0033CC" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #428BCA" data-color="#428BCA" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #CC0033" data-color="#CC0033" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #44AD8E" data-color="#44AD8E" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #A8D695" data-color="#A8D695" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #5CB85C" data-color="#5CB85C" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #69D100" data-color="#69D100" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #004E00" data-color="#004E00" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #34495E" data-color="#34495E" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #7F8C8D" data-color="#7F8C8D" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #A295D6" data-color="#A295D6" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #5843AD" data-color="#5843AD" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #8E44AD" data-color="#8E44AD" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #FFECDB" data-color="#FFECDB" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #AD4363" data-color="#AD4363" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #D10069" data-color="#D10069" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #FF0000" data-color="#FF0000" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #D9534F" data-color="#D9534F" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #D1D100" data-color="#D1D100" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #F0AD4E" data-color="#F0AD4E" href="javascript:;">&nbsp;
                                    </a><a style="background-color: #AD8D43" data-color="#AD8D43" href="javascript:;">&nbsp;
                                    </a></div>
                            </div>
                        </div>
                        <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                            @lang('app.save')
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>

<script>
    $(".colorpicker").asColorPicker();
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    $(".gradient-colorpicker").asColorPicker({
        mode: 'gradient'
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.task-label.update', $taskLabel->id)}}',
            container: '#createContract',
            type: "POST",
            redirect: true,
            data: $('#createContract').serialize()
        })
    });
    $('.suggest-colors a').click(function () {
        var color = $(this).data('color');
        $('#color').val(color);
        $('.asColorPicker-trigger span').css('background', color);
    });
</script>
@endpush

