@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.tickets.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.addNew')</li>
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

@section('content')

    {!! Form::open(['id'=>'storeTicket','class'=>'ajax-form','method'=>'POST']) !!}
    <div class="form-body">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading text-right">@lang('modules.tickets.ticket') # {{ (is_null($lastTicket)) ? "1" : ($lastTicket->id+1) }}</div>

                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tickets.ticketSubject') <span class="text-danger">*</span></label>
                                        <input type="text" id="subject" name="subject" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tickets.ticketDescription') <span class="text-danger">*</span></label></label>
                                        <textarea class="textarea_editor form-control" rows="10" name="description"
                                                  id="description"></textarea>
                                    </div>
                                </div>
                                <!--/span-->

                                {!! Form::hidden('status', 'open', ['id' => 'status']) !!}

                            </div>
                            <!--/row-->

                        </div>
                    </div>

                    <div class="panel-footer text-right">
                        <div class="btn-group dropup m-r-10">
                            <button aria-expanded="true" data-toggle="dropdown"
                                    class="btn btn-info btn-outline dropdown-toggle waves-effect waves-light"
                                    type="button"><i class="fa fa-bolt"></i> @lang('modules.tickets.applyTemplate')
                                <span class="caret"></span></button>
                            <ul role="menu" class="dropdown-menu">
                                @forelse($templates as $template)
                                    <li><a href="javascript:;" data-template-id="{{ $template->id }}" class="apply-template">{{ ucfirst($template->reply_heading) }}</a></li>
                                @empty
                                    <li>@lang('messages.noTemplateFound')</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="btn-group dropup">
                            <button aria-expanded="true" data-toggle="dropdown"
                                    class="btn btn-success dropdown-toggle waves-effect waves-light"
                                    type="button">@lang('app.submit') <span class="caret"></span></button>
                            <ul role="menu" class="dropdown-menu">
                                <li>
                                    <a href="javascript:;" class="submit-ticket" data-status="open">@lang('modules.tickets.submitOpen')
                                        <span style="width: 15px; height: 15px;"
                                              class="btn btn-danger btn-small btn-circle">&nbsp;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="submit-ticket" data-status="pending">@lang('modules.tickets.submitPending')
                                        <span style="width: 15px; height: 15px;"
                                              class="btn btn-warning btn-small btn-circle">&nbsp;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="submit-ticket" data-status="resolved">@lang('modules.tickets.submitResolved')
                                        <span style="width: 15px; height: 15px;"
                                              class="btn btn-info btn-small btn-circle">&nbsp;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="submit-ticket" data-status="closed">@lang('modules.tickets.submitClosed')
                                        <span style="width: 15px; height: 15px;"
                                              class="btn btn-success btn-small btn-circle">&nbsp;</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-4">
                <div class="panel panel-default">

                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tickets.requesterName')</label>
                                        <select  name="user_id" id="user_id" class="select2 form-control" data-style="form-control" >
                                            <option value="">@lang('app.select') @lang('modules.tickets.requesterName')</option>
                                            @foreach($requesters as $requester)
                                                <option value="{{ $requester->id }}">{{ ucwords($requester->name).' ['.$requester->email.']' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tickets.agent')</label>
                                        <select  name="agent_id" id="agent_id" class="select2 form-control" data-style="form-control" >
                                            <option value="">Agent not assigned</option>
                                            @forelse($groups as $group)
                                                @if(count($group->enabled_agents) > 0)
                                                    <optgroup label="{{ ucwords($group->group_name) }}">
                                                        @foreach($group->enabled_agents as $agent)
                                                            <option value="{{ $agent->user->id }}">{{ ucwords($agent->user->name).' ['.$agent->user->email.']' }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @empty
                                                <option value="">@lang('messages.noGroupAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.invoices.type') <a class="btn btn-xs btn-info btn-outline" href="javascript:;" id="add-type"><i class="fa fa-plus"></i> @lang('modules.tickets.addType')</a></label>
                                        <select class="form-control selectpicker add-type" name="type_id" id="type_id" data-style="form-control">
                                            @forelse($types as $type)
                                                <option value="{{ $type->id }}">{{ ucwords($type->type) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noTicketTypeAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tasks.priority') <span class="text-danger">*</span></label>
                                        <select class="form-control selectpicker" name="priority" id="priority" data-style="form-control">
                                            <option>low</option>
                                            <option>medium</option>
                                            <option>high</option>
                                            <option>urgent</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.tickets.channelName') <a class="btn btn-xs btn-info btn-outline" href="javascript:;" id="add-channel"><i class="fa fa-plus"></i> @lang('modules.tickets.addChannel')</a></label>
                                        <select class="form-control selectpicker" name="channel_id" id="channel_id" data-style="form-control">
                                            @forelse($channels as $channel)
                                                <option value="{{ $channel->id }}">{{ ucwords($channel->channel_name) }}</option>
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

                                        </select>
                                    </div>
                                </div>

                                <!--/span-->

                            </div>
                            <!--/row-->

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
            url: '{{route('admin.replyTemplates.fetchTemplate')}}',
            type: "POST",
            data: { _token: token, templateId: templateId },
            success: function (response) {
                if (response.status == "success") {
                    var editorObj = $("#description").data('wysihtml5');
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
            url: '{{route('member.tickets.store')}}',
            container: '#storeTicket',
            type: "POST",
            data: $('#storeTicket').serialize()
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

    function setValueInForm(id, data){
        $('#'+id).html(data);
        $('#'+id).selectpicker('refresh');
    }
</script>
@endpush