<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"> <i class="fa fa-search"></i>  @lang('app.creditedInvoices')</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        @if ($invoices->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <th>@lang('app.invoiceNumber') #</th>
                    <th>@lang('app.credit-notes.amountCredited')</th>
                    <th>@lang('app.date')</th>
                    <th></th>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>
                                {{ $invoice->invoice_number }}
                            </td>
                            <td>
                            {{ $invoice->currency->currency_symbol.' '.$invoice->pivot->credit_amount }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($invoice->pivot->date)->format($global->date_format) }}
                            </td>
                            <td class="text-center">
                                <a href="javascript:;"  data-toggle="tooltip" data-original-title="Delete" onclick="deleteCreditedInvoice({{ $creditNote->id }}, {{ $invoice->pivot->id }})" class="btn btn-danger btn-circle">
                                    <i class="fa fa-times"></i>
                                </a>
                            </td>
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
        @else
            <p>@lang('modules.credit-notes.noInvoicesFound')</p>
        @endif
        <!--/row-->
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
</div>
    