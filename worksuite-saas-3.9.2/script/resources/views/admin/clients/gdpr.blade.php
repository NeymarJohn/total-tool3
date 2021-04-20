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
                <li class="active">@lang('app.menu.projects')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
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
                        <p class="text-muted">{{ (!empty($client->client) ) ? ucwords($client->client[0]->company_name) : '-'}}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 col-xs-6 b-r"> <strong>@lang('modules.client.website')</strong> <br>
                        <p class="text-muted">{{ $clientDetail->website ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 col-xs-6"> <strong>@lang('app.address')</strong> <br>
                        <p class="text-muted">{!!  (!empty($client->client)) ? ucwords($client->client[0]->address) : '-' !!}</p>
                    </div>
                </div>

                {{--Custom fields data--}}
                @if(isset($fields))
                    <div class="row">
                        <hr>
                        @foreach($fields as $field)
                            <div class="col-md-4">
                                <strong>{{ ucfirst($field->label) }}</strong> <br>
                                <p class="text-muted">
                                    @if( $field->type == 'text')
                                        {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}
                                    @elseif($field->type == 'password')
                                        {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}
                                    @elseif($field->type == 'number')
                                        {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}

                                    @elseif($field->type == 'textarea')
                                        {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}

                                    @elseif($field->type == 'radio')
                                        {{ !is_null($clientDetail->custom_fields_data['field_'.$field->id]) ? $clientDetail->custom_fields_data['field_'.$field->id] : '-' }}
                                    @elseif($field->type == 'select')
                                        {{ (!is_null($clientDetail->custom_fields_data['field_'.$field->id]) && $clientDetail->custom_fields_data['field_'.$field->id] != '') ? $field->values[$clientDetail->custom_fields_data['field_'.$field->id]] : '-' }}
                                    @elseif($field->type == 'checkbox')
                                        {{ !is_null($clientDetail->custom_fields_data['field_'.$field->id]) ? $field->values[$clientDetail->custom_fields_data['field_'.$field->id]] : '-' }}
                                    @elseif($field->type == 'date')
                                        {{ isset($clientDetail->dob)?Carbon\Carbon::parse($clientDetail->dob)->format($global->date_format):Carbon\Carbon::now()->format($global->date_format)}}
                                    @endif
                                </p>

                            </div>
                        @endforeach
                    </div>
                @endif

                {{--custom fields data end--}}

            </div>
        </div>

        <div class="col-xs-12">

            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">
                        <nav>
                            <ul>
                                <li><a href="{{ route('admin.clients.projects', $client->id) }}"><span>@lang('app.menu.projects')</span></a>
                                <li><a href="{{ route('admin.clients.invoices', $client->id) }}"><span>@lang('app.menu.invoices')</span></a>
                                </li>
                                <li><a href="{{ route('admin.contacts.show', $client->id) }}"><span>@lang('app.menu.contacts')</span></a>
                                @if($gdpr->enable_gdpr)
                                <li class="tab-current"><a href="{{ route('admin.clients.gdpr', $client->id) }}"><span>@lang('modules.gdpr.gdpr')</span></a>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="row">
                                <div class="@if($gdpr->consent_customer)col-md-8 @else col-md-12 @endif " id="follow-list-panel">
                                    <div class="white-box">

                                        <div class="row m-b-10">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="consent-table">
                                                    <thead>
                                                    <tr>
                                                        <th>@lang('modules.gdpr.purpose')</th>
                                                        <th>@lang('app.date')</th>
                                                        <th>@lang('app.action')</th>
                                                        <th>@lang('modules.gdpr.ipAddress')</th>
                                                        <th>@lang('modules.gdpr.staffMember')</th>
                                                        <th>@lang('modules.gdpr.additionalDescription')</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($gdpr->consent_customer)
                                    <div class="col-md-4">
                                        <div class="white-box">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <h4 class="box-title">@lang('modules.gdpr.consent')</h4>
                                                    <hr>
                                                    <div class="panel-group" role="tablist" class="minimal-faq" aria-multiselectable="true">
                                                        @forelse($allConsents as $allConsent)
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="heading_{{ $allConsent->id }}">
                                                                    <h4 class="panel-title">
                                                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $allConsent->id }}" aria-expanded="true" aria-controls="collapse_{{ $allConsent->id }}" class="font-bold">
                                                                            @if($allConsent->user && $allConsent->user->status == 'agree') <i class="fa fa-check text-success"></i> @else <i class="fa fa-remove fa-2x text-danger"></i> @endif {{ $allConsent->name }}
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse_{{ $allConsent->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{ $allConsent->id }}">
                                                                    <div class="panel-body">
                                                                        {!! Form::open(['id'=>'updateConsentLeadData_'.$allConsent->id,'class'=>'ajax-form','method'=>'POST']) !!}
                                                                        <input type="hidden" name="consent_id" value="{{ $allConsent->id }}">
                                                                        <input type="hidden" name="status" value="@if($allConsent->user && $allConsent->user->status == 'agree') disagree @else agree @endif">
                                                                        <div class="row">
                                                                            <div class="col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">@lang('modules.gdpr.additionalDescription')</label>
                                                                                    <textarea name="additional_description"  rows="5" class="form-control"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        @if(($allConsent->user && $allConsent->user->status == 'disagree') || !$allConsent->user)
                                                                            <div class="row">
                                                                                <div class="col-xs-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label">@lang('modules.gdpr.purposeDescription')</label>
                                                                                        <textarea name="consent_description" rows="5" class="form-control">{{ $allConsent->description }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        <div class="form-actions">
                                                                            <a href="javascript:;" onclick="saveConsentLeadData({{ $allConsent->id }})" class="btn @if($allConsent->user && $allConsent->user->status == 'agree') btn-danger @else btn-success @endif">
                                                                                @if($allConsent->user && $allConsent->user->status == 'agree')
                                                                                    @lang('modules.gdpr.optOut')
                                                                                @else
                                                                                    @lang('modules.gdpr.optIn')
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <p class="text-center">@lang('modules.gdpr.noConsent')</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script type="text/javascript">

    table = $('#consent-table').dataTable({
        responsive: true,
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.clients.consent-purpose-data', $client->id) !!}',
        language: {
            "url": "<?php echo __("app.datatable") ?>"
        },
        "fnDrawCallback": function( oSettings ) {
            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });
        },
        columns: [
            { data: 'name', name: 'purpose_consent.name' },
            { data: 'created_at', name: 'purpose_consent_users.created_at' },
            { data: 'status', name: 'purpose_consent_users.status' },
            { data: 'ip', name: 'purpose_consent_users.ip' },
            { data: 'username', name: 'users.name' },
            { data: 'additional_description', name: 'purpose_consent_users.additional_description' }
        ]
    });


    function saveConsentLeadData(id) {
        var formId = '#updateConsentLeadData_'+id;

        $.easyAjax({
            url: '{{route('admin.clients.save-consent-purpose-data', $client->id)}}',
            container: formId,
            type: "POST",
            data: $(formId).serialize(),
            redirect: true
        })
    }

</script>
@endpush
