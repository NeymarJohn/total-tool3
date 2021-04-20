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
                <li><a href="{{ route('admin.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.projects')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('css/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables/buttons.dataTables.min.css') }}">
@endpush

@section('content')

    <div class="row">


        @include('admin.clients.client_header')


        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    @include('admin.clients.tabs')

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
                                                <div class="col-md-12">
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
                                                                                    <label class="control-label required">@lang('modules.gdpr.additionalDescription')</label>
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
                                                            <p class="text-center">No Consent available.</p>
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
<script src="{{ asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
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
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'purpose_consent_leads.status' },
            { data: 'ip', name: 'purpose_consent_leads.ip' },
            { data: 'username', name: 'users.name' },
            { data: 'additional_description', name: 'purpose_consent_leads.additional_description' }
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
<script>
    $('ul.showClientTabs .clientGdpr').addClass('tab-current');
</script>
@endpush