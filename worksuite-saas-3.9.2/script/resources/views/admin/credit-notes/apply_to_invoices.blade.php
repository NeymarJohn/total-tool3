<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"> <i class="fa fa-search"></i>  @lang('modules.credit-notes.applyToInvoice')</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        @if ($nonPaidInvoices->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <th>@lang('app.invoiceNumber') #</th>
                    <th>@lang('app.credit-notes.invoiceDate')</th>
                    <th>@lang('app.credit-notes.invoiceAmount')</th>
                    <th>@lang('app.credit-notes.invoiceBalanceDue')</th>
                    <th>@lang('app.credit-notes.amountToCredit')</th>
                </thead>
                <tbody>
                    @forelse ($nonPaidInvoices as $invoice)
                        <tr>
                            <td>
                                {{ $invoice->invoice_number }}
                            </td>
                            <td>
                                {{ $invoice->issue_date->format($global->date_format) }}
                            </td>
                            <td>
                                {{ $invoice->currency->currency_symbol.' '.$invoice->total }}
                            </td>
                            <td>
                                {{ $invoice->currency->currency_symbol.' '.($invoice->amountDue()) }}
                            </td>
                            <td>
                            <input data-invoice-id="{{ $invoice->id }}" data-balance-due='{{ $invoice->amountDue() }}' type="number" max="{{ min($creditNote->total, $invoice->amountDue()) }}" min="0" value="0" step="0.01" class="form-control amt-to-credit">
                            </td>
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-6 col-md-offset-6">
                    <div class="text-right">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="bold">@lang('app.credit-notes.amountToCredit'):</td>
                                    <td>{{ $global->currency->currency_symbol }} <span class="amount-to-credit">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="bold">@lang('app.credit-notes.remainingAmount'):</td>
                                    <td>
                                    {{ $global->currency->currency_symbol }} <span class="credit-note-balance-due">{{ $creditNote->total - $creditNote->invoices()->sum('credit_amount')}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <p>@lang('modules.credit-notes.noInvoicesFound')</p>
        @endif
        <!--/row-->
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
    @if ($nonPaidInvoices->count() > 0)
        <button type="button" class="btn btn-success waves-effect waves-light" id="apply-invoice">@lang('app.apply')</button>
    @endif
</div>

<script>
    function getTotalAmountToCredit() {
        let amount = 0.00;

        $('.amt-to-credit').each(function() {
            if ($(this).val() !== '0') {
                amount += parseFloat($(this).val());
            }
        })

        return amount;
    }

    $('.amt-to-credit').focus(function () {
        $(this).select();
    })

    $('.amt-to-credit').on('change keyup', function() {
        if ($(this).val() == '') {
            $(this).val('0');
        }

        if (parseFloat($(this).val()) > parseFloat($(this).prop('max'))) {
            $(this).val($(this).prop('max'))
        }

        let creditBalance = parseFloat('{{ $creditNote->creditAmountRemaining() }}');
        let amount = getTotalAmountToCredit();
        let remainingAmount = creditBalance - amount;
        console.log(remainingAmount);
        if (remainingAmount <= 0) {
            $(this).prop('max', $(this).val())
        }
        else {
            if ($(this).val() !== '' && $(this).val() !== '0' ) {
                $(this).prop('max', Math.min(remainingAmount + parseFloat($(this).val()), parseFloat($(this).data('balance-due'))))
            }
            else {
                $(this).prop('max', remainingAmount)
            }
        }

        $('.amount-to-credit').html(amount.toFixed(2))
        $('.credit-note-balance-due').html(remainingAmount.toFixed(2))
    })

    $('#apply-invoice').click(function () {
        let data = {invoices: []};
        const remainingAmount = $('.credit-note-balance-due').html();

        $('.amt-to-credit').each(function () {
            const invoiceId = $(this).data('invoice-id');
            const value = $(this).val();

            data.invoices = [...data.invoices, { invoiceId: invoiceId, value: value }];
        })

        data = {...data, remainingAmount: remainingAmount };

        let url = '{{ route('admin.all-credit-notes.apply-to-invoice', [':id']) }}';
        url = url.replace(':id', '{{ $creditNote->id }}'
        );

        $.easyAjax({
            url: url,
            type: 'POST',
            data: {...data, _token: '{{ csrf_token() }}'}
        })
    })
</script>
