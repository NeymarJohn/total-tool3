@forelse($project->invoices as $invoice)
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-5">
                {{ $invoice->invoice_number }}
            </div>
            <div class="col-md-2">
                {{ $invoice->currency->currency_symbol }} {{ $invoice->total }}
            </div>
            <div class="col-md-2">
                @if($invoice->status == 'unpaid')
                    <label class="label label-danger">@lang('modules.invoices.unpaid')</label>
                @else
                    <label class="label label-success">@lang('modules.invoices.paid')</label>
                @endif
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.invoices.download', $invoice->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn btn-inverse btn-circle"><i class="fa fa-download"></i></a>
                &nbsp;&nbsp;
                <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-invoice-id="{{ $invoice->id }}" class="btn btn-danger btn-circle sa-params"><i class="fa fa-times"></i></a>
                <span class="m-l-10">{{ $invoice->issue_date->format($global->date_format) }}</span>
            </div>
        </div>
    </li>
@empty
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-7">
                @lang('messages.noInvoice')
            </div>
        </div>
    </li>
@endforelse