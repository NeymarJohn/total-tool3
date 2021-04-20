@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <span id="filter-result" class="p-t-15 m-r-5"></span> &nbsp;
            <a href="javascript:;" id="toggle-filter" class="btn btn-sm btn-inverse btn-outline toggle-filter"><i class="fa fa-sliders"></i> @lang('app.filterResults')</a>
            <a href="javascript:;" id="createTaskCategory" class="btn btn-sm btn-outline btn-info"><i class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')</a>
            <a href="javascript:;" id="add-task" class="btn btn-sm btn-outline btn-success"><i class="fa fa-plus"></i> @lang('app.task')</a>
            <a href="javascript:;" id="add-column" class="btn btn-success btn-outline btn-sm"><i class="fa fa-plus"></i> @lang('modules.tasks.addBoardColumn')</a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/lobipanel/dist/css/lobipanel.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">

@endpush

@section('content')

    <div class="row">
        <div class="white-box">
            
            <div class="row" style="display: none; " id="ticket-filters">
                <div class="col-xs-12">
                    <h4>@lang('app.filterBy') </h4>
                </div>
                <form action="" id="filter-form">
                    <div class="col-md-3">
                        <h5>@lang('app.selectDateRange')</h5>
                        <div class="input-daterange input-group m-t-5" id="date-range">
                            <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                                   value="{{ $startDate }}"/>
                            <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                            <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                                   value="{{ $endDate }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5>@lang('app.selectProject')</h5>
                        <div class="form-group">
                            <select class="select2 form-control" data-placeholder="@lang('app.selectProject')" id="project_id">
                                <option value="all">@lang('app.all')</option>
                                @foreach($projects as $project)
                                    <option
                                            value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5>@lang('app.select') @lang('app.client')</h5>

                        <div class="form-group">
                            <select class="select2 form-control" data-placeholder="@lang('app.client')" id="clientID">
                                <option value="all">@lang('app.all')</option>
                                @foreach($clients as $client)
                                    <option
                                            value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5>@lang('app.select') @lang('modules.tasks.assignTo')</h5>

                        <div class="form-group">
                            <select class="select2 form-control" data-placeholder="@lang('modules.tasks.assignTo')" id="assignedTo">
                                <option value="all">@lang('app.all')</option>
                                @foreach($employees as $employee)
                                    <option
                                            value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5>@lang('app.select') @lang('modules.tasks.assignBy')</h5>

                        <div class="form-group">
                            <select class="select2 form-control" data-placeholder="@lang('modules.tasks.assignBy')" id="assignedBY">
                                <option value="all">@lang('app.all')</option>
                                @foreach($employees as $employee)
                                    <option
                                            value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5 >@lang('modules.taskCategory.taskCategory')</h5>
                
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <select class="select2 form-control" data-placeholder="@lang('modules.taskCategory.taskCategory')" id="category_id">
                                        <option value="all">@lang('app.all')</option>
                                        @foreach($taskCategories as $categ)
                                            <option value="{{ $categ->id }}">{{ ucwords($categ->category_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5 >@lang('app.select') @lang('app.label')</h5>
                
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                <select class="select2 form-control" data-placeholder="@lang('app.label')" id="label">
                                        <option value="all">@lang('app.all')</option>
                                        @foreach($taskLabels as $label)
                                        <option data-content="<span class='badge b-all' style='background:{{ $label->label_color }};'>{{ $label->label_name }}</span> " value="{{ $label->id }}">{{ $label->label_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group m-t-10">
                            <label class="control-label col-xs-12">&nbsp;</label>
                            <button type="button" id="apply-filters" class="btn btn-success btn-sm"><i class="fa fa-check"></i> @lang('app.apply')</button>
                            <button type="button" id="reset-filters" class="btn btn-inverse btn-sm"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                            <button type="button" class="btn btn-default btn-sm toggle-filter"><i class="fa fa-close"></i> @lang('app.close')</button>
                        </div>
                    </div>
                </form>
            </div>

            {!! Form::open(['id'=>'addColumn','class'=>'ajax-form','method'=>'POST']) !!}


            <div class="row" id="add-column-form" style="display: none;">
                <div class="col-xs-12">
                    <hr>
                    <div class="form-group">
                        <label class="control-label">@lang("modules.tasks.columnName")</label>
                        <input type="text" name="column_name" class="form-control">
                    </div>
                </div>
                <!--/span-->

                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang("modules.tasks.labelColor")</label><br>
                        <input type="text" class="colorpicker form-control"  name="label_color" value="#ff0000" />
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <button class="btn btn-success" id="save-form" type="submit"><i class="fa fa-check"></i> @lang('app.save')</button>
                    </div>
                </div>
                <!--/span-->

            </div>
            {!! Form::close() !!}

        </div>

    </div>

    <div class="container-scroll white-box">
        <div class ="row">
            <button id="toggle_fullscreen" class="btn btn-default btn-outline btn-sm pull-right"><i class="icon-size-fullscreen"></i></button>
            <button class="btn btn-default btn-outline btn-sm pull-right" id="my-tasks"><i class="fa fa-user"></i> My Tasks</button>
            <button class="btn btn-default btn-outline btn-sm pull-right" id="show-all-tasks" style="display: none"><i class="fa fa-users"></i> Show All</button>
        </div>
        <div class="row container-row">
        </div>
        <!-- .row -->
    </div>

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
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/lobipanel/dist/js/lobipanel.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
<!--slimscroll JavaScript -->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>

<script>

    $('#add-column').click(function () {
        $('#add-column-form').toggle();
    })

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    loadData();
    jQuery('#date-range').datepicker({
        toggleActive: true,
        language: '{{ $global->locale }}',
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });
    // Colorpicker

    $(".colorpicker").asColorPicker();


    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.taskboard.store')}}',
            container: '#addColumn',
            data: $('#addColumn').serialize(),
            type: "POST"
        })
    });
    $('#add-task').click(function () {
        var url = '{{ route('admin.projects.ajaxCreate')}}';
        $('#modelHeading').html('Add Task');
        $.ajaxModal('#eventDetailModal', url);
    });

    $('#createTaskCategory').click(function(){
        var url = '{{ route('admin.taskCategory.create-cat')}}';
        $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
        $.ajaxModal('#taskCategoryModal',url);
    });

    {{--$('#edit-column-form').on('click', '#update-form', function () {--}}
        {{--var id = $(this).data('column-id');--}}
        {{--var url = '{{route('admin.taskboard.update', ':id')}}';--}}
        {{--url = url.replace(':id', id);--}}

        {{--$.easyAjax({--}}
            {{--url: url,--}}
            {{--container: '#updateColumn',--}}
            {{--data: $('#updateColumn').serialize(),--}}
            {{--type: "PUT"--}}
        {{--})--}}
    {{--});--}}



    $('#apply-filters').click(function () {
        loadData();
    });
    $('#reset-filters').click(function () {
        $('#start-date').val('{{ $startDate }}');
        $('#end-date').val('{{ $endDate }}');
        $('.select2').val('all');
        $('.select2').trigger('change');

        loadData();
    })
    $('#my-tasks').click(function () {
        $('#assignedTo').val('{{$user->id}}');
        toggleFilter();
    });

    $('#show-all-tasks').click(function () {
        $('#assignedTo').val('all');
        toggleFilter();
    });

    $('.toggle-filter').click(function () {
        $('#ticket-filters').slideToggle();
    })
    function toggleFilter(){
        $('#assignedTo').select2().trigger('change');
        $('#show-all-tasks').toggle();
        $('#my-tasks').toggle();
        loadData()
    }
    function loadData () {
        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }
        var projectID = $('#project_id').val();
        var clientID = $('#clientID').val();
        var assignedBY = $('#assignedBY').val();
        var assignedTo = $('#assignedTo').val();
        var categoryId = $('#category_id').val();
        var labelId = $('#label').val();

        var url = '{{route('admin.taskboard.index')}}?startDate=' + startDate + '&endDate=' + endDate +'&clientID='+clientID +'&assignedBY='+ assignedBY+'&assignedTo='+ assignedTo+'&projectID='+ projectID+'&category_id='+ categoryId+'&label_id='+ labelId;

        $.easyAjax({
            url: url,
            container: '.container-row',
            type: "GET",
            success: function (response) {
                $('.container-row').html(response.view);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }

        })
    }

    $('body').on('click', '.edit-type', function () {
        var id = $(this).data('column-id');
        var url = '{{ route("admin.taskboard.edit", ':id') }}';
        url = url.replace(':id', id);

        $('#modelHeading').html("{{  __('app.edit')." ".__('app.task')." ".__('app.status') }}");
        $.ajaxModal('#eventDetailModal', url);
    })

    //    update task
    function storeTask() {
        $('#addedFiles').val(myDropzone.getQueuedFiles().length);
        $.easyAjax({
            url: '{{route('admin.all-tasks.store')}}',
            container: '#storeTask',
            type: "POST",
            data: $('#storeTask').serialize(),
            success: function (response) {

                var dropzone = 0;
                @if($upload)
                    dropzone = myDropzone.getQueuedFiles().length;
                @endif

                if(dropzone > 0){
                    $('#taskID').val(response.taskID);
                    myDropzone.processQueue();
                }
                else{
                    var msgs = "@lang('messages.taskCreatedSuccessfully')";
                    $.showToastr(msgs, 'success');
                    window.location.href = ''
                }
                $('#eventDetailModal').modal('hide');
                loadData ()
            }
        })
    };
    $('#toggle_fullscreen').on('click', function(){
        // if already full screen; exit
        // else go fullscreen
        if (
            document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement
        ) {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        } else {
            element = $('.container-scroll').get(0);
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        }
    });
</script>

@endpush