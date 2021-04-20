<div class="modal-header" id="attendDetail">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" style="display: inline-block;"><i class="icon-clock"></i> @lang('app.menu.attendance')
        @if($type == 'edit')
            @lang('app.details')
        @else
            @lang('app.mark')
        @endif

    </h4>
    <div class=""  style="display: inline-block;">
        @if($clock_in > 0)
            <label class="label label-success"><i class="fa fa-check"></i> @lang('modules.attendance.present')</label>
            <button type="button" title="Attendance Detail" id="viewAttendance" class="btn btn-info btn-sm btn-rounded view-attendance" data-attendance-id="{{$row->id}}">
                <i class="fa fa-search"></i> Detail
            </button>
        @else
            <label class="label label-danger"><i class="fa fa-exclamation-circle"></i> @lang('modules.attendance.absent')</label>
        @endif

    </div>
</div>
<div class="modal-body">

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-inverse">
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            @if($total_clock_in < $maxAttandenceInDay)
                                {!! Form::open(['id'=>'attendance-container','class'=>'ajax-form','method'=>'POST']) !!}
                                {{ csrf_field() }}
                                <input type="hidden" name="attendance_date" value="{{ Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($global->date_format) }}">
                                <input type="hidden" name="user_id" value="{{ $userid }}">

                                <div class="form-body ">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <label>@lang('modules.attendance.clock_in') </label>
                                                <input type="text" name="clock_in_time"
                                                       class="form-control a-timepicker"   autocomplete="off"   id="clock-in-time"
                                                       @if(!is_null($row->clock_in_time)) value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->clock_in_time)->timezone($global->timezone)->format($global->time_format) }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">@lang('modules.attendance.clock_in') IP</label>
                                                <input type="text" name="clock_in_ip" id="clock-in-ip"
                                                       class="form-control" value="{{ $row->clock_in_ip ?? request()->ip() }}">
                                            </div>
                                        </div>

                                        @if($row->total_clock_in == 0)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label" >@lang('modules.attendance.late')</label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" name="late" @if($row->late == "yes") checked @endif class="js-switch change-module-setting" data-color="#ed4040" id="late"  />
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="row m-t-15">

                                        <div class="col-md-4">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <label>@lang('modules.attendance.clock_out')</label>
                                                <input type="text" name="clock_out_time" id="clock-out"
                                                       class="form-control b-timepicker"   autocomplete="off"
                                                       @if(!is_null($row->clock_out_time)) value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->clock_out_time)->timezone($global->timezone)->format($global->time_format) }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">@lang('modules.attendance.clock_out') IP</label>
                                                <input type="text" name="clock_out_ip" id="clock-out-ip-{{ $row->id }}"
                                                       class="form-control" value="{{ $row->clock_out_ip ?? request()->ip() }}">
                                            </div>
                                        </div>

                                        @if($row->total_clock_in == 0)
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label" >@lang('modules.attendance.halfDay')</label>
                                                    <div class="switchery-demo">
                                                        <input type="checkbox" name="halfday"  @if($row->half_day == "yes") checked @endif class="js-switch change-module-setting" data-color="#ed4040" id="halfday"  />
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">@lang('modules.attendance.working_from')</label>
                                                <input type="text" name="working_from" id="working-from"
                                                       class="form-control" value="{{ $row->working_from ?? 'office' }}">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-success text-white save-attendance"><i
                                                            class="fa fa-check"></i> @lang('app.save')</button>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                                {!! Form::close() !!}
                            @else
                                <div class="col-xs-12">
                                    <div class="alert alert-info">@lang('modules.attendance.maxColckIn')</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $('.a-timepicker').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false,
        @endif
        minuteStep: 1
    });
    $('.b-timepicker').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false,
        @endif
        minuteStep: 1,
        defaultTime: false
    });
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());

    });
    $('#attendance-container').on('click', '.save-attendance', function () {
        @if($type == 'edit')
            var url = '{{route('member.attendances.updateDetails', $row->id)}}';
        @else
            var url = '{{route('member.attendances.storeMark')}}';
        @endif
        $.easyAjax({
            url: url,
            type: "POST",
            container: '#attendance-container',
            data: $('#attendance-container').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    showTable();
                   @if($type == 'edit')
                        $('#attendanceModal').modal('hide');
                   @else
                        $('#projectTimerModal').modal('hide');
                    @endif
                }
            }
        })
    });
    $('#viewAttendance').on('click',function () {
        $('#attendanceModal').modal('hide');
        var attendanceID = $(this).data('attendance-id');
        var url = '{!! route('member.attendances.info', ':attendanceID') !!}';
        url = url.replace(':attendanceID', attendanceID);

        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });

</script>