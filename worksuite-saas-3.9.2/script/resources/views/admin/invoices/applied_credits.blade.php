<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"> <i class="fa fa-search"></i>  @lang('app.creditedInvoices')</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        @if ($creditNotes->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <th>@lang('app.invoiceNumber') #</th>
                    <th>@lang('app.credit-notes.amountCredited')</th>
                    <th>@lang('app.date')</th>
                    <th></th>
                </thead>
                <tbody>
                    @forelse ($creditNotes as $creditNote)
                        <tr>
                            <td>
                                {{ $creditNote->cn_number }}
                            </td>
                            <td>
                            {{ $creditNote->currency->currency_symbol.' '.$creditNote->pivot->credit_amount }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($creditNote->pivot->date)->format($global->date_format) }}
                            </td>
                            <td class="text-center">
                                <a href="javascript:;"  data-toggle="tooltip" data-original-title="Delete" onclick="deleteAppliedCredit({{ $invoice->id }}, {{ $creditNote->pivot->id }})" class="btn btn-danger btn-circle">
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
