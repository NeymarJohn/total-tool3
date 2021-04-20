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
@endpush
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('modules.lead.leadAgent')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.lead_setting_menu')

                   

                    <div class="row">

                        <div class="col-md-12">
                            <div class="white-box">
                                <h3>@lang('app.addNew') @lang('modules.lead.leadAgent')</h3>

                                {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}

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

                                    <div class="form-actions">
                                        <button type="submit" id="save-type" class="btn btn-success"><i
                                                    class="fa fa-check"></i> @lang('app.save')
                                        </button>
                                    </div>
                                </div>

                                {!! Form::close() !!}

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="white-box">
                                <h3>@lang('modules.lead.leadAgent')</h3>


                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('app.name')</th>
                                            <th>@lang('app.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($leadAgents as $key=>$agents)
                                            <tr id="leadAgent_{{ $agents->id }}">
                                                <td width="10%">{{ ($key+1) }}</td>
                                                <td width="60%"> @if(!is_null($agents->user)) {{ ucwords($agents->user->name) }} @endif</td>
                                                <td width="30%">
                                                    <a href="javascript:;" data-type-id="{{ $agents->id }}"
                                                        class="btn btn-sm btn-danger btn-rounded btn-outline delete-type"><i
                                                                class="fa fa-times"></i> @lang('app.remove')</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>
                                                    @lang('messages.noLeadAgentAdded')
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
        </div>


    </div>
    <!-- .row -->


    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="leadStatusModal" role="dialog" aria-labelledby="myModalLabel"
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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script type="text/javascript">

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    //    save project members
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-agent-settings.store')}}',
            container: '#createTypes',
            type: "POST",
            data: $('#createTypes').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    });


    $('body').on('click', '.delete-type', function () {
        var id = $(this).data('type-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeLead')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.lead-agent-settings.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            $.unblockUI();
                            $('#leadAgent_'+id).fadeOut();
                        }
                    }
                });
            }
        });
    });

</script>


@endpush

