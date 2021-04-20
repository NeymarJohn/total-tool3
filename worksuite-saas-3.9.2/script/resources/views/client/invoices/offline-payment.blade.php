<style>
    .input-group[class*=col-] {
        padding-right: 7px !important;
        padding-left: 8px !important;
    }

</style>
<div id="event-detail">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-cash"></i> @lang('modules.payments.paymentDetails')</h4>
    </div>
    <div class="modal-body">
        <div class="form-body">
            {!! Form::open(['id'=>'saveDetail','class'=>'ajax-form','method'=>'POST']) !!}
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12">
                           <label class="control-label">Slip</label>
                            <div class="fileinput fileinput-new input-group"  data-provides="fileinput">
                                    {{--<div style="display:table;">--}}
                                    <div class="form-control " data-trigger="fileinput" >
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">@lang('app.selectFile')</span>
                                        <span class="fileinput-exists">@lang('app.change')</span>
                                        <input type="file" name="slip" id="slip">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                       data-dismiss="fileinput">@lang('app.remove')</a>
                                    {{--<div class="help-block">This is required</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="help-block">This is required</div>--}}
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.description')</label>
                                <textarea class="form-control" rows="4" name="description"></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="offline_id" value="{{ $offlineId }}">
                <input type="hidden" name="invoice_id" value="{{ $invoiceId }}">
            {{ Form::close() }}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
        <button type="button" class="btn btn-primary waves-effect" onclick="saveDetails(); return false;">@lang('app.save')</button>
    </div>
</div>
<script>
    function saveDetails()
    {
        $.easyAjax({
            url: '{{ route('client.invoices.offline-payment-submit') }}',
            type: "POST",
            container:'#saveDetail',
            messagePosition:'inline',
            file:true
        })
    }

</script>

