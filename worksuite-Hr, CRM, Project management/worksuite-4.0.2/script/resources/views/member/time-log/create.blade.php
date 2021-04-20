<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="ti-plus"></i> @lang('modules.timeLogs.startTimer')</h4>
</div>
<div class="modal-body">

    {!! Form::open(['id'=>'startTimer','class'=>'ajax-form','method'=>'POST', 'onSubmit' => 'return false']) !!}
    <div class="form-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">@lang('modules.timeLogs.selectProject')</label>
                    <select class="form-control select2" name="project_id" id="project_id" >
                        <option value="">--</option>
                        @foreach($projects as $project)
                            @if(!is_null($project->project))
                            <option value="{{ $project->project_id }}">{{ ucwords($project->project->project_name) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <!--/span-->

            <div class="col-md-12">
                <div class="form-group">

                    <div class="checkbox checkbox-info">
                        <input id="create_task" name="create_task" type="checkbox">
                        <label for="create_task">@lang('app.create') @lang('modules.tasks.newTask')</label>
                    </div>

                    <div class="checkbox checkbox-info" style="display: none" id="private_div">
                        <input id="private-task" name="is_private" value="true" type="checkbox">
                        <label for="private-task">@lang('modules.tasks.makePrivate')</label>
                    </div>

                    <input type="hidden" name="user_id[]" value="{{ $user->id}}">
                </div>
            </div>

            <div class="col-md-12" id="task_div">
                <div class="form-group">
                    <label class="control-label">@lang('modules.timeLogs.selectTask')</label>
                    <select class="select2 form-control" name="task_id" data-placeholder="@lang('app.selectTask')" id="task_id">
                        <option value="">--</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ ucwords($task->heading) }}</option>
                        @endforeach

                    </select>
                </div>
            </div>
            <!--/span-->

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label required">@lang('modules.timeLogs.memo')</label>
                    <input type="text" id="memo" name="memo" class="form-control">
                </div>
            </div>

            <!--/span-->

        </div>
        <!--/row-->

    </div>
    <div class="form-actions">
        <button type="button" id="start-timer-btn" class="btn btn-success"><i class="fa fa-check"></i> @lang('modules.timeLogs.startTimer')</button>
    </div>
    {!! Form::close() !!}

</div>

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $("input[name=create_task]").click(function () {
        if($(this).is(":checked")){
            $('#task_div').hide();
            $('#private_div').show();
        }
        else{
            $('#task_div').show();
            $('#private_div').hide();
        }
    })
    
    function updateTimer() {
        var $worked = $("#active-timer");
        var myTime = $worked.html();
        var ss = myTime.split(":");

        var hours = ss[0];
        var mins = ss[1];
        var secs = ss[2];
        secs = parseInt(secs)+1;

        if(secs > 59){
            secs = '00';
            mins = parseInt(mins)+1;
        }

        if(mins > 59){
            secs = '00';
            mins = '00';
            hours = parseInt(hours)+1;
        }

        if(hours.toString().length < 2) {
            hours = '0'+hours;
        }
        if(mins.toString().length < 2) {
            mins = '0'+mins;
        }
        if(secs.toString().length < 2) {
            secs = '0'+secs;
        }
        var ts = hours+':'+mins+':'+secs;

        $worked.html(ts);
        setTimeout(updateTimer, 1000);
    }

    //    save new task
    $('#start-timer-btn').click(function () {
        $.easyAjax({
            url: '{{route('member.time-log.store')}}',
            container: '#startTimer',
            type: "POST",
            data: $('#startTimer').serialize(),
            success: function (data) {
                $('#timer-section').html(data.html);
                $('#projectTimerModal').modal('hide');
                $('#projectTimerModal .modal-body').html('Loading...');
                $('#activeCurrentTimerCount').text(parseInt($('#activeCurrentTimerCount').text()) + 1);
                updateTimer();
            }
        })
    });


</script>
