<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">

                <div class="col-md-1 col-xs-3">
                    <img src="{{ $row->image_url }}" alt="user" class="img-circle" width="40" height="40">
                </div>
                <div class="col-md-11 col-xs-9">
                    {{ ucwords($row->name) }} <br>
                    <span class="font-light text-muted">{{ ucfirst($row->job_title) }}</span>
                </div>
                <div class="clearfix"></div>

            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <div class="row">
                        {!! Form::open(['id'=>'attendance-container-'.$row->id,'class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body ">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.attendance.clock_in')</label>
                                        @if(!is_null($row->clock_in_time)) <span class="label label-success">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->clock_in_time)->timezone($global->timezone)->format($global->time_format) }} <i class="icon-check"></i></span> @else
                                            <label class="label label-danger">@lang('modules.attendance.absent')</label> @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" >@lang('modules.attendance.late')</label>
                                        @if($row->late == "yes") <span class="label label-success"><i class="fa fa-check"></i> @lang('app.yes')</span> @else -- @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row m-t-15">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.attendance.clock_out')</label>
                                        @if(!is_null($row->clock_out_time)) <span class="label label-success">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->clock_out_time)->timezone($global->timezone)->format($global->time_format) }} <i class="icon-check"></i></span> @else -- @endif
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label" >@lang('modules.attendance.halfDay')</label>
                                        @if($row->half_day == "yes") <span class="label label-success"><i class="fa fa-check"></i> @lang('app.yes')</span> @else -- @endif
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.attendance.working_from')</label>
                                        {{ $row->working_from ?? '--' }}
                                    </div>
                                </div>



                            </div>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
