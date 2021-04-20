@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            @php $pin = $project->pinned() @endphp
            <a href="javascript:;" class="btn btn-sm btn-info @if(!$pin) btn-outline @endif"   data-placement="bottom"  data-toggle="tooltip" data-original-title="@if($pin) @lang('app.unpin') @else @lang('app.pin') @endif"   data-pinned="@if($pin) pinned @else unpinned @endif" id="pinnedItem" >
                <i class="icon-pin icon-2 pin-icon  @if($pin) pinned @else unpinned @endif" ></i>
            </a>
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projects.overview')</li>
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
                    @include('member.projects.show_project_menu')

                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="white-box">
                                <div class="row">

                                    <div class="col-md-9">
                                    
                                        <div class="row">
                                            <div class="col-md-12" style="max-height: 400px; overflow-y: auto;">
                                                <h5>@lang('app.project') @lang('app.details')</h5>
                                                {!! $project->project_summary !!}
                                            </div>
                                        </div>
            
                                        <div class="row m-t-25">
                                            @if($user->can('view_clients'))
                                            <div class="col-md-4">
                                                <div class="panel panel-inverse">
                                                    <div class="panel-heading">@lang('modules.client.clientDetails') </div>
                                                    <div class="panel-wrapper collapse in">
                                                        <div class="panel-body">
                                                            @if(!is_null($project->client))
                                                            <dl>
                                                                @if(!is_null($project->client->client_details))
                                                                <dt>@lang('modules.client.companyName')</dt>
                                                                <dd class="m-b-10">{{ $project->client->client_details->company_name }}</dd>
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
                                            @endif
            
                    
                                            @if($project->isProjectAdmin || $user->can('edit_projects'))
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
                                                                <div class="col-xs-9 font-12">
                                                                    {{ $time->duration }}
                                                                </div>
                                                                <div class="col-xs-3 text-right">
                                                                    <button type="button" data-time-id="{{ $time->id }}" class="btn btn-danger btn-xs stop-timer">@lang('app.stop')</button>
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
                                                        <div class="panel-body ">
                                                        <div class="row">
                                                            <div class="col-md-12 m-b-5 project-stats">
                                                                <div class="row">
                                                                    <div class="col-xs-6 text-right"><span class="text-danger font-semi-bold">{{ count($openTasks) }}</span></div>
                                                                    <div class="col-xs-6"> @lang('modules.projects.openTasks')</div>
                                                                </div>
                                                                    
                                                            </div>
                                                            <div class="col-md-12 m-b-5 project-stats">
                                                                <div class="row">
                                                                    <div class="col-xs-6 text-right"><span class="text-info font-semi-bold">{{ $daysLeft }}</span></div>
                                                                    <div class="col-xs-6"> @lang('modules.projects.daysLeft')</div>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-12 m-b-5 project-stats">
                                                                <div class="row">
                                                                    <div class="col-xs-6 text-right"><span class="text-success font-semi-bold">{{ $hoursLogged }}</span></div>
                                                                    <div class="col-xs-6"> @lang('modules.projects.hoursLogged')</div>
                                                                </div>
                                                                    
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

                            
                        </section>
                    </div><!-- /content -->
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

    $('#timer-list').on('click', '.stop-timer', function () {
       var id = $(this).data('time-id');
        var url = '{{route('member.all-time-logs.stopTimer', ':id')}}';
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
    $('ul.showProjectTabs .projects').addClass('tab-current');
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

                var url = "{{ route('member.pinned.destroy',':id') }}";
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
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('member.pinned.store') }}";

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
</script>
@endpush
