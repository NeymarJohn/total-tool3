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
            <a href="javascript:;" id="createTaskCategory" class="btn btn-sm btn-outline btn-info"><i class="fa fa-plus"></i> @lang('modules.taskCategory.addTaskCategory')</a>
            <a href="javascript:;"  class="btn btn-outline btn-info btn-sm pinnedItem">@lang('app.pinnedTask') <i class="icon-pin icon-2"></i></a>
            <a href="{{ route('admin.all-tasks.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.tasks.newTask') <i class="fa fa-plus" aria-hidden="true"></i></a>
            <a href="{{ route('admin.task-label.index') }}" class="btn btn-outline btn-primary btn-sm"> @lang('app.menu.taskLabel') </a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<style>
    .swal-footer {
        text-align: center !important;
    }
</style>
@endpush


@section('filter-section')
    <div class="row m-b-10">
        {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="col-xs-12">
            <div class="example">
                <h5 class="box-title">@lang('app.selectDateRange')</h5>

                <div class="input-daterange input-group" id="date-range">
                    <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                            value=""/>
                    <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                    <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                            value=""/>
                </div>
            </div>
        </div>

            @if(in_array('projects', $modules))
                <div class="col-xs-12">
                    <h5 class="box-title">@lang('app.selectProject')</h5>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12">
                                <select class="select2 form-control" data-placeholder="@lang('app.selectProject')" id="project_id">
                                    <option value="all">@lang('app.all')</option>
                                    @foreach($projects as $project)
                                        <option
                                                value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-xs-12">
                <h5 class="box-title">@lang('app.select') @lang('app.client')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <select class="select2 form-control" data-placeholder="@lang('app.client')" id="clientID">
                                <option value="all">@lang('app.all')</option>
                                @foreach($clients as $client)
                                    <option
                                            value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <h5 class="box-title">@lang('app.select') @lang('modules.tasks.assignTo')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <select class="select2 form-control" data-placeholder="@lang('modules.tasks.assignTo')" id="assignedTo">
                                <option value="all">@lang('app.all')</option>
                                @foreach($employees as $employee)
                                    <option
                                            value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <h5 class="box-title">@lang('app.select') @lang('modules.tasks.assignBy')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <select class="select2 form-control" data-placeholder="@lang('modules.tasks.assignBy')" id="assignedBY">
                                <option value="all">@lang('app.all')</option>
                                @foreach($employees as $employee)
                                    <option
                                            value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <h5 class="box-title">@lang('app.select') @lang('app.status')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <select class="select2 form-control" data-placeholder="@lang('status')" id="status">
                                <option value="all">@lang('app.all')</option>
                                @foreach($taskBoardStatus as $status)
                                    <option value="{{ $status->id }}">{{ ucwords($status->column_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <h5 class="box-title">@lang('app.select') @lang('app.label')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <select class="selectpicker form-control" data-placeholder="@lang('app.label')" id="label">
                                <option value="all">@lang('app.all')</option>
                                @foreach($taskLabels as $label)
                                    <option data-content="<span class='badge b-all' style='background:{{ $label->label_color }};'>{{ $label->label_name }}</span> " value="{{ $label->id }}">{{ $label->label_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="box-title">@lang('app.billableTask')</h5>
                    <select class="form-control select2" name="billable" id="billable" data-style="form-control">
                        <option value="all">@lang('modules.client.all')</option>
                        <option value="1">@lang('app.yes')</option>
                        <option value="0">@lang('app.no')</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="box-title">@lang('app.milestone')</h5>
                    <select class="form-control select2" name="milestone" id="milestone" data-style="form-control">
                        <option value="all">@lang('modules.client.all')</option>
                        @foreach($milestones as $milestone)
                        <option
                            value="{{ $milestone->id }}">{{ $milestone->milestone_title }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="box-title">@lang('app.category')</h5>
                    <select class="form-control select2" name="task_category" id="task_category" data-style="form-control">
                        <option value="all">@lang('modules.client.all')</option>
                        @foreach($taskCategories as $category)
                        <option
                            value="{{ $category->id }}">{{ $category->category_name}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12">

                <div class="checkbox checkbox-info">
                    <input type="checkbox" id="hide-completed-tasks" checked>
                    <label for="hide-completed-tasks">@lang('app.hideCompletedTasks')</label>
                </div>
            </div>

            <div class="col-xs-12">
                </button>
                <div class="form-group">
                    <label class="control-label col-xs-12">&nbsp;</label>
                    <button type="button" id="filter-results" class="btn btn-success col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                    <button type="button" id="reset-filters" class="btn btn-inverse col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    @endsection

    @section('content')

        <div class="row">
            <div class="col-xs-12">
                <div class="white-box">

                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                    </div>

                </div>
            </div>

        </div>
        <!-- .row -->

        {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="editTimeLogModal" role="dialog" aria-labelledby="myModalLabel"
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
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    @if($global->locale == 'en')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.{{ $global->locale }}-AU.min.js"></script>
    @else
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.{{ $global->locale }}.min.js"></script>
    @endif
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>

    <script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

    {!! $dataTable->scripts() !!}

    <script>

        $(document).ready(function(){
            showTable();
        });

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#allTasks-table').on('preXhr.dt', function (e, settings, data) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            var projectID = $('#project_id').val();
            if (!projectID) {
                projectID = 0;
            }
            var clientID = $('#clientID').val();
            var assignedBY = $('#assignedBY').val();
            var assignedTo = $('#assignedTo').val();
            var status = $('#status').val();
            var category_id = $('#category_id').val();
            var label = $('#label').val();
             var billable = $('#billable').val();
             var milestone = $('#milestone').val();
             var task_category = $('#task_category').val();


            if ($('#hide-completed-tasks').is(':checked')) {
                var hideCompleted = '1';
            } else {
                var hideCompleted = '0';
            }

            data['clientID'] = clientID;
            data['assignedBY'] = assignedBY;
            data['assignedTo'] = assignedTo;
            data['status'] = status;
            data['category_id'] = category_id;
            data['hideCompleted'] = hideCompleted;
            data['projectId'] = projectID;
            data['startDate'] = startDate;
            data['endDate'] = endDate;
            data['label'] = label;
            data['billable'] = billable;
            data['milestone'] = milestone;
            data['task_category'] = task_category;


        });

        jQuery('#date-range').datepicker({
            toggleActive: true,
            format: '{{ $global->date_picker_format }}',
            language: '{{ $global->locale }}',
            autoclose: true,
            weekStart:'{{ $global->week_start }}',
        });

        table = '';

        function showTable() {
            window.LaravelDataTables["allTasks-table"].draw();
        }

        $('#filter-results').click(function () {
            showTable();
        });

        $('#reset-filters').click(function () {
            $('.select2').val('all');
            $('.select2').trigger('change');

            $('#start-date').val('');
            $('#end-date').val('');

            $(".selectpicker").val('all');
            $(".selectpicker").selectpicker("refresh");

            showTable();
        })


        $('body').on('click', '.sa-params', function () {
            var id = $(this).data('task-id');
            var recurring = $(this).data('recurring');

            var buttons = {
                cancel: "@lang('messages.confirmNoArchive')",
                confirm: {
                    text: "@lang('messages.deleteConfirmation')",
                    value: 'confirm',
                    visible: true,
                    className: "danger",
                }
            };

            if(recurring == 'yes')
            {
                buttons.recurring = {
                    text: "{{ trans('modules.tasks.deleteRecurringTasks') }}",
                    value: 'recurring'
                }
            }

            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.recoverDeletedTask')",
                dangerMode: true,
                icon: 'warning',
                buttons: buttons
            }).then(function (isConfirm) {
                if (isConfirm == 'confirm' || isConfirm == 'recurring') {

                    var url = "{{ route('admin.all-tasks.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";
                    var dataObject = {'_token': token, '_method': 'DELETE'};

                    if(isConfirm == 'recurring')
                    {
                        dataObject.recurring = 'yes';
                    }

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: dataObject,
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.LaravelDataTables["allTasks-table"].draw();
                            }
                        }
                    });
                }
            });
        });

        $('#allTasks-table').on('click', '.show-task-detail', function () {
            $(".right-sidebar").slideDown(50).addClass("shw-rside");

            var id = $(this).data('task-id');
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
        })

        $('#allTasks-table').on('click', '.change-status', function () {
            var url = "{{route('admin.tasks.changeStatus')}}";
            var token = "{{ csrf_token() }}";
            var id =  $(this).data('task-id');
            var status =  $(this).data('status');

            $.easyAjax({
                url: url,
                type: "POST",
                data: {'_token': token, taskId: id, status: status, sortBy: 'id'},
                success: function (data) {
                    if (data.status == "success") {
                        window.LaravelDataTables["allTasks-table"].draw();
                    }
                }
            })
        })

        $('#createTaskCategory').click(function(){
            var url = '{{ route('admin.taskCategory.create-cat')}}';
            $('#modelHeading').html("@lang('modules.taskCategory.manageTaskCategory')");
            $.ajaxModal('#taskCategoryModal',url);
        });

        function exportData(){

            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            var projectID = $('#project_id').val();
            if (!projectID) {
                projectID = 0;
            }

            if ($('#hide-completed-tasks').is(':checked')) {
                var hideCompleted = '1';
            } else {
                var hideCompleted = '0';
            }

            var url = '{!!  route('admin.all-tasks.export', [':startDate', ':endDate', ':projectId', ':hideCompleted']) !!}';

            url = url.replace(':startDate', startDate);
            url = url.replace(':endDate', endDate);
            url = url.replace(':hideCompleted', hideCompleted);
            url = url.replace(':projectId', projectID);

            window.location.href = url;
        }

        $('.pinnedItem').click(function(){
            var url = '{{ route('admin.all-tasks.pinned-task')}}';
            $('#modelHeading').html('Pinned Task');
            $.ajaxModal('#taskCategoryModal',url);
        })

    </script>
    @endpush
