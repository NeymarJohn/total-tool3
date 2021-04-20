@extends('layouts.member-app')

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
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.clients.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.menu.contacts')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">

@endpush

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="white-box">

                <div class="row">
                    <div class="col-xs-6 b-r"> <strong>@lang('modules.employees.fullName')</strong> <br>
                        <p class="text-muted">{{ ucwords($client->name) }}</p>
                    </div>
                    <div class="col-xs-6"> <strong>@lang('app.mobile')</strong> <br>
                        <p class="text-muted">{{ $client->mobile ?? '-'}}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 col-xs-6 b-r"> <strong>@lang('app.email')</strong> <br>
                        <p class="text-muted">{{ $client->email }}</p>
                    </div>
                    <div class="col-md-3 col-xs-6"> <strong>@lang('modules.client.companyName')</strong> <br>
                        <p class="text-muted">{{ (count($client->client) > 0) ? ucwords($client->client[0]->company_name) : '-'}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">
                        <nav>
                            <ul>

                                <li><a href="{{ route('member.clients.projects', $client->id) }}"><span>@lang('app.menu.projects')</span></a></li>
                                <li><a href="{{ route('member.clients.invoices', $client->id) }}"><span>@lang('app.menu.invoices')</span></a></li>
                                <li><a href="{{ route('member.contacts.show', $client->id) }}"><span>@lang('app.menu.contacts')</span></a></li>
                                <li class="tab-current"><a href="{{ route('member.notes.show',$client->id) }}" class="waves-effect"><i class="fa fa-sticky-note-o"></i> <span class="hide-menu">@lang('modules.projects.notes') </span></a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-xs-12" id="issues-list-panel">
                                    <div class="white-box">
                                        <h2>@lang('app.menu.notes')</h2>
                                        <div class="table-responsive m-t-30">
                                            <table
                                                class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                                                id="contacts-table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('app.id')</th>
                                                        <th>@lang('app.notesTitle')</th>
                                                        <th>@lang('app.notesType')</th>
                                                        <th>@lang('app.action')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </section>

                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>
        {{-- Ajax Modal --}}
        <div class="modal fade bs-modal-md in" id="editContactModal" role="dialog" aria-labelledby="myModalLabel"
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
        {{-- Ajax Modal Ends --}}

    </div>

@endsection

@push('footer-script')

<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>


    <script>
        $(function() {
        var table = $('#contacts-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('member.notes.data') }}',
            deferRender: true,
            "order": [[ 0, "desc" ]],
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function( oSettings ) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
            { data: 'id', name: 'id' },
            { data: 'notes_title', name: 'notes_title' },
            { data: 'notes_type', name: 'notes_type' },
             { data: 'action', name: 'action' }
        ]
        });

    });   
             $('body').on('click', '.edit-contact', function () {
                var id = $(this).data('contact-id');

                var url = '{{ route('member.notes.edit', ':id')}}';
                url = url.replace(':id', id);

                $('#modelHeading').html('Update Contact');
                $.ajaxModal('#editContactModal',url);

            });
            $('body').on('click', '.view-notes-modal', function () {
                var id = $(this).data('contact-id');
              
                $("#modal-data-application").removeClass("modal-dialog modal-lg");
                 $("#modal-data-application").addClass("modal-dialog modal-md");
                var url = '{{ route('member.notes.verify-password', ':id')}}';
                url = url.replace(':id', id);

                $('#modelHeading').html('Update Contact');
                $.ajaxModal('#editContactModal',url);

            });
            $('body').on('click', '.view-notes', function () {
                var id = $(this).data('contact-id');
                $("#modal-data-application").removeClass("modal-dialog modal-md");
                 $("#modal-data-application").addClass("modal-dialog modal-lg");
                var url = '{{ route('member.notes.view', ':id')}}';
                url = url.replace(':id', id);

                $('#modelHeading').html('Update Contact');
                $.ajaxModal('#editContactModal',url);

            });
    $('body').on('click', '.sa-params', function(){
        var id = $(this).data('contact-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.recoverNotes')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.deleteConfirmation')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {
                var url = "{{ route('member.notes.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    location.reload();


                                }
                            }
                });
            }
        });
    });
             $('#show-add-form,#close-form').click(function () {
                $('#addNotes').toggleClass('hide', 'show');
            });
            

    </script>
@endpush
