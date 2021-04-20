<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"> <i class="fa fa-search"></i>  @lang('modules.payments.paymentDetails')</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-xs-12">
                @forelse($creditNote->payment as $payment)
                    <div class="list-group-item edit-task">
                        <h5 class="list-group-item-heading sbold">@lang('app.paymentOn'): {{ $payment->paid_on->format($global->date_format) }}</h5>
                        <p class="list-group-item-text">
                        <div class="row margin-top-5">
                            <div class="col-md-4">
                                <b>@lang('app.amount'):</b>  <br>
{{$creditNote->currency->currency_symbol}} {{$payment->amount}}
                            </div>
                            <div class="col-md-4">
                                <b>@lang('app.gateway'):</b>  <br>
                                {{$payment->gateway}}
                            </div>
                            <div class="col-md-4">
                                <b>@lang('app.transactionId'):</b> <br>
                                {{$payment->transaction_id}}
                            </div>
                        </div>
                        <div class="row margin-top-5">
                            <div class="col-md-12">
                                <b>@lang('app.remark'):</b>  <br>
                                {!!  ($payment->remarks != '') ? ucfirst($payment->remarks) : "<span class='font-red'>--</span>" !!}
                            </div>
                        </div>

                        </p>
                    </div>
                @empty
                    <p>@lang('modules.payments.paymentDetailNotFound')</p>
                @endforelse
            </div>
        </div>
        <!--/row-->
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
</div>


