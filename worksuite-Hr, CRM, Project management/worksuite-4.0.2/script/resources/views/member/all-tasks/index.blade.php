@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="javascript:;"  class="btn btn-outline btn-info btn-sm pinnedItem">@lang('app.pinnedTask') <i class="icon-pin icon-2"></i></a>

        @if($user->can('add_tasks') || $global->task_self == 'yes')
                <a href="{{ route('member.all-tasks.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.tasks.newTask') <i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
<style>
    .swal-footer {
        text-align: center !important;
    }
</style>
@endpush

@section('filter-section')
<div class="row">
    {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
    <div class="col-md-12">
        <div class="example">
            <h5 class="box-title">@lang('app.selectDateRange')</h5>

            <div class="input-daterange input-group" id="date-range">
                <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                    value="{{ $startDate }}"/>
                <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                    value="{{ $endDate }}"/>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <h5 class="box-title">@lang('app.selectProject')</h5>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
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

    <div class="col-md-12">
        <h5 class="box-title">@lang('app.select') @lang('modules.tasks.assignTo')</h5>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
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
    <div class="col-md-12">
        <h5 class="box-title">@lang('app.select') @lang('modules.tasks.assignBy')</h5>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
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
    <div class="col-md-12">
        <h5 class="box-title">@lang('app.select') @lang('app.status')</h5>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
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
        <div class="checkbox checkbox-info">
            <input type="checkbox" checked id="hide-completed-tasks">
            <label for="hide-completed-tasks">@lang('app.hideCompletedTasks')</label>
        </div>
    </div>

    <div class="col-md-12">
        <button type="button" class="btn btn-success" id="filter-results"><i class="fa fa-check"></i> @lang('app.apply')
        </button>
        <button type="button" id="reset-filters" class="btn btn-inverse"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
    </div>
    {!! Form::close() !!}

</div>
@endsection

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="white-box">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                           id="tasks-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('app.task')</th>
                            <th>@lang('app.project')</th>
                            <th>@lang('modules.tasks.assignTo')</th>
                            {{-- <th>@lang('modules.tasks.assignBy')</th> --}}
                            <th>@lang('app.dueDate')</th>
                            <th>@lang('app.status')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                    </table>
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

<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"></script>

<script>

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: '{{ $global->date_picker_format }}',
        language: '{{ $global->locale }}',
        autoclose: true
    });

    table = '';

    function showTable() {

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

        var assignedBY = $('#assignedBY').val();
        var assignedTo = $('#assignedTo').val();
        var status = $('#status').val();
        var billable = $('#billable').val();


        if ($('#hide-completed-tasks').is(':checked')) {
            var hideCompleted = '1';
        } else {
            var hideCompleted = '0';
        }

        var url = '{!!  route('member.all-tasks.data') !!}?&assignedBY='+ assignedBY+'&assignedTo='+ assignedTo+'&status='+ status+'&billable='+billable+'&_token={{ csrf_token() }}';

        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':hideCompleted', hideCompleted);
        url = url.replace(':projectId', projectID);

        table = $('#tasks-table').dataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                "url": url,
                "type": "POST",
                "data" : {
                    startDate : startDate,
                    endDate : endDate,
                    hideCompleted  : hideCompleted,
                    projectId : projectID
                }
            },
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            "order": [[0, "desc"]],
            columns: [
                { data: 'id', name: 'id' },
                {data: 'heading', name: 'heading'},
                {data: 'project_name', name: 'projects.project_name'},
                {data: 'users', name: 'member.name'},
                // {data: 'created_by', name: 'creator_user.name', width: '15%'},
                {data: 'due_date', name: 'due_date'},
                {data: 'board_column', name: 'board_column', searchable: false},
                {data: 'action', name: 'action', "searchable": false}
            ]
        });
    }

    $('#filter-results').click(function () {
        showTable();
    });

    $('#reset-filters').click(function () {
        $('.select2').val('all');
        $('.select2').trigger('change');

        $('#start-date').val('{{ $startDate }}');
        $('#end-date').val('{{ $endDate }}');

        showTable();
    })

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('task-id');
        var recurring = $(this).data('recurring');

        var buttons = {
            cancel: "No, cancel please!",
            confirm: {
                text: "Yes, delete it!",
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
            text: "@lang('messages.deleteTask')",
            dangerMode: true,
            icon: 'warning',
            buttons: buttons,
        }).then(function (isConfirm) {
            if (isConfirm == 'confirm' || isConfirm == 'recurring') {

                var url = "{{ route('member.all-tasks.destroy',':id') }}";
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
                            table._fnDraw();
                        }
                    }
                });
            }
        });
    });

    $('#tasks-table').on('click', '.show-task-detail', function () {
        $(".right-sidebar").slideDown(50).addClass("shw-rside");

        var id = $(this).data('task-id');
        var url = "{{ route('member.all-tasks.show',':id') }}";
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

    $('#tasks-table').on('click', '.change-status', function () {
        var url = "{{route('member.tasks.changeStatus')}}";
        var token = "{{ csrf_token() }}";
        var id =  $(this).data('task-id');
        var status =  $(this).data('status');

        $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, taskId: id, status: status, sortBy: 'id'},
            success: function (data) {
                if (data.status == "success") {
                    table._fnDraw();
                }
            }
        })
    })


    showTable();
    $('.pinnedItem').click(function(){
        var url = '{{ route('member.all-tasks.pinned-task')}}';
        $('#modelHeading').html('Pinned Task');
        $.ajaxModal('#editTimeLogModal',url);
    })

</script>
@endpush
