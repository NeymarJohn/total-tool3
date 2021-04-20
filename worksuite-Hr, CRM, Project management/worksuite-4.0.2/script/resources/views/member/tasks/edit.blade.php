<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

<div class="panel panel-default">
    <div class="panel-heading "><i class="ti-search"></i> @lang('modules.tasks.taskDetail')
        <div class="panel-action">
            <a href="javascript:;" id="hide-edit-task-panel" class="close " data-dismiss="modal"><i class="ti-close"></i></a>
        </div>
    </div>

    @if((!is_null($task->project) && $task->project->isProjectAdmin) || $user->can('edit_projects') || ($task->created_by == $user->id) )
        <div class="panel-wrapper collapse in">
            <div class="panel-body">
                {!! Form::open(['id'=>'updateTask','class'=>'ajax-form','method'=>'PUT']) !!}
                {!! Form::hidden('project_id', $task->project_id) !!}

                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('app.title')</label>
                                <input type="text" id="heading" name="heading" class="form-control" value="{{ $task->heading }}">
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('app.description')</label>
                                <textarea id="description" name="description" class="form-control summernote">{!! $task->description !!}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">

                                <div class="checkbox checkbox-info">
                                    <input id="dependent-task-2" name="dependent" value="yes"
                                            type="checkbox" @if($task->dependent_task_id != '') checked @endif>
                                    <label for="dependent-task-2">@lang('modules.tasks.dependent')</label>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="dependent-fields-2" @if($task->dependent_task_id == null) style="display: none" @endif>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">@lang('modules.tasks.dependentTask')</label>
                                    <select class="select2 form-control" data-placeholder="@lang('modules.tasks.chooseTask')" name="dependent_task_id" id="dependent_task_id" >
                                        <option value=""></option>
                                        @foreach($allTasks as $allTask)
                                            <option value="{{ $allTask->id }}" @if($allTask->id == $task->dependent_task_id) selected @endif>{{ $allTask->heading }} (@lang('app.dueDate'): {{ $allTask->due_date->format($global->date_format) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('app.startDate')</label>
                                <input type="text" name="start_date" autocomplete="off" id="start_date2" class="form-control" value="@if($task->start_date != '-0001-11-30 00:00:00' && $task->start_date != null) {{ $task->start_date->format($global->date_format) }} @endif">
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('app.dueDate')</label>
                                <input type="text" name="due_date" autocomplete="off" id="due_date2" class="form-control" value="@if($task->due_date != '-0001-11-30 00:00:00') {{ $task->due_date->format($global->date_format) }} @endif">
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('modules.tasks.assignTo')</label>
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
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('modules.tasks.taskCategory') </label>
                                <select class=" form-control" name="category_id" id="category_id"
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

                        <div class="col-md-3">
                            <div class="form-group">

                                <div class="checkbox checkbox-info">
                                    <input id="billable-task2" name="billable" value="true"
                                    @if ($task->billable)
                                        checked
                                    @endif
                                           type="checkbox">
                                    <label for="billable-task2">@lang('modules.tasks.billable') <a class="mytooltip font-12" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">@lang('modules.tasks.billableInfo')</span></span></span></a></label>
                                </div>
                            </div>
                        </div>

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
                    </div>
                    <!--/row-->

                </div>
                <div class="form-actions">
                    <button type="button" id="update-task" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    @else
        <div class="panel-wrapper collapse in">
            <div class="panel-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('app.title')</label>
                                <p>  {{ ucfirst($task->heading) }} </p>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('app.description')</label>
                                <p>  {!! ucfirst($task->description) !!} </p>
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
                                    <input id="dependent-task-2" name="dependent" value="yes"
                                            type="checkbox" @if($task->dependent_task_id != '') checked @endif>
                                    <label for="dependent-task-2">@lang('modules.tasks.dependent')</label>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="dependent-fields-2" @if($task->dependent_task_id == null) style="display: none" @endif>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">@lang('modules.tasks.dependentTask')</label>
                                    <select class="select2 form-control" data-placeholder="@lang('modules.tasks.chooseTask')" name="dependent_task_id" id="dependent_task_id" >
                                        <option value=""></option>
                                        @foreach($allTasks as $allTask)
                                            <option value="{{ $allTask->id }}" @if($allTask->id == $task->dependent_task_id) selected @endif>{{ $allTask->heading }} (@lang('app.dueDate'): {{ $allTask->due_date->format($global->date_format) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('app.dueDate')</label>
                                <p>  {{  $task->due_date->format('d-M-Y')  }} </p>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('modules.tasks.assignTo')</label>
                                <p>
                                    @foreach ($task->users as $item)
                                        <img src="{{ $item->image_url }}" data-toggle="tooltip"
                                            data-original-title="{{ ucwords($item->name) }}" data-placement="right"
                                            class="img-circle" width="25" height="25" alt="">
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('modules.tasks.priority')</label>
                                    <div  class="clearfix"></div>
                                    <label for="radio13" class="text-@if($task->priority == 'high')danger @elseif($task->priority == 'medium')warning @else success @endif ">
                                        @if($task->priority == 'high') @lang('modules.tasks.high') @elseif($task->priority == 'medium') @lang('modules.tasks.medium') @else @lang('modules.tasks.low') @endif</label>

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
                                <label>@lang('app.status')</label>
                                <div  class="clearfix"></div>
                                    <label for="radio13"  style="color: {{ $task->board_column->label_color }};"> {{ $task->board_column->column_name }}</label>

                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->

                </div>
                <div class="form-actions">
                </div>
            </div>
        </div>
    @endif
</div>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>

<script>
    $('#hide-edit-task-panel').click(function () {
        newTaskpanel.addClass('hide').removeClass('show');
        taskListPanel.switchClass("col-md-6", "col-md-12", 1000, "easeInOutQuad");
    });

</script>
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
                url: '{{route('member.tasks.checkTask', [$task->id])}}',
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
                            confirmButtonText: "@lang('messages.completeIt')",
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
            url: '{{route('member.tasks.update', [$task->id])}}',
            container: '#updateTask',
            type: "POST",
            data: $('#updateTask').serialize(),
            success: function (data) {
                showTable();
                $('body').find('#edit-task-panel').switchClass("show", "hide", 300, "easeInOutQuad");
            }
        })
    }
    $('#due_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true,
        startDate: '{{ $task->due_date->format($global->date_forma) }}'
    });

    jQuery('#start_date2').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function (selected) {
        $('#due_date2').datepicker({
            format: '{{ $global->date_picker_format }}',
            autoclose: true,
            todayHighlight: true
        });
        var minDate = new Date(selected.date.valueOf());
        $('#due_date2').datepicker("update", minDate);
        $('#due_date2').datepicker('setStartDate', minDate);
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
    $('#dependent-task-2').change(function () {
        if($(this).is(':checked')){
            $('#dependent-fields-2').show();
        }
        else{
            $('#dependent-fields-2').hide();
        }
    })
    
    $('#set-time-estimate-2').change(function () {
        if($(this).is(':checked')){
            $('#set-time-estimate-fields-2').show();
        }
        else{
            $('#set-time-estimate-fields-2').hide();
        }
    })


</script>
