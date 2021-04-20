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
                <li><a href="{{ route('admin.project-template.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projectTemplate.members')</li>
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
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">
                        <nav>
                            <ul>
                                <li><a href="{{ route('admin.project-template.show', $project->id) }}"><span>@lang('modules.projectTemplate.overview')</span></a>
                                </li>
                                <li class="tab-current"><a href="{{ route('admin.project-template-member.show', $project->id) }}"><span>@lang('modules.projectTemplate.members')</span></a></li>

                                <li><a href="{{ route('admin.project-template-task.show', $project->id) }}"><span>@lang('app.menu.tasks')</span></a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="content-wrap">
                        <section id="section-line-2" class="show">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">@lang('modules.projectTemplate.members')</div>
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('app.name')</th>
                                                                <th></th>
                                                                <th>@lang('app.action')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse($project->members as $member)
                                                            <tr>
                                                                <td>
                                                                    {!!  ($member->user->image) ? '<img src="'.asset_url('avatar/'.$member->user->image).'"
                                                            alt="user" class="img-circle" width="40">' : '<img src="'.asset('img/default-profile-3.png').'"
                                                            alt="user" class="img-circle" width="40">' !!}
                                                                    {{ ucwords($member->user->name) }}
                                                                </td>
                                                                <td>

                                                                </td>
                                                                <td><a href="javascript:;" data-member-id="{{ $member->id }}" class="btn btn-sm btn-danger btn-outline delete-members"><i class="fa fa-times"></i> @lang('app.remove')</a></td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3">
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

                                <div class="col-md-6">
                                    <div class="white-box">
                                        <h3>@lang('modules.projectTemplate.addMemberTitle')</h3>

                                        {!! Form::open(['id'=>'createMembers','class'=>'ajax-form','method'=>'POST']) !!}

                                        <div class="form-body">

                                            {!! Form::hidden('project_id', $project->id) !!}

                                            <div class="form-group" id="user_id">
                                                <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                                        data-placeholder="Choose Members" name="user_id[]">
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->id }}">{{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id == $user->id)
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
                                        <hr>

                                        <h3>@lang('app.add') @lang('app.team')</h3>

                                        {!! Form::open(['id'=>'saveGroup','class'=>'ajax-form','method'=>'POST']) !!}

                                        <div class="form-body">

                                            {!! Form::hidden('project_id', $project->id) !!}

                                            <div class="form-group" id="user_id">
                                                <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                                        data-placeholder="Choose Team" name="group_id[]">
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
<script type="text/javascript">

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    //    save project members
    $('#save-members').click(function () {
        $.easyAjax({
            url: '{{route('admin.project-template-member.store')}}',
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
            url: '{{route('admin.project-template-members.storeGroup')}}',
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

$('body').on('click', '.delete-members', function(){
    var id = $(this).data('member-id');
    swal({
        title: "@lang('messages.sweetAlertTitle')",
        text: "@lang('messages.removeMemberProject')",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "@lang('app.yes')",
        cancelButtonText: "@lang('messages.confirmNoArchive')",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm) {

            var url = "{{ route('admin.project-template-member.destroy',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {'_token': token, '_method': 'DELETE'},
                success: function (response) {
                    if (response.status == "success") {
                        $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                        window.location.reload();
                    }
                }
            });
        }
    });
});
</script>
@endpush