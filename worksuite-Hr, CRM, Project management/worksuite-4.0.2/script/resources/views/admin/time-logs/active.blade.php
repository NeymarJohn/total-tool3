@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-10 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.all-time-logs.index') }}"
                class="btn btn-inverse btn-outline btn-sm"><i
                         class="fa fa-list"></i> @lang('app.menu.timeLogs')
            </a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">

<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
    <style>
        #all-time-logs-table_wrapper .dt-buttons{
            display: none !important;
        }
    </style>
@endpush

@section('filter-section')
<div class="row">
    {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}

    <div class="col-md-12 m-t-20">
        <h5 class="box-title">@lang('app.selectProject')</h5>
        <div class="form-group" >
            <div class="row">
                <div class="col-md-12">
                    <select class="select2 form-control" data-placeholder="@lang('app.selectProject')" id="project_id">
                        <option value="all">@lang('modules.client.all')</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ ucwords($project->project_name) }}</option>
                            @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <h5 class="box-title">
            @lang('app.selectTask')
        </h5>
        <div class="form-group" >
            <div class="row">
                <div class="col-md-12">
                    <select class="select2 form-control" data-placeholder="@lang('app.selectTask')" id="task_id">
                        <option value="all">@lang('modules.client.all')</option>
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}">{{ ucwords($task->heading) }}</option>
                            @endforeach

                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <h5 class="box-title">@lang('modules.employees.title')</h5>
            <select class="form-control select2" name="employee" id="employee" data-style="form-control">
                <option value="all">@lang('modules.client.all')</option>
                @forelse($employees as $employee)
                    <option value="{{$employee->id}}">{{ ucfirst($employee->name) }}</option>
                @empty
                @endforelse
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <h5 class="box-title">@lang('app.approved')</h5>
            <select class="form-control select2" name="approved" id="approved" data-style="form-control">
                <option value="all">@lang('modules.client.all')</option>
                <option value="1">@lang('app.approved')</option>
                <option value="0">@lang('app.pending')</option>
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <button type="button" class="btn btn-success" id="filter-results"><i class="fa fa-check"></i> @lang('app.apply')</button>
        <button type="button" id="reset-filters" class="btn btn-inverse"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
    </div>
    {!! Form::close() !!}

</div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12" >
            <div class="white-box">

                <div class="table-responsive m-t-30">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                </div>

            </div>
        </div>

    </div>
    <!-- .row -->

  
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


<script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{!! $dataTable->scripts() !!}
<script>

    $('#close-form').click(function () {
        $('#hideShowTimeLogForm').addClass('hide');
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.time-logs.store')}}',
            container: '#logTime',
            type: "POST",
            data: $('#logTime').serialize(),
            success: function (data) {
                if (data.status == 'success') {
                    showTable();
                    $('#hideShowTimeLogForm').toggleClass('hide', 'show');
                    $('#logTime')[0].reset();
                    $('#project_id2').val('');
                    $('#project_id2').select2();
                    $('#task_id2').val('');
                    $('#task_id2').select2();

                }
            }
        })
    });

    $('#project_id2').change(function () {
        var id = $(this).val();
        if (id == "") {
            id = "0";
        }
        var url = '{{route('admin.all-time-logs.members', ':id')}}';
        url = url.replace(':id', id);
        // $('#employeeBox').show();
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            success: function (data) {
                $('#user_id').html(data.html);
                $('#task_id2').html(data.tasks);
                $('#user_id, #task_id2').select2();
                $("#task_id2").trigger('change');
            }
        })
    });

    $('#task_id2').change(function () {
        var id = $(this).val();
        var url = '{{route('admin.all-time-logs.task-members', ':id')}}';
        url = url.replace(':id', id);
        // $('#employeeBox').show();
        $.easyAjax({
            url: url,
            type: "GET",
            redirect: true,
            success: function (data) {
                $('#user_id').html(data.html);
                $('#user_id, #task_id2').select2();
            }
        })
    });

    $('#show-add-form').click(function () {
        $('#hideShowTimeLogForm').toggleClass('hide', 'show');
    });
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#date-range').datepicker({
        format: '{{ $global->date_picker_format }}',
        toggleActive: true,
    });

    $('#all-time-logs-table').on('preXhr.dt', function (e, settings, data) {
        
        var projectID = $('#project_id').val();
        var taskId = $('#task_id').val();
        var employee = $('#employee').val();
        var approved = $('#approved').val();

        data['projectId'] = projectID;
        data['taskId'] = taskId;
        data['employee'] = employee;
        data['approved'] = approved;
    });

    function showTable(){
        window.LaravelDataTables["all-time-logs-table"].draw();
    }

    $('#filter-results').click(function () {
        showTable();
    });

    $('#reset-filters').click(function () {
        $('.select2').val('all');
        $('.select2').trigger('change');

        showTable();
    })
    


    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('time-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.deleteTimeText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.all-time-logs.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            window.LaravelDataTables["all-time-logs-table"].draw();
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.approve-timelog', function(){
        var id = $(this).data('time-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: '@lang("app.yes")',
            cancelButtonText: '@lang("app.no")',
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.all-time-logs.approve-timelog') }}";

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, id: id},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            showTable();
                        }
                    }
                });
            }
        });
    });

    $('#all-time-logs-table').on('click', '.stop-timer', function () {
        var id = $(this).data('time-id');
        var url = '{{route('admin.all-time-logs.stopTimer', ':id')}}';
        url = url.replace(':id', id);
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            url: url,
            type: "POST",
            data: {timeId: id, _token: token},
            success: function (data) {
                
                showTable();
            }
        })

    });

    $('body').on('click', '.edit-time-log', function () {
        var id = $(this).data('time-id');

        var url = '{{ route('admin.time-logs.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#modelHeading').html('Update Time Log');
        $.ajaxModal('#editTimeLogModal', url);

    });

    function exportTimeLog(){

        var startDate = $('#start-date').val();

        if(startDate == ''){
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if(endDate == ''){
            endDate = null;
        }

        var projectID = $('#project_id').val();
        var employee = $('#employee').val();

        var url = '{{ route('admin.all-time-logs.export', [':startDate', ':endDate', ':projectId', ':employee']) }}';
        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':projectId', projectID);
        url = url.replace(':employee', employee);

        window.location.href = url;
    }

    $('#start_time, #end_time').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false,
        @endif
    }).on('hide.timepicker', function (e) {
        calculateTime();
    });

    jQuery('#start_date, #end_date').datepicker({
        // format: 'yyyy-mm-dd'
        autoclose: true,
        todayHighlight: true
    }).on('hide', function (e) {
        calculateTime();
    });



</script>
@endpush
