@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} <span class="text-warning b-l p-l-10 m-l-5">{{ $pendingLeaves}}</span> <a href="{{ route('admin.leaves.pending') }}" class="font-12 text-muted m-l-5"> @lang('modules.leaves.pendingLeaves')</a></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-primary waves-effect waves-light m-l-10 btn-outline">
                <i class="fa fa-calendar"></i> @lang('modules.leaves.calendarView')
            </a>
            
            <a href="{{ route('admin.leaves.create') }}" class="btn btn-sm btn-success waves-effect waves-light m-l-10 btn-outline">
            <i class="ti-plus"></i> @lang('modules.leaves.assignLeave')</a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang("app.menu.home")</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
   
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
@endpush

@section('content')

 
    @section('filter-section')
        <div class="row m-b-10">
            {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}

            <div class="col-xs-12">
                <div class="example">
                    <h5 class="box-title m-t-30">@lang('app.selectDateRange')</h5>

                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                               value="{{ $fromDate->format($global->date_format) }}"/>
                        <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                        <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                               value="{{ $toDate->format($global->date_format) }}"/>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <h5 class="box-title m-t-30">@lang('app.employee') @lang('app.name')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <select class="select2 form-control" data-placeholder="@lang('app.select') @lang('app.employee')" id="employee_id">
                                <option value="">--</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <button type="button" class="btn btn-success" id="filter-results"><i class="fa fa-check"></i> @lang('app.apply')
                </button>
            </div>
            {!! Form::close() !!}

        </div>
    @endsection

    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
                <h3 class="box-title">@lang("app.menu.leaves")</h3>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                           id="leave-table">
                        <thead>
                        <tr>
                            <th>@lang('app.id')</th>
                            <th>@lang('app.employee')</th>
                            <th>@lang('app.menu.leaves') @lang('app.date')</th>
                            <th>@lang('app.menu.leaves') @lang('app.status')</th>
                            <th>@lang('app.leave_type')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>

    </div>



    <div class="modal fade bs-example-modal-lg" id="leave-details" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="eventDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
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

    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>

    <script>

        $('#leave-table').on('click', '.leave-action-reject', function () {
            console.log('test');
            var action = $(this).data('leave-action');
            var leaveId = $(this).data('leave-id');
            var searchQuery = "?leave_action="+action+"&leave_id="+leaveId;
            var url = '{!! route('admin.leaves.show-reject-modal') !!}'+searchQuery;
            $('#modelHeading').html('Reject Reason');
            $.ajaxModal('#eventDetailModal', url);
        });

        $('#leave-table').on('click', '.leave-action', function() {
            var action = $(this).data('leave-action');
            var leaveId = $(this).data('leave-id');
            var url = '{{ route("admin.leaves.leaveAction") }}';

            $.easyAjax({
                type: 'POST',
                url: url,
                data: { 'action': action, 'leaveId': leaveId, '_token': '{{ csrf_token() }}' },
                success: function (response) {
                    if(response.status == 'success'){
                        /*window.location.reload();*/
                        loadTable();
                    }
                }
            });

        })

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        jQuery('#date-range').datepicker({
            toggleActive: true,
            weekStart:'{{ $global->week_start }}',
            format: '{{ $global->date_picker_format }}',
        });
        loadTable();
        $('#filter-results').click(function () {
            loadTable();
        });


        function loadTable(){
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            var employeeId = $('#employee_id').val();
            if (!employeeId) {
                employeeId = 0;
            }

            var url = '{!!  route('admin.leave.data', [':employeeId']) !!}';

            url = url.replace(':employeeId', employeeId);

            var table = $('#leave-table').dataTable({
                responsive: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    "url": url,
                    "type": "POST",
                    data: function (d) {
                        d.startDate = startDate;
                        d.endDate = endDate;
                        d._token = '{{ csrf_token() }}';
                    }
                },
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
                    { data: 'employee', name: 'employee' },
                    { data: 'date', name: 'date' },
                    { data: 'status', name: 'status' },
                    { data: 'leave_type', name: 'leave_type' },
                    { data: 'action', name: 'action' }
                ]
            });

        }

        $('body').on('click', '.show-leave', function () {
            var leaveId = $(this).data('leave-id');

            var url = '{{ route('admin.leaves.show', ':id') }}';
            url = url.replace(':id', leaveId);

            $('#modelHeading').html('Leave Details');
            $.ajaxModal('#leave-details', url);
        });

        $('#pending-leaves').click(function() {
            window.location = '{{ route("admin.leaves.pending") }}';
        })
    </script>
@endpush