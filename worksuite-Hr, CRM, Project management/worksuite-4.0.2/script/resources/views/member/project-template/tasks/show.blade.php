@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.project-template.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">Tasks</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/icheck/skins/all.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">

                        <nav>
                            <ul>
                                <li ><a href="{{ route('member.project-template.show', $project->id) }}"><span>@lang('modules.projects.overview')</span></a>
                                </li>
                                <li><a href="{{ route('member.project-template-member.show', $project->id) }}"><span>@lang('modules.projects.members')</span></a></li>
                                <li class="tab-current"><a href="{{ route('member.project-template-task.show', $project->id) }}"><span>@lang('app.menu.tasks')</span></a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-md-12" id="task-list-panel">
                                    <div class="white-box">
                                        <h2>@lang('app.menu.tasks')</h2>

                                        <div class="row m-b-10">
                                            <div class="col-md-5">
                                                <a href="javascript:;" id="show-new-task-panel"
                                                   class="btn btn-success btn-outline"><i class="fa fa-plus"></i> @lang('modules.templateTasks.newTask')</a>
                                            </div>
                                        </div>

                                        <ul class="list-group">
                                            @foreach($project->tasks as $task)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="checkbox checkbox-success checkbox-circle task-checkbox col-md-10">
                                                            <a href="javascript:;" class="text-muted edit-task"
                                                               data-task-id="{{ $task->id }}">{{ ucfirst($task->heading) }}</a>
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            {{-- {!! '<img data-toggle="tooltip" data-original-title="' . ucwords($task->user->name) . '" src="' . $task->user->image_url . '"
                        alt="user" class="img-circle" height="35"> ' !!} --}}
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>

                                <div class="col-md-6 hide" id="new-task-panel">
                                    <div class="panel panel-default">
                                        <div class="panel-heading "><i class="ti-plus"></i> New Task
                                            <div class="panel-action">
                                                <a href="javascript:;" id="hide-new-task-panel"><i class="ti-close"></i></a>
                                            </div>
                                        </div>
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body">
                                                {!! Form::open(['id'=>'createTask','class'=>'ajax-form','method'=>'POST']) !!}

                                                {!! Form::hidden('project_id', $project->id) !!}

                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang('app.title')</label>
                                                                <input type="text" id="heading" name="heading"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang('modules.tasks.taskCategory') 
                                                                </label>
                                                                <select class="selectpicker form-control" name="category_id" id="category_id"
                                                                        data-style="form-control">
                                                                    @forelse($categories as $category)
                                                                        <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
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
                                                                <textarea id="description" name="description"
                                                                          class="form-control summernote"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="control-label">@lang('modules.templateTasks.assignTo')</label>
                                                            <div class="form-group">
                                                                <select class="select2 select2-multiple " multiple="multiple"
                                                                        data-placeholder="@lang('modules.tasks.chooseAssignee')"
                                                                        name="user_id[]" id="user_id1">
                                                                    <option value=""></option>
                                                                    @foreach($project->members as $member)
                                                                        <option value="{{ $member->user->id }}">{{ ucwords($member->user->name) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang('modules.templateTasks.priority')</label>

                                                                <div class="radio radio-danger">
                                                                    <input type="radio" name="priority" id="radio13"
                                                                           value="high">
                                                                    <label for="radio13" class="text-danger">
                                                                        @lang('modules.templateTasks.high') </label>
                                                                </div>
                                                                <div class="radio radio-warning">
                                                                    <input type="radio" name="priority" checked
                                                                           id="radio14" value="medium">
                                                                    <label for="radio14" class="text-warning">
                                                                        @lang('modules.templateTasks.medium') </label>
                                                                </div>
                                                                <div class="radio radio-success">
                                                                    <input type="radio" name="priority" id="radio15"
                                                                           value="low">
                                                                    <label for="radio15" class="text-success">
                                                                        @lang('modules.templateTasks.low') </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->

                                                </div>
                                                <div class="form-actions">
                                                    <button type="submit" id="save-task" class="btn btn-success"><i
                                                                class="fa fa-check"></i> @lang('app.save')
                                                    </button>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 hide" id="edit-task-panel">
                                </div>
                            </div>
                        </section>

                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script type="text/javascript">
    var newTaskpanel = $('#new-task-panel');
    var taskListPanel = $('#task-list-panel');
    var editTaskPanel = $('#edit-task-panel');

    $("#user_id1").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    //    save new task
    $('#save-task').click(function () {
        $.easyAjax({
            url: '{{route('member.project-template-task.store')}}',
            container: '#createTask',
            type: "POST",
            data: $('#createTask').serialize(),
            formReset: true,
            success: function (data) {
                $('#task-list-panel ul.list-group').html(data.html);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    });

    //    save new task
    taskListPanel.on('click', '.edit-task', function () {
        var id = $(this).data('task-id');
        var url = "{{route('member.project-template-task.edit', ':id')}}";
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: "GET",
            data: {taskId: id},
            success: function (data) {
                editTaskPanel.html(data.html);
                taskListPanel.switchClass("col-md-12", "col-md-6", 1000, "easeInOutQuad");
                newTaskpanel.addClass('hide').removeClass('show');
                editTaskPanel.switchClass("hide", "show", 300, "easeInOutQuad");
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    });

    $('#show-new-task-panel').click(function () {
//    taskListPanel.switchClass('col-md-12', 'col-md-8', 1000, 'easeInOutQuad');
        taskListPanel.switchClass("col-md-12", "col-md-6", 1000, "easeInOutQuad");
        editTaskPanel.addClass('hide').removeClass('show');
        newTaskpanel.switchClass("hide", "show", 300, "easeInOutQuad");
    });

    $('#hide-new-task-panel').click(function () {
        newTaskpanel.addClass('hide').removeClass('show');
        taskListPanel.switchClass("col-md-6", "col-md-12", 1000, "easeInOutQuad");
    });

    editTaskPanel.on('click', '#hide-edit-task-panel', function () {
        editTaskPanel.addClass('hide').removeClass('show');
        taskListPanel.switchClass("col-md-6", "col-md-12", 1000, "easeInOutQuad");
    });

    jQuery('#due_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

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
@endpush
