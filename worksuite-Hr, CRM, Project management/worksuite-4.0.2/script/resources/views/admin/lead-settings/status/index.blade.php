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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/lobipanel/dist/css/lobipanel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush


@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.menu.leadStatus')</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.lead_setting_menu')



                    <div class="row">

                        <div class="col-md-12">
                            <div class="white-box">
                                <h3>@lang('app.addNew') @lang('modules.lead.leadStatus')</h3>

                                {!! Form::open(['id'=>'createTypes','class'=>'ajax-form','method'=>'POST']) !!}

                                <div class="form-body">

                                    <div class="form-group">
                                        <label class="required">@lang('modules.lead.leadStatus')</label>
                                        <input type="text" name="type" id="type" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label class="required">@lang("modules.tasks.labelColor")</label><br>
                                        <input type="text" class="colorpicker form-control"  name="label_color" value="#ff0000" />
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
                        <hr>
                        <div class="col-md-12">
                            <div class="white-box">
                                <h3>@lang('app.update') @lang('modules.lead.defaultLeadStatus')</h3>
                                
                                <div class="form-group">
                                    <label for="">@lang('app.select') @lang('modules.lead.defaultLeadStatus')</label>
                                    <select class="select2 form-control" data-placeholder="@lang('modules.lead.leadStatus')" onchange="updateLeadStatus(this.value)" id="status_id" name="status_id">
                                        @foreach($leadStatus as $status)
                                            <option value="{{ $status->id }}" @if($status->default == '1') selected @endif >{{ ucwords($status->type) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="white-box">
                                <h3>@lang('app.menu.leadStatus')</h3>


                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('app.name')</th>
                                            <th>@lang("modules.tasks.labelColor")</th>
                                            <th>@lang('app.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($leadStatus as $key=>$status)
                                            <tr>
                                                <td>{{ ($key+1) }}</td>
                                                <td>{{ ucwords($status->type) }}</td>
                                                <td>
                                                    <span style="width: 15px; height: 15px; background: {{ $status->label_color }}"
                                                        class="btn btn-small btn-circle">&nbsp;</span>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" data-type-id="{{ $status->id }}"
                                                        class="btn btn-sm btn-info btn-rounded btn-outline edit-type"><i
                                                                class="fa fa-edit"></i> @lang('app.edit')</a>

                                                    @if (!$status->default)
                                                    <a href="javascript:;" data-type-id="{{ $status->id }}"
                                                        class="btn btn-sm btn-danger btn-rounded btn-outline delete-type"><i
                                                                class="fa fa-times"></i> @lang('app.remove')</a>
                                                    @else
                                                        @lang('messages.defaultStatusCantDelete')
                                                    @endif
                                                   
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>
                                                    @lang('messages.noLeadStatusAdded')
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
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<script type="text/javascript">


    //    save project members
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.lead-status-settings.store')}}',
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

    function updateLeadStatus(id){
        console.log(id);
        var url = '{{route('admin.leadSetting.statusUpdate', ':id')}}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            container: '#createTypes',
            type: "GET",
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    window.location.reload();
                }
            }
        })
    }

    $(".colorpicker").asColorPicker();

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

                var url = "{{ route('admin.lead-status-settings.destroy',':id') }}";
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
    


    $('.edit-type').click(function () {
        var typeId = $(this).data('type-id');
        var url = '{{ route("admin.lead-status-settings.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.lead.leadStatus') }}");
        $.ajaxModal('#leadStatusModal', url);
    })


</script>


@endpush

