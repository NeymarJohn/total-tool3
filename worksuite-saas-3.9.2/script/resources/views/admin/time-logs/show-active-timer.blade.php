<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.projects.activeTimers')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.projects.whoWorking')</th>
                    <th>@lang('app.task')</th>
                    <th>@lang('modules.employees.memo')</th>
                    <th>@lang('modules.projects.activeSince')</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody id="active-timer-list">
                @forelse($activeTimers as $key=>$time)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($time->name) }}</td>
                        <td>
                            @if (!is_null($time->project_id) && !is_null($time->task_id))
                                <span class="font-semi-bold">
                                    {{ $time->task->heading }}
                                </span><br>
                                <span class="text-muted">
                                    {{ $time->project->project_name }}
                                </span>
                            @elseif (!is_null($time->project_id))
                                <span class="font-semi-bold">
                                    {{ $time->project->project_name }}
                                </span>
                            @elseif (!is_null($time->task_id))
                                <span class="font-semi-bold">
                                    {{ $time->task->heading }}
                                </span>
                            @endif
                        </td>
                        <td class="font-bold ">{{ ucwords(substr($time->memo, 0, 50)) }}</td>
                        <td class="font-bold "><i class="ti-alarm-clock"></i> <span class="timer">{{ $time->timer }}</span></td>
                        <td><a href="javascript:;" data-time-id="{{ $time->id }}" class="label label-danger stop-active-timer">@lang('app.stop')</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">@lang('messages.noActiveTimer')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(e) {
            function updateTimer($worked) {
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
            }
            $timers = $(".timer");
            $timers.each(function ($key, $timer) {
                setInterval(function() {
                    updateTimer($($timer))
                }, 1000);
            });

        $('#active-timer-list').on('click', '.stop-active-timer', function () {
            var id = $(this).data('time-id');
            var url = '{{route('admin.all-time-logs.stopTimer', ':id')}}';
            url = url.replace(':id', id);
            var token = '{{ csrf_token() }}';
            $.easyAjax({
                url: url,
                type: "POST",
                data: {timeId: id, _token: token},
                success: function (data) {
                    $('#active-timer-list').html(data.html);
                    $('#activeCurrentTimerCount').html(data.activeTimers);
                    $('.activeCurrentTimerCount').html(data.activeTimers);
                }
            })

        });
    });

</script>
