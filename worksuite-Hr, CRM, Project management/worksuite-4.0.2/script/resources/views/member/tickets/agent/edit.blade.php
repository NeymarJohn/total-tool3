@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <span id="ticket-status" class="m-r-5">
                <label class="label
                    @if($ticket->status == 'open')
                        label-danger
                @elseif($ticket->status == 'pending')
                        label-warning
                @elseif($ticket->status == 'resolved')
                        label-info
                @elseif($ticket->status == 'closed')
                        label-success
                @endif
                        ">{{ $ticket->status }}</label>
            </span>
            <span class="text-info text-uppercase font-bold">@lang('modules.tickets.ticket') # {{ $ticket->id }}</span>

            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.ticket-agent.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.update')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/html5-editor/bootstrap-wysihtml5.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush

@section('other-section')
    {!! Form::open(['id'=>'updateTicket1','class'=>'ajax-form updateTicket','method'=>'POST']) !!}
    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">@lang('modules.invoices.type')</label>
                <select class="form-control selectpicker add-type" name="type_id" id="type_id"
                        data-style="form-control">
                    @forelse($types as $type)
                        <option
                                @if($type->id == $ticket->type_id)
                                selected
                                @endif
                                value="{{ $type->id }}">{{ ucwords($type->type) }}</option>
                    @empty
                        <option value="">@lang('messages.noTicketTypeAdded')</option>
                    @endforelse
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">@lang('modules.tasks.priority') <span
                            class="text-danger">*</span></label>
                <select class="form-control selectpicker" name="priority" id="priority"
                        data-style="form-control">
                    <option @if($ticket->priority == 'low') selected @endif value="low">@lang('app.low')</option>
                    <option @if($ticket->priority == 'medium') selected @endif value="medium">@lang('app.medium')</option>
                    <option @if($ticket->priority == 'high') selected @endif value="high">@lang('app.high')</option>
                    <option @if($ticket->priority == 'urgent') selected @endif value="urgent">@lang('app.urgent')</option>
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">@lang('modules.tickets.channelName')</label>
                <select class="form-control selectpicker" name="channel_id" id="channel_id"
                        data-style="form-control">
                    @forelse($channels as $channel)
                        <option value="{{ $channel->id }}"
                                @if($channel->id == $ticket->channel_id)
                                selected
                                @endif
                        >{{ ucwords($channel->channel_name) }}</option>
                    @empty
                        <option value="">@lang('messages.noTicketChannelAdded')</option>
                    @endforelse
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">@lang('modules.tickets.tags')</label>
                <select multiple data-role="tagsinput" name="tags[]" id="tags">
                    @foreach($ticket->tags as $tag)
                        <option value="{{ $tag->tag->tag_name }}">{{ $tag->tag->tag_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success submit-ticket-2">@lang('app.save')</button>
            </div>
        </div>

        <!--/span-->

    </div>
    <!--/row-->
    {!! Form::close() !!}
@endsection

@section('content')

    {!! Form::open(['id'=>'updateTicket2','class'=>'ajax-form','method'=>'PUT', 'files' => true]) !!}
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    
                    <div class="row">

                        <div class="col-md-12 m-b-10">
                            <h4 class="text-capitalize text-info">{{ $ticket->subject }}</h4>

                            <div class="font-12">{{ $ticket->created_at->format($global->date_format .' '.$global->time_format) }} &bull; {{ ucwords($ticket->requester->name). ' <'.$ticket->requester->email.'>' }}</div>
                        </div>

                        {!! Form::hidden('status', $ticket->status, ['id' => 'status']) !!}

                    </div>
                    <!--/row-->


                    <div id="ticket-messages">

                        @forelse($ticket->reply as $reply)
                            <div class="panel-body @if($reply->user->id == $user->id) bg-owner-reply @else bg-other-reply @endif">

                                <div class="row m-b-5">

                                    <div class="col-xs-2 col-md-1">
                                        <img src="{{ $reply->user->image_url }}" alt="user" class="img-circle" width="40" height="40">
                                    </div>
                                    <div class="col-xs-10 col-md-11">
                                        <h5 class="m-t-0 font-bold"><a
                                                    @if($reply->user->hasRole('employee'))
                                                    href="{{ route('member.employees.show', $reply->user_id) }}"
                                                    @elseif($reply->user->hasRole('client'))
                                                    href="{{ route('member.clients.show', $reply->user_id) }}"
                                                    @endif
                                                    class="text-inverse">{{ ucwords($reply->user->name) }} <span
                                                        class="text-muted font-12 font-normal">{{ $reply->created_at->format($global->date_format .' '.$global->time_format) }}</span></a>
                                        </h5>

                                        <div class="font-light">
                                            {!! ucfirst(nl2br($reply->message)) !!}
                                        </div>
                                    </div>


                                </div>
                                <!--/row-->

                                @if(sizeof($reply->files) > 0)
                                <div class="row bg-white" id="list">
                                    <ul class="list-group" id="files-list">
                                        @forelse($reply->files as $file)
                                            <li class="list-group-item b-none col-md-6">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        {{ $file->filename }}
                                                    </div>
                                                    <div class="col-md-4 text-right">

                                                            <a target="_blank" href="{{ $file->file_url }}"
                                                            data-toggle="tooltip" data-original-title="View"
                                                            class="btn btn-info btn-sm btn-outline"><i
                                                                        class="fa fa-search text-info"></i></a>

                                                        @if(is_null($file->external_link))
                                                            &nbsp;&nbsp;
                                                            <a href="{{ route('member.ticket-files.download', $file->id) }}"
                                                            data-toggle="tooltip" data-original-title="Download"
                                                            class="btn btn-inverse btn-sm btn-outline"><i
                                                                        class="fa fa-download"></i></a>
                                                        @endif
                                                        {{--&nbsp;&nbsp;--}}
                                                        {{--<a href="javascript:;" data-toggle="tooltip"--}}
                                                        {{--data-original-title="Delete"--}}
                                                        {{--data-file-id="{{ $file->id }}"--}}
                                                        {{--class="btn btn-danger btn-circle sa-params" data-pk="list"><i--}}
                                                                    {{--class="fa fa-times"></i></a>--}}

                                                        <span class="clearfix font-12 text-muted">{{ $file->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        @lang('messages.noFileUploaded')
                                                    </div>
                                                </div>
                                            </li>
                                        @endforelse

                                    </ul>
                                </div>
                            <!--/row-->
                            @endif

                            </div>
                        @empty
                            <div class="panel-body b-b">

                                <div class="row">

                                    <div class="col-md-12">
                                        @lang('messages.noMessage')
                                    </div>

                                </div>
                                <!--/row-->

                            </div>
                        @endforelse
                    </div>
                    

                    <div class="panel-body">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">@lang('modules.tickets.reply') <span
                                                class="text-danger">*</span></label></label>
                                    <textarea class="textarea_editor form-control" rows="10" name="message"
                                                id="message"></textarea>
                                </div>
                            </div>
                            <!--/span-->


                        </div>
                        <!--/row-->

                        <div class="row m-b-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                        <label class="control-label">@lang('app.file') </label>
                                    <input type="file" name="file[]" id="file" class="form-control" multiple>
                                </div>
                            </div>
                        <!--/row-->
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-12 text-right">
                            <div class="btn-group dropup m-r-10">
                                <button aria-expanded="true" data-toggle="dropdown"
                                        class="btn btn-info btn-outline dropdown-toggle waves-effect waves-light"
                                        type="button"><i class="fa fa-bolt"></i> @lang('modules.tickets.applyTemplate')
                                    <span class="caret"></span></button>
                                <ul role="menu" class="dropdown-menu pull-right">
                                    @forelse($templates as $template)
                                        <li><a href="javascript:;" data-template-id="{{ $template->id }}"
                                            class="apply-template">{{ ucfirst($template->reply_heading) }}</a></li>
                                    @empty
                                        <li>@lang('messages.noTemplateFound')</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="btn-group dropup">
                                <button aria-expanded="true" data-toggle="dropdown"
                                        class="btn btn-success dropdown-toggle waves-effect waves-light"
                                        type="button">@lang('app.submit') <span class="caret"></span></button>
                                <ul role="menu" class="dropdown-menu  pull-right">
                                    <li>
                                        <a href="javascript:;" class="submit-ticket" data-status="open">@lang('modules.tickets.submitOpen')
                                            <span style="width: 15px; height: 15px;"
                                                class="btn btn-danger btn-small btn-circle">&nbsp;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="submit-ticket"
                                        data-status="pending">@lang('modules.tickets.submitPending')
                                            <span style="width: 15px; height: 15px;"
                                                class="btn btn-warning btn-small btn-circle">&nbsp;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="submit-ticket"
                                        data-status="resolved">@lang('modules.tickets.submitResolved')
                                            <span style="width: 15px; height: 15px;"
                                                class="btn btn-info btn-small btn-circle">&nbsp;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="submit-ticket"
                                        data-status="closed">@lang('modules.tickets.submitClosed')
                                            <span style="width: 15px; height: 15px;"
                                                class="btn btn-success btn-small btn-circle">&nbsp;</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            
        </div>
        <!-- .row -->
    </div>
    {!! Form::close() !!}

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="ticketModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
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
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/html5-editor/wysihtml5-0.3.0.js') }}"></script>
<script src="{{ asset('plugins/bower_components/html5-editor/bootstrap-wysihtml5.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script>
    $('.textarea_editor').wysihtml5();

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('.apply-template').click(function () {
        var templateId = $(this).data('template-id');
        var token = '{{ csrf_token() }}';

        $.easyAjax({
            url: '{{route('member.ticket-agent.fetchTemplate')}}',
            type: "POST",
            data: {_token: token, templateId: templateId},
            success: function (response) {
                if (response.status == "success") {
                    var editorObj = $("#message").data('wysihtml5');
                    var editor = editorObj.editor;
                    editor.setValue(response.replyText);
                }
            }
        })
    })


    $('.submit-ticket').click(function () {

        var status = $(this).data('status');
        $('#status').val(status);

        $.easyAjax({
            url: '{{route('member.ticket-agent.update', $ticket->id)}}',
            container: '#updateTicket2',
            type: "POST",
            data: $('#updateTicket2').serialize(),
            file: true,
            success: function (response) {
                if(response.status == 'success'){
                    $('#scroll-here').remove();
                    $('#ticket-messages').append(response.lastMessage);
                    $('#message').data("wysihtml5").editor.clear();

                    // update status on top
                    if(status == 'open')
                        $('#ticket-status').html('<label class="label label-danger">'+status+'</label>');
                    else if(status == 'pending')
                        $('#ticket-status').html('<label class="label label-warning">'+status+'</label>');
                    else if(status == 'resolved')
                        $('#ticket-status').html('<label class="label label-info">'+status+'</label>');
                    else if(status == 'closed')
                        $('#ticket-status').html('<label class="label label-success">'+status+'</label>');

                    scrollChat();
                }
            }
        })
    });

    $('.submit-ticket-2').click(function () {

        var status = $(this).data('status');
        $('#status').val(status);

        $.easyAjax({
            url: '{{route('member.ticket-agent.updateAgentOtherData', $ticket->id)}}',
            container: '#updateTicket1',
            type: "POST",
            data: $('#updateTicket1').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    $('#scroll-here').remove();
                    $('#ticket-messages').append(response.lastMessage);
                    $('#message').data("wysihtml5").editor.clear();

                    // update status on top
                    if(status == 'open')
                        $('#ticket-status').html('<label class="label label-danger">'+status+'</label>');
                    else if(status == 'pending')
                        $('#ticket-status').html('<label class="label label-warning">'+status+'</label>');
                    else if(status == 'resolved')
                        $('#ticket-status').html('<label class="label label-info">'+status+'</label>');
                    else if(status == 'closed')
                        $('#ticket-status').html('<label class="label label-success">'+status+'</label>');

                    scrollChat();
                }
            }
        })
    });

    $('#add-type').click(function () {
        var url = '{{ route("admin.ticketTypes.createModal")}}';
        $('#modelHeading').html("{{ __('app.addNew').' '.__('modules.tickets.ticketTypes') }}");
        $.ajaxModal('#ticketModal', url);
    })

    $('#add-channel').click(function () {
        var url = '{{ route("admin.ticketChannels.createModal")}}';
        $('#modelHeading').html("{{ __('app.addNew').' '.__('modules.tickets.ticketTypes') }}");
        $.ajaxModal('#ticketModal', url);
    })

    function setValueInForm(id, data) {
        $('#' + id).html(data);
        $('#' + id).selectpicker('refresh');
    }

    function scrollChat() {
        $('#ticket-messages').animate({
            scrollTop: $('#ticket-messages')[0].scrollHeight
        }, 'slow');
    }

    scrollChat();
</script>
@endpush