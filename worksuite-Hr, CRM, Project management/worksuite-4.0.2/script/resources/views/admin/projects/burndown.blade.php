@extends('layouts.app')

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
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projects.burndownChart')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <section>
                <div class="sttabs tabs-style-line">
                    @include('admin.projects.show_project_menu')
                    <div class="white-box">
                        <form action="" id="filter-form" class="m-b-20">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 >@lang('app.selectDateRange')</h5>
                                    <div class="input-daterange input-group" id="date-range">
                                        <input type="text" class="form-control" autocomplete="off" id="start-date" placeholder="@lang('app.startDate')"
                                               value=""/>
                                        <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                                        <input type="text" class="form-control" id="end-date" autocomplete="off" placeholder="@lang('app.endDate')"
                                               value=""/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group m-t-10">
                                        <label class="control-label col-xs-12">&nbsp;</label>
                                        <button type="button" id="apply-filters" class="btn btn-success col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                                        <button type="button" id="reset-filters" class="btn btn-inverse col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-md-12" id="task-list-panel">
                                    <div><canvas id="burndown43"></canvas></div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- .row -->
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    @if($global->locale == 'en')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.{{ $global->locale }}-AU.min.js"></script>
    @else
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.{{ $global->locale }}.min.js"></script>
    @endif
    <script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script>
        /**
         * Sum elements of an array up to the index provided.
         */

        function showBurnDown(elementId, burndownData, scopeChange = [], dates) {

            var speedCanvas = document.getElementById(elementId);

            Chart.defaults.global.defaultFontFamily = "Arial";
            Chart.defaults.global.defaultFontSize = 14;

            var speedData = {
                labels: JSON.parse(dates),
                datasets: [
                    {
                        label: "@lang('modules.burndown.actual')",
                        borderColor: "#6C8893",
                        backgroundColor: "#6C8893",
                        lineTension: 0,
                        borderDash: [5, 5],
                        fill: false,
                        data: scopeChange,
                        steppedLine: true
                    },
                    {
                        label: "@lang('modules.burndown.ideal')",
                        data: burndownData,
                        fill: false,
                        borderColor: "#ccc",
                        backgroundColor: "#ccc",
                        lineTension: 0,
                    },
                ]
            };

            var chartOptions = {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 80,
                        fontColor: 'black'
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: Math.round(burndownData[0] * 1.2)
                        }
                    }]
                },
                responsive: true
            };

            var lineChart = new Chart(speedCanvas, {
                type: 'line',
                data: speedData,
                options: chartOptions
            });

        }
    </script>
    <script>
        jQuery('#date-range').datepicker({
            toggleActive: true,
            format: '{{ $global->date_picker_format }}',
            language: '{{ $global->locale }}',
            autoclose: true,
            startDate: '{{ $startDate }}',
            endDate: '{{ $endDate }}',
        });
        function loadChart(){
            var startDate = $('#start-date').val();
            if (startDate == '') { startDate = null; }

            var endDate = $('#end-date').val();
            if (endDate == '') { endDate = null; }

            var token = "{{ csrf_token() }}";
            $.easyAjax({
                url: '{{route('admin.projects.burndown-chart', [$project->id])}}',
                container: '#section-line-3',
                type: "GET",
                redirect: false,
                data: {'_token': token, startDate: startDate, endDate: endDate},
                success: function (data) {
                    showBurnDown ("burndown43", JSON.parse(data.deadlineTasks), JSON.parse(data.uncompletedTasks), data.datesArray);
                }
            });
        }

        $('#apply-filters').click(function () {
            loadChart();
        });

        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            loadChart();
        });
        loadChart();

        $('ul.showProjectTabs .burndownChart').addClass('tab-current');
    </script>
@endpush

