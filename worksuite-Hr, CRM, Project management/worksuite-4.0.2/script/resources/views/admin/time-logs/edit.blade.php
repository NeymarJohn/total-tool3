@extends('layouts.app')

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
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.employees.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('app.update') @lang('app.menu.timeLogs')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateTimeLogs','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('app.project') @lang('app.name')</label>
                                        <div>{{ $timeLogs->project->project_name }}</div>
                                        <input name="project_id" type="hidden" value="{{ $timeLogs->project->id }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('app.employee') @lang('app.name')</label>
                                        <div>{{ $timeLogs->user->name }}</div>
                                        <input name="user_id" type="hidden" value="{{ $timeLogs->user->id }}">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('modules.timeLogs.startDate')</label>
                                        <input type="text" name="start_date" id="start_date"
                                               value="{{ $timeLogs->start_time->format('m/d/Y') }}"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('modules.timeLogs.endDate')</label>
                                        <input type="text" name="end_date" id="end_date"
                                               value="{{ $timeLogs->end_time->format('m/d/Y') }}"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.timeLogs.startTime')</label>
                                        <input type="text" name="start_time" id="start_time"
                                               class="form-control" value="{{ $timeLogs->start_time->format('H:i') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.timeLogs.endTime')</label>
                                        <input type="text" name="end_time" id="end_time"
                                               class="form-control" value="{{ $timeLogs->end_time->format('H:i') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('modules.timeLogs.totalHours')</label>
                                        <input type="text" name="total_hours" id="total_hours"
                                               value="{{ $timeLogs->total_hours }}"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('modules.timeLogs.memo')</label>
                                        <input type="text" name="memo" id="memo"
                                               value="{{ $timeLogs->memo }}"
                                               class="form-control">
                                    </div>
                                </div>

                            </div>
                            <!--/row-->


                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"><i
                                        class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('admin.all-time-logs.index') }}" class="btn btn-default">@lang('app.back')</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

    <script>
        $("#end_date, #start_date, .date-picker").datepicker({
            todayHighlight: true,
            autoclose: true
        }).on('hide', function (e) {
            calculateTime();
        });

        $('#start_time, #end_time').timepicker({
            @if($global->time_format == 'H:i')
            showMeridian: false,
            @endif
        }).on('hide.timepicker', function (e) {
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
                $('#total_hours').html(hours + "Hrs " + minutes + "Mins");
            }
        }

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.all-time-logs.update', [$timeLogs->id])}}',
                container: '#updateTimeLogs',
                type: "POST",
                redirect: true,
                data: $('#updateTimeLogs').serialize()
            })
        });
    </script>
@endpush

