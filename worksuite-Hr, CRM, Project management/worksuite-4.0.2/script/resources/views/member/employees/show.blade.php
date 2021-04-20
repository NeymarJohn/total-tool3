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
                <li><a href="{{ route('member.employees.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.details')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<style>
    .counter{
        font-size: large;
    }
</style>

<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush

@section('content')
        <!-- .row -->
<div class="row">
    <div class="col-md-5 col-xs-12">
        <div class="white-box">
            <div class="user-bg">
                {!!  ($employee->image) ? '<img src="'.asset_url('avatar/'.$employee->image).'"
                                                            alt="user" width="100%">' : '<img src="'.asset('img/default-profile-3.png').'"
                                                            alt="user" width="100%">' !!}
                <div class="overlay-box">
                    <div class="user-content"> <a href="javascript:void(0)">
                            {!!  ($employee->image) ? '<img src="'.asset_url('avatar/'.$employee->image).'"
                                                            alt="user" class="thumb-lg img-circle">' : '<img src="'.asset('img/default-profile-3.png').'"
                                                            alt="user" class="thumb-lg img-circle">' !!}
                            </a>
                        <h4 class="text-white">{{ ucwords($employee->name) }}</h4>
                        <h5 class="text-white">{{ $employee->email }}</h5>
                    </div>
                </div>
            </div>
            {{-- <div class="user-btm-box">
                <div class="row row-in">
                    <div class="col-md-6 row-in-br">
                        <div class="col-in row">
                                <h3 class="box-title">@lang('modules.employees.tasksDone')</h3>
                                <div class="col-xs-4"><i class="ti-check-box text-success"></i></div>
                                <div class="col-xs-8 text-right counter">{{ $taskCompleted }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 row-in-br  b-r-none">
                        <div class="col-in row">
                                <h3 class="box-title">@lang('modules.employees.hoursLogged')</h3>
                            <div class="col-xs-4"><i class="icon-clock text-info"></i></div>
                            <div class="col-xs-8 text-right counter">{{ floor($hoursLogged) }}</div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="col-md-7">
        <div class="user-btm-box">
            <div class="row row-in">
                <div class="col-md-6 row-in-br">
                    <div class="col-in row">
                            <h3 class="box-title">@lang('modules.employees.tasksDone')</h3>
                            <div class="col-xs-4"><i class="ti-check-box text-success"></i></div>
                            <div class="col-xs-8 text-right counter">{{ $taskCompleted }}</div>
                    </div>
                </div>
                <div class="col-md-6 row-in-br  b-r-none">
                    <div class="col-in row">
                            <h3 class="box-title">@lang('modules.employees.hoursLogged')</h3>
                        <div class="col-xs-2"><i class="icon-clock text-info"></i></div>
                        <div class="col-xs-10 text-right counter" style="font-size: 13px">{{ $hoursLogged }}</div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="col-xs-12">
        <div class="white-box">
            <ul class="nav nav-tabs tabs customtab">
                <li class="active tab"><a href="#profile" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">@lang('modules.employees.profile')</span> </a> </li>
                <li class=" tab"><a href="#home" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-home"></i></span> <span class="hidden-xs">@lang('modules.employees.activity')</span> </a> </li>
                <li class="tab"><a href="#projects" data-toggle="tab" aria-expanded="true"> <span class="visible-xs"><i class="icon-layers"></i></span> <span class="hidden-xs">@lang('app.menu.projects')</span> </a> </li>
                <li class="tab"><a href="#tasks" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="icon-list"></i></span> <span class="hidden-xs">@lang('app.menu.tasks')</span> </a> </li>
                <li class="tab"><a href="#time-logs" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="icon-clock"></i></span> <span class="hidden-xs">@lang('app.menu.timeLogs')</span> </a> </li>
                <li class="tab"><a href="#docs" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="icon-docs"></i></span> <span class="hidden-xs">@lang('app.menu.documents')</span> </a> </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="home">
                    <div class="steamline">
                        @forelse($activities as $key=>$activity)
                        <div class="sl-item">
                            <div class="sl-left">
                                {!!  ($employee->image) ? '<img src="'.asset_url('avatar/'.$employee->image).'"
                                                            alt="user" class="img-circle">' : '<img src="'.asset('img/default-profile-3.png').'"
                                                            alt="user" class="img-circle">' !!}
                            </div>
                            <div class="sl-right">
                                <div class="m-l-40"><a href="#" class="text-info">{{ ucwords($employee->name) }}</a> <span  class="sl-date">{{ $activity->created_at->diffForHumans() }}</span>
                                    <p>{!! ucfirst($activity->activity) !!}</p>
                                </div>
                            </div>
                        </div>
                            @if(count($activities) > ($key+1))
                                <hr>
                            @endif
                        @empty
                            <div>@lang('messages.noActivityByThisUser')</div>
                        @endforelse
                    </div>
                </div>
                <div class="tab-pane active" id="profile">
                    <div class="row">
                        <div class="col-xs-6 col-md-4  b-r"> <strong>@lang('modules.employees.fullName')</strong> <br>
                            <p class="text-muted">{{ ucwords($employee->name) }}</p>
                        </div>
                        <div class="col-xs-6 col-md-4  b-r"> <strong>@lang('modules.employees.employeeId')</strong> <br>
                            <p class="text-muted">{{ ucwords($employee->employeeDetail->employee_id) }}</p>
                        </div>
                        <div class="col-md-4 col-xs-6"> <strong>@lang('app.mobile')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->country_id)) ? '+'.$employee->country->phonecode.'-' : ''}}{{ $employee->mobile ?? 'NA'}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 col-md-6 col-xs-6 b-r"> <strong>@lang('app.email')</strong> <br>
                            <p class="text-muted">{{ $employee->email }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>@lang('app.address')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail)) ? $employee->employeeDetail->address : 'NA'}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.designation')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail) && !is_null($employee->employeeDetail->designation)) ? ucwords($employee->employeeDetail->designation->name) : 'NA' }}</p>
                        </div>
                        <div class="col-md-4 col-xs-6"> <strong>@lang('app.department')</strong> <br>
                            @if (count($employee->employee) > 0)
                                <p class="text-muted">{{ (isset($employee->employeeDetail->department->team_name)) ? ucwords($employee->employeeDetail->department->team_name) : 'NA' }}</p>
                            @else
                                <p class="text-muted">NA</p>
                            @endif

                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 col-xs-6"> <strong>@lang('modules.employees.hourlyRate')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail)) ? $employee->employeeDetail->hourly_rate : 'NA' }}</p>
                        </div>
                        <div class="col-md-6 col-xs-6"> <strong>@lang('modules.employees.slackUsername')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail)) ? '@'.$employee->employeeDetail->slack_username : 'NA' }}</p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="projects">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('app.project')</th>
                                <th>@lang('app.deadline')</th>
                                <th>@lang('app.completion')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($projects as $key=>$project)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td><a href="{{ route('member.projects.show', $project->id) }}">{{ ucwords($project->project_name) }}</a></td>
                                    <td>@if($project->deadline){{ $project->deadline->format('m/d/Y') }}@else {{ \Carbon\Carbon::now()->format('m/d/Y') }} @endif</td>
                                    <td>
                                        <?php

                                        if ($project->completion_percent < 50) {
                                        $statusColor = 'danger';
                                        }
                                        elseif ($project->completion_percent >= 50 && $project->completion_percent < 75) {
                                        $statusColor = 'warning';
                                        }
                                        else {
                                        $statusColor = 'success';
                                        }
                                        ?>

                                        <h5>@lang('app.completed')<span class="pull-right">{{  $project->completion_percent }}%</span></h5><div class="progress">
                                            <div class="progress-bar progress-bar-{{ $statusColor }}" aria-valuenow="{{ $project->completion_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->completion_percent }}%" role="progressbar"> <span class="sr-only">{{ $project->completion_percent }}% @lang('app.completed')</span> </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">@lang('messages.noProjectFound')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tasks">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="checkbox checkbox-info">
                                <input type="checkbox" id="hide-completed-tasks">
                                <label for="hide-completed-tasks">@lang('app.hideCompletedTasks')</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                               id="tasks-table">
                            <thead>
                            <tr>
                                <th>@lang('app.id')</th>
                                <th>@lang('app.project')</th>
                                <th>@lang('app.task')</th>
                                <th>@lang('app.dueDate')</th>
                                <th>@lang('app.status')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                </div>
                <div class="tab-pane" id="time-logs">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="timelog-table">
                            <thead>
                            <tr>
                                <th>@lang('app.id')</th>
                                <th>@lang('app.project')</th>
                                <th>@lang('modules.employees.startTime')</th>
                                <th>@lang('modules.employees.endTime')</th>
                                <th>@lang('modules.employees.totalHours')</th>
                                <th>@lang('modules.employees.memo')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>


                </div>
                <div class="tab-pane" id="docs">
                    <button class="btn btn-sm btn-info addDocs" onclick="showAdd()"><i
                                class="fa fa-plus"></i> @lang('app.add')</button>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th width="70%">@lang('app.name')</th>
                                <th>@lang('app.action')</th>
                            </tr>
                            </thead>
                            <tbody id="employeeDocsList">
                            @forelse($employeeDocs as $key=>$employeeDoc)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td width="70%">{{ ucwords($employeeDoc->name) }}</td>
                                    <td>
                                        <a href="{{ route('member.employee-docs.download', $employeeDoc->id) }}"
                                           data-toggle="tooltip" data-original-title="Download"
                                           class="btn btn-inverse btn-circle"><i
                                                    class="fa fa-download"></i></a>
                                        <a target="_blank" href="{{ asset_url('employee-docs/'.$employeeDoc->user_id.'/'.$employeeDoc->hashname) }}"
                                           data-toggle="tooltip" data-original-title="View"
                                           class="btn btn-info btn-circle"><i
                                                    class="fa fa-search"></i></a>
                                        <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $employeeDoc->id }}"
                                           data-pk="list" class="btn btn-danger btn-circle sa-params"><i class="fa fa-times"></i></a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">@lang('messages.noDocsFound')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
        {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="edit-column-form" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
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
            <!-- /.modal-dialog -->
        </div>
        {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
<script>
    var table;
    // Show Create employeeDocs Modal
    function showAdd() {
        var url = "{{ route('member.employees.docs-create', [$employee->id]) }}";
        $.ajaxModal('#edit-column-form', url);
    }
    showTable();

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('file-id');
        var deleteView = $(this).data('pk');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeFileText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.employee-docs.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE', 'view': deleteView},
                    success: function (response) {
                        console.log(response);
                        if (response.status == "success") {
                            $.unblockUI();
                            $('#employeeDocsList').html(response.html);
                        }
                    }
                });
            }
        });
    });
    function showTable() {

        if ($('#hide-completed-tasks').is(':checked')) {
            var hideCompleted = '1';
        } else {
            var hideCompleted = '0';
        }

        var url = '{{ route('member.employees.tasks', [$employee->id, ':hideCompleted']) }}';
        url = url.replace(':hideCompleted', hideCompleted);

        table = $('#tasks-table').dataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: url,
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
                {data: 'id', name: 'id'},
                {data: 'project_name', name: 'projects.project_name', width: '20%'},
                {data: 'heading', name: 'heading', width: '20%'},
                {data: 'due_date', name: 'due_date'},
                {data: 'status', name: 'status'}
            ]
        });
    }

    $('#hide-completed-tasks').click(function () {
        showTable();
    });


</script>

<script>
    var table2;

    function showTable2(){

        var url = '{{ route('member.employees.time-logs', [$employee->id]) }}';

        table2 = $('#timelog-table').dataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: url,
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            "order": [[ 0, "desc" ]],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'project_name', name: 'projects.project_name' },
                { data: 'start_time', name: 'start_time' },
                { data: 'end_time', name: 'end_time' },
                { data: 'total_hours', name: 'total_hours' },
                { data: 'memo', name: 'memo' }
            ]
        });
    }

    showTable2();
</script>
@endpush

