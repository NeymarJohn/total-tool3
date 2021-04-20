@extends('layouts.client-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span
                        class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('client.projects.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.menu.timeLogs')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <section>
                <div class="sttabs tabs-style-line">
                    @include('client.projects.show_project_menu')
                    
                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-xs-12" id="issues-list-panel">
                                    <div class="white-box">
                                        <h2>@lang('app.menu.timeLogs')</h2>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                {!! Form::open(['id'=>'logTime','class'=>'ajax-form hide','method'=>'POST']) !!}

                                                {!! Form::hidden('project_id', $project->id) !!}

                                                <div class="form-body">
                                                    <div class="row m-t-30">
                                                        <div class="col-md-3 ">
                                                            <div class="form-group">
                                                                <label>@lang('modules.timeLogs.employeeName')</label>
                                                                <select class="selectpicker form-control" name="user_id" id="user_id" data-style="form-control">
                                                                    @forelse($project->members as $member)
                                                                        <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                                                    @empty
                                                                        <option value="">@lang('messages.noMemberAddedToProject')</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 ">
                                                            <div class="form-group">
                                                                <label>@lang('modules.timeLogs.noMemberAddedToProject')</label>
                                                                <input id="start_date" name="start_date" type="text" class="form-control" value="{{ \Carbon\Carbon::today()->format($global->date_format) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 ">
                                                            <div class="form-group">
                                                                <label>@lang('modules.timeLogs.endDate')</label>
                                                                <input id="end_date" name="end_date" type="text" class="form-control" value="{{ \Carbon\Carbon::today()->format($global->date_format) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="input-group bootstrap-timepicker timepicker">
                                                                <label>@lang('modules.timeLogs.startTime')</label>
                                                                <input type="text" name="start_time" id="start_time"  class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="input-group bootstrap-timepicker timepicker">
                                                                <label>@lang('modules.timeLogs.endTime')</label>
                                                                <input type="text" name="end_time" id="end_time" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">@lang('modules.timeLogs.totalTime')</label>
                                                            <p id="total_time" class="form-control-static">0 Hrs</p>
                                                        </div>
                                                    </div>

                                                    <div class="row m-t-20">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="memo">@lang('modules.timeLogs.memo')</label>
                                                                <input type="text" name="memo" id="memo" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-30">
                                                    <button type="button" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
                                                </div>
                                                {!! Form::close() !!}

                                                <hr>
                                            </div>
                                        </div>

                                        <div class="table-responsive m-t-30">
                                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="timelog-table">
                                                <thead>
                                                <tr>
                                                    <th>@lang('app.id')</th>
                                                    <th>@lang('modules.timeLogs.whoLogged')</th>
                                                    <th>@lang('modules.timeLogs.whoLogged')</th>
                                                    <th>@lang('modules.timeLogs.endTime')</th>
                                                    <th>@lang('modules.timeLogs.totalHours')</th>
                                                    <th>@lang('modules.timeLogs.memo')</th>
                                                    <th>@lang('modules.timeLogs.lastUpdatedBy')</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </section>

                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')


<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>

<script>
    var table = $('#timelog-table').dataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('client.time-log.data', $project->id) !!}',
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
            { data: 'user_id', name: 'user_id' },
            { data: 'start_time', name: 'start_time' },
            { data: 'end_time', name: 'end_time' },
            { data: 'total_hours', name: 'total_hours' },
            { data: 'memo', name: 'memo' },
            { data: 'edited_by_user', name: 'edited_by_user' }
        ]
    });

    $('ul.showProjectTabs .projectTimelogs').addClass('tab-current');
</script>
@endpush
