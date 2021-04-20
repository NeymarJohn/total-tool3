@extends('layouts.client-app')

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
                <li><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <style>
        .col-in {
            padding: 0 20px !important;

        }

        .fc-event {
            font-size: 10px !important;
        }

        @media (min-width: 769px) {
            .panel-wrapper {
                height: 500px;
                overflow-y: auto;
            }
        }

    </style>
@endpush

@section('content')

    <div class="row dashboard-stats">
        @if($gdpr->top_information_block)
            <div class="col-md-12 ">
                <div class="white-box">
                    {!! $gdpr->top_information_block  !!}
                </div>
            </div>
        @endif
        @if($gdpr->terms || $gdpr->policy || $gdpr->customer_footer )
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="row">

                        <div class="col-xs-12 text-center">
                            <span class="widget-title"> @lang('modules.gdpr.rightToInform')</span>
                            <br>
                            <br>
                            <span class="counter"><a href="{{route('client.gdpr.terms')}}" id="save-form"
                                                     class="btn btn-info">@lang('modules.gdpr.termCondition')</a></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($gdpr->public_lead_edit )
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="row">

                        <div class="col-xs-12 text-center">
                            <span class="widget-title"> @lang('modules.gdpr.rightOfAccess')</span>
                            <br>
                            <br>
                            <span class="counter">
                            <a href="{{route('client.profile.index')}}"
                               class="btn btn-info"> @lang('modules.gdpr.editInformation')</a></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($gdpr->data_removal )
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="row">

                        <div class="col-xs-12 text-center">
                            <span class="widget-title"> @lang('modules.gdpr.rightToErasure')</span>
                            <br>
                            <br>
                            <span class="counter">
                                <button type="button" class="btn btn-info"
                                        onclick="removeUserRequest();">@lang('modules.gdpr.requestDataRemove')</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($gdpr->enable_export )
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="row">

                        <div class="col-xs-12 text-center">
                            <span class="widget-title"> @lang('modules.gdpr.rightToDataProtability')</span>
                            <br>
                            <br>
                            <span class="counter"><a href="{{route('client.gdpr.download-json')}}"  id="save-form"
                                                          class="btn btn-info">@lang('modules.gdpr.exportData')</a></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($gdpr->consent_customer )
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="row">

                        <div class="col-xs-12 text-center">
                            <span class="widget-title"> @lang('modules.gdpr.consent')</span>
                            <br>
                            <br>
                            <span class="counter"><a href="{{ route('client.gdpr.consent') }}" class="btn btn-info">@lang('modules.gdpr.consent')</a></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="consentModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading">@lang('modules.gdpr.requestAccountRemoval')</span>
                </div>
                {!! Form::open(['id'=>'removeUser','class'=>'ajax-form']) !!}
                <div class="modal-body">

                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-12 ">
                                <div class="form-group">
                                    <label>@lang('modules.offlinePayment.description')</label>
                                    <textarea name="description" class="form-control" placeholder=@lang('modules.gdpr.accountRemovalDescription')></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">@lang('app.close')</button>
                    <button type="submit" id="save-consent"  onclick="submitForm();" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.submit')</button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
    <script>
        function removeUserRequest() {
            $('#consentModal').modal('show')
        }

        function submitForm(){
            $.easyAjax({
                url: '{{route('client.gdpr.remove-request')}}',
                container: '#removeUser',
                type: "POST",
                data: $('#removeUser').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        $('#consentModal').modal('hide');
                    }
                }
            })
        }

        function downloadJSON(){
            $.easyAjax({
                url: '{{route('client.gdpr.download-json')}}',
                container: '#removeUser',
                type: "POST",
                data: $('#removeUser').serialize(),
            })
        }

    </script>
@endpush