@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.employees.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.details')</li>
            </ol>
        </div>
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.employees.edit',$employee->id) }}"
               class="btn btn-outline btn-success btn-sm">@lang('modules.lead.edit')
                <i class="fa fa-edit" aria-hidden="true"></i></a>
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
                <img src="{{$employee->image_url}}" alt="user" width="100%">
                <div class="overlay-box">
                    <div class="user-content">
                        <a href="javascript:void(0)"><img src="{{$employee->image_url}}" alt="user"   class="thumb-lg img-circle" width="100%"></a>
                        <h4 class="text-white">{{ ucwords($employee->name) }}</h4>
                        <h5 class="text-white">{{ $employee->email }}</h5>
                        @if (!is_null($employee->last_login))
                            <h6 class="text-white">
                                @lang('app.lastLogin'): {{ $employee->last_login->timezone($global->timezone)->format($global->date_format.' '.$global->time_format) }}
                            </h6>
                        @else
                            <h6 class="text-white">@lang('app.lastLogin'): --</h6>
                        @endif
                    </div>
                </div>
            </div>

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
            <div class="row row-in">
                <div class="col-md-6 row-in-br b-t">
                    <div class="col-in row">
                            <h3 class="box-title">@lang('modules.leaves.leavesTaken')</h3>
                            <div class="col-xs-4"><i class="icon-logout text-warning"></i></div>
                            <div class="col-xs-8 text-right counter">{{ $leavesCount }}</div>
                    </div>
                </div>
                <div class="col-md-6 row-in-br  b-r-none b-t">
                    <div class="col-in row">
                            <h3 class="box-title">@lang('modules.leaves.remainingLeaves')</h3>
                        <div class="col-xs-4"><i class="icon-logout text-danger"></i></div>
                        <div class="col-xs-8 text-right counter">{{ ($allowedLeaves-$leavesCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="white-box">
            <ul class="nav nav-tabs tabs customtab">
                <li class="tab active"><a href="#profile" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">@lang('modules.employees.profile')</span> </a> </li>
                <li class="tab"><a href="#home" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-home"></i></span> <span class="hidden-xs">@lang('modules.employees.activity')</span> </a> </li>
                <li class="tab"><a href="#projects" data-toggle="tab" aria-expanded="true"> <span class="visible-xs"><i class="icon-layers"></i></span> <span class="hidden-xs">@lang('app.menu.projects')</span> </a> </li>
                <li class="tab"><a href="#tasks" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="icon-list"></i></span> <span class="hidden-xs">@lang('app.menu.tasks')</span> </a> </li>
                <li class="tab"><a href="#leaves" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="icon-logout"></i></span> <span class="hidden-xs">@lang('app.menu.leaves')</span> </a> </li>
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
                            <div class="text-center">
                                <div class="empty-space" style="height: 200px;">
                                    <div class="empty-space-inner">
                                        <div class="icon" style="font-size:30px"><i
                                                    class="fa fa-tasks"></i>
                                        </div>
                                        <div class="title m-b-15">@lang('messages.noActivityByThisUser')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="tab-pane active" id="profile">
                    <div class="row">
                        <div class="col-xs-6 col-md-4  b-r"> <strong>@lang('modules.employees.fullName')</strong> <br>
                            <p class="text-muted">{{ ucwords($employee->name) }}</p>
                        </div>
                        <div class="col-xs-6 col-md-4  b-r"> <strong>@lang('modules.employees.employeeId')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail) && !is_null($employee->employeeDetail->employee_id)) ? ucwords($employee->employeeDetail->employee_id) : '--'  }}</p>
                        </div>
                        <div class="col-md-4 col-xs-6 "> <strong>@lang('app.email')</strong> <br>
                            <p class="text-muted">{{ $employee->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.designation')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail) && !is_null($employee->employeeDetail->designation)) ? ucwords($employee->employeeDetail->designation->name) : 'NA' }}</p>
                        </div>
                        <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.department')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail) && !is_null($employee->employeeDetail->department)) ? ucwords($employee->employeeDetail->department->team_name) : 'NA' }}</p>
                        </div>
                        <div class="col-md-4 col-xs-6"> <strong>@lang('modules.employees.joiningDate')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail)) ? $employee->employeeDetail->joining_date->format($global->date_format) : 'NA' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-4 b-r"> <strong>@lang('app.mobile')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->country_id)) ? '+'.$employee->country->phonecode.'-' : ''}}{{ $employee->mobile ?? 'NA'}}</p>
                        </div>
                        <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('modules.employees.gender')</strong> <br>
                            <p class="text-muted">{{ $employee->gender }}</p>
                        </div>
                        <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('modules.employees.slackUsername')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail)) ? '@'.$employee->employeeDetail->slack_username : 'NA' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 col-xs-6"> <strong>@lang('modules.employees.hourlyRate')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail)) ? $employee->employeeDetail->hourly_rate : 'NA' }}</p>
                        </div>
                        <div class="col-md-4 col-xs-6"> <strong>@lang('app.skills')</strong> <br>
                            {{implode(', ', $employee->skills()) }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12"> <strong>@lang('app.address')</strong> <br>
                            <p class="text-muted">{{ (!is_null($employee->employeeDetail)) ? $employee->employeeDetail->address : 'NA'}}</p>
                        </div>
                    </div>
                    {{--Custom fields data--}}
                        @if(isset($fields))
                        <div class="row">
                            <hr>
                            @foreach($fields as $field)
                                <div class="col-md-6">
                                    <strong>{{ ucfirst($field->label) }}</strong> <br>
                                    <p class="text-muted">
                                        @if( $field->type == 'text')
                                            {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? '-'}}
                                        @elseif($field->type == 'password')
                                            {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? '-'}}
                                        @elseif($field->type == 'number')
                                            {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? '-'}}

                                        @elseif($field->type == 'textarea')
                                            {{$employeeDetail->custom_fields_data['field_'.$field->id] ?? '-'}}

                                        @elseif($field->type == 'radio')
                                            {{ !is_null($employeeDetail->custom_fields_data['field_'.$field->id]) ? $employeeDetail->custom_fields_data['field_'.$field->id] : '-' }}
                                        @elseif($field->type == 'select')
                                            {{ (!is_null($employeeDetail->custom_fields_data['field_'.$field->id]) && $employeeDetail->custom_fields_data['field_'.$field->id] != '') ? $field->values[$employeeDetail->custom_fields_data['field_'.$field->id]] : '-' }}
                                        @elseif($field->type == 'checkbox')
                                            {{ !is_null($employeeDetail->custom_fields_data['field_'.$field->id]) ? $field->values[$employeeDetail->custom_fields_data['field_'.$field->id]] : '-' }}
                                        @elseif($field->type == 'date')
                                            {{ isset($employeeDetail->custom_fields_data['field_'.$field->id])? Carbon\Carbon::parse($employeeDetail->custom_fields_data['field_'.$field->id])->format($global->date_format): ''}}
                                        @endif
                                    </p>

                                </div>
                            @endforeach
                        </div>
                        @endif

                    {{--custom fields data end--}}

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
                                    <td><a href="{{ route('admin.projects.show', $project->id) }}">{{ ucwords($project->project_name) }}</a></td>
                                    <td>@if(!is_null($project->deadline)){{ $project->deadline->format($global->date_format) }} @endif</td>
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
                                    <td colspan="4" class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i
                                                            class="icon-layers"></i>
                                                </div>
                                                <div class="title m-b-15">@lang('messages.noProjectFound')
                                                </div>
                                                <div class="subtitle">
                                                    <a href="{{route('admin.projects.create')}}" type="button" class="btn btn-info"><i
                                                                class="zmdi zmdi-arrow-left"></i>
                                                        @lang('modules.client.assignProject')
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

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
                                <th>#</th>
                                <th>@lang('app.project')</th>
                                <th>@lang('app.task')</th>
                                <th>@lang('app.dueDate')</th>
                                <th>@lang('app.status')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                </div>
                <div class="tab-pane" id="leaves">
                    <div class="row">

                        <div class="col-md-4">
                            <p><strong>@lang('modules.leaves.leavesTaken')</strong>
                            <a href="javascript:;" id="edit-leave-type" class="btn btn-info btn-xs"><i class="fa fa-gear"></i> @lang('app.manage')</a>
                            </p>
                            <ul class="basic-list">
                                @forelse($leaveTypes as $key=>$leaveType)
                                    <li>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                {{ ucfirst($leaveType->type_name) }}
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                    <span class="label-{{ $leaveType->color }} label">{{ (isset($leaveType->leavesCount[0])) ? $leaveType->leavesCount[0]->count : '0' }} / {{  isset($employeeLeavesQuota[$key]) ? $employeeLeavesQuota[$key]->no_of_leaves : 0 }}</span>
                                                    
                                            </div>
                                        </div>

                                    </li>
                                @empty
                                    <li>@lang('messages.noRecordFound')</li>
                                @endforelse
                            </ul>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" id="leave-table">
                                <thead>
                                <tr>
                                    <th>@lang('modules.leaves.leaveType')</th>
                                    <th>@lang('app.date')</th>
                                    <th>@lang('modules.leaves.reason')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($leaves as $key=>$leave)
                                    <tr>
                                        <td>
                                            <label class="label label-{{ $leave->type->color }}">{{ ucwords($leave->type->type_name) }}</label>
                                        </td>
                                        <td>
                                            {{ $leave->leave_date->format($global->date_format) }}
                                        </td>
                                        <td>
                                            {{ $leave->reason }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <div class="empty-space" style="height: 200px;">
                                                <div class="empty-space-inner">
                                                    <div class="icon" style="font-size:30px"><i
                                                                class="icon-logout"></i>
                                                    </div>
                                                    <div class="title m-b-15">@lang('messages.noRecordFound')
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="tab-pane" id="time-logs">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="timelog-table">
                            <thead>
                            <tr>
                                <th>#</th>
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
                                        <a href="{{ route('admin.employee-docs.download', $employeeDoc->id) }}"
                                           data-toggle="tooltip" data-original-title="Download"
                                           class="btn btn-primary btn-circle"><i
                                                    class="fa fa-download"></i></a>
                                        <a target="_blank" href="{{ $employeeDoc->doc_url }}"
                                           data-toggle="tooltip" data-original-title="View"
                                           class="btn btn-info btn-circle"><i
                                                    class="fa fa-search"></i></a>

                                        <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $employeeDoc->id }}"
                                                                                    data-pk="list" class="btn btn-danger btn-circle sa-params"><i class="fa fa-times"></i></a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i
                                                            class="fa fa-dashcube"></i>
                                                </div>
                                                <div class="title m-b-15">@lang('messages.noDocsFound')
                                                </div>
                                                <div class="subtitle">
                                                    <button onclick="showAdd()" type="button" class="btn btn-info">
                                                        <i class="fa fa-plus"></i>
                                                        @lang('app.add')
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
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
    // Show Create employeeDocs Modal
    function showAdd() {
        var url = "{{ route('admin.employees.docs-create', [$employee->id]) }}";
        $.ajaxModal('#edit-column-form', url);
    }
    showTable();

    $('#edit-leave-type').click(function () {
        var url = "{{ route('admin.employees.leaveTypeEdit', [$employee->id]) }}";
        $.ajaxModal('#edit-column-form', url);
    })

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

                var url = "{{ route('admin.employee-docs.destroy',':id') }}";
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

    $('#leave-table').dataTable({
        responsive: true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0, 'width': '20%' },
            { responsivePriority: 2, targets: 1, 'width': '20%' }
        ],
        "autoWidth" : false,
        searching: false,
        paging: false,
        info: false
    });

    var table;

    function showTable() {
        if ($('#hide-completed-tasks').is(':checked')) {
            var hideCompleted = '1';
        } else {
            var hideCompleted = '0';
        }

        var url = '{{ route('admin.employees.tasks', [$employee->id, ':hideCompleted']) }}';
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
                { data: 'id', name: 'id' },
                {data: 'project_name', name: 'projects.project_name', width: '20%'},
                {data: 'heading', name: 'heading', width: '20%'},
                {data: 'due_date', name: 'due_date'},
                {data: 'column_name', name: 'taskboard_columns.column_name'},
            ]
        });
    }

    $('#hide-completed-tasks').click(function () {
        showTable();
    });

    $('#tasks-table').on('click', '.show-task-detail', function () {
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

<script>
    var table2;

    function showTable2(){

        var url = '{{ route('admin.employees.time-logs', [$employee->id]) }}';

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
                { data: 'DT_RowIndex', orderable: false, searchable: false },
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

