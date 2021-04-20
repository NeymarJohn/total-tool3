<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">
<div class="row dashboard-stats front-dashboard">
    @if(in_array('clients',$modules) && in_array('total_clients',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.clients.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-success-gradient"><i class="icon-people"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalClients')</span><br>
                            <span class="counter">{{ $totalClient }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @if(in_array('leads',$modules) && in_array('total_leads',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.leads.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-warning-gradient"><i class="icon-people"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalLeads')</span><br>
                            <span class="counter">{{ $totalLead }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @if(in_array('leads',$modules) && in_array('total_lead_conversions',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.leads.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-danger-gradient"><i class="icon-people"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalLeadConversions')</span><br>
                            <span class="counter">{{ $totalLeadConversions }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @if(in_array('contracts',$modules) && in_array('total_contracts_generated',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.contracts.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-inverse-gradient"><i class="icon-book-open"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalContractsGenerated')</span><br>
                            <span class="counter">{{ $totalContractsGenerated }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @if(in_array('contracts',$modules) && in_array('total_contracts_signed',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.contracts.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-success-gradient"><i class="icon-book-open"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalContractsSigned')</span><br>
                            <span class="counter">{{ $totalContractsSigned }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
</div> 
<div class="row">
    @if(in_array('payments',$modules) && in_array('client_wise_earnings',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.clientWiseEarnings')
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                @if(!empty(json_decode($chartData)))
                                    <div id="morris-area-chart"  class="morris-bar-chart" style="height: 350px"></div>
                                    <h6 style="line-height: 2em;"><span class=" label label-danger">@lang('app.note'):</span> @lang('messages.earningChartNote')
                                        <a href="{{ route('admin.settings.index') }}"><i class="fa fa-arrow-right"></i></a>
                                    </h6>
                                @else
                                    <div  class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="fa fa-money"></i></div>
                                                <div class="title m-b-15">@lang('messages.noEarningRecordFound')</div>
                                                <div class="subtitle">
                                                    <a href="{{route('admin.payments.index')}}" class="btn btn-info btn-outline btn-sm">
                                                        <i class="fa fa-plus"></i>
                                                        @lang('app.manage')
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
    
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
    @endif
    @if(in_array('timelogs',$modules) && in_array('client_wise_timelogs',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.clientWiseTimelogs')</div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                @if(!empty(json_decode($clientWiseTimelogChartData)))
                                    <div id="morris-timelog-chart" class="morris-bar-chart" style="height: 350px; padding-bottom: 25px;"></div>
                                @else
                                    <div  class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="fa fa-clock-o"></i></div>
                                                <div class="title m-b-15">@lang('messages.noTimeLogsFound')</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
    
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
    @endif
    @if(in_array('leads',$modules) && in_array('lead_vs_status',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.leadVsStatus')
                    <a href="javascript:;" data-chart-id="lead_vs_status_chart" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
    
                                @if(!empty(json_decode($leadVsStatus)))
                                    <div>
                                        <canvas id="lead_vs_status_chart" height="240"></canvas>
                                    </div>
                                @else
                                    <div  class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px">
                                                    <i class="fa fa-tasks"></i>
                                                </div>
                                                <div class="title m-b-15">@lang('messages.noLeadsFound')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
    
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
    @endif
    @if(in_array('leads',$modules) && in_array('lead_vs_source',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.leadVsSource')
                    <a href="javascript:;" data-chart-id="lead_vs_source_chart" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
    
                                @if(!empty(json_decode($leadVsSource)))
                                    <div>
                                        <canvas id="lead_vs_source_chart" height="240"></canvas>
                                    </div>
                                @else
                                    <div  class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px">
                                                    <i class="fa fa-tasks"></i>
                                                </div>
                                                <div class="title m-b-15">@lang('messages.noLeadsFound')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
    
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
    @endif
    @if(in_array('clients',$modules) && in_array('latest_client',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.latestClient')</div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="steamline">
                            @forelse($latestClient as $key=>$activity)
                                <div class="sl-item">
                                    <div class="sl-left">
                                        <img src="{{ $activity->image_url }}" width="40" height="40" alt="user" class="img-circle">
                                    </div>
                                    <div class="sl-right">
                                        <div class="m-l-40"><a href="{{ route('admin.clients.show', $activity->id) }}" class="text-success">{{ ucwords($activity->name) }} {{ ($activity->company_name) ? ' (' . ucwords($activity->company_name) . ')' : '' }}</a>
                                            <span class="sl-date">{{ $activity->created_at ? $activity->created_at->diffForHumans(): '--' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if(count($latestClient) > ($key+1))
                                    <hr>
                                @endif
                            @empty
                            <div  class="text-center">
                                <div class="empty-space" style="height: 200px;">
                                    <div class="empty-space-inner">
                                        <div class="icon" style="font-size:30px">
                                            <i class="icon-people"></i>
                                        </div>
                                        <div class="title m-b-15">@lang('messages.noLatestClientFound')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(in_array('clients',$modules) && in_array('recent_login_activities',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.recentLoginActivities')</div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="steamline">
                            @forelse($recentLoginActivities as $key=>$activity)
                                <div class="sl-item">
                                    <div class="sl-left">
                                        <img src="{{ $activity->image_url }}" width="40" height="40" alt="user" class="img-circle">
                                    </div>
                                    <div class="sl-right">
                                        <div class="m-l-40"><a href="{{ route('admin.clients.show', $activity->id) }}" class="text-success">{{ ucwords($activity->name) }}</a>
                                            <span class="sl-date">@lang('app.last') @lang('app.login') {{ $activity->last_login ? \Carbon\Carbon::parse($activity->last_login)->diffForHumans(): '--' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if(count($recentLoginActivities) > ($key+1))
                                    <hr>
                                @endif
                            @empty
                                <div class="text-center">
                                    <div class="empty-space" style="height: 200px;">
                                        <div class="empty-space-inner">
                                            <div class="icon" style="font-size:30px">
                                                <i class="icon-lock"></i>
                                            </div>
                                            <div class="title m-b-15">@lang('messages.noLoginActivityFound')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

<!--weather icon -->

<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>
    $(document).ready(function () {
        @if(!empty(json_decode($chartData)))
            var chartData = {!!  $chartData !!};

            function barChart() {
                Morris.Bar({
                    element: 'morris-area-chart',
                    data: chartData,
                    xkey: 'client',
                    ykeys: ['total'],
                    labels: ['Earning'],
                    pointSize: 3,
                    fillOpacity: 0,
                    barColors: ['#6fbdff'],
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    lineWidth: 2,
                    hideHover: 'auto',
                    lineColors: ['#e20b0b'],
                    resize: true,
                    xLabelMargin: 10,
                    xLabelAngle: 70,
                    padding: 20
                });
                $('#morris-area-chart').addClass('customChartCss');
            }
            barChart();
        @endif

        @if(!empty(json_decode($clientWiseTimelogChartData)))

            var clientWiseTimelogChartData = {!!  $clientWiseTimelogChartData !!};
            
            function timelogBarChart() {
                Morris.Bar({
                    element: 'morris-timelog-chart',
                    data: clientWiseTimelogChartData,
                    xkey: 'client',
                    ykeys: ['totalHours'],
                    labels: ['Hours'],
                    pointSize: 3,
                    fillOpacity: 0,
                    barColors: ['#6fbdff'],
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    lineWidth: 2,
                    hideHover: 'auto',
                    lineColors: ['#e20b0b'],
                    resize: true,
                    xLabelMargin: 10,
                    xLabelAngle: 70,
                    padding: 20
                });
                $('#morris-timelog-chart').addClass('customChartCss');
            }

            timelogBarChart();
        @endif

        @if(!empty(json_decode($leadVsStatus)))

            function pieChart(leadVsStatus) {
                var ctx2 = document.getElementById("lead_vs_status_chart");
                var data = new Array();
                var color = new Array();
                var labels = new Array();
                var total = 0;

                $.each(leadVsStatus, function(key,val){
                    labels.push(val.label);
                    data.push(parseInt(val.total));
                    total = total+parseInt(val.total);
                    color.push(val.color);
                });

                // labels.push('Total '+total);
                var chart = new Chart(ctx2,{
                    "type":"doughnut",
                    "data":{
                        "labels":labels,
                        "datasets":[{
                            "data":data,
                            "backgroundColor":color
                        }]
                    }
                });
                chart.canvas.parentNode.style.height = '470px';
                chart.canvas.parentNode.style.width = '470px';
            }

            pieChart(jQuery.parseJSON('{!! $leadVsStatus !!}'));

        @endif
        
        @if(!empty(json_decode($leadVsSource)))

            function leadVsSourcePieChart(leadVsSource) {
                
                var ctx3 = document.getElementById("lead_vs_source_chart").getContext('2d');;
                var data = new Array();
                var color = new Array();
                var labels = new Array();

                $.each(leadVsSource, function(key,val){
                    labels.push(val.label);
                    data.push(parseInt(val.total));
                    color.push(getRandomColor());
                });

                var chart = new Chart(ctx3,{
                    "type":"doughnut",
                    "data":{
                        "labels":labels,
                        "datasets":[{
                            "data":data,
                            "backgroundColor":color
                        }]
                    }
                });
                chart.canvas.parentNode.style.height = '470px';
                chart.canvas.parentNode.style.width = '470px';
            }

            leadVsSourcePieChart(jQuery.parseJSON('{!! $leadVsSource !!}'));

        @endif

        function getRandomColor() {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        $('.download-chart').click(function() {
            var id = $(this).data('chart-id');
            this.href = $('#'+id)[0].toDataURL();// Change here
            this.download = id+'.png';
        });

    })
</script>