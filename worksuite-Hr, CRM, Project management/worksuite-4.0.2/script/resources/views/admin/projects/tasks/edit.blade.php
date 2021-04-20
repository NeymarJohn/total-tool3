<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">

<style>
    select.bs-select-hidden, select.selectpicker {
        display: block!important;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading "><i class="ti-pencil"></i> @lang('modules.tasks.updateTask')
        <div class="panel-action">
            <a href="javascript:;" class="close" id="hide-edit-task-panel" data-dismiss="modal"><i class="ti-close"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in">
        <div class="panel-body">
            {!! Form::open(['id'=>'updateTask','class'=>'ajax-form','method'=>'PUT']) !!}
            {!! Form::hidden('project_id', $task->project_id) !!}

            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label required">@lang('app.title')</label>
                            <input type="text" id="heading" name="heading" class="form-control" value="{{ $task->heading }}">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.description')</label>
                            <textarea id="description" name="description" class="form-control summernote">{{ $task->description }}</textarea>
                        </div>
                    </div>
                    <!--/span-->

                    <div class="col-md-3">
                        <div class="form-group">

                            <div class="checkbox checkbox-info">
                                <input id="private-task-2" name="is_private" value="true"
                                @if ($task->is_private)
                                    checked
                                @endif
                                       type="checkbox">
                                <label for="private-task-2">@lang('modules.tasks.makePrivate') <a class="mytooltip font-12" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.tasks.privateInfo')</span></span></span></a></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">

                            <div class="checkbox checkbox-info">
                                <input id="billable-task-2" name="billable" value="true"
                                @if ($task->billable)
                                    checked
                                @endif
                                       type="checkbox">
                                <label for="billable-task-2">@lang('modules.tasks.billable') <a class="mytooltip font-12" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.tasks.billableInfo')</span></span></span></a></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="checkbox checkbox-info">
                                <input id="set-time-estimate-2"
                                @if ($task->estimate_hours > 0 || $task->estimate_minutes > 0)
                                    checked
                                @endif
                                name="set_time_estimate" value="true" type="checkbox">
                                <label for="set-time-estimate-2">@lang('modules.tasks.setTimeEstimate')</label>
                            </div>
                        </div>
                    </div>

                    <div id="set-time-estimate-fields-2" @if ($task->estimate_hours == 0 && $task->estimate_minutes == 0) style="display: none" @endif>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                <input type="number" min="0" value="{{ $task->estimate_hours }}" class="w-50 p-5 p-10" name="estimate_hours" > @lang('app.hrs')
                                &nbsp;&nbsp;
                                <input type="number" min="0" value="{{ $task->estimate_minutes }}" name="estimate_minutes" class="w-50 p-5 p-10"> @lang('app.mins')
                            </div>
                        </div>
                       
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">

                            <div class="checkbox checkbox-info">
                                <input id="dependent-task-project" name="dependent" value="yes"
                                       type="checkbox" @if($task->dependent_task_id != '') checked @endif onclick="dependedSelected(this)">
                                <label for="dependent-task-project">@lang('modules.tasks.dependent')</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="dependent-fields-project" @if($task->dependent_task_id == null) style="display: none" @endif>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('modules.tasks.dependentTask')</label>
                                <select class="select2 form-control" data-placeholder="@lang('modules.tasks.chooseTask')" name="dependent_task_id" id="dependent_task_id_project" >
                                    @foreach($allTasks as $allTask)
                                        <option value="{{ $allTask->id }}" @if($allTask->id == $task->dependent_task_id) selected @endif>{{ $allTask->heading }} (@lang('app.dueDate'): {{ $allTask->due_date->format($global->date_format) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label required">@lang('app.startDate')</label>
                            <input type="text" name="start_date" id="start_date2" class="form-control" autocomplete="off" value="@if($task->start_date != '-0001-11-30 00:00:00' && $task->start_date != null) {{ $task->start_date->format($global->date_format) }} @endif">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label required">@lang('app.dueDate')</label>
                            <input type="text" name="due_date" id="due_date2" class="form-control" autocomplete="off" value="@if($task->due_date != '-0001-11-30 00:00:00') {{ $task->due_date->format($global->date_format) }} @endif">
                        </div>
                    </div>
                    <!--/span-->

                    <div class="col-md-12">
                        <label class="control-label">@lang('modules.projects.milestones')</label>
                        <div class="form-group">
                            <select class="form-control" name="milestone_id" id="milestone_id"
                                    data-style="form-control">
                                <option value="">--</option>
                                @foreach($task->project->milestones as $milestone)
                                    <option
                                    @if($milestone->id == $task->milestone_id) selected @endif
                                     value="{{ $milestone->id }}">{{ $milestone->milestone_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label select-required">@lang('modules.tasks.assignTo')</label>
                            <select class="select2 select2-multiple " multiple="multiple" data-placeholder="@lang('modules.tasks.chooseAssignee')"  name="user_id[]" id="user_id2">
                                @if(is_null($task->project_id))
                                    @foreach($employees as $employee)

                                        @php
                                            $selected = '';
                                        @endphp

                                        @foreach ($task->users as $item)
                                            @if($item->id == $employee->id)
                                                @php
                                                    $selected = 'selected';
                                                @endphp
                                            @endif

                                        @endforeach

                                        <option {{ $selected }}
                                                value="{{ $employee->id }}">{{ ucwords($employee->name) }}
                                        </option>

                                    @endforeach
                                @else
                                    @foreach($task->project->members as $member)
                                        @php
                                            $selected = '';
                                        @endphp

                                        @foreach ($task->users as $item)
                                            @if($item->id == $member->user->id)
                                                @php
                                                    $selected = 'selected';
                                                @endphp
                                            @endif

                                        @endforeach

                                        <option {{ $selected }}
                                            value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.tasks.taskCategory') <a
                                        href="javascript:;" id="createTaskCategory"
                                        class="btn btn-sm btn-outline btn-success"><i
                                            class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')</a>
                            </label>
                            <select class="selectpicker form-control" name="category_id" id="category_id"
                                    data-style="form-control">
                                @forelse($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if($task->task_category_id == $category->id)
                                            selected
                                            @endif
                                    >{{ ucwords($category->category_name) }}</option>
                                @empty
                                    <option value="">@lang('messages.noTaskCategoryAdded')</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.status')</label>
                            <select name="status" id="status" class="form-control">
                                @foreach($taskBoardColumns as $taskBoardColumn)
                                    <option @if($task->board_column_id == $taskBoardColumn->id) selected @endif value="{{$taskBoardColumn->id}}">{{ $taskBoardColumn->column_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--/span-->
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.tasks.priority')</label>

                            <div class="radio radio-danger">
                                <input type="radio" name="priority" id="radio13"
                                       @if($task->priority == 'high') checked @endif
                                       value="high">
                                <label for="radio13" class="text-danger">
                                    @lang('modules.tasks.high') </label>
                            </div>
                            <div class="radio radio-warning">
                                <input type="radio" name="priority"
                                       @if($task->priority == 'medium') checked @endif
                                       id="radio14" value="medium">
                                <label for="radio14" class="text-warning">
                                    @lang('modules.tasks.medium') </label>
                            </div>
                            <div class="radio radio-success">
                                <input type="radio" name="priority" id="radio15"
                                       @if($task->priority == 'low') checked @endif
                                       value="low">
                                <label for="radio15" class="text-success">
                                    @lang('modules.tasks.low') </label>
                            </div>
                        </div>
                    </div>

                </div>
                <!--/row-->

            </div>
            <div class="form-actions">
                <button type="button" id="update-task" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="taskCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" id="modal-data-application">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
            </div>
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <button type="button" class="btn blue">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->.
</div>
{{--Ajax Modal Ends--}}

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script>

    $("#dependent_task_id_project, #user_id2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    //    update task
    $('#update-task').click(function () {

        var status = '{{ $task->board_column->slug }}';
        var currentStatus =  $('#status').val();

        if(status == 'incomplete' && currentStatus == 'completed'){

            $.easyAjax({
                url: '{{route('admin.tasks.checkTask', [$task->id])}}',
                type: "GET",
                data: {},
                success: function (data) {
                    console.log(data.taskCount);
                    if(data.taskCount > 0){
                        swal({
                            title: "@lang('messages.sweetAlertTitle')",
                            text: "@lang('messages.markCompleteTask')",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "@lang('app.yes')",
                            cancelButtonText: "@lang('messages.confirmNoArchive')",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        }, function (isConfirm) {
                            if (isConfirm) {
                                updateTask();
                            }
                        });
                    }
                    else{
                        updateTask();
                    }

                }
            });
        }
        else{
            updateTask();
        }

    });

    function updateTask(){
        $.easyAjax({
            url: '{{route('admin.tasks.update', [$task->id])}}',
            container: '#updateTask',
            type: "POST",
            data: $('#updateTask').serialize(),
            success: function (data) {
                $('#task-list-panel ul.list-group').html(data.html);

                $('#edit-task-panel').switchClass("show", "hide", 300, "easeInOutQuad");
                showTable();
            }
        })
    }

    jQuery('#due_date2, #start_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
       autoclose: true,
        todayHighlight: true
    });

    $('.summernote').summernote({
        height: 100,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ["view", ["fullscreen"]]
        ]
    });

    $('#dependent-task-project').change(function () {
        if($(this).is(':checked')){
            $('#dependent-fields-project').show();
        }
        else{
            $('#dependent-fields-project').hide();
        }
    })

    $('#createTaskCategory').click(function () {
        var url = '{{ route('admin.taskCategory.create-cat')}}';
        $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
        $.ajaxModal('#taskCategoryModal', url);
    });

    $('#set-time-estimate-2').change(function () {
        if($(this).is(':checked')){
            $('#set-time-estimate-fields-2').show();
        }
        else{
            $('#set-time-estimate-fields-2').hide();
        }
    })
</script>
