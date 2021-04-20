@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
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

<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">

<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
<style>
    #task-report-table_wrapper .dt-buttons{
        display: none !important;
    }
</style>
@endpush

@section('filter-section')
<div class="row">
    {!! Form::open(['id'=>'filter-form','class'=>'ajax-form','method'=>'POST']) !!}
    <div class="col-md-12">
        <div class="example">
            <h5 class="box-title">@lang('app.selectDateRange')</h5>

            <div class="input-daterange input-group" id="date-range">
                <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                    value="{{ $fromDate->format($global->date_format) }}"/>
                <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                    value="{{ $toDate->format($global->date_format) }}"/>
            </div>
        </div>
    </div>

    <div class="col-md-12 m-t-20">
        <h5 class="box-title">@lang('app.selectProject')</h5>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <select class="select2 form-control" data-placeholder="@lang('app.selectProject')" id="project_id">
                        <option value="">@lang('app.all')</option>
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
        <h5 class="box-title">@lang('app.select') @lang('app.client')</h5>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <select class="select2 form-control" data-placeholder="@lang('app.client')" id="clientID">
                        <option value="all">@lang('app.all')</option>
                        @foreach($clients as $client)
                            <option
                                value="{{ $client->id }}">{{ ucwords($client->name) }}{{ ($client->company_name != '') ? " [".$client->company_name."]" : "" }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <h5 class="box-title">@lang('modules.employees.employeeName')</h5>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <select class="select2 form-control" data-placeholder="@lang('modules.employees.employeeName')" id="employeeId">
                        <option value=""></option>
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
        <button type="button" class="btn btn-success" id="filter-results"><i class="fa fa-check"></i> @lang('app.apply')
        </button>
        <button type="button" id="reset-filters" class="btn btn-inverse "><i class="fa fa-refresh"></i> @lang('app.reset')</button>
    </div>
    {!! Form::close() !!}

</div>
@endsection

@section('content')

    <div class="row dashboard-stats">
        <div class="col-md-12">
            <div class="white-box">
                <div class="col-md-4">
                    <h4><span class="text-info" id="total-counter">{{ $totalTasks }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.taskReport.taskToComplete')</span></h4>
                </div>
                <div class="col-md-4">
                    <h4><span class="text-success" id="completed-counter">{{ $completedTasks }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.taskReport.completedTasks')</span></h4>
                </div>
                <div class="col-md-4">
                    <h4><span class="text-warning" id="pending-counter">{{ $pendingTasks }}</span> <span class="font-12 text-muted m-l-5"> @lang("modules.taskReport.pendingTasks")</span></h4>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 m-t-20">
            <div class="white-box">

                <h3 class="box-title">@lang("modules.taskReport.chartTitle")</h3>
                <div>
                    <canvas id="chart3" height="50"></canvas>
                </div>


                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                </div>
            </div>

        </div>

    </div>

@endsection

@push('footer-script')


<script src="{{ asset('plugins/bower_components/Chart.js/Chart.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>



<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{!! $dataTable->scripts() !!}
<script>

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    initConter();
    $('#task-report-table').on('preXhr.dt', function (e, settings, data) {
        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var projectID = $('#project_id').val();
        if (projectID == '') {
            projectID = 0;
        }

        var employeeId = $('#employeeId').val();
        if (!employeeId) {
            employeeId = 0;
        }

        var clientID = $('#clientID').val();

        var status = $('#status').val();

        data['startDate'] = startDate;
        data['endDate'] = endDate;
        data['projectId'] = projectID;
        data['employeeId'] = employeeId;
        data['clientID'] = clientID;
        data['status'] = status;
        data['_token'] = '{{csrf_token()}}';
    });
    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: '{{ $global->date_picker_format }}',
    });

    $('#filter-results').click(function () {
        var token = '{{ csrf_token() }}';
        var url = '{{ route('admin.task-report.store') }}';

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        var projectID = $('#project_id').val();
        var employeeId = $('#employeeId').val();

        var clientID = $('#clientID').val();
        var status = $('#status').val();

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {_token: token, startDate: startDate, endDate: endDate, projectId: projectID, employeeId: employeeId, clientID: clientID, status: status},
            success: function (response) {

                $('#completed-counter').html(response.completedTasks);
                $('#total-counter').html(response.totalTasks);
                $('#pending-counter').html(response.pendingTasks);
                // console.log(response.taskStatus);
                pieChart(response.taskStatus);
                initConter();
            }
        });
    })

    $('#reset-filters').click(function () {
        $('#filter-form')[0].reset();
        $('#status').val('all');
        $('.select2').val('all');
        $('#project_id').val('');
        $('#filter-form').find('select').select2();
        $('#filter-results').trigger("click");
    })

    function initConter() {
        $(".counter").counterUp({
            delay: 100,
            time: 1200
        });
    }
</script>

<script>
    function pieChart(taskStatus) {
        var ctx3 = document.getElementById("chart3").getContext("2d");
        var data3 = new Array();
        $.each(taskStatus, function(key,val){
            // console.log("key : "+key+" ; value : "+val);
            data3.push(
                {
                    value: parseInt(val.count),
                    color: val.color,
                    highlight: "#57ecc8",
                    label: val.label
                }
            );
        });

        console.log(data3);

        var myPieChart = new Chart(ctx3).Pie(data3,{
            segmentShowStroke : true,
            segmentStrokeColor : "#fff",
            segmentStrokeWidth : 0,
            animationSteps : 100,
            tooltipCornerRadius: 0,
            animationEasing : "easeOutBounce",
            animateRotate : true,
            animateScale : false,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            responsive: true
        });

        showTable();
    }

    pieChart(jQuery.parseJSON('{!! $taskStatus !!}'));

    var table;

    function showTable() {
        window.LaravelDataTables["task-report-table"].draw();
    }

    function exportData(){
        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = 0;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = 0;
        }

        var projectID = $('#project_id').val();
        if (!projectID) {
            projectID = 0;
        }

        var employeeId = $('#employeeId').val();
        if (!employeeId) {
            employeeId = 0;
        }
        var url = '{!!  route('admin.task-report.export', [':startDate', ':endDate', ':employeeId', ':projectId']) !!}';

        url = url.replace(':startDate', startDate);
        url = url.replace(':endDate', endDate);
        url = url.replace(':employeeId', employeeId);
        url = url.replace(':projectId', projectID);

        window.location.href = url;
    }

    $('#task-report-table').on('click', '.show-task-detail', function () {
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

</script>
@endpush
