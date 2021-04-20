@extends('layouts.app')

@push('head-script')
<style>
    .fc-event{
        font-size: 10px !important;
    }
    #calendar .fc-view-container .fc-view .fc-more-popover{
        top: 136px !important;
        left: 105px !important;
    }
    @keyframes fa-blink {
     0% { opacity: 1; }
     25% { opacity: 0.25; }
     50% { opacity: 0.5; }
     75% { opacity: 0.75; }
     100% { opacity: 0; }
 }
.fa-blink {
   -webkit-animation: fa-blink 1.75s linear infinite;
   -moz-animation: fa-blink 1.75s linear infinite;
   -ms-animation: fa-blink 1.75s linear infinite;
   -o-animation: fa-blink 1.75s linear infinite;
   animation: fa-blink 1.75s linear infinite;
}
    .dashboard-clock {
        font-size: 26px;
        font-weight: 300;
    }
</style>
@endpush
@section('page-title')


    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title "><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
            
               
           
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            
            @if(session('impersonate'))
            <a class="btn b-all waves-effect waves-light pull-right" data-toggle="tooltip" 
        data-original-title="{{__('messages.stopImpersonation')}}" data-placement="left" href="{{route('admin.impersonate.stop')}}" >
                    <i class="fa fa-stop fa-blink text-danger"></i>
                         
                </a>
             @endif

            <div class="col-md-4 pull-right hidden-xs hidden-sm">

               
            {!! Form::open(['id'=>'createProject','class'=>'ajax-form','method'=>'POST']) !!}
            <div class="btn-group dropdown keep-open pull-right m-l-10">
                <button aria-expanded="true" data-toggle="dropdown"
                        class="btn b-all dropdown-toggle waves-effect waves-light"
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
            @if($global->dashboard_clock)
                <span id="clock" class="dashboard-clock text-muted m-r-30"></span>
            @endif

            
            <select class="selectpicker language-switcher  pull-right" data-width="fit">
                <option value="en" @if($global->locale == "en") selected @endif data-content='<span class="flag-icon flag-icon-gb"></span>'>En</option>
                @foreach($languageSettings as $language)
                    <option value="{{ $language->language_code }}" @if($global->locale == $language->language_code) selected @endif  data-content='<span class="flag-icon @if($language->language_code == 'zh-CN') flag-icon-cn @elseif($language->language_code == 'zh-TW') flag-icon-tw @else flag-icon-{{ $language->language_code }} @endif"></span>'>{{ $language->language_code }}</option>
                @endforeach
            </select>
            

        <!-- .breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        <!-- /.breadcrumb -->
        
        </div>
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}"><!--Owl carousel CSS -->
<link rel="stylesheet" href="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.css') }}"><!--Owl carousel CSS -->
<link rel="stylesheet" href="{{ asset('plugins/bower_components/owl.carousel/owl.theme.default.css') }}"><!--Owl carousel CSS -->

<style>
    .col-in {
        padding: 0 20px !important;

    }

    .fc-event {
        font-size: 10px !important;
    }

    .dashboard-settings {
        padding-bottom: 8px !important;
    }
    .front-dashboard .white-box{
        margin-bottom: 8px;
    }
    @media (min-width: 769px) {
        #wrapper .panel-wrapper {
            height: 530px;
            overflow-y: auto;
        }
    }

</style>
@endpush

