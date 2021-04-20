@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dhtmlxgantt_material.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush

@section('content')

    <div class="row m-b-10">
        <div class="col-md-12">
            <section>
                <div class="sttabs tabs-style-line">
                    @include('member.projects.show_project_menu')

                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="white-box">
                                <div id="gantt_here" style='width:100%; height: calc(100vh - 206px);'></div>

                            </div>

                            
                        </section>
                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>
    </div>    <!-- .row -->


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
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
    <script src="//cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">

        gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";

        gantt.templates.task_class = function (st, end, item) {
            return item.$level == 0 ? "gantt_project" : ""
        };

        gantt.config.scale_unit = "month";
        gantt.config.date_scale = "%F, %Y";

        gantt.config.scale_height = 50;

        gantt.config.subscales = [
            {unit: "day", step: 1, date: "%j, %D"}
        ];

        gantt.config.server_utc = false;

        gantt.i18n.setLocale('{{ $global->locale }}');

        // default columns definition
        gantt.config.columns=[
            {name:"text",       label:"@lang('modules.gantt.task_name')",  tree:true, width:'*' },
            {name:"start_date", label:"@lang('modules.gantt.start_time')", align: "center" },
            {name:"duration",   label:"@lang('modules.gantt.duration')",   align: "center" },
            {name:"priority",        label:"@lang('modules.gantt.action')",   width: 90, align: "center", template: function (item) {
                if(item.$level == 0){
                    @if($user->can('add_tasks') || $global->task_self == 'yes')
                    return '<a href="javascript:addTask('+item.project_id+', '+item.id+');"><i class="fa fa-plus"></i></a>';
                    @endif
                } else {
                    return '';
                }

            }}
        ];

        //defines the text inside the tak bars
        gantt.templates.task_text = function (start, end, task) {
            // if ( task.$level > 0 ){
            //     return task.text + ", <b> @lang('modules.tasks.assignTo'):</b> " + task.users;
            // }
            return task.text;

        };

        gantt.attachEvent("onTaskCreated", function(task){
            //any custom logic here
            return false;
        });

        gantt.attachEvent("onBeforeTaskDrag", function(id, mode, e){
            var task = gantt.getTask(id);

            if(task.$level == 0)
            {
                return false;
            } else {
                return true;
            }
        });

        gantt.attachEvent("onAfterTaskDrag", function(id, mode, e){
            var task = gantt.getTask(id);
            var taskId = task.taskid;
            var token = '{{ csrf_token() }}';
            var url = '{{route('member.projects.gantt-task-update', ':id')}}';
            url = url.replace(':id', taskId);
            var startDate = moment.utc(task.start_date.toDateString()).format('DD/MM/Y');
            var endDate = moment.utc(task.end_date.toDateString()).subtract(1, "days").format('DD/MM/Y');

            $.easyAjax({
                url: url,
                type: "POST",
                container: '#gantt_here',
                data: { '_token': token, 'start_date': startDate, 'end_date': endDate }
            })
        });

        gantt.attachEvent("onBeforeLightbox", function(id) {
            var task = gantt.getTask(id);

            if ( task.$level > 0 ){
                $(".right-sidebar").slideDown(50).addClass("shw-rside");

                var taskId = task.taskid;
                var url = "{{ route('member.all-tasks.show',':id') }}";
                url = url.replace(':id', taskId);

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
            return false;
        });

        gantt.init("gantt_here");

        @if($ganttProjectId == '')
            gantt.load('{{ route("member.projects.ganttData") }}');
        @else
            gantt.config.open_tree_initially = true;
            gantt.load('{{ route("member.projects.ganttData", $ganttProjectId) }}');
        @endif


        function addTask(id, parentId) {
            var url = '{{ route('member.projects.ajaxCreate', ':id')}}';
            url = url.replace(':id', id) + '?parent_gantt_id='+parentId;

            $('#modelHeading').html('...');
            $.ajaxModal('#eventDetailModal', url);
        }

        //    update task
        function storeTask() {
            $.easyAjax({
                url: '{{route('member.all-tasks.store')}}',
                container: '#storeTask',
                type: "POST",
                data: $('#storeTask').serialize(),
                success: function (response) {
                    if (response.status == "success") {
                        $('#eventDetailModal').modal('hide');
                        var responseTasks = response.tasks;
                        var responseLinks = response.links;

                        responseTasks.forEach(function(responseTask) {
                            gantt.addTask(responseTask);
                        });

                        responseLinks.forEach(function(responseLink) {
                            gantt.addLink(responseLink);
                        });
                    }
                }
            })
        };
        
        function showTable() {
            var url = '{{ route("member.projects.ganttData") }}';

            @if($ganttProjectId != '')
                var url = '{{ route("member.projects.ganttData", $ganttProjectId) }}';
            @endif

            gantt.clearAll();
            gantt.load(url);
            $(".right-sidebar").slideDown(50).removeClass("shw-rside");
        }

        function limitMoveRight(task, limit) {
            var dur = task.end_date - task.start_date;
            task.start_date = new Date(limit.end_date);
            task.end_date = new Date(+task.start_date + dur);
        }

        function limitResizeRight(task, limit) {
            task.start_date = new Date(limit.end_date)
        }


        gantt.attachEvent("onTaskDrag", function (id, mode, task, original, e) {

            if(task.dependent_task_id !== null)
            {
                var parent = gantt.getTask(task.dependent_task_id),
                    modes = gantt.config.drag_mode;

                var limitLeft = null,
                    limitRight = null;

                if (!(mode == modes.move || mode == modes.resize)) return;

                if (mode == modes.move) {
                    limitRight = limitMoveRight;
                } else if (mode == modes.resize) {
                    limitRight = limitResizeRight;
                }

                if (parent && +parent.end_date > +task.start_date) {
                    limitRight(task, parent);
                }
            }
        });

        $('ul.showProjectTabs .gantt').addClass('tab-current');
    </script>
@endpush

