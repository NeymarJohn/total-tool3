@extends('layouts.app')
@section('page-title')
<div class="row bg-title">
    <!-- .page title -->
    <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang('app.project') #{{ $project->id }} - {{ ucwords($project->project_name) }}</h4>
    </div>
    <!-- /.page title -->
    <!-- .breadcrumb -->
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
        @php $projectPin = $project->pinned(); @endphp
        <a href="javascript:;" class="btn btn-sm btn-info @if(!$projectPin) btn-outline @endif"  data-placement="bottom"  data-toggle="tooltip" data-original-title="@if($projectPin) @lang('app.unpin') @else @lang('app.pin') @endif"   data-pinned="@if($projectPin) pinned @else unpinned @endif" id="pinnedItem" >
            <i class="icon-pin icon-2 pin-icon  @if($projectPin) pinned @else unpinned @endif" ></i>
        </a>
    <a href="{{ route('admin.payments.create', ['project' => $project->id]) }}" class="btn btn-sm btn-primary btn-outline" ><i class="fa fa-plus"></i> @lang('modules.payments.addPayment')</a>

        @php
            if ($project->status == 'in progress') {
                $statusText = __('app.inProgress');
                $statusTextColor = 'text-info';
                $btnTextColor = 'btn-info';
            } else if ($project->status == 'on hold') {
                $statusText = __('app.onHold');
                $statusTextColor = 'text-warning';
                $btnTextColor = 'btn-warning';
            } else if ($project->status == 'not started') {
                $statusText = __('app.notStarted');
                $statusTextColor = 'text-warning';
                $btnTextColor = 'btn-warning';
            } else if ($project->status == 'canceled') {
                $statusText = __('app.canceled');
                $statusTextColor = 'text-danger';
                $btnTextColor = 'btn-danger';
            } else if ($project->status == 'finished') {
                $statusText = __('app.finished');
                $statusTextColor = 'text-success';
                $btnTextColor = 'btn-success';
            }else if ($project->status == 'under review') {
                $statusText = __('app.underReview');
                $statusTextColor = 'text-warning';
                $btnTextColor = 'btn-warning';
            }
        @endphp

        <div class="btn-group dropdown">
            <button aria-expanded="true" data-toggle="dropdown"
                    class="btn b-all dropdown-toggle waves-effect waves-light visible-lg visible-md"
                    type="button">{{ $statusText }} <span style="width: 15px; height: 15px;"
                    class="btn {{ $btnTextColor }} btn-small btn-circle">&nbsp;</span></button>
            <ul role="menu" class="dropdown-menu pull-right">
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="in progress">@lang('app.inProgress')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-info btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="on hold">@lang('app.onHold')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-warning btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="not started">@lang('app.notStarted')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-warning btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="canceled">@lang('app.canceled')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-danger btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="finished">@lang('app.finished')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-success btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
            </ul>
        </div>

        <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-success btn-outline" ><i class="icon-note"></i> @lang('app.edit')</a>

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
            <li><a href="{{ route('admin.projects.index') }}">@lang($pageTitle)</a></li>
            <li class="active">@lang('app.details')</li>
        </ol>
    </div>
    <!-- /.breadcrumb -->
</div>
@endsection
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/icheck/skins/all.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">

<style>
    #section-line-1 .col-in{
        padding:0 10px;
    }

    #section-line-1 .col-in h3{
        font-size: 15px;
    }

    #project-timeline .panel-body {
        max-height: 389px !important;
    }

    #milestones .panel-body {
        max-height: 189px;
        overflow: auto;
    }
</style>
@endpush
@section('content')