@section('content')

    <div class="white-box">
        @if(session('impersonate'))
        <div class="col-md-12">
            <div class="alert alert-danger">
                {{__('messages.impersonate')}} {{$company->company_name}}
            </div>
        </div>
         @endif
            @if($global->status == 'license_expired')

            <div class="col-md-12 alert alert-danger ">
                    <div class="col-md-6">
                        <h5 class="text-white">{{ $superadmin->expired_message }}</h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{route('admin.billing')}}" class="btn btn-success">{{ __('app.menu.billing') }}
                            <i class="fa fa-shopping-cart"></i></a>
                    </div>
                </div>
            @endif
                @if($company->package->default == 'yes' || $company->package->default == 'trial')
                    @if($packageSetting && !$packageSetting->all_packages)
                        <div class="col-md-12 alert alert-danger ">
                            <div class="col-md-6">
                                <h5 class="text-white">@lang('messages.purchasePackageMessage')</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{route('admin.billing')}}"
                                   class="btn btn-success">{{ __('app.menu.billing') }}
                                    <i class="fa fa-shopping-cart"></i></a>
                            </div>
                        </div>
                    @endif
                @endif

            @if($company->show_update_popup == 1)
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" onclick="hidePopUp()" aria-hidden="true"> <i class="fa fa-times"></i> </button>
                        @lang('messages.stripeUpdatePopup')
                    </div>
                </div>
            @endif

        <div class="row dashboard-stats front-dashboard">

            @if(in_array('clients',$modules)  && in_array('total_clients',$activeWidgets))
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.clients.index') }}">
                    <div class="white-box">
                    <div class="row">
                        <div class="col-xs-3">
                            <div>
                                <span class="bg-success-gradient"><i class="icon-user"></i></span>
                            </div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span class="widget-title"> @lang('modules.dashboard.totalClients')</span><br>
                            <span class="counter">{{ $counts->totalClients }}</span>
                        </div>
                    </div>
                    </div>
                </a>
            </div>
            @endif

            @if(in_array('employees',$modules)  && in_array('total_employees',$activeWidgets))
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.employees.index') }}">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-warning-gradient"><i class="icon-people"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title"> @lang('modules.dashboard.totalEmployees')</span><br>
                                <span class="counter">{{ $counts->totalEmployees }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if(in_array('projects',$modules)  && in_array('total_projects',$activeWidgets))
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.projects.index') }}">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-danger-gradient"><i class="icon-layers"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title"> @lang('modules.dashboard.totalProjects')</span><br>
                                <span class="counter">{{ $counts->totalProjects }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if(in_array('invoices',$modules)  && in_array('total_unpaid_invoices',$activeWidgets))
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.all-invoices.index') }}">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-inverse-gradient"><i class="ti-receipt"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title"> @lang('modules.dashboard.totalUnpaidInvoices')</span><br>
                                <span class="counter">{{ $counts->totalUnpaidInvoices }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif
            @if(in_array('tasks',$modules)  && in_array('total_pending_tasks',$activeWidgets))
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.all-tasks.index') }}">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div>
                                        <span class="bg-warning-gradient"><i class="ti-alert"></i></span>
                                    </div>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <span class="widget-title"> @lang('modules.dashboard.totalPendingTasks')</span><br>
                                    <span class="counter">{{ $counts->totalPendingTasks }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
            @if(in_array('timelogs',$modules)  && in_array('total_hours_logged',$activeWidgets))
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.all-time-logs.index') }}">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-info-gradient"><i class="icon-clock"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title"> @lang('modules.dashboard.totalHoursLogged')</span><br>
                                <span class="counter-loged">{{ $counts->totalHoursLogged }}</span>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            @endif

            @if(in_array('tasks',$modules) && in_array('completed_tasks',$activeWidgets))
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.all-tasks.index') }}">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div>
                                        <span class="bg-success-gradient"><i class="ti-check-box"></i></span>
                                    </div>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <span class="widget-title"> @lang('modules.dashboard.totalCompletedTasks')</span><br>
                                    <span class="counter">{{ $counts->totalCompletedTasks }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            @if(in_array('attendance',$modules)  && in_array('total_today_attendance',$activeWidgets))
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.attendances.index') }}">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-danger-gradient"><i class="fa fa-percent" style="display: inherit;"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title"> @lang('modules.dashboard.totalTodayAttendance')</span><br>
                                <span class="counter">@if($counts->totalEmployees > 0){{ round((($counts->totalTodayAttendance/$counts->totalEmployees)*100), 2) }}@else 0 @endif</span>%
                                <span class="text-muted">({{ $counts->totalTodayAttendance.'/'.$counts->totalEmployees }})</span>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            @endif

            @if(in_array('tickets',$modules) && in_array('total_resolved_tickets',$activeWidgets))
                <div class="col-md-3 col-sm-6 front-dashboard dashboard-stats">
                    <a href="{{ route('admin.tickets.index') }}">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-success-gradient"><i class="ti-ticket"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title"> @lang('modules.tickets.totalResolvedTickets')</span><br>
                                <span class="counter">{{ floor($counts->totalResolvedTickets) }}</span>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            @endif

            @if(in_array('tickets',$modules)   && in_array('total_unresolved_tickets',$activeWidgets))
                <div class="col-md-3 col-sm-6 front-dashboard dashboard-stats">
                    <a href="{{ route('admin.tickets.index') }}">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div>
                                        <span class="bg-danger-gradient"><i class="ti-ticket"></i></span>
                                    </div>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <span class="widget-title"> @lang('modules.tickets.totalUnresolvedTickets')</span><br>
                                    <span class="counter">{{ floor($counts->totalUnResolvedTickets) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
            
        </div>
        <!-- .row -->

        <div class="row">
            @if(in_array('payments',$modules)  && in_array('recent_earnings',$activeWidgets))
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">@lang('modules.dashboard.recentEarnings')</h3>

                            <div id="morris-area-chart" style="height: 190px;"></div>
                            <h6 style="line-height: 2em;"><span class=" label label-danger">@lang('app.note'):</span> @lang('messages.earningChartNote') <a href="{{ route('admin.settings.index') }}"><i class="fa fa-arrow-right"></i></a></h6>
                        </div>
                    </div>

                </div>

            </div>
            @endif
        </div>

        <div class="row">
            @if(in_array('leaves',$modules)  && in_array('settings_leaves',$activeWidgets))
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">@lang('app.menu.leaves')</div>
                    <div class="panel-wrapper collapse in" style="overflow: auto">
                        <div class="panel-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(in_array('tickets',$modules)  && in_array('new_tickets',$activeWidgets))
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">@lang('modules.dashboard.newTickets')</div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <ul class="list-task list-group" data-role="tasklist">
                                @forelse($newTickets as $key=>$newTicket)
                                    <li class="list-group-item" data-role="task">
                                        {{ ($key+1) }}. <a href="{{ route('admin.tickets.edit', $newTicket->id) }}" class="text-danger"> {{  ucfirst($newTicket->subject) }}</a> <i>{{ ucwords($newTicket->created_at->diffForHumans()) }}</i>
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

        <div class="row" >
            @if(in_array('tasks',$modules)  && in_array('overdue_tasks',$activeWidgets))
                <div class="col-md-6">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">@lang('modules.dashboard.overdueTasks')</div>
                        <div class="panel-wrapper collapse in">
                            <div class="panel-body">
                                <ul class="list-task list-group" data-role="tasklist">
                                    <li class="list-group-item" data-role="task">
                                        <strong>@lang('app.title')</strong> <span
                                                class="pull-right"><strong>@lang('modules.dashboard.dueDate')</strong></span>
                                    </li>
                                    @forelse($pendingTasks as $key=>$task)
                                        @if((!is_null($task->project_id) && !is_null($task->project) ) || is_null($task->project_id))
                                        <li class="list-group-item row" data-role="task">
                                            <div class="col-xs-9">
                                                {!! ($key+1).'. <a href="javascript:;" data-task-id="'.$task->id.'" class="show-task-detail">'.ucfirst($task->heading).'</a>' !!}
                                                @if(!is_null($task->project_id) && !is_null($task->project))
                                                    <a href="{{ route('admin.projects.show', $task->project_id) }}" class="font-12">{{ ucwords($task->project->project_name) }}</a>
                                                @endif
                                            </div>
                                            <label class="label label-danger pull-right col-xs-3">{{ $task->due_date->format($global->date_format) }}</label>
                                        </li>
                                        @endif
                                    @empty
                                        <li class="list-group-item" data-role="task">
                                            @lang("messages.noOpenTasks")
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(in_array('leads',$modules)  && in_array('pending_follow_up',$activeWidgets))
                <div class="col-md-6">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">@lang('modules.dashboard.pendingFollowUp')</div>
                        <div class="panel-wrapper collapse in">
                            <div class="panel-body">
                                <ul class="list-task list-group" data-role="tasklist">
                                    <li class="list-group-item" data-role="task">
                                        <strong>@lang('app.title')</strong> <span
                                                class="pull-right"><strong>@lang('modules.dashboard.followUpDate')</strong></span>
                                    </li>
                                    @forelse($pendingLeadFollowUps as $key=>$follows)
                                        <li class="list-group-item row" data-role="task">
                                            <div class="col-xs-9">
                                                {{ ($key+1) }}

                                                <a href="{{ route('admin.leads.show', $follows->lead_id) }}" class="text-danger">{{ ucwords($follows->lead->company_name) }}</a>

                                            </div>
                                            <label class="label label-danger pull-right col-xs-3">{{ $follows->next_follow_up_date->format($global->date_format.' '.$global->time_format) }}</label>
                                        </li>
                                    @empty
                                        <li class="list-group-item" data-role="task">
                                            <div class="text-center">
                                                <div class="empty-space" style="height: 200px;">
                                                    <div class="empty-space-inner">
                                                        <div class="icon" style="font-size:20px"><i
                                                                    class="fa fa-user-plus"></i>
                                                        </div>
                                                        <div class="title m-b-15">@lang("messages.noPendingLeadFollowUps")
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


            @if(in_array('projects',$modules)  && in_array('project_activity_timeline',$activeWidgets))
            <div class="col-md-6" id="project-timeline">
                <div class="panel panel-inverse">
                    <div class="panel-heading">@lang('modules.dashboard.projectActivityTimeline')</div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="steamline">
                                @foreach($projectActivities as $activ)
                                    <div class="sl-item">
                                        <div class="sl-left"><i class="fa fa-circle text-info"></i>
                                        </div>
                                        <div class="sl-right">
                                            <div><h6><a href="{{ route('admin.projects.show', $activ->project_id) }}" class="font-bold">{{ ucwords($activ->project->project_name) }}:</a> {{ $activ->activity }}</h6> <span class="sl-date">{{ $activ->created_at->diffForHumans() }}</span></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(in_array('employees',$modules)  && in_array('user_activity_timeline',$activeWidgets))
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">@lang('modules.dashboard.userActivityTimeline')</div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="steamline">
                                @forelse($userActivities as $key=>$activity)
                                    <div class="sl-item">
                                        <div class="sl-left">
                                            <img src="{{ $activity->user->image_url }}" alt="user" width="30" height="30" class="img-circle">'
                                        </div>
                                        <div class="sl-right">
                                            <div class="m-l-40"><a href="{{ route('admin.employees.show', $activity->user_id) }}" class="text-success">{{ ucwords($activity->user->name) }}</a> <span  class="sl-date">{{ $activity->created_at->diffForHumans() }}</span>
                                                <p>{!! ucfirst($activity->activity) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if(count($userActivities) > ($key+1))
                                        <hr>
                                    @endif
                                @empty
                                    <div>@lang("messages.noActivityByThisUser")</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
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

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in"  id="subTaskModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="subTaskModelHeading">Sub Task e</span>
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

@endsection


@push('footer-script')
<script>
    var taskEvents = [
        @foreach($leaves as $leave)
        @if($leave->status == 'approved')
        {
            id: '{{ ucfirst($leave->id) }}',
            title: '{{ ucfirst($leave->user->name) }}',
            start: '{{ $leave->leave_date }}',
            end: '{{ $leave->leave_date }}',
            className: 'bg-{{ $leave->type->color }}'
        },
        @else
        {
            id: '{{ ucfirst($leave->id) }}',
            title: '<i class="fa fa-warning"></i> {{ ucfirst($leave->user->name) }}',
            start: '{{ $leave->leave_date }}',
            end: '{{ $leave->leave_date }}',
            className: 'bg-{{ $leave->type->color }}'
        },
        @endif
        @endforeach
    ];

    var getEventDetail = function (id) {
        var url = '{{ route('admin.leaves.show', ':id')}}';
        url = url.replace(':id', id);

        $('#modelHeading').html('Event');
        $.ajaxModal('#eventDetailModal', url);
    }

    var calendarLocale = '{{ $global->locale }}';
    var firstDay = '{{ $global->week_start }}';

    $('.leave-action').click(function () {
        var action = $(this).data('leave-action');
        var leaveId = $(this).data('leave-id');
        var url = '{{ route("admin.leaves.leaveAction") }}';

        $.easyAjax({
            type: 'POST',
            url: url,
            data: { 'action': action, 'leaveId': leaveId, '_token': '{{ csrf_token() }}' },
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        });
    })
</script>


<script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

<script src="{{ asset('plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
<script src="{{ asset('plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>

<!-- jQuery for carousel -->
<script src="{{ asset('plugins/bower_components/owl.carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/owl.carousel/owl.custom.js') }}"></script>

<!--weather icon -->
<script src="{{ asset('plugins/bower_components/skycons/skycons.js') }}"></script>

<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/jquery.fullcalendar.js') }}"></script>
<script src="{{ asset('plugins/bower_components/calendar/dist/locale-all.js') }}"></script>
<script src="{{ asset('js/event-calendar.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/moment-timezone.js') }}"></script>
<script>
     @if(in_array('payments',$modules)  && in_array('recent_earnings',$activeWidgets))
        $(document).ready(function () {
            var chartData = {!!  $chartData !!};
            function barChart() {

                Morris.Bar({
                    element: 'morris-area-chart',
                    data: chartData,
                    xkey: 'date',
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
                    resize: true

                });

            }

            @if(in_array('payments',$modules))
            barChart();
            @endif

            $(".counter").counterUp({
                delay: 100,
                time: 1200
            });

            $('.vcarousel').carousel({
                interval: 3000
            })


            var icons = new Skycons({"color": "#ffffff"}),
                    list  = [
                        "clear-day", "clear-night", "partly-cloudy-day",
                        "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
                        "fog"
                    ],
                    i;
            for(i = list.length; i--; ) {
                var weatherType = list[i],
                        elements = document.getElementsByClassName( weatherType );
                for (e = elements.length; e--;){
                    icons.set( elements[e], weatherType );
                }
            }
            icons.play();
        })
    @endif
    $('.show-task-detail').click(function () {
        $(".right-sidebar").slideDown(50).addClass("shw-rside");

        var id = $(this).data('task-id');
        var url = "{{ route('admin.all-tasks.show',':id') }}";
        url = url.replace(':id', id);

        $.easyAjax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status == "success") {
                    $('#right-sidebar-content').html(response.view);
                }
            }
        });
    })

    {{--$('#save-form').click(function () {--}}
        {{--$.easyAjax({--}}
            {{--url: '{{route('admin.dashboard.widget')}}',--}}
            {{--container: '#createProject',--}}
            {{--type: "POST",--}}
            {{--redirect: true,--}}
            {{--data: $('#createProject').serialize()--}}
        {{--})--}}
    {{--});--}}

     $('.keep-open .dropdown-menu').on({
         "click":function(e){
             e.stopPropagation();
         }
     });
     $('[data-toggle="tooltip"]').tooltip();
     $('#save-form').click(function () {
         $.easyAjax({
             url: '{{route('admin.dashboard.widget', "admin-dashboard")}}',
             container: '#createProject',
             type: "POST",
             redirect: true,
             data: $('#createProject').serialize(),
             success: function(){
                 window.location.reload();
             }
         })
     });

     function hidePopUp () {
         $.easyAjax({
             url: '{{route('admin.dashboard.stripe-pop-up-close')}}',
             type: "GET",
         })
     }
     /** clock timer start here */
     function currentTime() {
         let date = new Date();
         date = moment.tz(date, "{{ $global->timezone }}");

         // console.log(moment.tz(date, "America/New_York"));

         let hour = date.hour();
         let min = date.minutes();
         let sec = date.seconds();
         let midday = "AM";
         midday = (hour >= 12) ? "PM" : "AM";
         @if($global->time_format == 'h:i A')
             hour = (hour == 0) ? 12 : ((hour > 12) ? (hour - 12): hour); /* assigning hour in 12-hour format */
         @endif
             hour = updateTime(hour);
         min = updateTime(min);
         document.getElementById("clock").innerText = `${hour} : ${min} ${midday}`
         const time = setTimeout(function(){ currentTime() }, 1000);
     }

     function updateTime(timer) {
         if (timer < 10) {
             return "0" + timer;
         }
         else {
             return timer;
         }
     }

     currentTime();
</script>
@endpush

