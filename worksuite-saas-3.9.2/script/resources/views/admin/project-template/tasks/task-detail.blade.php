
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="ti-eye"></i> @lang('app.menu.tasks') @lang('app.details')</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-xs-12">
                <h5>{{ ucwords($task->heading) }}
                    @if($task->task_category_id)
                        <label class="label label-default text-dark m-l-5 font-light">{{ ucwords($task->category->category_name) }}</label>
                    @endif

                    <label class="m-l-5 font-light label
                @if($task->priority == 'high')
                            label-danger @elseif($task->priority == 'medium') label-warning @else label-success @endif ">
                        <span class="text-dark">@lang('modules.tasks.priority') ></span>  {{ ucfirst($task->priority) }}
                    </label>


                </h5>
                @if(!is_null($task->project_id))
                    <p><i class="icon-layers"></i> {{ ucfirst($task->project->project_name) }}</p>
                @endif
            </div>

            <div class="col-xs-6">
                <label class="font-12" for="">@lang('modules.tasks.assignTo')</label><br>
                @foreach($task->users_many as $user)
                <a href="javascript:;" data-toggle="tooltip" data-original-title="{{ ucwords($user->name) }}">
                    <img src="{{ $user->image_url }}" class="img-circle" width="25" height="25" alt=""></a>
                @endforeach
            </div>
            <div class="col-xs-6">
                <label class="font-12" for="">@lang('app.menu.projectTemplate')</label><br>
                {{ ucwords($task->projectTemplate->project_name) }}
            </div>
            <div class="col-xs-12 m-t-10">
                <label class="font-12" for="">@lang('app.description')</label><br>
                {!! ucwords($task->description) !!}
            </div>
        </div>
        <div class="col-xs-12 m-t-10">
            <h5><i class="ti-check-box"></i> @lang('modules.tasks.subTask')
                {{--@if (sizeof($task->subtasks) > 0)--}}
                    {{--<span class="pull-right"><span class="donut" data-peity='{ "fill": ["#00c292", "#eeeeee"],    "innerRadius": 5, "radius": 8 }'></span> <span class="text-muted font-12">{{ floor((count($task->completedSubtasks)/count($task->subtasks))*100) }}%</span></span>--}}
                {{--@endif--}}
            </h5>
            <ul class="list-group" id="sub-task-list">
                @forelse($task->subtasks as $subtask)
                    <li class="list-group-item row" id="subTaskList{{ $subtask->id }}">
                        <div class="col-xs-9">
                            <div class="checkbox checkbox-success checkbox-circle task-checkbox">
                                <span id="taskData{{ $subtask->id }}">{{ ucfirst($subtask->title) }}</span>
                            </div>
                        </div>

                        <div class="col-xs-3 text-right">
                            <a href="javascript:;" data-sub-task-id="{{ $subtask->id }}" title="@lang('app.edit')" class="edit-sub-task"><i class="fa fa-pencil"></i></a>&nbsp;
                            <a href="javascript:;" data-sub-task-id="{{ $subtask->id }}" title="@lang('app.delete')" class="delete-sub-task"><i class="fa fa-trash"></i></a>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item row">
                        <div class="col-xs-12">
                            <div class="checkbox checkbox-success checkbox-circle task-checkbox">
                                <span>@lang('messages.noTemplateSubTaskFound')</span>
                            </div>
                        </div>

                    </li>
                @endforelse
            </ul>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $('body').on('click', '.delete-sub-task', function () {
        var id = $(this).data('sub-task-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted sub task!",
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: "No, cancel please!",
                confirm: {
                    text: "Yes, delete it!",
                    value: true,
                    visible: true,
                    className: "danger",
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.project-template-sub-task.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
//                            $('#sub-task-list').html(response.view);
                            $('#subTaskList'+id).remove();

                        }
                    }
                });
            }
        });
    });
    $('body').on('click', '.edit-sub-task', function () {
        var id = $(this).data('sub-task-id');
        var url = '{{ route('admin.project-template-sub-task.edit', ':id')}}';
        url = url.replace(':id', id);
        $('#subTaskModelHeading').html('Sub Task');
        $.ajaxModal('#subTaskModal', url);
    })
</script>

