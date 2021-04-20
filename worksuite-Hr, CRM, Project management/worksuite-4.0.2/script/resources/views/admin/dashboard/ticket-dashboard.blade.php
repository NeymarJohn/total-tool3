<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">

<div class="row dashboard-stats front-dashboard">
    @if(in_array('tickets',$modules) && in_array('total_unresolved_tickets',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.tickets.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-success-gradient"><i class="ti-ticket fa-fw"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalUnresolvedTickets')</span><br>
                            <span class="counter">{{ $totalUnresolvedTickets }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @if(in_array('tickets',$modules) && in_array('total_unassigned_ticket',$activeWidgets))
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.tickets.index') }}">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div><span class="bg-warning-gradient"><i class="ti-ticket fa-fw"></i></span></div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalUnassignedTicket')</span><br>
                            <span class="counter">{{ $totalUnassignedTicket }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
</div>
<div class="row">
    @if(in_array('tickets',$modules) && in_array('type_wise_ticket',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.typeWiseTicket')
                    <a href="javascript:;" data-chart-id="typeWiseTicket" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">            
                                @if(!empty(json_decode($typeWiseTicket)))
                                    <div>
                                        <canvas id="typeWiseTicket"></canvas>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="ti-ticket fa-fw"></i></div>
                                                <div class="title m-b-15">@lang('messages.noTicketFound')</div>
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

    @if(in_array('tickets',$modules) && in_array('status_wise_ticket',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.statusWiseTicket')
                    <a href="javascript:;" data-chart-id="statusWiseTicket" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
          
                                @if(!empty(json_decode($statusWiseTicket)))
                                    <div>
                                        <canvas id="statusWiseTicket"></canvas>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="ti-ticket fa-fw"></i></div>
                                                <div class="title m-b-15">@lang('messages.noTicketFound')</div>
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

    @if(in_array('tickets',$modules) && in_array('channel_wise_ticket',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.channelWiseTicket')
                    <a href="javascript:;" data-chart-id="channelWiseTicket" class="text-dark pull-right download-chart">
                        <i class="fa fa-download"></i> @lang('app.download')
                    </a>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">           
                                @if(!empty(json_decode($channelWiseTicket)))
                                    <div>
                                        <canvas id="channelWiseTicket"></canvas>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:30px"><i class="ti-ticket fa-fw"></i></div>
                                                <div class="title m-b-15">@lang('messages.noTicketFound')</div>
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

    @if(in_array('tickets',$modules) && in_array('new_tickets',$activeWidgets))
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.dashboard.newTickets')</div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <ul class="list-task list-group" data-role="tasklist">
                            @forelse($newTickets as $key=>$newTicket)
                                <li class="list-group-item" data-role="task">
                                    {{ ($key+1) }}. <a href="{{ route('admin.tickets.edit', $newTicket->id) }}"
                                                    class="font-semi-bold"> {{  ucfirst($newTicket->subject) }}</a>
                                    <i class="font-12">{{ ucwords($newTicket->created_at->diffForHumans()) }}</i>
                                </li>
                            @empty
                                <li class="list-group-item" data-role="task">
                                    <div class="text-center">
                                        <div class="empty-space" style="height: 200px;">
                                            <div class="empty-space-inner">
                                                <div class="icon" style="font-size:20px"><i
                                                            class="ti-ticket"></i>
                                                </div>
                                                <div class="title m-b-15">@lang("messages.noTicketFound")
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/jquery.fullcalendar.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/locale-all.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>
    $(document).ready(function () {
        @if(!empty(json_decode($typeWiseTicket)))
            function typeWiseTicketPieChart(typeWiseTicket) {
                
                var ctx2 = document.getElementById("typeWiseTicket");
                var data = new Array();
                var color = new Array();
                var labels = new Array();

                $.each(typeWiseTicket, function(key,val){
                    labels.push(val.type.toUpperCase());
                    data.push(parseInt(val.totalTicket));
                    color.push(getRandomColor());
                });
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
            }
            typeWiseTicketPieChart(jQuery.parseJSON('{!! $typeWiseTicket !!}'));

        @endif
        @if(!empty(json_decode($statusWiseTicket)))
            function statusWiseTicketPieChart(statusWiseTicket) {
                
                var ctx2 = document.getElementById("statusWiseTicket");
                var data = new Array();
                var color = new Array();
                var labels = new Array();

                $.each(statusWiseTicket, function(key,val){
                    labels.push(val.status.toUpperCase());
                    data.push(parseInt(val.totalTicket));
                    color.push(getRandomColor());
                });
                
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
            }
            statusWiseTicketPieChart(jQuery.parseJSON('{!! $statusWiseTicket !!}'));

        @endif
        @if(!empty(json_decode($channelWiseTicket)))
            function channelWiseTicketPieChart(channelWiseTicket) {
                
                var ctx2 = document.getElementById("channelWiseTicket");
                var data = new Array();
                var color = new Array();
                var labels = new Array();

                $.each(channelWiseTicket, function(key,val){
                    labels.push(val.channel_name.toUpperCase());
                    data.push(parseInt(val.totalTicket));
                    color.push(getRandomColor());
                });
                
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
            }
            channelWiseTicketPieChart(jQuery.parseJSON('{!! $channelWiseTicket !!}'));

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


    });

    
    
</script>