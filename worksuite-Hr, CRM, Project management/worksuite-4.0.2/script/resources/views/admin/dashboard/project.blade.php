@extends('layouts.app')

@push('head-script')
    <style>
        .list-group{
            margin-bottom:0px !important;
        }
    </style>
@endpush
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        
        <div class="col-lg-9 col-sm-4 col-md-4 col-xs-12">
            <div class="col-lg-12 col-md-12 pull-right hidden-xs hidden-sm">
                {!! Form::open(['id'=>'createProject','class'=>'ajax-form','method'=>'POST']) !!}
                {!! Form::hidden('dashboard_type', 'admin-client-dashboard') !!}
                <div class="btn-group dropdown keep-open pull-right m-l-10">
                    <button aria-expanded="true" data-toggle="dropdown"
                            class="btn bg-white b-all dropdown-toggle waves-effect waves-light"
                            type="button"><i class="icon-settings"></i>
                    </button>
                    <ul role="menu" class="dropdown-menu  dropdown-menu-right dashboard-settings">
                            <li class="b-b"><h4>@lang('modules.dashboard.dashboardWidgets')</h4></li>

                        @foreach ($widgets as $widget)
                            @php
                                $wname = \Illuminate\Support\Str::camel($widget->widget_name);
                            @endphp
                            <li>
                                <div class="checkbox checkbox-info ">
                                    <input id="{{ $widget->widget_name }}" name="{{ $widget->widget_name }}" value="true"
                                        @if ($widget->status)
                                            checked
                                        @endif
                                            type="checkbox">
                                    <label for="{{ $widget->widget_name }}">@lang('modules.dashboard.' . $wname)</label>
                                </div>
                            </li>
                        @endforeach

                        <li>
                            <button type="button" id="save-form" class="btn btn-success btn-sm btn-block">@lang('app.save')</button>
                        </li>

                    </ul>
                </div>
                {!! Form::close() !!}
                
                <select class="selectpicker language-switcher  pull-right" data-width="fit">
                    <option value="en" @if($global->locale == "en") selected @endif data-content='<span class="flag-icon flag-icon-gb" title="English"></span>'>En</option>
                    @foreach($languageSettings as $language)
                        <option value="{{ $language->language_code }}" @if($global->locale == $language->language_code) selected @endif  data-content='<span class="flag-icon flag-icon-{{ $language->language_code }}" title="{{ ucfirst($language->language_name) }}"></span>'>{{ $language->language_code }}</option>
                    @endforeach
                </select>
            </div>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>

        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}"><!--Owl carousel CSS -->
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.css') }}"><!--Owl carousel CSS -->
    <link rel="stylesheet"
          href="{{ asset('plugins/bower_components/owl.carousel/owl.theme.default.css') }}"><!--Owl carousel CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">
    <style>
        .col-in {padding: 0 20px !important;}
        .fc-event {font-size: 10px !important;}
        .dashboard-settings {padding-bottom: 8px !important;}
        .customChartCss { height: 100% !important; }
        .customChartCss svg { height: 400px; }
        @media (min-width: 769px) {
            #wrapper .panel-wrapper {height: 530px;overflow-y: auto;}
        }
    </style>
@endpush

@section('content')

    <div class="white-box">
        <div class="row">
            <div class="col-xs-12 m-b-10" style="display: flex;align-items: center;">
                <label style="font-size: 13px;margin-bottom: 0;margin-right: 10px;">@lang('app.selectDateRange')</label>
                <div class="input-daterange input-group" id="date-range" style="margin-right: 10px;">
                    <input type="text" class="form-control" id="start-date" placeholder="@lang('app.startDate')" style="width: 110px;"
                           value="{{ \Carbon\Carbon::parse($fromDate)->timezone($global->timezone)->format($global->date_format) }}"/>
                    <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                    <input type="text" class="form-control" id="end-date" placeholder="@lang('app.endDate')" style="width: 110px;"
                           value="{{ \Carbon\Carbon::parse($toDate)->timezone($global->timezone)->format($global->date_format) }}"/>
                </div>
                <button type="button" id="apply-filters" class="btn btn-success btn-sm"><i class="fa fa-check"></i> @lang('app.apply')</button>
            </div>
            
        </div>
    </div>

    <div class="white-box" id="dashboard-content">
             
    </div>

@endsection


@push('footer-script')
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>

{{-- {!! $dataTable->scripts() !!} --}}

<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>

<!--weather icon -->

<script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/jquery.fullcalendar.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/locale-all.js') }}"></script>
{{-- <script src="{{ asset('js/event-calendar.js') }}"></script> --}}
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>

    var startDate = '';
    var endDate = '';

    function getLatestDate(){
        startDate = $('#start-date').val();
        if (startDate == '') { startDate = null; }
        endDate = $('#end-date').val();
        if (endDate == '') { endDate = null; }

        startDate = encodeURIComponent(startDate);
        endDate = encodeURIComponent(endDate);
    }

    $(function() {
        jQuery('#date-range').datepicker({
            toggleActive: true,
            format: '{{ $global->date_picker_format }}',
            language: '{{ $global->locale }}',
            autoclose: true
        });
    });
    $('#apply-filters').click(function() {
        getLatestDate();
        loadData();
    })
    
    getLatestDate();
    loadData();

    $('.keep-open .dropdown-menu').on({
        "click":function(e){
        e.stopPropagation();
        }
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.dashboard.widget', "admin-project-dashboard")}}',
            container: '#createProject',
            type: "POST",
            redirect: true,
            data: $('#createProject').serialize(),
            success: function(){
                window.location.reload();
            }
        })
    });

    function loadData () {

            var url = '{{route('admin.projectDashboard')}}?startDate=' + startDate + '&endDate=' + endDate;

            $.easyAjax({
                url: url,
                container: '#dashboard-content',
                type: "GET",
                success: function (response) {
                    $('#dashboard-content').html(response.view);
                }
            })

        }
</script>

@endpush