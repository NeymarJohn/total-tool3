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
                <li><a href="{{ route('client.dashboard.index') }}">@lang("app.menu.home")</a></li>
                <li><a href="{{ route('client.invoices.index') }}">@lang("app.menu.invoices")</a></li>
                <li class="active">@lang('app.invoice')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<style>
    .ribbon-wrapper {
        background: #ffffff !important;
    }
    .displayNone {
        display: none;
    }
    .takeLeft {
        float: left;
    }
</style>
@endpush

@section('content')

    <section>
        <div class="sttabs tabs-style-line">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-12 p-r-0">
                        <nav>
                            <ul>
                                <li class="tab-current"><a href="{{ route('client.invoice-recurring.show', $invoice->id) }}"><span>@lang('app.invoiceRecurring') @lang('app.info')</span></a>
                                </li>
                                <li ><a href="{{ route('client.invoice-recurring.invoice', $invoice->id) }}"><span>@lang('app.recurringInvoices')</span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="white-box">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="clearfix"></div>
                        <div class="ribbon-content ">
                            <div class="row">
                                <h4 >@lang('app.recurringDetail')</h4>
                                <hr>
                            </div>
                            {{--<h3><b>@lang('app.invoiceRecurring')</b> <span class="pull-right"></span></h3>--}}
                            <div class="row">
                                <div class="col-xs-6 b-r">
                                    <strong class="clearfix">@lang('app.price')</strong> <br>
                                    <span class="text-muted">{{ $invoice->total }} </span> <label class="label label-info">{{ strtoupper($invoice->rotation) }}</label>
                                </div>
                                <div class="col-xs-6">
                                    <strong class="clearfix">@lang('app.totalAmount')</strong> <br>
                                    <p class="text-muted">{{ $invoice->currency->currency_symbol.''.$invoice->recurrings->sum('price') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 b-r">
                                    <strong class="clearfix">@lang('app.completedInvoice')</strong> <br>
                                    <p class="text-muted">{{ $invoice->recurrings->count() }}</p>
                                </div>
                                <div class="col-xs-6 ">
                                    <strong class="clearfix">@lang('app.pendingInvoice')</strong> <br>
                                    @if($invoice->unlimited_recurring == 0 )
                                        @if($invoice->billing_cycle > $invoice->recurrings->count())
                                            <p class="text-muted">{{ $invoice->billing_cycle - $invoice->recurrings->count() }}</p>
                                        @else
                                            <p><label class="label label-success"> @lang('app.completed') </label></p>
                                        @endif
                                    @else
                                        <p><label class="label label-info"> @lang('app.infinite') </label></p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6"> <strong class="clearfix">@lang('modules.expensesRecurring.lastPaymentDate')</strong> <br>
                                    @if($invoice->recurrings->count() > 0)
                                        {{ $invoice->recurrings[$invoice->recurrings->count()-1]->created_at->format($global->date_format) }}
                                    @else
                                        --
                                    @endif
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12">

                                    <div class="pull-left">
                                        <address>
                                            <h3> &nbsp;<b class="text-danger">{{ ucwords($global->company_name) }}</b></h3>
                                            @if(!is_null($settings))
                                                <p class="text-muted m-l-5">{!! nl2br($global->address) !!}</p>
                                            @endif
                                            @if($invoiceSetting->show_gst == 'yes' && !is_null($invoiceSetting->gst_number))
                                                <p class="text-muted m-l-5"><b>@lang('app.gstIn')
                                                        :</b>{{ $invoiceSetting->gst_number }}</p>
                                            @endif
                                        </address>
                                    </div>
                                    <div class="pull-right text-right">
                                        <address>
                                            @if(!is_null($invoice->project_id) && !is_null($invoice->project->client))
                                                <h3>To,</h3>
                                                <h4 class="font-bold">{{ ucwords($invoice->project->client->name) }}</h4>
                                                <p class="m-l-30">
                                                    <b>@lang('app.address') :</b>
                                                    <span class="text-muted">
                                    {!! nl2br($invoice->project->client->address) !!}
                                </span>
                                                </p>
                                                @if($invoice->show_shipping_address === 'yes')
                                                    <p class="m-t-5">
                                                        <b>@lang('app.shippingAddress') :</b>
                                                        <span class="text-muted">
                                        {!! nl2br($invoice->project->client->shipping_address) !!}
                                    </span>
                                                    </p>
                                                @endif
                                                @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->project->client->gst_number))
                                                    <p class="m-t-5"><b>@lang('app.gstIn')
                                                            :</b>  {{ $invoice->project->client->gst_number }}
                                                    </p>
                                                @endif
                                            @elseif(!is_null($invoice->client_id))
                                                <h3>@lang('modules.invoices.to'),</h3>
                                                <h4 class="font-bold">{{ ucwords($invoice->withoutGlobalScopeCompanyClient->name) }}</h4>
                                                <p class="m-l-30">
                                                    <b>@lang('app.address') :</b>
                                                    <span class="text-muted">
                                    {!! nl2br($invoice->clientdetails->address) !!}
                                </span>
                                                </p>
                                                @if($invoice->show_shipping_address === 'yes')
                                                    <p class="m-t-5">
                                                        <b>@lang('app.shippingAddress') :</b>
                                                        <span class="text-muted">
                                        {!! nl2br($invoice->clientdetails->shipping_address) !!}
                                    </span>
                                                    </p>
                                                @endif
                                                @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->clientdetails->gst_number))
                                                    <p class="m-t-5"><b>@lang('app.gstIn')
                                                            :</b>  {{ $invoice->clientdetails->gst_number }}
                                                    </p>
                                                @endif
                                            @endif

                                            <p class="m-t-30"><b>@lang('app.invoice') @lang('app.date') :</b> <i
                                                        class="fa fa-calendar"></i> {{ $invoice->issue_date->format($global->date_format) }}
                                            </p>

                                            <p><b>@lang('app.dueDate') :</b> <i
                                                        class="fa fa-calendar"></i> {{ $invoice->due_date->format($global->date_format) }}
                                            </p>
                                            @if($invoice->recurring == 'yes')
                                                <p><b class="text-danger">@lang('modules.invoices.billingFrequency') : </b> {{ $invoice->billing_interval . ' '. ucfirst($invoice->billing_frequency) }} ({{ ucfirst($invoice->billing_cycle) }} cycles)</p>
                                            @endif
                                        </address>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="table-responsive m-t-40" style="clear: both;">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>@lang("modules.invoices.item")</th>
                                        @if($invoiceSetting->hsn_sac_code_show)
                                            <th >@lang('modules.invoices.hsnSacCode')</th>
                                        @endif
                                                <th class="text-right">@lang("modules.invoices.qty")</th>
                                                <th class="text-right">@lang("modules.invoices.unitPrice")</th>
                                                <th class="text-right">@lang("modules.invoices.price")</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $count = 0; ?>
                                            @foreach($invoice->items as $item)
                                                @if($item->type == 'item')
                                                    <tr>
                                                        <td class="text-center">{{ ++$count }}</td>
                                                        <td>{{ ucfirst($item->item_name) }}
                                                            @if(!is_null($item->item_summary))
                                                                <p class="font-12">{{ $item->item_summary }}</p>
                                                            @endif
                                                        </td>
                                                        @if($invoiceSetting->hsn_sac_code_show)
                                                    <td>{{ ($item->hsn_sac_code) ?? '--' }}</td>
                                                @endif
                                                <td class="text-right">{{ $item->quantity }}</td>
                                                        <td class="text-right"> {!!  $invoice->currency->currency_symbol. ' '.$item->unit_price !!} </td>
                                                        <td class="text-right"> {!! $invoice->currency->currency_symbol. ' '.$item->amount !!} </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="pull-right m-t-30 text-right">
                                        <p>@lang("modules.invoices.subTotal")
                                            : {!! $invoice->currency->currency_symbol. ' '.$invoice->sub_total !!}</p>

                                        <p>@lang("modules.invoices.discount")
                                            : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $discount }} </p>
                                        @foreach($taxes as $key=>$tax)
                                            <p>{{ strtoupper($key) }}
                                                : {!! htmlentities($invoice->currency->currency_symbol). ' '.$tax !!} </p>
                                        @endforeach
                                        <hr>
                                        <h3><b>@lang("modules.invoices.total")
                                                :</b> {!!  htmlentities($invoice->currency->currency_symbol) .' '.$invoice->total !!}
                                        </h3>
                                    </div>

                                    @if(!is_null($invoice->note))
                                        <div class="col-xs-12">
                                            <p><strong>@lang('app.note')</strong>: {{ $invoice->note }}</p>
                                        </div>
                                    @endif
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /content -->
        </div>
        <!-- /tabs -->
    </section>

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script>
    $(function () {

    });
</script>
@endpush
