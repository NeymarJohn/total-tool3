<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" ><i class="icon-clock"></i> @lang('app.menu.attendance') @lang('app.details') </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>@lang('app.menu.attendance') <small class="text-muted">{{ $startTime->format($global->date_format) }}</small></h4>
                    <div class="punch-det">
                        <h6>@lang('modules.attendance.clock_in')</h6>
                        <p>{{ $startTime->format($global->time_format) }}</p>
                    </div>
                    <div class="punch-info">
                        <div class="punch-hours">
                            <span>{{ $totalTime }} @lang('app.hrs')</span>
                        </div>
                    </div>
                    <div class="punch-det">
                        <h6>@lang('modules.attendance.clock_out')</h6>
                        <p>{{ $endTime->format($global->time_format) }} 
                        @if (isset($notClockedOut))
                            (@lang('modules.attendance.notClockOut'))
                        @endif
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card recent-activity">
                <div class="white-box">
                    <h5 class="card-title">@lang('modules.employees.activity')</h5>

                        @foreach ($attendanceActivity->reverse() as $item)
                         <div class="row res-activity-box" id="timelogBox{{ $item->aId }}">
                            <ul class="res-activity-list col-md-6">
                                <li>
                                    <p class="mb-0">@lang('modules.attendance.clock_in')</p>
                                    <p class="res-activity-time">
                                        <i class="fa fa-clock-o"></i>
                                        {{ $item->clock_in_time->timezone($global->timezone)->format($global->time_format) }}.
                                    </p>
                                </li>
                                <li>
                                    <p class="mb-0">@lang('modules.attendance.clock_out')</p>
                                    <p class="res-activity-time">
                                        <i class="fa fa-clock-o"></i>
                                        @if (!is_null($item->clock_out_time))
                                            {{ $item->clock_out_time->timezone($global->timezone)->format($global->time_format) }}.
                                        @else
                                            @lang('modules.attendance.notClockOut')
                                        @endif
                                    </p>
                                </li>
                            </ul>

                             <div class="col-md-6">
                                <a href="javascript:;" onclick="editAttendance({{ $item->aId }})"  id="attendance-edit" data-attendance-id="{{ $item->aId }}" class="btn btn-info btn-xs"  ><i class="fa fa-pencil"></i> @lang('app.edit')</a>
                                <a href="javascript:;" onclick="deleteAttendance({{ $item->aId }})" id="attendance-edit" data-attendance-id="{{ $item->aId }}" class="btn btn-danger btn-xs"  ><i class="fa fa-times"></i> @lang('app.delete')</a>
                            </div>
                         </div>
                        @endforeach

                </div>
            </div>
        </div>
    </div>

</div>
<script>
     function deleteAttendance(id){
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.recoverRecord')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('member.attendances.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();

                            $('#timelogBox'+id).remove();
//                                    swal("Deleted!", response.message, "success");
                            showTable();
                            $('#projectTimerModal').modal('hide');
                        }
                    }
                });
            }
        });
    }

</script>