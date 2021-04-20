@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $lead->id }} - <span
                        class="font-bold">{{ ucwords($lead->company_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.leads.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projects.files')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')

<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">
                        <nav>
                            <ul>
                                <li class="tab-current"><a href="{{ route('member.leads.show', $lead->id) }}"><span>@lang('modules.lead.profile')</span></a>
                                </li>
                                @if($user->can('edit_lead'))
                                    <li><a href="{{ route('member.proposals.show', $lead->id) }}"><span>@lang('modules.lead.proposal')</span></a></li>
                                    <li ><a href="{{ route('member.lead-files.show', $lead->id) }}"><span>@lang('modules.lead.file')</span></a></li>
                                    <li><a href="{{ route('member.leads.followup', $lead->id) }}"><span>@lang('modules.lead.followUp')</span></a></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-md-12" id="files-list-panel">

                                    <div class="white-box">
                                        <div class="row">
                                            <div class="col-xs-6 b-r"> <strong>@lang('modules.lead.companyName')</strong> <br>
                                                <p class="text-muted">{{ ucwords($lead->company_name) }}</p>
                                            </div>
                                            <div class="col-xs-6"> <strong>@lang('modules.lead.website')</strong> <br>
                                                <p class="text-muted">{{ $lead->website ?? 'NA'}}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-xs-6 b-r"> <strong>@lang('modules.lead.mobile')</strong> <br>
                                                <p class="text-muted">{{ $lead->mobile ?? 'NA'}}</p>
                                            </div>
                                            <div class="col-xs-6"> <strong>@lang('modules.lead.address')</strong> <br>
                                                <p class="text-muted">{{ $lead->address ?? 'NA'}}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-xs-6 b-r" > <strong>@lang('modules.lead.clientName')</strong> <br>
                                                <p class="text-muted">{{ $lead->client_name ?? 'NA'}}</p>
                                            </div>
                                            <div class="col-xs-6"> <strong>@lang('modules.lead.clientEmail')</strong> <br>
                                                <p class="text-muted">{{ $lead->client_email ?? 'NA'}}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            @if($lead->source_id != null)
                                            <div class="col-xs-6 b-r"> <strong>@lang('modules.lead.source')</strong> <br>
                                                <p class="text-muted">{{ $lead->lead_source->type ?? 'NA'}}</p>
                                            </div>
                                            @endif
                                            @if($lead->status_id != null)
                                            <div class="col-xs-6"> <strong>@lang('modules.lead.status')</strong> <br>
                                                <p class="text-muted">{{ $lead->lead_status->type ?? 'NA'}}</p>
                                            </div>
                                            @endif
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-xs-12"> <strong>@lang('app.note')</strong> <br>
                                                <p class="text-muted">{{ $lead->note ?? 'NA'}}</p>
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

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>
<script>
    $('#show-dropzone').click(function () {
        $('#file-dropzone').toggleClass('hide show');
    });

    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    // "myAwesomeDropzone" is the camelized version of the HTML element's ID
    Dropzone.options.fileUploadDropzone = {
        paramName: "file", // The name that will be used to transfer the file
//        maxFilesize: 2, // MB,
        dictDefaultMessage: "@lang('modules.projects.dropFile')",
        accept: function (file, done) {
            done();
        },
        init: function () {
            this.on("success", function (file, response) {
                console.log(response);
                $('#files-list-panel ul.list-group').html(response.html);
            })
        }
    };

    $('body').on('click', '.sa-params', function () {
        var id = $(this).data('file-id');
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.removeFileText')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {

                var url = "{{ route('member.files.destroy',':id') }}";
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
                            $('#files-list-panel ul.list-group').html(response.html);

                        }
                    }
                });
            }
        });
    });

</script>
@endpush