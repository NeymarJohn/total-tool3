@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $lead->id }} - <span
                        class="font-bold">{{ ucwords($lead->company_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.leads.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('modules.projects.files')</li>
            </ol>
        </div>
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.leads.edit',$lead->id) }}"
               class="btn btn-outline btn-success btn-sm">@lang('modules.lead.edit')
                <i class="fa fa-edit" aria-hidden="true"></i></a>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')

<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">
                        <nav>
                            <ul>
                                <li class="tab-current"><a href="{{ route('admin.leads.show', $lead->id) }}"><span>@lang('modules.lead.profile')</span></a>
                                </li>
                                <li><a href="{{ route('admin.proposals.show', $lead->id) }}"><span>@lang('modules.lead.proposal')</span></a></li>
                                <li ><a href="{{ route('admin.lead-files.show', $lead->id) }}"><span>@lang('modules.lead.file')</span></a></li>
                                <li><a href="{{ route('admin.leads.followup', $lead->id) }}"><span>@lang('modules.lead.followUp')</span></a></li>
                                @if($gdpr->enable_gdpr)
                                    <li><a href="{{ route('admin.leads.gdpr', $lead->id) }}"><span>@lang('app.menu.gdpr')</span></a></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-xs-12" id="files-list-panel">
                                    <div class="white-box">
                                        <h2>@lang('modules.lead.leadDetail')</h2>

                                        <div class="white-box">
                                            <div class="row">
                                                <div class="col-xs-6 b-r"> <strong>@lang('modules.lead.companyName')</strong> <br>
                                                    <p class="text-muted">{{ ucwords($lead->company_name) }}</p>
                                                </div>
                                                <div class="col-xs-6"> <strong>@lang('modules.lead.website')</strong> <br>
                                                    <p class="text-muted">{{ $lead->website ?? '-'}}</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-xs-6 b-r"> <strong>@lang('modules.lead.mobile')</strong> <br>
                                                    <p class="text-muted">{{ $lead->mobile ?? '-'}}</p>
                                                </div>
                                                <div class="col-xs-6 b-r"> <strong>@lang('modules.clients.officePhoneNumber')</strong> <br>
                                                    <p class="text-muted">{{ $lead->office_phone ?? '-'}}</p>
                                                </div>

                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-xs-6 b-r"> <strong>@lang('modules.lead.address')</strong> <br>
                                                    <p class="text-muted">{{ $lead->address ?? '-'}}</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-xs-6 col-md-4 b-r" > <strong>@lang('modules.lead.clientName')</strong> <br>
                                                    <p class="text-muted">{{ $lead->client_name ?? '-'}}</p>
                                                </div>
                                                <div class="col-xs-6 col-md-4 b-r"> <strong>@lang('modules.lead.clientEmail')</strong> <br>
                                                    <p class="text-muted">{{ $lead->client_email ?? '-'}}</p>
                                                </div>
                                                <div class="col-xs-6 col-md-4"> <strong>@lang('app.lead') @lang('app.value')</strong> <br>
                                                    <p class="text-muted">{{ company()->currency->currency_symbol.$lead->value ?? '-'}}</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                @if($lead->agent_id != null)
                                                <div class="col-md-4 b-r"> <strong>@lang('modules.lead.leadAgent')</strong> <br>
                                                   <p>
                                                        <img src="{{ $lead->lead_agent->user->image_url }}" alt="user" class="img-circle" width="25" height="25">
                                                        {{ ucwords($lead->lead_agent->user->name)}}
                                                   </p>
                                                    
                                                </div>
                                                @endif
                                                @if($lead->source_id != null)
                                                <div class="col-xs-6 col-md-4 b-r"> <strong>@lang('modules.lead.source')</strong> <br>
                                                    <p class="text-muted">{{ $lead->lead_source->type ?? '-'}}</p>
                                                </div>
                                                @endif
                                                @if($lead->status_id != null)
                                                <div class="col-xs-6 col-md-4 b-r"> <strong>@lang('modules.lead.status')</strong> <br>
                                                    <p class="text-muted">{{ $lead->lead_status->type ?? '-'}}</p>
                                                </div>
                                                @endif
                                                @if($lead->category_id != null)
                                                <div class="col-md-4"> <strong>@lang('modules.lead.leadCategory')</strong> <br>
                                                    <p class="text-muted"> @foreach($categories as $category)
                                                    @if($lead->category_id == $category->id)
                                                    {{ ucwords($category->category_name) }}
                                                    @endif
                                                     @endforeach
                                                </div>
                                                @endif
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-xs-12"> <strong>@lang('app.note')</strong> <br>
                                                    <p class="text-muted">@if($lead->note) {!! $lead->note !!} @else - @endif </p>
                                                </div>
                                            </div>

                                            {{--Custom fields data--}}
                        @if(isset($fields))
                        <div class="row">
                            <hr>
                            @foreach($fields as $field)
                                <div class="col-md-6">
                                    <strong>{{ ucfirst($field->label) }}</strong> <br>
                                    <p class="text-muted">
                                        @if( $field->type == 'text')
                                            {{$lead->custom_fields_data['field_'.$field->id] ?? ''}}
                                        @elseif($field->type == 'password')
                                            {{$lead->custom_fields_data['field_'.$field->id] ?? ''}}
                                        @elseif($field->type == 'number')
                                            {{$lead->custom_fields_data['field_'.$field->id] ?? ''}}

                                        @elseif($field->type == 'textarea')
                                        {{$lead->custom_fields_data['field_'.$field->id] ?? ''}}

                                        @elseif($field->type == 'radio')
                                        {{ !is_null($lead->custom_fields_data['field_'.$field->id]) ? $lead->custom_fields_data['field_'.$field->id] : '-' }}
                                        @elseif($field->type == 'select')
                                            {{ (!is_null($lead->custom_fields_data['field_'.$field->id]) && $lead->custom_fields_data['field_'.$field->id] != '') ? $field->values[$lead->custom_fields_data['field_'.$field->id]] : '-' }}
                                        @elseif($field->type == 'checkbox')
                                            {{ !is_null($lead->custom_fields_data['field_'.$field->id]) ? $field->values[$lead->custom_fields_data['field_'.$field->id]] : '-' }}
                                        @elseif($field->type == 'date')
                                            {{ !is_null($lead->custom_fields_data['field_'.$field->id]) ? \Carbon\Carbon::parse($lead->custom_fields_data['field_'.$field->id])->format($global->date_format) : '--'}}
                                        @endif
                                    </p>

                                </div>
                            @endforeach
                        </div>
                        @endif

                    {{--custom fields data end--}}
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

                var url = "{{ route('admin.files.destroy',':id') }}";
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
