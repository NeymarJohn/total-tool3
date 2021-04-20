@extends('layouts.app')

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
                <li><a href="{{ route('admin.dashboard') }}">@lang("app.menu.home")</a></li>
                <li class="active">@lang($pageTitle)</li>
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
    <link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
    <style>
        #leave-report-table_wrapper .dt-buttons{
            display: none !important;
        }
    </style>
@endpush

@section('content')



    @section('filter-section')
        <div class="row">
            {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}

            <div class="col-md-12">
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

            <div class="col-md-12">
                <h5 class="box-title m-t-30">@lang('app.employee') @lang('app.name')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <select class="select2 form-control" data-placeholder="@lang('app.select') @lang('app.employee')" id="employee_id">
                                <option value="">@lang('app.all')</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <button type="button" class="btn btn-success" id="filter-results"><i class="fa fa-check"></i> @lang('app.apply')
                </button>

                <button type="button" id="reset-filters"
                                            class="btn btn-inverse "><i
                                                class="fa fa-refresh"></i> @lang('app.reset')</button>
            </div>
            {!! Form::close() !!}

        </div>
    @endsection

    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">

                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
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

@endsection

@push('footer-script')

    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

    {!! $dataTable->scripts() !!}
    <script>

        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#leave-report-table').on('preXhr.dt', function (e, settings, data) {
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

            data['startDate'] = startDate;
            data['endDate'] = endDate;
            data['employeeId'] = employeeId;
            data['_token'] = '{{ csrf_token() }}';
        });

        jQuery('#date-range').datepicker({
            toggleActive: true,
            format: '{{ $global->date_picker_format }}',
        });
        // loadTable();
        $('#filter-results').click(function () {
            loadTable();
        });

        $('#reset-filters').click(function () {
            $('#storePayments')[0].reset();
            $('.select2').val('');
            $('.select2').trigger('change');
            
            loadTable();
        })


        function loadTable(){
            window.LaravelDataTables["leave-report-table"].draw();
        }

        $('#leave-report-table').on( 'click', '.view-approve', function (event) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            event.preventDefault();
            var id = $(this).data('pk');
            var url = '{{ route('admin.leave-report.show', ':id') }}?startDate=' + encodeURIComponent(startDate) + '&endDate=' + encodeURIComponent(endDate);
            url = url.replace(':id', id);
            $.ajaxModal('#leave-details', url);
        });

        $('#leave-report-table').on( 'click', '.view-pending', function (event) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }
            event.preventDefault();
            var id = $(this).data('pk');
            var url = '{{ route('admin.leave-report.pending-leaves', ':id') }}?startDate=' + encodeURIComponent(startDate) + '&endDate=' + encodeURIComponent(endDate);
            url = url.replace(':id', id);
            $.ajaxModal('#leave-details', url);
        });

        $('#leave-report-table').on( 'click', '.view-upcoming', function (event) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }
            event.preventDefault();
            var id = $(this).data('pk');
            var url = '{{ route('admin.leave-report.upcoming-leaves', ':id') }}?startDate=' + encodeURIComponent(startDate) + '&endDate=' + encodeURIComponent(endDate);
            url = url.replace(':id', id);
            $.ajaxModal('#leave-details', url);
        });


        $('#leave-report-table').on( 'click', '.exportUserData', function (event) {

            var id = $(this).data('pk');
            var startDate = $('#start-date').val();
            if(startDate == ''){
                startDate = null;
            } else {
                startDate = moment(startDate, "{{ strtoupper($global->date_picker_format) }}").format('YYYY-MM-DD');
            }

            var endDate = $('#end-date').val();

            if(endDate == ''){
                endDate = null;
            } else {
                endDate = moment(endDate, "{{ strtoupper($global->date_picker_format) }}").format('YYYY-MM-DD');
            }

            var projectID = $('#project_id').val();

            if(projectID == ''){
                projectID = 0;
            }

            var url = '{{ route('admin.leave-report.export', [ ':id', ':startDate', ':endDate']) }}';
            url = url.replace(':startDate', startDate);
            url = url.replace(':endDate', endDate);
            url = url.replace(':id', id);

            window.location.href = url;
        });
    </script>
@endpush
