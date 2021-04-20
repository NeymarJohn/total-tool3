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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.update') @lang('app.menu.attendanceSettings')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                            <div class="row">
                                <div class="form-body ">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <label class="required">@lang('modules.attendance.officeStartTime')</label>
                                                <input type="text" name="office_start_time" id="office_start_time"
                                                       class="form-control"
                                                       value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $attendanceSetting->office_start_time)->format('h:i A') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <label class="required">@lang('modules.attendance.officeEndTime')</label>
                                                <input type="text" name="office_end_time" id="office_end_time"
                                                       class="form-control"
                                                       value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $attendanceSetting->office_end_time)->format('h:i A') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <label>@lang('modules.attendance.halfDayMarkTime')</label>
                                                <input type="text" name="halfday_mark_time" id="halfday_mark_time"
                                                       class="form-control"
                                                       value="@if($attendanceSetting->halfday_mark_time){{ \Carbon\Carbon::createFromFormat('H:i:s', $attendanceSetting->halfday_mark_time)->format('h:i A') }}@else 01:00 @endif">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="required" for="late_mark_duration">@lang('modules.attendance.lateMark')</label>
                                            <input type="number" class="form-control" id="late_mark_duration"
                                                   name="late_mark_duration"
                                                   value="{{ $attendanceSetting->late_mark_duration }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="late_mark_duration">@lang('modules.attendance.checkininday')</label>
                                            <input type="number" class="form-control" id="clockin_in_day"
                                                   name="clockin_in_day"
                                                   value="{{ $attendanceSetting->clockin_in_day }}">
                                        </div>
                                    </div>

                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-info  col-md-10">
                                                <input id="employee_clock_in_out" name="employee_clock_in_out" value="yes"
                                                       @if($attendanceSetting->employee_clock_in_out == "yes") checked
                                                       @endif
                                                       type="checkbox">
                                                <label for="employee_clock_in_out">@lang('modules.attendance.allowSelfClock')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-info  col-md-10">
                                                <input id="radius_check" name="radius_check" value="yes"
                                                       @if($attendanceSetting->employee_clock_in_out == "no") disabled
                                                       @endif
                                                       @if($attendanceSetting->radius_check == "yes") checked
                                                       @endif
                                                       type="checkbox">
                                                <label for="radius_check">@lang('modules.attendance.checkForRadius')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 @if($attendanceSetting->radius_check == "no") hidden @endif" id="radiusBox">
                                        <div class="form-group">
                                            <label for="late_mark_duration">@lang('modules.attendance.radius')</label>
                                            <input type="number" class="form-control" id="radius"
                                                   name="radius"
                                                   value="{{ $attendanceSetting->radius }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-info  col-md-10">
                                                <input id="ip_check" name="ip_check" value="yes"
                                                        @if($attendanceSetting->employee_clock_in_out == "no") disabled
                                                       @endif
                                                       @if($attendanceSetting->ip_check == "yes") checked
                                                        @endif
                                                    type="checkbox">
                                                <label for="ip_check">@lang('modules.attendance.checkForIp')</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 @if($attendanceSetting->ip_check == "no") hidden @endif" id="ipBox">
                                        <div id="addMoreBox1" class="clearfix">
                                            @forelse($ipAddresses as $index => $ipAddress)
                                            <div class="col-md-5" style="margin-left: 5px;">
                                                <div class="form-group" id="occasionBox">
                                                    <input class="form-control"  type="text" value="{{ $ipAddress }}" name="ip[{{ $index }}]" placeholder="@lang('modules.attendance.ipAddress')"/>
                                                    <div id="errorOccasion"></div>
                                                </div>
                                            </div>
                                            @empty
                                            <div class="col-md-5" style="margin-left: 5px;">
                                                <div class="form-group" id="occasionBox">
                                                    <input class="form-control"  type="text" name="ip[0]" placeholder="@lang('modules.attendance.ipAddress')"/>
                                                    <div id="errorOccasion"></div>
                                                </div>
                                            </div>
                                            @endforelse
                                            <div class="col-md-1">
                                                {{--<button type="button"  onclick="removeBox(1)"  class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>--}}
                                            </div>
                                        </div>
                                        <div id="insertBefore"></div>
                                        <div class="clearfix">

                                        </div>
                                        <button type="button" id="plusButton" class="btn btn-sm btn-info" style="margin-bottom: 20px;margin-left: 13px">
                                            @lang('app.add') <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="col-xs-12">
                                        <hr>
                                        <label class="control-label col-md-12 p-l-0">@lang('modules.attendance.officeOpenDays')</label>
                                        <div class="form-group">
                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                <input id="open_mon" name="office_open_days[]" value="1"
                                                       @foreach($openDays as $day)
                                                           @if($day == 1) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_mon">@lang('app.monday')</label>
                                            </div>
                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                <input id="open_tues" name="office_open_days[]" value="2"
                                                       @foreach($openDays as $day)
                                                       @if($day == 2) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_tues">@lang('app.tuesday')</label>
                                            </div>
                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                <input id="open_wed" name="office_open_days[]" value="3"
                                                       @foreach($openDays as $day)
                                                       @if($day == 3) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_wed">@lang('app.wednesday')</label>
                                            </div>
                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                <input id="open_thurs" name="office_open_days[]" value="4"
                                                       @foreach($openDays as $day)
                                                       @if($day == 4) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_thurs">@lang('app.thursday')</label>
                                            </div>
                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                <input id="open_fri" name="office_open_days[]" value="5"
                                                       @foreach($openDays as $day)
                                                       @if($day == 5) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_fri">@lang('app.friday')</label>
                                            </div>
                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                <input id="open_sat" name="office_open_days[]" value="6"
                                                       @foreach($openDays as $day)
                                                       @if($day == 6) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_sat">@lang('app.saturday')</label>
                                            </div>
                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                <input id="open_sun" name="office_open_days[]" value="0"
                                                       @foreach($openDays as $day)
                                                       @if($day == 0) checked @endif
                                                       @endforeach
                                                       type="checkbox">
                                                <label for="open_sun">@lang('app.sunday')</label>
                                            </div>
                                        </div>
                                    </div>

                                <div class="col-md-12">
                                    <div class="form-actions m-t-15">
                                        <button type="submit" id="save-form"
                                                class="btn btn-success waves-effect waves-light m-r-10">
                                            @lang('app.update')
                                        </button>
                                    </div>

                                </div>

                                </div>



                            </div>
                            {!! Form::close() !!}

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
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>


