<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">

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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.title')</label>
                            <input type="text" id="heading" name="heading" class="form-control" value="{{ $task->heading }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.tasks.taskCategory') <a
                                        href="javascript:;" id="createTaskCategory"
                                        class="btn btn-sm btn-outline btn-success"><i
                                            class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')</a>
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.description')</label>
                            <textarea id="description" name="description" class="form-control summernote">{!! $task->description !!}</textarea>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.templateTasks.assignTo')</label>
                            {{-- <select class="form-control" name="user_id" id="user_id" >
                                @foreach($task->projectTemplate->members as $member)
                                    <option @if($task->user_id == $member->user->id) selected @endif
                                    value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                @endforeach
                            </select> --}}
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
                    <div class="col-md-12">
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
                <button type="button" id="update-task" onclick="updateTask(); return false;" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
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

<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>
    //    update task
    $("#user_id2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    function updateTask(){
        $.easyAjax({
            url: '{{route('admin.project-template-task.update', [$task->id])}}',
            container: '#updateTask',
            type: "POST",
            data: $('#updateTask').serialize(),
            success: function (data) {
                $('#edit-task-panel').switchClass("show", "hide", 300, "easeInOutQuad");
                showTable();
            }
        })
    }

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
    $('#createTaskCategory').click(function () {
        var url = '{{ route('admin.taskCategory.create-cat')}}';
        $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
        $.ajaxModal('#taskCategoryModal', url);
    });
</script>
