<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

<div class="panel panel-default">
    <div class="panel-heading "><i class="ti-pencil"></i> @lang('modules.templateTasks.updateTask')
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
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.heading')</label>
                            <input type="text" id="heading" name="title" class="form-control" value="{{ $task->heading }}">
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.tasks.taskCategory') 
                            </label>
                            <select class="form-control" name="category_id" id="category_id"
                                    data-style="form-control">
                                @forelse($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if($task->project_template_task_category_id == $category->id)
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
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.description')</label>
                            <textarea id="description" name="description" class="form-control summernote">{{ $task->description }}</textarea>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.templateTasks.assignTo')</label>
                            <select class="select2 select2-multiple " multiple="multiple"
                                data-placeholder="@lang('modules.tasks.chooseAssignee')"
                                name="user_id[]" id="user_id2">
                                @if(is_null($task->project_id))
                                    @foreach($task->projectTemplate->members as $member)

                                        @php
                                            $selected = '';
                                        @endphp

                                        @foreach ($task->users as $item)
                                            @if($item->user_id == $member->user->id)
                                                @php
                                                    $selected = 'selected';
                                                @endphp
                                            @endif

                                        @endforeach

                                        <option {{ $selected }}
                                                value="{{ $member->user->id }}">{{ ucwords($member->user->name) }}
                                        </option>

                                    @endforeach
                                @else
                                    @foreach($task->projectTemplate->members as $member)
                                        @php
                                            $selected = '';
                                        @endphp

                                        @foreach ($task->users as $item)
                                            @if($item->user_id == $member->user->id)
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
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.templateTasks.priority')</label>

                            <div class="radio radio-danger">
                                <input type="radio" name="priority" id="radio13"
                                       @if($task->priority == 'high') checked @endif
                                       value="high">
                                <label for="radio13" class="text-danger">
                                    @lang('modules.templateTasks.high') </label>
                            </div>
                            <div class="radio radio-warning">
                                <input type="radio" name="priority"
                                       @if($task->priority == 'medium') checked @endif
                                       id="radio14" value="medium">
                                <label for="radio14" class="text-warning">
                                    @lang('modules.templateTasks.medium') </label>
                            </div>
                            <div class="radio radio-success">
                                <input type="radio" name="priority" id="radio15"
                                       @if($task->priority == 'low') checked @endif
                                       value="low">
                                <label for="radio15" class="text-success">
                                    @lang('modules.templateTasks.low') </label>
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

<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script>
    $("#user_id2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    //    update task

    //    update task
    $('#update-task').click(function () {
        updateTask();
    });

    function updateTask(){
        $.easyAjax({
            url: '{{route('member.project-template-task.update', [$task->id])}}',
            container: '#updateTask',
            type: "POST",
            data: $('#updateTask').serialize(),
            success: function (data) {
                $('#task-list-panel ul.list-group').html(data.html);
            }
        })
    }

    $('.summernote').summernote({
        height: 200,                 // set editor height
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
</script>