<script>
    var $insertBefore = $('#insertBefore');
    var $i = {{ count($ipAddresses) }};
    $('#office_end_time, #office_start_time, #halfday_mark_time').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false,
        @endif
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.attendance-settings.update', ['1'])}}',
            container: '#editSettings',
            type: "POST",
            redirect: true,
            data: $('#editSettings').serialize()
        })
    });

    $('#employee_clock_in_out').click(function(){
        if($(this).prop("checked") == true){
            $('#radius_check').removeAttr("disabled");
            $('#ip_check').removeAttr("disabled");
        }
        else if($(this).prop("checked") == false){
            if($('#radius_check').prop("checked") == true){
                $('#radius_check').trigger('click');
            }
            if($('#ip_check').prop("checked") == true){
                $('#ip_check').trigger('click');
            }
            
            $('#radius_check').attr("disabled", 'disabled');
            $('#ip_check').attr("disabled", 'disabled');
        }
    });

    $('#radius_check').click(function(){
        if($(this).prop("checked") == true){
            $('#radiusBox').attr("style", "display: block !important");
        }
        else if($(this).prop("checked") == false){
            $('#radiusBox').attr("style", "display: none !important");
        }
    });
    $('#ip_check').click(function(){
        if($(this).prop("checked") == true){
            $('#ipBox').attr("style", "display: block !important");
        }
        else if($(this).prop("checked") == false){
            $('#ipBox').attr("style", "display: none !important");
        }
    });
    // Add More Inputs
    $('#plusButton').click(function(){

        $i = $i+1;
        var indexs = $i+1;
        $(' <div id="addMoreBox'+indexs+'" class="clearfix"> ' +
            '<div class="col-md-5 "style="margin-left:5px;"><div class="form-group"><input class="form-control " name="ip['+$i+']" type="text" value="" placeholder="@lang('modules.attendance.ipAddress')"/></div></div>' +
            '<div class="col-md-1"><button type="button" onclick="removeBox('+indexs+')" class="btn btn-sm btn-inverse btn-outline"><i class="fa fa-trash"></i></button></div>' +
            '</div>').insertBefore($insertBefore);

    });
    // Remove fields
    function removeBox(index){
        $('#addMoreBox'+index).remove();
    }

</script>

@endpush

