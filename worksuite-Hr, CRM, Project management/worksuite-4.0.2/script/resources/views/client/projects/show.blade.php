@extends('layouts.client-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            @php
            if ($project->status == 'in progress') {
                $statusText = __('app.inProgress');
                $statusTextColor = 'text-info';
                $btnTextColor = 'label-info';
            } else if ($project->status == 'on hold') {
                $statusText = __('app.onHold');
                $statusTextColor = 'text-warning';
                $btnTextColor = 'label-warning';
            } else if ($project->status == 'not started') {
                $statusText = __('app.notStarted');
                $statusTextColor = 'text-warning';
                $btnTextColor = 'label-warning';
            } else if ($project->status == 'canceled') {
                $statusText = __('app.canceled');
                $statusTextColor = 'text-danger';
                $btnTextColor = 'label-danger';
            } else if ($project->status == 'finished') {
                $statusText = __('app.finished');
                $statusTextColor = 'text-success';
                $btnTextColor = 'label-success';
            }
            @endphp

            <label class="label {{ $btnTextColor }}">{{ $statusText }}</label>

            <ol class="breadcrumb">
                <li><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('client.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.project')</li>
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
<style>
    #section-line-1 .col-in{
        padding:0 10px;
    }

    #section-line-1 .col-in h3{
        font-size: 15px;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    @include('client.projects.show_project_menu')


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

                                <div class="row">
                                    <div class="col-md-12" style="max-height: 400px; overflow-y: auto;">
                                        <h5>@lang('app.project') @lang('app.details')</h5>
                                        {!! $project->project_summary !!}
                                    </div>
                                </div>

                                <div class="row m-t-25">
                                    <div class="col-md-4">
                                        <div class="panel panel-inverse">
                                            <div class="panel-heading">@lang('modules.client.clientDetails') </div>
                                            <div class="panel-wrapper collapse in">
                                                <div class="panel-body">
                                                    @if(!is_null($project->client))
                                                    <dl>
                                                        @if(!is_null($project->client->client))
                                                        <dt>@lang('modules.client.companyName')</dt>
                                                        <dd class="m-b-10">{{ $project->client->client[0]->company_name }}</dd>
                                                        @endif

                                                        <dt>@lang('modules.client.clientName')</dt>
                                                        <dd class="m-b-10">{{ ucwords($project->client->name) }}</dd>

                                                        <dt>@lang('modules.client.clientEmail')</dt>
                                                        <dd class="m-b-10">{{ $project->client->email }}</dd>
                                                    </dl>
                                                    @else @lang('messages.noClientAddedToProject') @endif {{--Custom fields data--}} @if(isset($fields))
                                                    <dl>
                                                        @foreach($fields as $field)
                                                        <dt>{{ ucfirst($field->label) }}</dt>
                                                        <dd class="m-b-10">
                                                            @if( $field->type == 'text') {{$project->custom_fields_data['field_'.$field->id] ?? '-'}} @elseif($field->type == 'password')
                                                            {{$project->custom_fields_data['field_'.$field->id] ?? '-'}}
                                                            @elseif($field->type == 'number') {{$project->custom_fields_data['field_'.$field->id]
                                                            ?? '-'}} @elseif($field->type == 'textarea') {{$project->custom_fields_data['field_'.$field->id]
                                                            ?? '-'}} @elseif($field->type == 'radio') {{ !is_null($project->custom_fields_data['field_'.$field->id])
                                                            ? $project->custom_fields_data['field_'.$field->id] : '-' }}
                                                            @elseif($field->type == 'select') {{ (!is_null($project->custom_fields_data['field_'.$field->id])
                                                            && $project->custom_fields_data['field_'.$field->id] != '') ?
                                                            $field->values[$project->custom_fields_data['field_'.$field->id]]
                                                            : '-' }} @elseif($field->type == 'checkbox') {{ !is_null($project->custom_fields_data['field_'.$field->id])
                                                            ? $field->values[$project->custom_fields_data['field_'.$field->id]]
                                                            : '-' }} @elseif($field->type == 'date')
                                                                {{ \Carbon\Carbon::parse($project->custom_fields_data['field_'.$field->id])->format($global->date_format)}}
                                                            @endif
                                                        </dd>
                                                        @endforeach
                                                    </dl>
                                                    @endif {{--custom fields data end--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(in_array('timelogs',$modules))
                                    <div class="col-md-8">
                                        <div class="panel panel-inverse">
                                            <div class="panel-heading">@lang('modules.projects.activeTimers')</div>
                                            <div class="panel-wrapper collapse in">
                                                <div class="panel-body" id="timer-list">

                                                    @forelse($activeTimers as $key=>$time)
                                                    <div class="row m-b-10">
                                                        <div class="col-xs-12 m-b-5">
                                                            {{ ucwords($time->user->name) }}
                                                        </div>
                                                        <div class="col-xs-12 font-12">
                                                            {{ $time->duration }}
                                                        </div>

                                                    </div>

                                                    @empty
                                                        @lang('messages.noActiveTimer')
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif


                                </div>

                            </div>

                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-inverse">
                                            <div class="panel-heading">@lang('modules.projects.members')
                                                <span class="label label-rouded label-custom pull-right">{{ count($project->members) }}</span>
                                            </div>
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

                                    <div class="col-md-12">
                                        <div class="panel panel-inverse">

                                            <div class="panel-wrapper collapse in">
                                                <div class="panel-body dashboard-stats">
                                                   <div class="row">
                                                       
                                                       <div class="col-md-12 m-b-5 project-stats">
                                                            <span class="text-danger">{{ count($openTasks) }}</span> @lang('modules.projects.openTasks')
                                                       </div>
                                                       <div class="col-md-12 m-b-5 project-stats">
                                                            <span class="text-info">{{ $daysLeft }}</span>@lang('modules.projects.daysLeft')
                                                       </div>
                                                       <div class="col-md-12 m-b-5 project-stats">
                                                            <span class="text-success">{{ $hoursLogged }}</span>@lang('modules.projects.hoursLogged')
                                                       </div>
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12"   id="project-timeline">
                                        <div class="panel panel-inverse">
                                            <div class="panel-heading">@lang('modules.projects.activityTimeline')</div>

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

                    </div>


                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
//    (function () {
//
//        [].slice.call(document.querySelectorAll('.sttabs')).forEach(function (el) {
//            new CBPFWTabs(el);
//        });
//
//    })();

    // $('#timer-list').on('click', '.stop-timer', function () {
    //    var id = $(this).data('time-id');
    //     var url = '{{route('admin.time-logs.stopTimer', ':id')}}';
    //     url = url.replace(':id', id);
    //     var token = '{{ csrf_token() }}'
    //     $.easyAjax({
    //         url: url,
    //         type: "POST",
    //         data: {timeId: id, _token: token},
    //         success: function (data) {
    //             $('#timer-list').html(data.html);
    //         }
    //     })

    // });
    $('ul.showProjectTabs .projects').addClass('tab-current');

</script>
@endpush
