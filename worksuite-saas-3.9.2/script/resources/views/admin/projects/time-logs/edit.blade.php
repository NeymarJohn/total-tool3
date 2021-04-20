<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-clock-o"></i> @lang('app.update') @lang('app.menu.timeLogs')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(['id'=>'updateTime','class'=>'ajax-form','method'=>'PUT']) !!}
                <div class="form-body">
                    <div class="row m-t-30">
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>@lang('app.project')</label>
                                <select class="form-control select2" name="project_id" data-placeholder="@lang('app.selectProject')"  id="project_id_edit">
                                    <option value="">--</option>
                                    @forelse($timeLogProjects as $tlproject)
                                        <option @if($tlproject->id  == $timeLog->project_id) selected @endif value="{{ $tlproject->id }}">{{ ucwords($tlproject->project_name) }}</option>
                                            @empty
                                        <option value="">@lang('messages.noProjectFound')</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label>@lang('modules.timeLogs.task')</label>
                                <select class="form-control select2" name="task_id"
                                        id="task_id_edit" data-style="form-control">
                                        <option value="">--</option>
                                    @forelse($tasks as $task)
                                        <option @if($task->id == $timeLog->task_id)
                                                selected
                                                @endif value="{{ $task->id }}">{{ ucfirst($task->heading) }}</option>
                                    @empty
                                        <option value="">@lang('messages.noTaskAddedToProject')</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        @if(isset($project))
                            <div class="col-md-4 " id="editEmployeeBox">
                                <div class="form-group">
                                    <label>@lang('modules.timeLogs.employeeName')</label>
                                    <select class="form-control select2" name="user_id" id="user_id_edit">
                                        @forelse($project->members as $member)
                                            <option
                                                    @if($member->user->id == $timeLog->user_id)
                                                    selected
                                                    @endif
                                                    value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                        @empty
                                            <option value="">No member added to project</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label>@lang('app.startDate')</label>
                                <input id="start_date" name="start_date" type="text" class="form-control"
                                       value="{{ $timeLog->start_time->timezone($global->timezone)->format($global->date_format) }}">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label>@lang('app.endDate')</label>
                                <input id="end_date" name="end_date" type="text" class="form-control"
                                       @if(!is_null($timeLog->end_time)) value="{{ $timeLog->end_time->timezone($global->timezone)->format($global->date_format) }}" @else value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group bootstrap-timepicker timepicker">
                                <label>@lang('modules.timeLogs.startTime')</label>
                                <input type="text" name="start_time" id="start_time"
                                       value="{{ $timeLog->start_time->timezone($global->timezone)->format('h:i A') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group bootstrap-timepicker timepicker">
                                <label>@lang('modules.timeLogs.endTime')</label>
                                <input type="text" name="end_time" @if(!is_null($timeLog->end_time)) value="{{ $timeLog->end_time->timezone($global->timezone)->format('h:i A') }}" @endif
                                id="end_time" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">@lang('modules.timeLogs.totalHours')</label>

                            <p id="total_time" class="form-control-static">
                                <?php
                                $datetime1 = new DateTime($timeLog->start_time);
                                $datetime2 = new DateTime($timeLog->end_time);
                                $interval = $datetime1->diff($datetime2);
                                $hours = $interval->format('%h');
                                $days = $interval->format('%d');
                                if ($interval->format('%d') > 0) {
                                    $hours = $hours + $days * 24;
                                }
                                echo $hours . " Hours " . $interval->format('%i') . " Minutes";
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="row m-t-20">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="memo">@lang('modules.timeLogs.memo')</label>
                                <input type="text" name="memo" id="memo" class="form-control" value="{{ $timeLog->memo }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions m-t-30">
                    <button type="button" id="update-form" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')
                    </button>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>


<script>
    $('#editEmployeeBox').show();
    $('#user_id_edit, #task_id_edit').select2();
    $("#project_id_edit, #user_id_edit").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#project_id_edit').change(function () {
        var id = $(this).val();
        var url = '{{route('admin.all-time-logs.members', ':id')}}';
        url = url.replace(':id', id);
        $('#editEmployeeBox').show();
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            success: function (data) {
                $('#user_id_edit').html(data.html);
                $('#task_id_edit').html(data.tasks);
                $('#user_id_edit, #task_id_edit').select2();
            }
        })
    });
    $('#updateTime #start_time, #updateTime #end_time').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false
        @endif
    }).on('hide.timepicker', function (e) {
//        console.log('The time is ' + e.time.value);
//        console.log('The hour is ' + e.time.hours);
//        console.log('The minute is ' + e.time.minutes);
//        console.log('The meridian is ' + e.time.meridian);
        calculateTime();
    });

    jQuery('#updateTime #start_date,#updateTime #end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    }).on('hide', function (e) {
        calculateTime();
    });


    function calculateTime() {
        var format = '{{ $global->moment_format }}';
        var startDate = $('#updateTime #start_date').val();
        var endDate = $('#updateTime #end_date').val();
        var startTime = $("#updateTime #start_time").val();
        var endTime = $("#updateTime #end_time").val();

        startDate = moment(startDate, format).format('YYYY-MM-DD');
        endDate = moment(endDate, format).format('YYYY-MM-DD');

        var timeStart = new Date(startDate + " " + startTime);
        var timeEnd = new Date(endDate + " " + endTime);

        var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds

        var minutes = diff % 60;
        var hours = (diff - minutes) / 60;

        if (hours < 0 || minutes < 0) {
            var numberOfDaysToAdd = 1;
            timeEnd.setDate(timeEnd.getDate() + numberOfDaysToAdd);
            var dd = timeEnd.getDate();

            if (dd < 10) {
                dd = "0" + dd;
            }

            var mm = timeEnd.getMonth() + 1;

            if (mm < 10) {
                mm = "0" + mm;
            }

            var y = timeEnd.getFullYear();

            calculateTime();
        } else {
            $('#updateTime #total_time').html(hours + "Hrs " + minutes + "Mins");
        }

    }

    $('#update-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.time-logs.update', $timeLog->id)}}',
            container: '#updateTime',
            type: "POST",
            data: $('#updateTime').serialize(),
            success: function (response) {
                $('#editTimeLogModal').modal('hide');
                if (typeof table !== "undefined") {
                    table._fnDraw();                    
                } else {
                    showTable();
                }
            }
        })
    });
</script>