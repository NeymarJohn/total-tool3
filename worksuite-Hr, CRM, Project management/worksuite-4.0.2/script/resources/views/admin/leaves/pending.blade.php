@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) <span class="text-warning b-l p-l-10 m-l-5">{{ count($pendingLeaves)}}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.leaves.pendingLeaves')</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        @if(!$pendingLeaves->isEmpty())
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.leave.all-leaves') }}" class="btn btn-sm btn-info waves-effect waves-light btn-outline">
                        <i class="fa fa-list"></i> @lang('app.all') @lang('app.menu.leaves')
            </a>

            <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-primary waves-effect waves-light m-l-10 btn-outline">
                    <i class="fa fa-calendar"></i> @lang('modules.leaves.calendarView')
            </a>

            <a href="{{ route('admin.leaves.create') }}" class="btn btn-sm btn-success waves-effect waves-light m-l-10 btn-outline">
            <i class="ti-plus"></i> @lang('modules.leaves.assignLeave')</a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>

        </div>
    @endif
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">

        <div class="col-md-12">

            <div class="white-box">
                <div class="row">
                    @forelse($pendingLeaves as $key=>$pendingLeave)
                        <div class="col-md-3 m-b-25">
                            <div class=" pending-leaves  p-10">
                            <h5 class="font-normal">{{ $pendingLeave->type->type_name }} @lang('modules.leaves.leaveRequest') @if($pendingLeave->duration == 'half day') <label class="label label-danger">@lang('modules.leaves.halfDay')</label> @endif</h5>
                            <div class="m-b-15">
                                <img src="{{ $pendingLeave->user->image_url }}" alt="user" class="img-circle" width="30" height="30" height="30">
                                <span class="m-l-5"><a href="{{ route('admin.employees.show', $pendingLeave->user_id) }}" >{{  ucwords($pendingLeave->user->name) }}</a></span>
                            </div>
                            @php
                                try{
                                    $allowedLeaves = $pendingLeave->user->leaveTypes->sum('no_of_leaves');
                                    $leavesRemaining = ($allowedLeaves-$pendingLeave->leaves_taken_count);
                                    $percentLeavesRemaining = ($leavesRemaining/$allowedLeaves) * 100;
                                }
                                catch(Exception $e){
                                    $percentLeavesRemaining = 0;
                                }
                            @endphp
                            <div class="text-center bg-light p-t-20 p-b-20 m-l-n-25 m-r-n-25">
                                {{ $pendingLeave->leave_date->format($global->date_format) }} ({{ $pendingLeave->leave_date->format('l') }})
                                <div class="progress m-l-30 m-r-30 m-t-15">
                                    <div class="progress-bar progress-bar-info" aria-valuenow="{{ $percentLeavesRemaining }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $percentLeavesRemaining }}%" role="progressbar"> <span class="sr-only">{{ $percentLeavesRemaining }}% Complete</span> </div>
                                </div>

                                <div class="m-l-30 m-r-30 m-t-15">
                                    {{ ($leavesRemaining) }} @lang('modules.leaves.remainingLeaves')
                                </div>
                            </div>

                            <div class="p-t-10">
                                <h6 class="font-normal">@lang('app.reason')</h6>
                                <div class="p-b-15 font-12" style="height: 80px; overflow-y: auto;">{{ $pendingLeave->reason }}</div>

                                <div class="p-t-20 text-center m-l-n-25 m-r-n-25">
                                    <a href="javascript:;" data-leave-id="{{ $pendingLeave->id }}" data-leave-action="approved" class="btn btn-success btn-rounded m-r-5 leave-action"><i class="fa fa-check"></i> @lang('app.accept')</a>

                                    <a href="javascript:;" data-leave-id="{{ $pendingLeave->id }}" data-leave-action="rejected" class="btn btn-danger btn-rounded leave-action-reject"><i class="fa fa-times"></i> @lang('app.reject')</a>

                                </div>

                            </div>
                            </div>
                        </div>
                    @empty
                        <div  class="text-center">
                            <div class="empty-space" style="height: 300px;">
                                <div class="empty-space-inner">
                                    <div class="icon" style="font-size:30px"><i
                                                class="icon-logout"></i>
                                    </div>
                                    <div class="title m-b-15">@lang('messages.noPendingLeaves')
                                    </div>
                                    <div class="subtitle">
                                        <a href="{{ route('admin.leave.all-leaves') }}" class="btn btn-sm btn-info waves-effect waves-light btn-outline">
                                            <i class="fa fa-list"></i> @lang('app.all') @lang('app.menu.leaves')
                                        </a>

                                        <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-primary waves-effect waves-light m-l-10 btn-outline">
                                            <i class="fa fa-calendar"></i> @lang('modules.leaves.calendarView')
                                        </a>

                                        <a href="{{ route('admin.leaves.create') }}" class="btn btn-sm btn-success waves-effect waves-light m-l-10 btn-outline">
                                            <i class="ti-plus"></i> @lang('modules.leaves.assignLeave')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>


    </div>
    <!-- .row -->

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

@endsection

@push('footer-script')

<script>


    $('.leave-action-reject').click(function () {
        var action = $(this).data('leave-action');
        var leaveId = $(this).data('leave-id');
        var searchQuery = "?leave_action="+action+"&leave_id="+leaveId;
        var url = '{!! route('admin.leaves.show-reject-modal') !!}'+searchQuery;
        $('#modelHeading').html('Reject Reason');
        $.ajaxModal('#eventDetailModal', url);
    });

    $('.leave-action').on('click', function() {
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

@endpush
