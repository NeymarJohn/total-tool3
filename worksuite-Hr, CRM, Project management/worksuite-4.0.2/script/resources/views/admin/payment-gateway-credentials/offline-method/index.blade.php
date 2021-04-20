@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            @if(!$offlineMethods->isEmpty())
            <a href="javascript:;" id="addMethod" class="btn btn-outline btn-success btn-sm">@lang('modules.offlinePayment.addMethod') <i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
    <style>
        .panel-black .panel-heading a, .panel-inverse .panel-heading a {
            color: unset!important;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

                <div class="vtabs customvtab m-t-10">

                    @include('sections.payment_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('app.menu.method')</th>
                                                <th>@lang('app.description')</th>
                                                <th>@lang('app.status')</th>
                                                <th width="20%">@lang('app.action')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($offlineMethods as $key=>$method)
                                                <tr>
                                                    <td>{{ ($key+1) }}</td>
                                                    <td>{{ ucwords($method->name) }}</td>
                                                    <td>{!! ucwords($method->description) !!} </td>
                                                    <td>@if($method->status == 'yes') <label class="label label-success">@lang('modules.offlinePayment.active')</label> @else <label class="label label-danger">@lang('modules.offlinePayment.inActive')</label> @endif </td>
                                                    <td>
                                                        <div class="btn-group dropdown m-r-10">
                                                            <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                                                            <ul role="menu" class="dropdown-menu pull-right">
                                                                <li><a href="javascript:;" data-type-id="{{ $method->id }}"
                                                                        class="edit-type"><i class="fa fa-edit"></i> @lang('app.edit')</a></li>
                                                                <li><a href="javascript:;"  data-type-id="{{ $method->id }}"  class="delete-type"><i class="fa fa-times" aria-hidden="true"></i> @lang('app.delete') </a></li>

                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <div class="empty-space" style="height: 200px;">
                                                            <div class="empty-space-inner">
                                                                <div class="icon" style="font-size:30px"><i
                                                                            class="fa fa-key"></i>
                                                                </div>
                                                                <div class="title m-b-15">@lang('messages.noMethodsAdded')
                                                                </div>
                                                                <div class="subtitle">
                                                                    <a href="javascript:;" id="addMethod" class="btn btn-outline btn-success btn-sm">@lang('modules.offlinePayment.addMethod') <i class="fa fa-plus" aria-hidden="true"></i></a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="clearfix"></div>
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

    <script>

    //    save project members
    $('#save-type').click(function () {
        $.easyAjax({
            url: '{{route('admin.offline-payment-setting.store')}}',
            container: '#createMethods',
            type: "POST",
            data: $('#createMethods').serialize(),
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
            text: "@lang('messages.removeMethodText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('app.yes')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('admin.offline-payment-setting.destroy',':id') }}";
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
        var url = '{{ route("admin.offline-payment-setting.edit", ":id")}}';
        url = url.replace(':id', typeId);

        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.offlinePayment.title') }}");
        $.ajaxModal('#leadStatusModal', url);
    })
    $('#addMethod').click(function () {
        var url = '{{ route("admin.offline-payment-setting.create")}}';
        $('#modelHeading').html("{{  __('app.edit')." ".__('modules.offlinePayment.title') }}");
        $.ajaxModal('#leadStatusModal', url);
    })


</script>


@endpush

