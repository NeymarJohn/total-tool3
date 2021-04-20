@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-success btn-outline" ><i class="icon-note"></i> @lang('app.edit')</a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projects.members')</li>
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
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('admin.projects.show_project_menu')

                    <div class="content-wrap">
                        <section id="section-line-2" class="show">
                            <div class="white-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="panel panel-inverse">
                                            <div class="panel-heading">@lang('modules.projects.addMemberTitle')</div>

                                            <div class="panel-wrapper collapse in">
                                                <div class="panel-body">
                                                {!! Form::open(['id'=>'createMembers','class'=>'ajax-form','method'=>'POST']) !!}

                                                <div class="form-body">

                                                    {!! Form::hidden('project_id', $project->id) !!}

                                                    <div class="form-group" id="user_id">
                                                        <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                                                data-placeholder="@lang('modules.projects.chooseMembers')" name="user_id[]">
                                                            @foreach($employees as $emp)
                                                                <option value="{{ $emp->id }}">{{ ucwords($emp->name) }} @if($emp->id == $user->id)
                                                                        (YOU) @endif</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-actions">
                                                        <button type="submit" id="save-members" class="btn btn-success"><i
                                                                    class="fa fa-check"></i> @lang('app.save')
                                                        </button>
                                                    </div>
                                                </div>

                                                {!! Form::close() !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="panel panel-inverse">

                                            <div class="panel-heading">@lang('app.add') @lang('app.team')</div>

                                            <div class="panel-wrapper collapse in">
                                                <div class="panel-body">
                                                    {!! Form::open(['id'=>'saveGroup','class'=>'ajax-form','method'=>'POST']) !!}

                                                    <div class="form-body">

                                                        {!! Form::hidden('project_id', $project->id) !!}

                                                        <div class="form-group" id="user_id">
                                                            <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                                                    data-placeholder="@lang('modules.projects.chooseDepartment')" name="group_id[]">
                                                                @foreach($groups as $group)
                                                                    <option value="{{ $group->id }}">{{ ucwords($group->team_name) }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-actions">
                                                            <button type="submit" id="save-group" class="btn btn-success"><i
                                                                        class="fa fa-check"></i> @lang('app.save')
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-wrapper collapse in">
                                                <div class="panel-body">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('app.name')</th>
                                                                <th>@lang('modules.employees.hourlyRate')</th>
                                                                <th>@lang('app.role')</th>
                                                                <th>@lang('app.action')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse($project->members as $member)
                                                            <tr>
                                                                <td>
                                                                    <div class="row">

                                                                        <div class="col-sm-3 col-xs-4">
                                                                            <img src="{{ $member->user->image_url }}" alt="user" class="img-circle" width="40" height="40">

                                                                        </div>
                                                                        <div class="col-sm-9 col-xs-8">
                                                                                {{ ucwords($member->user->name) }}<br>

                                                                                <span class="text-muted font-12">{{ (!is_null($member->user->employeeDetail) && !is_null($member->user->employeeDetail->designation)) ? ucwords($member->user->employeeDetail->designation->name) : ' ' }}</span>

                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{ $member->hourly_rate }}
                                                                </td>
                                                                <td>
                                                                    <div class="radio radio-info">
                                                                        <input type="radio" name="project_admin" class="assign_role" id="project_admin_{{ $member->user->id }}" value="{{ $member->user->id }}"
                                                                        @if($member->user->id == $project->project_admin) checked @endif
                                                                        >
                                                                        <label for="project_admin_{{ $member->user->id }}"> @lang('app.projectAdmin') </label>
                                                                        @if($member->user->id == $project->project_admin) 
                                                                            <br><a href="javascript:;" class="text-danger remove-admin"><i class="fa fa-times"></i> @lang('app.remove')</a>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a href="javascript:;" data-member-id="{{ $member->id }}" class="btn btn-sm btn-info btn-outline edit-members"><i class="fa fa-pencil"></i></a>

                                                                    <a href="javascript:;" data-member-id="{{ $member->id }}" class="btn btn-sm btn-danger btn-outline delete-members"><i class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td>
                                                                    @lang('messages.noMemberAddedToProject')
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                        </tbody>
                                                    </table>
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

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    //    save project members
    $('#save-members').click(function () {
        $.easyAjax({
            url: '{{route('admin.project-members.store')}}',
            container: '#createMembers',
            type: "POST",
            data: $('#createMembers').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    window.location.reload();
                }
            }
        })
    });

//    add group members
$('#save-group').click(function () {
    $.easyAjax({
        url: '{{route('admin.project-members.storeGroup')}}',
        container: '#saveGroup',
        type: "POST",
        data: $('#saveGroup').serialize(),
        success: function (response) {
            if (response.status == "success") {
                $.unblockUI();
                window.location.reload();
            }
        }
    })
});



$('body').on('click', '.edit-members', function(){
    var id = $(this).data('member-id');
    var url = '{{ route('admin.project-members.edit', ":id")}}';
    url = url.replace(':id', id);
    $('#modelHeading').html('@lang('app.update') @lang('modules.projects.milestones')');
    $.ajaxModal('#projectTimerModal',url);
    
});


$('body').on('click', '.delete-members', function(){
    var id = $(this).data('member-id');
    swal({
        title: "@lang('messages.sweetAlertTitle')",
        text: "@lang('messages.deleteProjectMember')",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "@lang('messages.confirmDelete')",
        cancelButtonText: "@lang('messages.confirmNoArchive')",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm) {

            var url = "{{ route('admin.project-members.destroy',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {'_token': token, '_method': 'DELETE'},
                success: function (response) {
                    if (response.status == "success") {
                        $.unblockUI();
                        window.location.reload();
                    }
                }
            });
        }
    });
});

$('body').on('click', '.assign_role', function(){
    var userId = $(this).val();
    var projectId = '{{ $project->id }}';
    var token = "{{ csrf_token() }}";

    $.easyAjax({
        url: '{{route('admin.employees.assignProjectAdmin')}}',
        type: "POST",
        data: {userId: userId, projectId: projectId, _token : token},
        success: function (response) {
            if(response.status == "success"){
                $.unblockUI();
                window.location.reload();
            }
        }
    })

});

$('body').on('click', '.remove-admin', function(){
    var userId = null;
    var projectId = '{{ $project->id }}';
    var token = "{{ csrf_token() }}";

    $.easyAjax({
        url: '{{route('admin.employees.assignProjectAdmin')}}',
        type: "POST",
        data: {userId: userId, projectId: projectId, _token : token},
        success: function (response) {
            if(response.status == "success"){
                $.unblockUI();
                window.location.reload();
            }
        }
    })

});

$('ul.showProjectTabs .projectMembers').addClass('tab-current');

</script>
@endpush
