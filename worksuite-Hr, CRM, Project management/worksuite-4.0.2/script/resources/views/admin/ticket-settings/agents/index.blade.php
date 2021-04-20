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
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.settings.index') }}">@lang('app.menu.settings')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">

<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="vtabs customvtab">

                @include('sections.ticket_setting_menu')
                <div class="row">

                    <div class="col-md-12">
                        <div class="white-box">
                            <h4>@lang('app.addNew') @lang('modules.tickets.agents')</h4>

                            {!! Form::open(['id'=>'createAgents','class'=>'ajax-form','method'=>'POST']) !!}

                            <div class="form-body">

                                <div class="form-group" id="user_id">
                                    <label for="" class="required">@lang('modules.tickets.chooseAgents')</label>
                                    <select class="select2 m-b-10 select2-multiple " multiple="multiple"
                                            data-placeholder="@lang('modules.tickets.chooseAgents')" name="user_id[]">
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id == $user->id)
                                                    (YOU) @endif</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="" class="required">@lang('modules.tickets.assignGroup')
                                        <a href="javascript:;" class="btn btn-info btn-xs btn-outline" id="manage-groups"><i class="ti-settings"></i> @lang('modules.tickets.manageGroups')</a>
                                    </label>
                                    <select class="selectpicker form-control" name="group_id" id="group_id"
                                            data-style="form-control">
                                        <option value="">--</option>

                                    @foreach($groups as $group)
                                            <option value="{{ $group->id }}">{{ ucwords($group->group_name) }}</option>
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

                    <div class="col-md-12">
                        <div class="white-box">
                            <h3>@lang('modules.tickets.agents') </h3>

                            <div class="table-responsive">
                                <table class="table" id="agents-table">
                                    <thead>
                                    <tr>
                                        <th>@lang('app.name')</th>
                                        <th>@lang('modules.tickets.group')</th>
                                        <th>@lang('app.status')</th>
                                        <th>@lang('app.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($agents as $agent)
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)">
                                                    <img src="{{ $agent->user->image_url }}"  alt="user" class="img-circle" width="25" height="
25">

                                                    {{ ucwords($agent->user->name) }}
                                                </a>
                                            </td>
                                            <td>
                                                <select class="change-agent-group form-control" data-agent-id="{{ $agent->id }}">
                                                    <option value="">No group assigned</option>
                                                    @foreach($groups as $group)
                                                        <option
                                                                @if($group->id == $agent->group_id) selected @endif
                                                        value="{{ $group->id }}">{{ $group->group_name }}</option>
                                                    @endforeach

                                                </select>
                                            </td>
                                            <td>
                                                <select class="change-agent-status  form-control" data-agent-id="{{ $agent->id }}">
                                                    <option @if($agent->status == 'enabled') selected @endif>@lang('app.enabled')</option>
                                                    <option @if($agent->status == 'disabled') selected @endif>@lang('app.disabled')</option>
                                                </select>
                                            </td>
                                            <td>
                                                <a href="javascript:;" data-agent-id="{{ $agent->id }}"
                                                    class="btn btn-sm btn-inverse btn-outline delete-agents"><i
                                                            class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <div class="empty-space" style="height: 200px;">
                                                    <div class="empty-space-inner">
                                                        <div class="icon" style="font-size:30px"><i
                                                                    class="ti-headphone-alt"></i>
                                                        </div>
                                                        <div class="title m-b-15">@lang('messages.noAgentAdded')
                                                        </div>

                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

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


    </div>
    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="ticketGroupModal" role="dialog" aria-labelledby="myModalLabel"
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
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>


<script type="text/javascript">

    $('#agents-table').dataTable({
        responsive: true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 }
        ],
        searching: false,
        paging: false,
        info: false
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    //    save project members
    $('#save-members').click(function () {
        $.easyAjax({
            url: '{{route('admin.ticket-agents.store')}}',
            container: '#createAgents',
            type: "POST",
            data: $('#createAgents').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });


    $('body').on('click', '.delete-agents', function () {
        var id = $(this).data('agent-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeAgentText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.ticket-agents.destroy',':id') }}";
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

    $('.change-agent-status').change(function () {
        var agentId = $(this).data('agent-id');
        var status = $(this).val();
        var token = '{{ csrf_token() }}';
        var url = '{{ route("admin.ticket-agents.update", ':id') }}';
        url = url.replace(':id', agentId);

        $.easyAjax({
            type: 'PUT',
            url: url,
            data: {'_token': token, 'status': status}
        });

    })

    $('.change-agent-group').change(function () {
        var agentId = $(this).data('agent-id');
        var groupId = $(this).val();
        var token = '{{ csrf_token() }}';
        var url = '{{ route("admin.ticket-agents.update-group", ':id') }}';
        url = url.replace(':id', agentId);

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, 'groupId': groupId}
        });

    })

    $('#manage-groups').click(function () {
        var url = '{{ route("admin.ticket-groups.create")}}';
        $('#modelHeading').html("{{  __('modules.tickets.manageGroups') }}");
        $.ajaxModal('#ticketGroupModal', url);
    })


</script>


@endpush

