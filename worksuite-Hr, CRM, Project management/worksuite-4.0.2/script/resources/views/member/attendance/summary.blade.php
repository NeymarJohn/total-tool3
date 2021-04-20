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

            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
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
@endpush

@section('content')
    <div class="row">

        <div class="sttabs tabs-style-line col-md-12">
            <div class="white-box">
                <nav>
                    <ul>
                        <li class="tab-current"><a href="{{ route('member.attendances.summary') }}"><span>@lang('app.summary')</span></a>
                        </li>
                        <li><a href="{{ route('member.attendances.index') }}"><span>@lang('modules.attendance.attendanceByMember')</span></a>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- .row -->

    <div class="row">
        <div class="col-md-12">
            <div class="white-box p-b-0">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.timeLogs.employeeName')</label>
                            <select class="select2 form-control" data-placeholder="Choose Employee" id="user_id" name="user_id">
                                <option value="0">--</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">@lang('app.department')</label>
                            <select class="form-control select2" name="department" id="department" data-style="form-control">
                                <option value="all">@lang('modules.client.all')</option>
                                @forelse($departments as $department)
                                    <option value="{{$department->id}}">{{ ucfirst($department->team_name) }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">@lang('app.select') @lang('app.month')</label>
                            <select class="select2 form-control" data-placeholder="" id="month">
                                <option @if($month == '01') selected @endif value="01">@lang('app.january')</option>
                                <option @if($month == '02') selected @endif value="02">@lang('app.february')</option>
                                <option @if($month == '03') selected @endif value="03">@lang('app.march')</option>
                                <option @if($month == '04') selected @endif value="04">@lang('app.april')</option>
                                <option @if($month == '05') selected @endif value="05">@lang('app.may')</option>
                                <option @if($month == '06') selected @endif value="06">@lang('app.june')</option>
                                <option @if($month == '07') selected @endif value="07">@lang('app.july')</option>
                                <option @if($month == '08') selected @endif value="08">@lang('app.august')</option>
                                <option @if($month == '09') selected @endif value="09">@lang('app.september')</option>
                                <option @if($month == '10') selected @endif value="10">@lang('app.october')</option>
                                <option @if($month == '11') selected @endif value="11">@lang('app.november')</option>
                                <option @if($month == '12') selected @endif value="12">@lang('app.december')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">@lang('app.select') @lang('app.year')</label>
                            <select class="select2 form-control" data-placeholder="" id="year">
                                @for($i = $year; $i >= ($year-4); $i--)
                                    <option @if($i == $year) selected @endif value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group m-t-20">
                            <button type="button" id="apply-filter" class="btn btn-success btn-block">@lang('app.apply')</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>

    <div class="row">
        <div class="col-md-12" id="attendance-data"></div>
    </div>
    {{--Timer Modal--}}
    <div class="modal fade bs-modal-lg in" id="attendanceModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    {{--Timer Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

<script>
    
    $('#apply-filter').click(function () {
       showTable();
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    function showTable() {

        var year = $('#year').val();
        var month = $('#month').val();



        var userId = $('#user_id').val();
        var department = $('#department').val();
      
        //refresh counts
        var url = '{!!  route('member.attendances.summaryData') !!}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': token,
                year: year,
                month: month,
                department: department,
                userId: userId
            },
            url: url,
            success: function (response) {
               $('#attendance-data').html(response.data);
            }
        });

    }

    showTable();

    $('#attendance-data').on('click', '.view-attendance',function () {
        var attendanceID = $(this).data('attendance-id');
        var url = '{!! route('member.attendances.info', ':attendanceID') !!}';
        url = url.replace(':attendanceID', attendanceID);

        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });


    $('#attendance-data').on('click', '.edit-attendance',function (event) {
        var attendanceDate = $(this).data('attendance-date');
        var userData       = $(this).closest('tr').children('td:first');
        var userID         = $(this).data('user-id');
        var year           = $('#year').val();
        var month          = $('#month').val();

        var url = '{!! route('member.attendances.mark', [':userid',':day',':month',':year',]) !!}';
            url = url.replace(':userid', userID);
            url = url.replace(':day', attendanceDate);
            url = url.replace(':month', month);
            url = url.replace(':year', year);

        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });

    function editAttendance (id) {
        $('#projectTimerModal').modal('hide');

        var url = '{!! route('member.attendances.edit', [':id']) !!}';
        url = url.replace(':id', id);

        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#attendanceModal', url);
    }
</script>
@endpush