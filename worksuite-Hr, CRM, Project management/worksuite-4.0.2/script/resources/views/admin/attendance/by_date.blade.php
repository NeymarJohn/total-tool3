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
            <a href="{{ route('admin.attendances.create') }}"
            class="btn btn-success btn-sm btn-outline pull-right">@lang('modules.attendance.markAttendance') <i class="fa fa-plus"
                                                                                            aria-hidden="true"></i></a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active"><a href="{{ route('admin.attendances.index') }}">@lang($pageTitle)</a></li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush

@section('content')

    <div class="row">

        <div class="sttabs tabs-style-line col-md-12">
            <div class="white-box">
                <nav>
                    <ul>
                        <li><a href="{{ route('admin.attendances.summary') }}"><span>@lang('app.summary')</span></a>
                        </li>
                        <li><a href="{{ route('admin.attendances.index') }}"><span>@lang('modules.attendance.attendanceByMember')</span></a>
                        </li>
                        <li class="tab-current"><a href="{{ route('admin.attendances.attendanceByDate') }}"><span>@lang('modules.attendance.attendanceByDate')</span></a>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>

        <div class="col-md-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('app.menu.attendance') @lang('app.date')</label>
                            <input type="text" class="form-control" name="attendance_date" id="attendance_date" value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                        </div>
                    </div>
                </div>
                
                <div class="row dashboard-stats">
                    <div class="col-md-12 m-b-30">
                        <div class="white-box">
                            <div class="col-sm-4 text-center">
                                <h4><span class="text-info" id="totalEmployees">0</span> <span class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalEmployees')</span></h4>
                            </div>
                            <div class="col-sm-4 b-l text-center">
                                <h4><span class="text-success" id="employeesPresent">0</span> <span class="font-12 text-muted m-l-5"> @lang('modules.attendance.present')</span></h4>
                            </div>
                            <div class="col-sm-4 b-l text-center">
                                <h4><span class="text-danger" id="employeesAbsent">0</span> <span class="font-12 text-muted m-l-5"> @lang('app.days') @lang('modules.attendance.absent')</span></h4>
                            </div>
                            
                        </div>
                    </div>
                
                </div>

                <div class="row" id="tableBox">
                    <table  id="attendance-table">

                    </table>
                </div>
                <div id="holidayBox" style="display: none">
                    <div class="alert alert-primary"> @lang('modules.attendance.holidayfor') <span id="holidayReason"> </span>. </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

    <script>

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        var currentDate = $('#attendance_date').val();

        if(currentDate){
            showTable();
            getAttendanceCount(currentDate);
        }

        jQuery('#attendance_date').datepicker({
            format: '{{ $global->date_picker_format }}',
            autoclose: true,
            todayHighlight: true,
            endDate: '+0d'
        }).on('changeDate', function (ev) {
            var attendanceDate = $('#attendance_date').val();
            getAttendanceCount(attendanceDate);
        });

        var table;

        function showTable(){
            table = $('#attendance-table').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                // searching: false,
                ajax: {
                    url: "{!! route('admin.attendances.byDateData') !!}",
                    data: function (d) {
                        d.date = $('#attendance_date').val();
                    }
                },
                "bStateSave": true,
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },

                columns: [
                    { data: 'id', name: 'id', width:50 }],
                "fnDrawCallback": function (oSettings) {
                    $(oSettings.nTHead).hide();
                    $('.a-timepicker').timepicker({
                        @if($global->time_format == 'H:i')
                        showMeridian: false,
                        @endif
                        minuteStep: 1
                    });
                    $('.b-timepicker').timepicker({
                        @if($global->time_format == 'H:i')
                        showMeridian: false,
                        @endif
                        minuteStep: 1,
                        defaultTime: false
                    });

                    $('#attendance-table_wrapper').removeClass( 'form-inline' );

                    // Switchery
                    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                    $('.js-switch').each(function() {
                        new Switchery($(this)[0], $(this).data());

                    });



                },
                "destroy" : true
            });
        }

        $('#attendance-table').on('click', '.save-attendance', function () {
            var userId = $(this).data('user-id');
            var clockInTime = $('#clock-in-'+userId).val();
            var clockInIp = $('#clock-in-ip-'+userId).val();
            var clockOutTime = $('#clock-out-'+userId).val();
            var clockOutIp = $('#clock-out-ip-'+userId).val();
            var workingFrom = $('#working-from-'+userId).val();
            var date = $('#attendance_date').val();

            var late = 'no';
            if($('#late-'+userId).is(':checked')){
                late = 'yes';
            }
            var halfDay = 'no';
            if($('#halfday-'+userId).is(':checked')){
                halfDay = 'yes';
            }
            var token = "{{ csrf_token() }}";

            $.easyAjax({
                url: '{{route('admin.attendances.store')}}',
                type: "POST",
                container: '#attendance-container-'+userId,
                data: {
                    user_id: userId,
                    clock_in_time: clockInTime,
                    clock_in_ip: clockInIp,
                    clock_out_time: clockOutTime,
                    clock_out_ip: clockOutIp,
                    late: late,
                    half_day: halfDay,
                    working_from: workingFrom,
                    date: date,
                    _token: token
                },
                success: function (response) {
                    if(response.status == 'success'){
                        showTable();
                    }
                }
            })
        });

        function getAttendanceCount(date){
            $.easyAjax({
                url: '{{route('admin.attendances.dateAttendanceCount')}}',
                type: "POST",
                data: {
                    date: date,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if(response.status == 'success'){
                        $('#totalEmployees').html(response.totalEmployees);

                        if(response.holiday == 1){
                            $('#presentBox').hide();
                            $('#absentBox').hide();
                            $('#holidayBox').show();
                            $('#tableBox').hide();
                            $('#holidayReason').html(response.holidayReason);
                        }
                        else{
                            showTable();
                            $('#employeesPresent').html(response.totalPresent);
                            $('#employeesAbsent').html(response.totalAbsent);
                            $('#presentBox').show();
                            $('#absentBox').show();
                            $('#holidayBox').hide();
                            $('#tableBox').show();

                        }
                    }
                }
            })

        }

    </script>
@endpush