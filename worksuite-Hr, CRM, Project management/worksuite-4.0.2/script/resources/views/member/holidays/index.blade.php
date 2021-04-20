@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) @lang('modules.holiday.listOf') {{ \Carbon\Carbon::now()->format('Y') }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="javascript:;" onclick="calendarData()" class="btn btn-outline btn-info btn-sm ">@lang('modules.holiday.viewOnCalendar') <i class="fa fa-calendar" aria-hidden="true"></i></a>

            @if($user->can('add_holiday'))
                <a class="btn btn-outline btn-sm btn-primary markHoliday" onclick="showMarkHoliday()" style="display: none">
                @lang('modules.holiday.markSunday')
                <i class="fa fa-check"></i> </a>
            @endif

            @if($user->can('add_holiday'))
                <a onclick="showAdd()" class="btn btn-outline btn-success btn-sm ">@lang('modules.holiday.addNewHoliday') <i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
           
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle) @lang('modules.holiday.listOf') {{ \Carbon\Carbon::now()->format('Y') }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')

@endpush

@section('content')

    <div class="row">

        <div class="col-md-12 panel-inverse">
            <div class="white-box">
               
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group col-md-2 pull-right">
                            <label class="control-label">@lang('app.select') @lang('app.year')</label>
                            <select onchange="showData()" class="select2 form-control" data-placeholder="@lang('app.menu.projects') @lang('app.status')" id="year">
                                @forelse($years as $yr)
                                    <option @if($yr == $year) selected @endif value="{{ $yr }}">{{ $yr }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row" id="holidaySectionData">

                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
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
    <script>
        @if($number_of_sundays>$holidays_in_db)
        $('.markHoliday').show();
        @endif

        showData();
       // Delete Holiday
        function del(id, date) {

            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.deleteHolidayText')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('member.holidays.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                    });
                }
            });
        }
        // Show Create Holiday Modal
        function showAdd() {
            var url = "{{ route('member.holidays.create') }}";
            $.ajaxModal('#edit-column-form', url);
        }
        // Show Create Holiday Modal
        function showMarkHoliday() {
            var url = "{{ route('member.holidays.mark-holiday') }}";
            $.ajaxModal('#edit-column-form', url);
        }

        // Show Create Holiday Modal
        function calendarData() {
            var year = $('#year').val();
            var url = "{{ route('member.holidays.calendar', ':year') }}";
            url = url.replace(':year', year);
            window.location.href = url;
        }

        // Show Holiday
        function showData() {
            var year = $('#year').val();
            var url = "{{ route('member.holidays.view-holiday',':year') }}"
            url = url.replace(':year', year);
            $.easyAjax({
                type: 'GET',
                url: url,
                container: '#holidaySectionData',
                success: function (response) {
                    $('#holidaySectionData').html(response.view);
                    if(response.number_of_sundays > response.holidays_in_db){
                        $('.markHoliday').show();
                    }
                    else{
                        $('.markHoliday').hide();
                    }
                }
            });
        }

    </script>
@endpush