<div class="row">
    <div class="col-md-12">

        <section>
            <div class="sttabs tabs-style-line">

                @include('admin.projects.show_project_menu')

                <div class="white-box">
                    <div class="row">

                        <div class="col-md-9">
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="text-primary">
                                        @if(!is_null($project->project_budget))
                                        {{ !is_null($project->currency_id) ? $project->currency->currency_symbol.$project->project_budget : $project->project_budget }}
                                        @else
                                        --
                                        @endif
                                    </span> <span class="font-12 text-muted m-l-5"> @lang('modules.projects.projectBudget')</span>
                                </div>

                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                    <span class="text-success">
                                        {{ !is_null($project->currency_id) ? $project->currency->currency_symbol.$earnings : $earnings }}
                                    </span> <span class="font-12 text-muted m-l-5"> @lang('app.earnings')</span>
                                </div>

                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="text-info">
                                        {{ $hoursLogged }}
                                    </span> <span class="font-12 text-muted m-l-5"> @lang('modules.projects.hoursLogged')</span>
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                    <span class="text-warning">
                                        {{ !is_null($project->currency_id) ? $project->currency->currency_symbol.$expenses : $expenses }}
                                    </span> <span class="font-12 text-muted m-l-5"> @lang('modules.projects.expenses_total')</span>

                                </div>
                            </div>

                            <div class="row m-t-20">
                                <div class="col-md-12">
                                    <div class="panel ">
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body b-all border-radius">
                                                <div class="row">
                                                    <div class="col-xs-12" style="max-height: 100px; overflow-y: hidden;">
                                                        <label class="font-semi-bold">@lang('app.project') @lang('app.details')</label><br>
                                                        
                                                        {!! $project->project_summary !!}
                                                        
                                                    </div>
                                                    <div class="col-xs-12 text-center">
                                                        <a href="javascript:;" class="btn p-t-15 text-info" data-toggle="modal" data-target="#project-summary-modal"><i class="ti-arrows-vertical"></i> @lang('app.expand')</a>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row m-t-10">
                                <div class="col-md-6">
                                    <div class="panel ">
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body b-all border-radius">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <label class="font-semi-bold">@lang('app.startDate')</label><br>
                                                        <p>
                                                            {{ $project->start_date->format($global->date_format) }}
                                                        </p>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <label class="font-semi-bold">@lang('app.endDate')</label><br>
                                                        <p>
                                                            {{ (!is_null($project->deadline) ? $project->deadline->format($global->date_format) : '-') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                @if(!is_null($project->client))
                                                    <div class="row m-t-20">
                                                        <div class="col-xs-12">
                                                            <label class="font-bold">@lang('modules.client.clientDetails')</label>
                                                        </div>
                                                        @if(!is_null($project->client->client_details))
                                                            <div class="col-xs-6 m-b-10">
                                                                <label class="font-semi-bold">@lang('modules.client.companyName')</label><br>
                                                                {{ $project->client->client_details->company_name }}
                                                            </div>
                                                        @endif
                                                        <div class="col-xs-6 m-b-10">
                                                            <label class="font-semi-bold">@lang('modules.client.clientName')</label><br>
                                                            {{ ucwords($project->client->name) }}
                                                        </div>
                                                        <div class="col-xs-6 m-b-10">
                                                            <label class="font-semi-bold">@lang('modules.client.clientEmail')</label><br>
                                                            {{ $project->client->email }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel b-all border-radius" id="milestones">
                                        <div class="panel-heading b-b">@lang('modules.projects.milestones')</div>
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body">
                                                @forelse ($milestones as $key=>$item)
                                                <div class="row m-b-10">
                                                    <div class="col-xs-12 m-b-5">
                                                        <a href="javascript:;" class="milestone-detail" data-milestone-id="{{ $item->id }}">
                                                            <h6>{{ ucfirst($item->milestone_title )}}</h6>
                                                        </a>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        @if($item->status == 'complete')
                                                            <label class="label label-success">@lang('app.complete')</label>
                                                        @else
                                                            <label class="label label-danger">@lang('app.incomplete')</label>
                                                        @endif
                                                    </div>
                                                    <div class="col-xs-6 text-right">
                                                        @if($item->cost > 0)
                                                            {{ $item->currency->currency_symbol.$item->cost
                                                        }}
                                                        @endif
                                                    </div>
                                                </div>
                                                @empty
                                                    @lang('messages.noRecordFound')
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    

                                </div>

                            </div>

                            {{--Custom fields data--}}
                            @if(isset($fields))
                            <div class="row m-t-10">
                                <div class="col-md-12">
                                    <div class="panel ">
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body b-all border-radius">
                                                <div class="row">
                                                    @foreach($fields as $field)
                                                        <div class="col-md-3">
                                                            <strong>{{ ucfirst($field->label) }}</strong> <br>
                                                            <p class="text-muted">
                                                                @if( $field->type == 'text')
                                                                    {{$project->custom_fields_data['field_'.$field->id] ?? '-'}}
                                                                @elseif($field->type == 'password')
                                                                    {{$project->custom_fields_data['field_'.$field->id] ?? '-'}}
                                                                @elseif($field->type == 'number')
                                                                    {{$project->custom_fields_data['field_'.$field->id] ?? '-'}}
                            
                                                                @elseif($field->type == 'textarea')
                                                                    {{$project->custom_fields_data['field_'.$field->id] ?? '-'}}
                            
                                                                @elseif($field->type == 'radio')
                                                                    {{ !is_null($project->custom_fields_data['field_'.$field->id]) ? $project->custom_fields_data['field_'.$field->id] : '-' }}
                                                                @elseif($field->type == 'select')
                                                                    {{ (!is_null($project->custom_fields_data['field_'.$field->id]) && $project->custom_fields_data['field_'.$field->id] != '') ? $field->values[$project->custom_fields_data['field_'.$field->id]] : '-' }}
                                                                @elseif($field->type == 'checkbox')
                                                                    {{ !is_null($project->custom_fields_data['field_'.$field->id]) ? $field->values[$project->custom_fields_data['field_'.$field->id]] : '-' }}
                                                                @elseif($field->type == 'date')
                                                                    {{ !is_null($project->custom_fields_data['field_'.$field->id]) ? \Carbon\Carbon::parse($project->custom_fields_data['field_'.$field->id])->format($global->date_format) : '--'}}
                                                                @endif
                                                            </p>
                            
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{--custom fields data end--}}
            
                        </div>

                        <div class="col-md-3">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body">
                                                @forelse($project->members as $member)
                                                    <img src="{{ asset($member->user->image_url) }}"
                                                    data-toggle="tooltip" data-original-title="{{ ucwords($member->user->name) }}"

                                                    alt="user" class="img-circle" width="25" height="25" height="25">
                                                @empty
                                                    @lang('messages.noMemberAddedToProject')
                                                @endforelse

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="project-timeline">
                                    <div class="panel b-all border-radius">
                                        <div class="panel-heading b-b">@lang('modules.projects.activityTimeline')</div>

                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body">
                                                <div class="steamline">
                                                    @foreach($activities as $activ)
                                                    <div class="sl-item">
                                                        <div class="sl-left"><i class="fa fa-circle text-primary"></i>
                                                        </div>
                                                        <div class="sl-right">
                                                            <div>
                                                                <h6>{{ $activ->activity }}</h6> <span class="sl-date">{{ $activ->created_at->diffForHumans() }}</span></div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel b-all border-radius">
                                <div class="panel-heading b-b">@lang('app.menu.tasks')</div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        @if(!empty($taskStatus))
                                            <canvas id="chart3" height="150"></canvas>
                                        @else
                                            @lang('messages.noRecordFound')
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="panel b-all border-radius">
                                <div class="panel-heading b-b">@lang('app.earnings')</div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        @if($chartData != '[]')
                                            <div id="morris-bar-chart" style="height: 191px;"></div>
                                        @else
                                            @lang('messages.noRecordFound')
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="panel b-all border-radius">
                                <div class="panel-heading b-b">@lang('app.menu.timeLogs')</div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        @if($timechartData != '[]')
                                            <div id="morris-bar-timelogbarChart" style="height: 191px;"></div>
                                        @else
                                            @lang('messages.noRecordFound')
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /content -->
            </div>
            <!-- /tabs -->
        </section>
    </div>


</div>
<!-- .row -->

{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <!-- /.modal-dialog -->.
</div>
{{--Ajax Modal Ends--}}

{{--Ajax Modal--}}
<div class="modal fade bs-modal-lg in" id="project-summary-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><i class="icon-layers"></i> @lang('modules.projects.projectSummary')</h4>
            </div>
            <div class="modal-body">
                {!! $project->project_summary !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">@lang('app.close')</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->.
</div>
{{--Ajax Modal Ends--}}


@endsection
 @push('footer-script')
 <script src="{{ asset('plugins/bower_components/Chart.js/Chart.min.js') }}"></script>

 <script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
 <script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
 
 <script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
    $('body').on('click', '#pinnedItem', function(){
        var type = $('#pinnedItem').attr('data-pinned');
        var id = {{ $project->id }};
        console.log(['type', type]);
        var dataPin = type.trim(type);
        if(dataPin == 'pinned'){
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.projectunpinText')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmUnpin')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('admin.pinned.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('.pin-icon').removeClass('pinned');
                                $('.pin-icon').addClass('unpinned');
                                $('#pinnedItem').attr('data-pinned','unpinned');
                                $('#pinnedItem').attr('data-original-title','Pin');
                                $("#pinnedItem").tooltip("hide");
                            }
                        }
                    });
                }
            });
        }
        else {

            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.projectPin')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmUnpin')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.pinned.store') }}";

                    var token = "{{ csrf_token() }}";
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token,'project_id':id},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('.pin-icon').removeClass('unpinned');
                                $('.pin-icon').addClass('pinned');
                                $('#pinnedItem').attr('data-pinned','pinned');
                                $('#pinnedItem').attr('data-original-title','Unpin');
                                $("#pinnedItem").tooltip("hide");
                            }
                        }
                    });
                }
            });

        }
    });
    function pieChart(taskStatus) {
        var ctx3 = document.getElementById("chart3").getContext("2d");
        var data3 = new Array();
        $.each(taskStatus, function(key,val){
            // console.log("key : "+key+" ; value : "+val);
            data3.push(
                {
                    value: parseInt(val.count),
                    color: val.color,
                    highlight: "#57ecc8",
                    label: val.label
                }
            );
        });

        // console.log(data3);

        var myPieChart = new Chart(ctx3).Pie(data3,{
            segmentShowStroke : true,
            segmentStrokeColor : "#fff",
            segmentStrokeWidth : 0,
            animationSteps : 100,
            tooltipCornerRadius: 0,
            animationEasing : "easeOutBounce",
            animateRotate : true,
            animateScale : false,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            responsive: true
        });
    }



    @if(!empty($taskStatus))
        pieChart(jQuery.parseJSON('{!! $taskStatus !!}'));
    @endif

    var chartData = {!!  $chartData !!};
    function barChart() {

        Morris.Bar({
            element: 'morris-bar-chart',
            data: chartData,
            xkey: 'date',
            ykeys: ['total'],
            labels: ['Earning'],
            barColors:['#00c292'],
            hideHover: 'auto',
            gridLineColor: '#eef0f2',
            resize: true
        });

    }

    @if($chartData != '[]')
    barChart();
    @endif

    var chartData = {!!  $timechartData !!};
    function timelogbarChart() {

        Morris.Bar({
            element: 'morris-bar-timelogbarChart',
            data: chartData,
            xkey: 'date',
            ykeys: ['total_hours'],
            labels: ['Hours Logged'],
            barColors:['#3594fa'],
            hideHover: 'auto',
            gridLineColor: '#ccccccc',
            resize: true
        });

    }

    @if($timechartData != '[]')
    timelogbarChart();
    @endif
</script>

<script type="text/javascript">

    $('#timer-list').on('click', '.stop-timer', function () {
       var id = $(this).data('time-id');
        var url = '{{route('admin.time-logs.stopTimer', ':id')}}';
        url = url.replace(':id', id);
        var token = '{{ csrf_token() }}'
        $.easyAjax({
            url: url,
            type: "POST",
            data: {timeId: id, _token: token},
            success: function (data) {
                $('#timer-list').html(data.html);
            }
        })

    });

    $('.milestone-detail').click(function(){
        var id = $(this).data('milestone-id');
        var url = '{{ route('admin.milestones.detail', ":id")}}';
        url = url.replace(':id', id);
        $('#modelHeading').html('@lang('app.update') @lang('modules.projects.milestones')');
        $.ajaxModal('#projectCategoryModal',url);
    })

    $('.submit-ticket').click(function () {

        const status = $(this).data('status');
        const url = '{{route('admin.projects.updateStatus', $project->id)}}';
        const token = '{{ csrf_token() }}'

        $.easyAjax({
            url: url,
            type: "POST",
            data: {status: status, _token: token},
            success: function (data) {
                window.location.reload();
            }
        })
    });
    $('ul.showProjectTabs .projects').addClass('tab-current');
</script>

@endpush
