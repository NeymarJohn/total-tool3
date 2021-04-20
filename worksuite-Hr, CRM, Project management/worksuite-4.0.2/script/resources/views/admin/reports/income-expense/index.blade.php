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
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">

<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">
@endpush

@section('content')

    @section('filter-section')
        <div class="row">
            {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
            <div class="col-md-12">
                <div class="example">
                    <h5 class="box-title">@lang('app.selectDateRange')</h5>

                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                            value="{{ $fromDate->format($global->date_format) }}"/>
                        <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                        <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                            value="{{ $toDate->format($global->date_format) }}"/>
                    </div>
                </div>
            </div>

            <div class="col-md-12 m-t-20">
                <h5 class="box-title">@lang('app.select') @lang('app.duration')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <select class="select2 form-control" data-placeholder="@lang('app.duration')" id="duration">
                                <option value="1">@lang('app.last') 30 @lang('app.days')</option>
                                <option value="3">@lang('app.last') 3 @lang('app.month')</option>
                                <option value="6">@lang('app.last') 6 @lang('app.month')</option>
                                <option value="12">@lang('app.last') 1 @lang('app.year')</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success" id="filter-results"><i class="fa fa-check"></i> @lang('app.apply')
                            </button>
                            <button type="button" id="reset-filters" class="btn btn-inverse"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    @endsection

    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <div class="col-md-6  text-center">
                    <h4><span class="text-info">{{ $global->currency->currency_symbol }}</span><span class="text-info" id="total-incomes">{{ $totalIncomes }}</span> <span class="font-12 text-muted m-l-5">@lang("modules.incomeVsExpenseReport.totalIncome")</span></h4>
                </div>
                <div class="col-md-6 b-l text-center">
                    <h4><span class="text-danger">{{ $global->currency->currency_symbol }}</span><span class="text-danger" id="total-expenses">{{ $totalExpenses }}</span> <span class="font-12 text-muted m-l-5"> @lang("modules.incomeVsExpenseReport.totalExpense")</span></h4>
                </div>
            </div>
        </div>

    </div>

 
    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
                <h3 class="box-title">@lang("modules.incomeVsExpenseReport.chartTitle")</h3>
                <div>
                    <div id="bar-chart" height="100"></div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('footer-script')


<script src="{{ asset('plugins/bower_components/Chart.js/Chart.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>

<script>

    var barGraph;

    $(function () {
        barChart({!! json_encode($graphData) !!});
        initConter();
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: '{{ $global->date_picker_format }}',
    });

    function populateChart() {
        var token = '{{ csrf_token() }}';
        var url = '{{ route('admin.income-expense-report.store') }}';

        var startDate = $('#start-date').val();

        if (startDate == '') {
            startDate = null;
        }

        var endDate = $('#end-date').val();

        if (endDate == '') {
            endDate = null;
        }

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {_token: token, startDate: startDate, endDate: endDate},
            success: function (response) {

                $('#total-incomes').html(response.totalIncomes);
                $('#total-expenses').html(response.totalExpenses);

                $('#bar-chart').empty();
                barChart(response.graphData);
                initConter();
            }
        });
    }

    function barChart(graphData) {
        barGraph = Morris.Bar({
            element: 'bar-chart',
            data: graphData,
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Income', 'Expense'],
            barColors:['#b8edf0', '#fcc9ba'],
            hideHover: 'auto',
            gridLineColor: '#eef0f2',
            resize: true
        });
    }

    function initConter() {
        $(".counter").counterUp({
            delay: 100,
            time: 1200
        });
    }

    $('#duration').on('change', function () {
        var month = this.value;

        var end_date = moment().format('YYYY-MM-DD');
        var start_date = moment().subtract('month', month).format('YYYY-MM-DD');

        $('#start-date').val(start_date);
        $('#end-date').val(end_date);
    });

    $('#filter-results').click(function () {
        populateChart();
    })

    $('#reset-filters').click(function () {
        $('.select2').val('1');
        $('.select2').trigger('change');
        
        populateChart();
    })
</script>
@endpush