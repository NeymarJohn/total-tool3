@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.menu.invoices')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection


@section('content')

    <div class="row">


        @include('admin.clients.client_header')


        <div class="col-xs-12">

            <section>
                <div class="sttabs tabs-style-line">
                    @include('admin.clients.tabs')

                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="row">


                                <div class="col-xs-12" >
                                    <div class="white-box">

                                        <div class="row">
                                            <div class="col-xs-12" >
                                                <button class="btn btn-sm btn-info addDocs m-t-10 m-b-10 " style="" onclick="showAdd()"><i
                                                            class="fa fa-plus"></i> @lang('app.add')</button>
                                                <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th width="70%">@lang('app.name')</th>
                                                        <th>@lang('app.action')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="employeeDocsList">
                                                    @forelse($clientDocs as $key=>$clientDoc)
                                                        <tr>
                                                            <td>{{ $key+1 }}</td>
                                                            <td width="70%">{{ ucwords($clientDoc->name) }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.client-docs.download', $clientDoc->id) }}"
                                                                   data-toggle="tooltip" data-original-title="Download"
                                                                   class="btn btn-default btn-circle"><i
                                                                            class="fa fa-download"></i></a>
                                                                <a target="_blank" href="{{ $clientDoc->file_url }}"
                                                                   data-toggle="tooltip" data-original-title="View"
                                                                   class="btn btn-info btn-circle"><i
                                                                            class="fa fa-search"></i></a>
                                                                <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $clientDoc->id }}"
                                                                   data-pk="list" class="btn btn-danger btn-circle sa-params"><i class="fa fa-times"></i></a>
                                                            </td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3">@lang('messages.noDocsFound')</td>
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
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="edit-column-form" role="dialog" aria-labelledby="myModalLabel"
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
        function showAdd() {
            var url = "{{ route('admin.client-docs.quick-create', [$client->id]) }}";
            $.ajaxModal('#edit-column-form', url);
        }

        $('body').on('click', '.sa-params', function () {
            var id = $(this).data('file-id');
            var deleteView = $(this).data('pk');
            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.confirmation.deleteFile')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.deleteConfirmation')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {

                    var url = "{{ route('admin.client-docs.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE', 'view': deleteView},
                        success: function (response) {
                            console.log(response);
                            if (response.status == "success") {
                                $.unblockUI();
                                $('#employeeDocsList').html(response.html);
                            }
                        }
                    });
                }
            });
        });

        $('ul.showClientTabs .clientDocs').addClass('tab-current');
    </script>
@endpush
