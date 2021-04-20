<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">

                <div class="col-md-1 col-xs-2">
                    <img src="{{ $row->image_url }}" alt="user" class="img-circle" width="40" height="40">
                </div>
                <div class="col-md-8 col-xs-6">
                    {{ ucwords($row->name) }} <br>
                    <span class="font-light text-muted">{{ ucfirst($row->designation_name) }}</span>
                </div>
                <div class="col-md-3 col-xs-4">
                    @if($row->clock_in > 0)
                        <label class="label label-success"><i class="fa fa-check"></i> @lang('modules.attendance.present')</label>
                        <button type="button" title="Attendance Detail" class="btn btn-info btn-sm btn-rounded" onclick="attendanceDetail('{{ $row->id }}', '{{ \Carbon\Carbon::createFromFormat('Y-m-d', $row->atte_date)->timezone($global->timezone)->format('Y-m-d')   }}')">
                            <i class="fa fa-search"></i> Detail
                        </button>
                    @else
                        <label class="label label-danger"><i class="fa fa-exclamation-circle"></i> @lang('modules.attendance.absent')</label>
                    @endif

                </div>
                <div class="clearfix"></div>

            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <div class="row">
                        @if($row->total_clock_in < $maxAttandenceInDay)
                        {!! Form::open(['id'=>'attendance-container-'.$row->id,'class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body ">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.attendance.clock_in')</label>
                                        <input type="text" name="clock_in_time"   autocomplete="off"
                                               class="form-control a-timepicker" id="clock-in-{{ $row->id }}"
                                               @if(!is_null($row->clock_in_time)) value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->clock_in_time)->timezone($global->timezone)->format('h:i A') }}" @endif>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.attendance.clock_in') IP</label>
                                        <input type="text" name="clock_in_ip" id="clock-in-ip-{{ $row->id }}"
                                               class="form-control" value="{{ $row->clock_in_ip ?? request()->ip() }}">
                                    </div>
                                </div>
                                @if($row->total_clock_in == 0)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" >@lang('modules.attendance.late')</label>
                                            <div class="switchery-demo">
                                                <input type="checkbox" @if($row->late == "yes") checked @endif class="js-switch change-module-setting" data-color="#ed4040" id="late-{{ $row->id }}"  />
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="row m-t-15">

                                <div class="col-md-4">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.attendance.clock_out')</label>
                                        <input type="text" autocomplete="off" name="clock_out_time" id="clock-out-{{ $row->id }}"
                                               class="form-control b-timepicker"
                                               @if(!is_null($row->clock_out_time)) value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->clock_out_time)->timezone($global->timezone)->format('h:i A') }}" @endif>
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
                                                <input type="checkbox" @if($row->half_day == "yes") checked @endif class="js-switch change-module-setting" data-color="#ed4040" id="halfday-{{ $row->id }}"  />
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.attendance.working_from')</label>
                                        <input type="text" name="working_from" id="working-from-{{ $row->id }}"
                                               class="form-control" value="{{ $row->working_from ?? 'office' }}">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-2 clearfix">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success text-white save-attendance"
                                                data-user-id="{{ $row->id }}"><i
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
