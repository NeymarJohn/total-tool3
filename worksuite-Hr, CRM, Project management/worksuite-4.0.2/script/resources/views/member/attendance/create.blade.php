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
                <li><a href="{{ route('member.attendances.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.attendance.markAttendance')</li>
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

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="attendancesDetailsModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    checkHoliday();
    jQuery('#attendance_date').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true,
        endDate: '+0d'
    }).on('changeDate', function (ev) {
        checkHoliday();
    });

    function checkHoliday() {
        var selectedDate = $('#attendance_date').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('member.attendances.check-holiday')}}',
            type: "GET",
            data: {
                date: selectedDate,
                _token: token
            },
            success: function (response) {
                if(response.status == 'success'){
                    if(response.holiday != null){
                        $('#holidayBox').show();
                        $('#tableBox').hide();
                        $('#holidayReason').html(response.holiday.occassion);
                    }else{
                        $('#holidayBox').hide();
                        $('#tableBox').show();
                        showTable();
                    }

                }
            }
        })
    }

    var table;

    function showTable(){
        table = $('#attendance-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{!! route('member.attendances.data') !!}",
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
            url: '{{route('member.attendances.storeAttendance')}}',
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
    })

    // Attendance Detail
   function attendanceDetail(id,attendanceDate) {
       var url = "{{ route('member.attendances.detail') }}?userID="+id+"&date="+attendanceDate;

        $('#modelHeading').html('@lang('modules.attendance.attendanceDetail')');
        $.ajaxModal('#attendancesDetailsModal',url);
    }


</script>
@endpush