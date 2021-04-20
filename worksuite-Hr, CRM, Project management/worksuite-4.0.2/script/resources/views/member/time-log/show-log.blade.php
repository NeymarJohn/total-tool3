@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.tasks')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">

<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    @include('member.projects.show_project_menu')


                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-md-12" id="issues-list-panel">
                                    <div class="white-box">

                                        @if($user->can('add_timelogs'))
                                        <div class="row m-b-10">
                                            <div class="col-md-12">
                                                <a href="javascript:;" id="show-add-form"
                                                   class="btn btn-success btn-outline"><i
                                                            class="fa fa-clock-o"></i> @lang('modules.timeLogs.logTime')
                                                </a>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::open(['id'=>'logTime','class'=>'ajax-form hide','method'=>'POST']) !!}
                                                {!! Form::hidden('project_id', $project->id) !!}

                                                <div class="form-body">
                                                    <div class="row m-t-30">
                                                        <div class="col-md-3 ">
                                                            <div class="form-group">
                                                                <label>@lang('modules.timeLogs.task')</label>
                                                                <select class="selectpicker form-control" name="task_id"
                                                                        id="task_id" data-style="form-control">
                                                                    @forelse($tasks as $task)
                                                                        <option value="{{ $task->id }}">{{ ucfirst($task->heading) }}</option>
                                                                    @empty
                                                                        <option value="">@lang('messages.noTaskAddedToProject')</option>
                                                                    @endforelse
                                                                </select>
                                                               
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 ">
                                                            <div class="form-group">
                                                                <label>@lang('modules.timeLogs.employeeName')</label>
                                                                <select class="selectpicker form-control" name="user_id"
                                                                        id="user_id" data-style="form-control">
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
                                                                <label>@lang('modules.timeLogs.startDate')</label>
                                                                <input id="start_date" name="start_date" type="text"
                                                                       class="form-control"
                                                                       value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 ">
                                                            <div class="form-group">
                                                                <label>@lang('modules.timeLogs.endDate')</label>
                                                                <input id="end_date" name="end_date" type="text"
                                                                       class="form-control"
                                                                       value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="input-group bootstrap-timepicker timepicker">
                                                                <label>@lang('modules.timeLogs.startTime')</label>
                                                                <input type="text" name="start_time" id="start_time"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="input-group bootstrap-timepicker timepicker">
                                                                <label>@lang('modules.timeLogs.endTime')</label>
                                                                <input type="text" name="end_time" id="end_time"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">@lang('modules.timeLogs.totalHours')</label>

                                                            <p id="total_time" class="form-control-static">0 Hrs</p>
                                                        </div>
                                                    </div>

                                                    <div class="row m-t-20">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label class="required" for="memo">@lang('modules.timeLogs.memo')</label>
                                                                <input type="text" name="memo" id="memo"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-30">
                                                    <button type="button" id="save-form" class="btn btn-success"><i
                                                                class="fa fa-check"></i> @lang('app.save')</button>
                                                </div>
                                                {!! Form::close() !!}

                                            </div>
                                        </div>

                                        <div class="table-responsive m-t-30">
                                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="timelog-table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('modules.timeLogs.whoLogged')</th>
                                                    <th>@lang('modules.timeLogs.startTime')</th>
                                                    <th>@lang('modules.timeLogs.endTime')</th>
                                                    <th>@lang('modules.timeLogs.totalHours')</th>
                                                    <th>@lang('modules.timeLogs.memo')</th>
                                                    <th>@lang('modules.timeLogs.lastUpdatedBy')</th>
                                                    <th>@lang('app.action')</th>
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

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="editTimeLogModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

<script>
    var table = $('#timelog-table').dataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('member.time-log.data', $project->id) !!}',
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
            { data: 'user_id', name: 'user_id' },
            { data: 'start_time', name: 'start_time' },
            { data: 'end_time', name: 'end_time' },
            { data: 'total_hours', name: 'total_hours' },
            { data: 'memo', name: 'memo' },
            { data: 'edited_by_user', name: 'edited_by_user' },
            {data: 'action', name: 'action'}
        ]
    });

    $('#start_time, #end_time').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false,
        @endif
    }).on('hide.timepicker', function (e) {
//        console.log('The time is ' + e.time.value);
//        console.log('The hour is ' + e.time.hours);
//        console.log('The minute is ' + e.time.minutes);
//        console.log('The meridian is ' + e.time.meridian);
        calculateTime();
    });

    jQuery('#start_date, #end_date').datepicker({
        autoclose: true,
        todayHighlight: true
    }).on('hide', function (e) {
        calculateTime();
    });

    function calculateTime() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var startTime = $("#start_time").val();
        var endTime = $("#end_time").val();

        var timeStart = new Date(startDate + " " + startTime);
        var timeEnd = new Date(endDate + " " + endTime);

        var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds

        var minutes = diff % 60;
        var hours = (diff - minutes) / 60;

        if (hours < 0 || minutes < 0) {
            var numberOfDaysToAdd = 1;
            timeEnd.setDate(timeEnd.getDate() + numberOfDaysToAdd);
            var dd = timeEnd.getDate();

            if (dd < 10) {
                dd = "0" + dd;
            }

            var mm = timeEnd.getMonth() + 1;

            if (mm < 10) {
                mm = "0" + mm;
            }

            var y = timeEnd.getFullYear();

            $('#end_date').val(mm + '/' + dd + '/' + y);
            calculateTime();
        } else {
            $('#total_time').html(hours + "Hrs " + minutes + "Mins");
        }

//        console.log(hours+" "+minutes);
    }

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.time-log.store-time-log')}}',
            container: '#logTime',
            type: "POST",
            data: $('#logTime').serialize(),
            success: function (data) {
                if (data.status == 'success') {
                    table._fnDraw();
                }
            }
        })
    });

    $('#show-add-form').click(function () {
        $('#logTime').toggleClass('hide', 'show');
    });

    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('time-id');
        swal({
            title: "lang('messages.sweetAlertTitle')",
            text: "lang('messages.deleteTimeText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('member.all-time-logs.destroy',':id') }}";
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

    $('body').on('click', '.edit-time-log', function () {
        var id = $(this).data('time-id');

        var url = '{{ route('member.time-log.edit', ':id')}}';
        url = url.replace(':id', id);

        $('#modelHeading').html('Update Time Log');
        $.ajaxModal('#editTimeLogModal',url);

    });
    $('ul.showProjectTabs .projectTimelogs').addClass('tab-current');


</script>
@endpush
