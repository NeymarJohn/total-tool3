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
            <a href="javascript:;"  class="btn btn-outline btn-success btn-sm pinnedItem">@lang('app.pinnedItem') <i class="icon-pin icon-2"></i></a>
        @if($user->can('add_projects'))
                <a href="{{ route('member.project-template.index') }}"  class="btn btn-outline btn-primary btn-sm">@lang('app.menu.addProjectTemplate') <i class="fa fa-plus" aria-hidden="true"></i></a>
                <a href="{{ route('member.projects.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.projects.addNewProject') <i class="fa fa-plus" aria-hidden="true"></i></a>
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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">

<style>
        .custom-action a {
            margin-right: 15px;
            margin-bottom: 15px;
        }
        .custom-action a:last-child {
            margin-right: 0px;
            float: right;
        }

        .dashboard-stats .white-box .list-inline {
            margin-bottom: 0;
        }

        .dashboard-stats .white-box {
            padding: 10px;
        }

        .dashboard-stats .white-box .box-title {
            font-size: 13px;
            text-transform: capitalize;
            font-weight: 300;
        }

        @media all and (max-width: 767px) {
            .custom-action a {
                margin-right: 0px;
            }

            .custom-action a:last-child {
                margin-right: 0px;
                float: none;
            }
        }
    </style>
@endpush

@section('content')

    <div class="row dashboard-stats">
        <div class="col-md-12 m-t-20">
            <div class="white-box">
                <div class="col-md-4">
                    <h4><span class="text-dark" id="totalWorkingDays">{{ $totalProjects }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalProjects')</span></h4>
                </div>
                <div class="col-md-4">
                    <h4><span class="text-danger" id="daysPresent">{{ $overdueProjects }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.tickets.overDueProjects')</span></h4>
                </div>
                <div class="col-md-4">
                    <h4><span class="text-warning" id="daysLate">{{ $notStartedProjects }}</span> <span class="font-12 text-muted m-l-5"> @lang('app.notStarted') @lang('app.menu.projects')</span></h4>
                </div>
                <div class="col-md-4">
                    <h4><span class="text-success" id="halfDays">{{ $finishedProjects }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.tickets.completedProjects')</span></h4>
                </div>
                <div class="col-md-4">
                    <h4><span class="text-info" id="absentDays">{{ $inProcessProjects }}</span> <span class="font-12 text-muted m-l-5"> @lang('app.inProgress') @lang('app.menu.projects')</span></h4>
                </div>
                <div class="col-md-4">
                    <h4><span class="text-primary" id="holidayDays">{{ $canceledProjects }}</span> <span class="font-12 text-muted m-l-5">@lang('app.canceled') @lang('app.menu.projects')</span></h4>
                </div>
            </div>
        </div>

    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="white-box">

                @if($user->can('view_projects'))
                    @section('filter-section')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">@lang('app.menu.projects') @lang('app.status')</label>
                                        <select class="select2 form-control" data-placeholder="@lang('app.menu.projects') @lang('app.status')" id="status">
                                            <option selected value="all">@lang('app.all')</option>
                                            <option
                                                value="not started">@lang('app.notStarted')
                                            </option>
                                            <option
                                                value="in progress">@lang('app.inProgress')
                                            </option>
                                            <option
                                                value="on hold">@lang('app.onHold')
                                            </option>
                                            <option
                                                value="canceled">@lang('app.canceled')
                                            </option>
                                            <option
                                                value="finished">@lang('app.finished')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">@lang('app.clientName')</label>
                                        <select class="select2 form-control" data-placeholder="@lang('app.clientName')" id="client_id">
                                            <option selected value="all">@lang('app.all')</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">@lang('app.menu.teams')</label>
                                <select class="select2 form-control" data-placeholder="@lang('app.menu.teams')" id="team_id">
                                    <option selected value="all">@lang('app.all')</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @endsection
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="project-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('modules.projects.projectName')</th>
                            <th>@lang('modules.projects.projectMembers')</th>
                            <th>@lang('modules.projects.deadline')</th>
                            <th>@lang('app.completion')</th>
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
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
<script>
    var table;
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    $('.select2').val('all');
    $(function() {
        showData();
        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('user-id');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.projectText')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('member.projects.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

        $('#createProject').click(function(){
            var url = '{{ route('admin.projectCategory.create')}}';
            $('#modelHeading').html('...');
            $.ajaxModal('#projectCategoryModal',url);
        })

    });

    function showData(){
        var status = "";
        var clientID = "";
        var teamID = "";

        if($('#status').length){
            status = $('#status').val();
        }

        if($('#client_id').length){
            clientID = $('#client_id').val();
        }

        if($('#team_id').length){
            teamID = $('#team_id').val();
        }

        var searchQuery = "?status="+status+"&client_id="+clientID+"&team_id="+teamID;

        table = $('#project-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: '{!! route('member.projects.data') !!}'+searchQuery,
            deferRender: true,
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'project_name', name: 'project_name'},
                { data: 'members', name: 'members' },
                { data: 'deadline', name: 'deadline' },
                { data: 'completion_percent', name: 'completion_percent' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ]
        });
    }

    $('#status').on('change', function(event) {
        event.preventDefault();
        showData();
    });

    $('#client_id').on('change', function(event) {
        event.preventDefault();
        showData();
    });

    $('#team_id').on('change', function(event) {
        event.preventDefault();
        showData();
    });
    $('.pinnedItem').click(function(){
        var url = '{{ route('member.projects.pinned-project')}}';
        $('#modelHeading').html('Pinned Project');
        $.ajaxModal('#projectCategoryModal',url);
    })
</script>
@endpush
