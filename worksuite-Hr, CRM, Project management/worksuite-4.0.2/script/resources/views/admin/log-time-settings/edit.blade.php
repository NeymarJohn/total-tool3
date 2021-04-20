@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
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
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang($pageTitle)</div>

                <div class="vtabs customvtab m-t-10">
                    @include('sections.admin_setting_menu')

                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="white-box">

                                <div class="alert alert-info ">
                                    <i class="fa fa-info-circle"></i> @lang('messages.logTimeNote')
                                </div>
                                {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}

                                <div class="form-group">
                                    <div class="checkbox checkbox-info  col-md-10">
                                        <input id="auto_timer_stop" name="auto_timer_stop" value="yes"
                                                @if($logTime->auto_timer_stop == "yes") checked
                                                @endif
                                                type="checkbox">
                                        <label for="auto_timer_stop">@lang('modules.logTimeSetting.autoStopTimerAfterOfficeTime')</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox checkbox-info  col-md-10">
                                        <input id="approval_required" name="approval_required" value="true"
                                                @if($logTime->approval_required) checked
                                                @endif
                                                type="checkbox">
                                        <label for="approval_required">@lang('modules.logTimeSetting.approvalRequired')</label>
                                    </div>
                                </div>

                                {{--<div class="row">--}}
                                    {{--<div class="col-md-12 m-t-30">--}}
                                        {{--<button class="btn btn-success" id="save-form" type="button"><i class="fa fa-check"></i> @lang('app.save')</button>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                            
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->



@endsection

@push('footer-script')

<script>

    // change Log Time For Setting
    $('input[name=log_time_for]').click(function () {
        var timeFor = $('input[name=log_time_for]:checked').val();

        $.easyAjax({
            url: '{{route('admin.log-time-settings.store')}}',
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', 'log_time_for': timeFor}
        })
    });

    $('#auto_timer_stop').click(function(){
        var auto_timer_stop = 'no';
        if($(this).prop("checked") == true){
             auto_timer_stop = 'yes';
        }
        $.easyAjax({
            url: '{{route('admin.log-time-settings.store')}}',
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', 'auto_timer_stop': auto_timer_stop}
        })
    });

    $('#approval_required').click(function(){
        var approval_required = '0';
        if($(this).prop("checked") == true){
            approval_required = '1';
        }
        $.easyAjax({
            url: '{{route('admin.log-time-settings.store')}}',
            type: "POST",
            data: {'_token': '{{ csrf_token() }}', 'approval_required': approval_required}
        })
    });
</script>
@endpush

