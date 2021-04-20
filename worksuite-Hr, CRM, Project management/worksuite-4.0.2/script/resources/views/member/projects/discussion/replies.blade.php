@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span
                        class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('member.projects.edit', $project->id) }}" class="btn btn-sm btn-info btn-outline" ><i class="icon-note"></i> @lang('app.edit')</a>
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projects.discussion')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<style>
    .action-div {
        visibility: hidden;
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
                        <section class="show" id="discussion">
                            <div class="white-box">

                                <div class="row m-b-10">
                                    <div class="col-xs-12">
                                        <a href="{{ route('member.projects.discussion', $project->id) }}" class="btn btn-warning"><i class="fa fa-chevron-left"></i> @lang('app.back')</a>

                                        <a href="javascript:;" class="btn btn-info btn-outline m-l-10 add-reply" ><i class="fa fa-mail-reply"></i> @lang('app.reply')</a>

                                        @if (!is_null($discussion->best_answer_id))
                                            <a href="javascript:;" class="btn btn-success btn-outline m-l-10 go-best-reply" data-reply-id="{{ $discussion->best_answer_id }}" >@lang('modules.discussions.goBestReply')</a>                                            
                                        @endif
                                    </div>
                                </div>
                                
                                <div id="discussion-replies">
                                    @foreach ($discussionReplies as $key=>$reply)
                                        <div class="panel-body 
                                        @if ($discussion->best_answer_id == $reply->id)
                                            bg-best-reply 
                                        @else
                                            @if($reply->user->id == $user->id) 
                                            bg-owner-reply 
                                            @else 
                                            bg-other-reply 
                                            @endif 
                                        @endif
                                        
                                        " id="replyMessageBox_{{$reply->id}}">

                                            @if ($key == 0)
                                                <div class="row">

                                                    <div class="col-md-10 m-b-10">
                                                        <h4 class="text-capitalize">{{ $discussion->title }}</h4>

                                                    </div>
                                                    
                                                    <div class="col-md-2 m-b-10 text-right">
                                                        <span style="color:  {{ $discussion->category->color }}"><i class="fa fa-circle"></i> {{ ucwords($discussion->category->name) }}</span>

                                                    </div>

                            
                                                    {!! Form::hidden('project_id', $project->id, ['id' => 'project_id']) !!}
                            
                                                </div>
                                            @endif

                                            <div class="row">
            
                                                <div class="col-xs-2 col-md-1">
                                                    <img src="{{ $reply->user->image_url }}" alt="user" class="img-circle" width="40" height="40">
                                                </div>
                                                <div class="col-xs-7 col-md-9">
                                                    <h5 class="m-t-0 font-bold">
                                                        <a
                                                                @if($reply->user->hasRole('employee'))
                                                                href="{{ route('member.employees.show', $reply->user_id) }}"
                                                                @elseif($reply->user->hasRole('client'))
                                                                href="{{ route('member.clients.projects', $reply->user_id) }}"
                                                                @endif
                                                                class="text-inverse">{{ ucwords($reply->user->name) }}
                                                            <span class="text-muted font-12 font-normal">{{ $reply->created_at->timezone($global->timezone)->format($global->date_format.' '.$global->time_format) }}</span>
                                                        </a>
                                                    </h5>
            
                                                    <div class="font-light">
                                                        {!! $reply->body !!}
                                                    </div>
                                                </div>

                                                @if ($key != 0 && is_null($discussion->best_answer_id) && $discussion->user_id == $user->id)
                                                    <div class="col-md-2 col-xs-3 text-right">
                                                        <a href="javascript:;" data-reply-id="{{ $reply->id }}" class="btn btn-default set-best-answer btn-outline btn-sm">@lang('modules.discussions.bestReply')</a>
                                                    </div>
                                                @elseif($discussion->best_answer_id == $reply->id)
                                                    <div class="col-md-2 col-xs-3 text-right">
                                                        <label for="" class="label label-success">@lang('modules.discussions.bestReply')</label>
                                                    </div>
                                                @endif
            
                                                <div class="col-xs-10 col-xs-offset-2 col-md-11 col-md-offset-1 action-div">
                                                    <a href="javascript:;"
                                                    data-reply-id="{{ $reply->id }}" class="font-12 add-reply text-muted">
                                                    <i  class="fa fa-mail-reply"></i> @lang('app.reply')</a>

                                                    @if($discussion->best_answer_id == $reply->id && $discussion->user_id == $user->id)
                                                        <a href="javascript:;"
                                                        data-reply-id="{{ $reply->id }}" class="m-l-10 font-12 unset-best-answer text-muted">
                                                        <i  class="fa fa-times"></i> @lang('modules.discussions.removeBestReply')</a>
                                                    @endif


                                                    @if ($reply->user_id == $user->id)

                                                        <a href="javascript:;"
                                                        data-reply-id="{{ $reply->id }}" class="m-l-10 font-12 edit-reply text-muted">
                                                        <i  class="fa fa-edit"></i> @lang('app.edit')</a>

                                                        @if ($key != 0)
                                                            <a href="javascript:;"
                                                            data-reply-id="{{ $reply->id }}" class="m-l-10 font-12 delete-reply text-muted">
                                                            <i  class="fa fa-trash"></i> @lang('app.delete')</a>
                                                        @endif
                                                    @endif
                                                                
                                                </div>
            
            
                                            </div>
                                            
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="editTimeLogModal" role="dialog" aria-labelledby="myModalLabel"
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

    $('body').on('click', '.delete-reply', function () {
        var id = $(this).data('reply-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.recoverRecord')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.discussion-reply.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            $('#discussion-replies').html(response.html);
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.add-reply', function () {
        var url = '{{ route('member.discussion-reply.create', ['id' => $discussion->id]) }}';

        $('#modelHeading').html('{{ __('app.edit') }} {{ __('modules.projects.milestones') }}');
        $.ajaxModal('#editTimeLogModal', url);

    });

    $('body').on('click', '.edit-reply', function () {
        var categoryId = $(this).data('reply-id');
        var url = '{{ route('member.discussion-reply.edit', ':id') }}';
        url = url.replace(':id', categoryId);

        $('#modelHeading').html('{{ __('app.edit') }} {{ __('modules.projects.discussion') }}');
        $.ajaxModal('#editTimeLogModal', url);
    });

    $('body').on('click', '.set-best-answer', function () {
        var replyId = $(this).data('reply-id');
        var type = 'set';
        var discussionId = '{{ $discussion->id }}';
        var url = '{{ route('member.discussion.setBestAnswer') }}';
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'POST', 'discussionId': discussionId, 'replyId': replyId, 'type': type},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    $('#discussion-replies').html(response.html);
                }
            }
        });
    });

    $('body').on('click', '.unset-best-answer', function () {
        var replyId = $(this).data('reply-id');
        var type = 'unset';
        var discussionId = '{{ $discussion->id }}';
        var url = '{{ route('member.discussion.setBestAnswer') }}';
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'POST', 'discussionId': discussionId, 'replyId': replyId, 'type': type},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    $('#discussion-replies').html(response.html);
                }
            }
        });
    });

    $('.go-best-reply').click(function() {
        var replyId = $(this).data('reply-id');

        $('html, body').animate({
            scrollTop: $("#replyMessageBox_"+replyId).offset().top
        }, 1000);
    });

    $('body').on('mouseover', '#discussion-replies > .panel-body', function () {
        $(this).find('.action-div').css('visibility', 'visible');
    });

    $('body').on('mouseout', '#discussion-replies > .panel-body', function () {
        $('.action-div').css('visibility', 'hidden');
    });

   
    $('ul.showProjectTabs .discussion').addClass('tab-current');
</script>
@endpush
