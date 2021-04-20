@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang('app.project') #{{ $project->id }} - {{ ucwords($project->project_name) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="javascript:;" onclick="addTask('{{$project->id }}')" class="btn btn-sm btn-outline btn-success"><i class="fa fa-plus"></i> @lang('app.task')</a>
            <a href="javascript:;" id="createTaskCategory" class="btn btn-sm btn-outline btn-info"><i class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')</a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/frappe/frappe-gantt.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">

    <style>

        .gantt_task_drag {
            width: 6px;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAACCAYAAAB7Xa1eAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QYDDjkw3UJvAwAAABRJREFUCNdj/P//PwM2wASl/6PTAKrrBf4+lD8LAAAAAElFTkSuQmCC);
            z-index: 1;
            top: 0;
        }

        .gantt_task_drag.task_left{
            left: 0;
        }

        .gantt_task_drag.task_right{
            right: 0;
        }

    </style>
@endpush

@section('content')

    <div class="row">

        <div class="col-xs-12">
            <section>
                <div class="sttabs tabs-style-line">

                    @include('admin.projects.show_project_menu')

                    <div class="white-box">
                        <div class="row m-t-20">
                            <div class="col-md-3">
                                <h5>@lang('app.select') @lang('modules.tasks.assignTo')</h5>
        
                                <div class="form-group">
                                    <select class="select2 form-control" data-placeholder="@lang('modules.tasks.assignTo')" id="assignedTo">
                                        <option value="all">@lang('app.all')</option>
                                        @foreach($project->members as $employee)
                                            <option
                                                    value="{{ $employee->user->id }}">{{ ucwords($employee->user->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                            <!-- ASSIGN START -->
                            <div class="select-box py-2 px-lg-3 px-md-3 px-0">
                                <h5>@lang('app.view')</h5>
                                <div class="form-group">
                                    <select class="form-control select2" id="gantt-view" data-size="8">
                                        <option value="Day">@lang('app.day')</option>
                                        <option value="Week">@lang('app.week')</option>
                                        <option value="Month">@lang('app.month')</option>
                                    </select>
                                </div>
                            </div>
                            <!-- ASSIGN END -->
                            </div>
                        </div>
                        <div id="gantt"></div>

                    </div>
                    <!-- /content -->
                </div>
                <!-- /tabs -->
            </section>
        </div>
    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
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
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in"  id="subTaskModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="subTaskModelHeading">Sub Task e</span>
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
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/frappe/frappe-gantt.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#createTaskCategory').click(function(){
            var url = '{{ route('admin.taskCategory.create-cat')}}';
            $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
            $.ajaxModal('#taskCategoryModal',url);
        });

        

        function addTask(id, parentId) {
            var url = '{{ route('admin.projects.ajaxCreate', ':id')}}';
            url = url.replace(':id', id) + '?parent_gantt_id='+parentId;

            $('#modelHeading').html('Add Task');
            $.ajaxModal('#eventDetailModal', url);
        }

        //    update task
        function storeTask() {
            $.easyAjax({
                url: '{{route('admin.all-tasks.store')}}',
                container: '#storeTask',
                type: "POST",
                data: $('#storeTask').serialize(),
                success: function (response) {
                    if (response.status == "success") {
                        $('#eventDetailModal').modal('hide');
                        window.location.reload();
                    }
                }
            })
        };

        

    </script>
    <script>
        $(document).ready(function() {
            function loadData() {
                var projectID = "{{ $project->id }}";
                var assignedTo = $('#assignedTo').val();
                var viewMode = $('#gantt-view').val();
                var token = "{{ csrf_token() }}";
    
                var url = "{{ route('admin.projects.ganttData', $project->id) }}?assignedTo=" +
                    assignedTo;
    
                $.easyAjax({
                    url: url,
                    blockUI: true,
                    container: '.content-wrapper',
                    success: function(response) {
                        if (!response.length) {
                            $("#gantt").html(
                                "<div class='d-flex justify-content-center p-20'>{{ __('messages.noRecordFound') }}</div>"
                            );
                            return;
                        }
    
                        $("#gantt").html("");
    
                        var gantt = new Gantt("#gantt", response, {
                            popup_trigger: "mouseover",
                            view_mode: viewMode,
                            on_click: function(task) {
                                // console.log(task);
                                taskDetail(task.taskid);
                            },
                            on_date_change: function(task, start, end) {
                                // console.log(task, start, end);
                                var taskId = task.taskid;
                                var token = '{{ csrf_token() }}';
                                var url =
                                    "{{ route('admin.projects.gantt-task-update', ':id') }}";
                                url = url.replace(':id', taskId);
                                var startDate = moment.utc(start.toDateString())
                                    .format('DD/MM/Y');
                                var endDate = moment.utc(end.toDateString())
                                    .subtract(1, "days").format('DD/MM/Y');
    
                                $.easyAjax({
                                    url: url,
                                    type: "POST",
                                    container: '#gantt',
                                    data: {
                                        '_token': token,
                                        'start_date': startDate,
                                        'end_date': endDate
                                    }
                                });
                            },
                            on_progress_change: function(task, progress) {
                                // console.log(task, progress);
                            },
                            on_view_change: function(mode) {
                                // console.log(mode);
                            }
                        });
    
                    }
                });
            }
    
            $('#assignedTo, #gantt-view').on('change keyup', function() {
                loadData();
            });
    
            // Task Detail show in sidebar
            var taskDetail = function(id) {

                $(".right-sidebar").slideDown(50).addClass("shw-rside");

                var url = "{{ route('admin.all-tasks.show',':id') }}";
                url = url.replace(':id', id);

                $.easyAjax({
                    type: 'GET',
                    url: url,
                    success: function (response) {
                        if (response.status == "success") {
                            $('#right-sidebar-content').html(response.view);
                        }
                    }
                });
            }
    
            loadData();
        });
    
    </script>
@endpush

