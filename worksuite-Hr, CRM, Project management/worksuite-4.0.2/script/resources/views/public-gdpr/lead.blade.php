@extends('layouts.public-gdpr')

@section('content')
    <div class="col-md-offset-1 col-md-10 col-md-offset-1">


        <div class="row m-b-10 text-right">
            <div class="col-md-12">
                @if($gdprSetting->consent_leads)
                    <a href="{{ route('front.gdpr.consent', md5($lead->id)) }}" target="_blank" class="btn btn-success"><i class="fa fa-eye"></i> @lang('app.view') @lang('modules.gdpr.consent')</a>
                @endif
                    @if($gdprSetting->lead_removal_public_form)
                        <button class="btn btn-danger" onclick="removeLeadRequest()"><i class="fa fa-trash"></i> @lang('modules.gdpr.requestDataRemoval')</button>
                    @endif
            </div>
        </div>


        <div class="panel panel-inverse">
            <div class="panel-heading"> @lang('modules.lead.updateTitle')</div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    {!! Form::open(['id'=>'updateLead','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="form-body">
                        <h3 class="box-title">@lang('modules.lead.companyDetails')</h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">@lang('modules.lead.companyName')</label>
                                    <input type="text" id="company_name" name="company_name" class="form-control"  value="{{ $lead->company_name ?? '' }}">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">@lang('modules.lead.website')</label>
                                    <input type="text" id="website" name="website" class="form-control" value="{{ $lead->website ?? '' }}" >
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">@lang('app.address')</label>
                                    <textarea name="address"  id="address"  rows="5" class="form-control">{{ $lead->address ?? '' }}</textarea>
                                </div>
                            </div>
                            <!--/span-->

                        </div>
                        <!--/row-->

                        <h3 class="box-title m-t-40">@lang('modules.lead.leadDetails')</h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label>@lang('modules.lead.clientName')</label>
                                    <input type="text" name="client_name" id="client_name" class="form-control" value="{{ $lead->client_name }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('modules.lead.clientEmail')</label>
                                    <input type="email" name="client_email" id="client_email" class="form-control" value="{{ $lead->client_email }}">
                                    <span class="help-block">@lang('modules.lead.emailNote')</span>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <!--/span-->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('modules.lead.mobile')</label>
                                    <input type="tel" name="mobile" id="mobile" value="{{ $lead->mobile }}" class="form-control">
                                </div>
                            </div>
                            <!--/span-->
                        </div>

                        <!--/row-->

                    </div>
                    <div class="form-actions">
                        <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="consentModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading">@lang('modules.gdpr.requestDataRemoval')</span>
                </div>
                {!! Form::open(['id'=>'removeUser','class'=>'ajax-form']) !!}
                <div class="modal-body">
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-xs-12 ">
                                <div class="form-group">
                                    <label>@lang('app.description')</label>
                                    <textarea name="description" class="form-control" placeholder="@lang('modules.gdpr.dataRemovalDescription')"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" id="save-consent"  onclick="sendRemoveRequest();" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.submit')</button>
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

        function removeLeadRequest() {
            $('#consentModal').modal('show')
        }

        function sendRemoveRequest(){
            $.easyAjax({
                url: '{{route('front.gdpr.remove-lead-request')}}',
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

        $(".date-picker").datepicker({
            todayHighlight: true,
            autoclose: true
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('front.gdpr.lead.update', [md5($lead->id)])}}',
                container: '#updateLead',
                type: "POST",
                data: $('#updateLead').serialize()
            })
        });
    </script>
@endpush