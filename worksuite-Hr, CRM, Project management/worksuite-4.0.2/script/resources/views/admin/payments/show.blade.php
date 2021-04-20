<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"> <i class="fa fa-search"></i>  @lang('modules.payments.paymentDetails')</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-xs-12">
                <div class="edit-task">
                    <h4 class="list-group-item-heading sbold m-b-10">@lang('app.paymentOn'): {{ $payment->paid_on->format($global->date_format) }}</h4>
                    <p class="list-group-item-text">
                    <div class="row margin-top-5">
                        <div class="col-md-4">
                            <b>@lang('app.invoice')</b>  <br>
                            {{ (!is_null($payment->invoice_id)) ? $payment->invoice->invoice_number : "--"}}
                        </div>
                        <div class="col-md-4">
                            <b>@lang('app.project')</b>  <br>
                            {{ (!is_null($payment->project_id)) ? $payment->project->project_name : "--"}}
                        </div>
                        <div class="col-md-4">
                            <b>@lang('app.status')</b> <br>
                            @if ($payment->status == 'pending')
                                <label class="label label-warning">{{ strtoupper($payment->status) }}</label>
                            @else
                                <label class="label label-success">{{ strtoupper($payment->status) }}</label>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row margin-top-5">
                        <div class="col-md-3">
                            <b>@lang('app.amount')</b>  <br>
                            {{$payment->currency->currency_symbol}} {{$payment->amount}}
                        </div>
                        <div class="col-md-3">
                            <b>@lang('app.gateway')</b>  <br>
                            {{$payment->gateway}}
                        </div>
                        <div class="col-md-3">
                            <b>@lang('app.transactionId')</b> <br>
                            {{$payment->transaction_id}}
                        </div>
                        <div class="col-md-3">
                            <b>@lang('app.receipt')</b> <br>
                            @if(!is_null($payment->bill))
                                <a target="_blank"
                                    href="{{ $payment->file_url }}">@lang('app.view') @lang('app.receipt') <i class="fa fa-external-link"></i></a>
                            @else
                            --
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row margin-top-5">
                        @if(!is_null($payment->offline_method_id))
                            <div class="col-md-6">
                                <b>@lang('app.details')</b>  <br>
                                {!! $payment->offlineMethod->name !!}<br>
                                {!! $payment->offlineMethod->description !!}
                            </div>
                        @endif

                         <div class="col-md-6">
                            <b>@lang('app.remark')</b>  <br>
                            {!!  ($payment->remarks != '') ? ucfirst($payment->remarks) : "<span class='font-red'>--</span>" !!}
                        </div>
                    </div>

                    </p>
                </div>
            </div>
        </div>
        <!--/row-->
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
</div>


