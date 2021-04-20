@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a class="btn btn-default btn-outline btn-sm"
                href="{{ route('admin.all-invoices.download', $invoice->id) }}"> <span><i class="fa fa-file-pdf-o"></i> @lang('modules.invoices.downloadPdf')</span> </a>
            @if($invoice->status == 'review')
                <button type="button" data-invoice-id="{{ $invoice->id }}" class="btn btn-primary btn-sm verify">
                    <i class="fa fa-check"></i> @lang('app.verify')
                </button>
            @endif
            <button type="button" onclick="showPayments()" class="btn btn-info btn-sm">
                @lang('app.view') @lang('app.menu.payments')
            </button>
            <button type="button" data-clipboard-text="{{ route('front.invoice', [md5($invoice->id)]) }}" class="btn btn-success btn-sm btn-copy">
                <i class="fa fa-copy"></i>
                <span id="copy_payment_text">
                    @lang('modules.invoices.copyPaymentLink')
                </span>
            </button>
            @if ($invoice->credit_notes->count() > 0)
                <a href="javascript:;" onclick="showAppliedCredits('{{ route('admin.all-invoices.applied-credits', $invoice->id) }}')" class="btn btn-info  btn-sm">
                    @lang('app.appliedCredits')
                </a>
            @endif

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang("app.menu.home")</a></li>
                <li><a href="{{ route('admin.all-invoices.index') }}">@lang("app.menu.invoices")</a></li>
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

<style>
    .ribbon-wrapper {
        background: #ffffff !important;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12 m-t-20">
            <div class="white-box">
                <div class="col-md-4 text-center">
                    <h4><span class="text-dark">{{ $invoice->currency->currency_symbol}}{{ $invoice->total }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.payments.totalAmount')</span></h4>
                </div>

                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-success">{{ $invoice->currency->currency_symbol.' '.$invoice->amountPaid() }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.payments.totalPaid')</span></h4>
                </div>

                <div class="col-md-4 text-center b-l">
                    <h4><span class="text-danger">{{ $invoice->currency->currency_symbol.' '.$invoice->amountDue() }}</span> <span class="font-12 text-muted m-l-5"> @lang('modules.payments.totalDue')</span></h4>
                </div>

            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-xs-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                   <i class="fa fa-check"></i> {!! $message !!}
                </div>
                <?php Session::forget('success');?>
            @endif

            @if ($message = Session::get('error'))
                <div class="custom-alerts alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('error');?>
            @endif


            <div class="white-box printableArea ribbon-wrapper">

                <div class="clearfix"></div>
                <div class="ribbon-content m-t-40 b-all p-20 ">
                    @if($invoice->credit_note)
                        <div class="ribbon ribbon-bookmark ribbon-warning">@lang('app.credit-note')</div>
                    @else
                        @if($invoice->status == 'paid')
                            <div class="ribbon ribbon-bookmark ribbon-success">@lang('modules.invoices.paid')</div>
                        @elseif($invoice->status == 'partial')
                            <div class="ribbon ribbon-bookmark ribbon-info">@lang('modules.invoices.partial')</div>
                        @elseif($invoice->status == 'review')
                            <div class="ribbon ribbon-bookmark ribbon-warning">@lang('modules.invoices.review')</div>
                        @else
                            <div class="ribbon ribbon-bookmark ribbon-danger">@lang('modules.invoices.unpaid')</div>
                        @endif
                    @endif

                    <h4 class="text-right"><b>@lang('app.invoice')</b> <span class="pull-right m-l-10">{{ $invoice->invoice_number }}</span></h4>
                    <hr>
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="pull-left">
                                <address>
                                    <h4> &nbsp;<b class="text-danger">{{ ucwords($global->company_name) }}</b></h4>
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
                                        <h3>@lang('modules.invoices.to')</h3>
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
                                        <h4 class="font-bold">{{ ucwords($invoice->clientdetails->name) }}</h4>
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
                                                <td class="text-right"> {!! currency_position($item->unit_price, $invoice->currency->currency_symbol) !!} </td>
                                                <td class="text-right"> {!! currency_position($item->amount, $invoice->currency->currency_symbol) !!} </td>
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
                                    : {!! currency_position($invoice->sub_total,htmlentities($invoice->currency->currency_symbol)) !!}</p>

                                @if ($discount > 0)
                                    <p>@lang("modules.invoices.discount")
                                    : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $discount }} </p>
                                @endif
                                @foreach($taxes as $key=>$tax)
                                    <p>{{ strtoupper($key) }}
                                        : {!! currency_position($tax, htmlentities($invoice->currency->currency_symbol)) !!} </p>
                                @endforeach
                                <hr>
                                <h3><b>@lang("modules.invoices.total")
                                        :</b> {!! currency_position($invoice->total, htmlentities($invoice->currency->currency_symbol)) !!}
                                </h3>
                                <hr>
                                @if ($invoice->credit_notes()->count() > 0)
                                    <p>
                                        @lang('modules.invoices.appliedCredits'): {!! currency_position($invoice->appliedCredits(), htmlentities($invoice->currency->currency_symbol)) !!}
                                    </p>
                                @endif
                                @forelse($payments as $payment)
                                    <p>
                                        @php($method = (!is_null($payment->offline_method_id)?  $payment->offlineMethod->name : $payment->gateway))

                                        @lang('messages.invoicePaymentBy', ['date'=> $payment->paid_on->format($global->date_format), 'method' => $method]) : {{ currency_position($payment->amount, $invoice->currency->currency_symbol) }}
                                    </p>
                                @empty
                                @endforelse
                                <p>
                                    @lang('modules.invoices.amountPaid'): {{ currency_position($invoice->amountPaid(), $invoice->currency->currency_symbol) }}
                                </p>

                                <p class="@if ($invoice->amountDue() > 0) text-danger @endif">
                                    @lang('modules.invoices.amountDue'): {{ currency_position($invoice->amountDue(), $invoice->currency->currency_symbol) }}
                                </p>
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

                {{--Custom fields data--}}
                @if(isset($fields) && count($fields) > 0)
                <h3 class="box-title m-t-20">@lang('modules.projects.otherInfo')</h3>
                <div class="row">
                    @foreach($fields as $field)
                        <div class="col-md-3">
                            <strong>{{ ucfirst($field->label) }}</strong> <br>
                            <p class="text-muted">
                                @if( $field->type == 'text')
                                    {{$invoice->custom_fields_data['field_'.$field->id] ?? '-'}}
                                @elseif($field->type == 'password')
                                    {{$invoice->custom_fields_data['field_'.$field->id] ?? '-'}}
                                @elseif($field->type == 'number')
                                    {{$invoice->custom_fields_data['field_'.$field->id] ?? '-'}}

                                @elseif($field->type == 'textarea')
                                    {{$invoice->custom_fields_data['field_'.$field->id] ?? '-'}}

                                @elseif($field->type == 'radio')
                                    {{ !is_null($invoice->custom_fields_data['field_'.$field->id]) ? $invoice->custom_fields_data['field_'.$field->id] : '-' }}
                                @elseif($field->type == 'select')
                                    {{ (!is_null($invoice->custom_fields_data['field_'.$field->id]) && $invoice->custom_fields_data['field_'.$field->id] != '') ? $field->values[$invoice->custom_fields_data['field_'.$field->id]] : '-' }}
                                @elseif($field->type == 'checkbox')
                                    {{ !is_null($invoice->custom_fields_data['field_'.$field->id]) ? $field->values[$invoice->custom_fields_data['field_'.$field->id]] : '-' }}
                                @elseif($field->type == 'date')
                                    {{ !is_null($invoice->custom_fields_data['field_'.$field->id]) ? \Carbon\Carbon::parse($invoice->custom_fields_data['field_'.$field->id])->format($global->date_format) : '--'}}
                                @endif
                            </p>

                        </div>
                    @endforeach
                </div>
                @endif
                {{--custom fields data end--}}
                @if(!is_null($payments))
                    <div class="b-all m-t-20 m-b-20">
                    <h3 class="box-title m-t-20 text-center">Recent Payments</h3>
                    <hr>
                    <div class="row">
                        <div class="col-xs-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">@lang("modules.invoices.price")</th>
                                    <th class="text-center">@lang("modules.invoices.paymentMethod")</th>
                                    <th class="text-center">@lang("modules.invoices.paidOn")</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $count = 0; ?>
                                @forelse($payments as $key => $payment)
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td class="text-center"> {!! currency_position($payment->amount, $invoice->currency->currency_symbol) !!} </td>
                                        <td class="text-center">
                                            @php($method = (!is_null($payment->offline_method_id)?  $payment->offlineMethod->name : $payment->gateway))
                                            {{ $method }}
                                        </td>
                                        <td class="text-center"> {{ $payment->paid_on->format($global->date_format) }} </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="paymentDetail" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="appliedCredits" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    @lang('app.loading')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">@lang('app.close')</button>
                    <button type="button" class="btn blue">@lang('app.save') @lang('app.changes')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
    {{--Ajax Modal Ends--}}
    <div class="modal fade bs-modal-md in" id="offlinePaymentDetails" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/clipboard/clipboard.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script>

    var clipboard = new ClipboardJS('.btn-copy');

    clipboard.on('success', function(e) {
        var copied = "<?php echo __("app.copied") ?>";
        // $('#copy_payment_text').html(copied);
        $.toast({
            heading: 'Success',
            text: copied,
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'success',
            hideAfter: 3500
        });
    });

    function showAppliedCredits(url) {
        $.ajaxModal('#appliedCredits', url);
    }

    function deleteAppliedCredit(invoice_id, id) {
        let url = '{{ route('admin.all-invoices.delete-applied-credit', [':id']) }}';
        url = url.replace(':id', id);

        $.easyAjax({
            url: url,
            type: 'POST',
            data: { invoice_id: invoice_id, _token: '{{csrf_token()}}'},
            success: function (response) {
                $('#appliedCredits .modal-content').html(response.view);
                $('#appliedCredits').on('hide.bs.modal', function (e) {
                    location.reload();
                })
            }
        })
    }

    $(function () {
        var table = $('#invoices-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.all-invoices.create') }}',
            deferRender: true,
            "order": [[0, "desc"]],
            language: {
                "url": "<?php echo __("app.datatable") ?>"
            },
            "fnDrawCallback": function (oSettings) {
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'project_name', name: 'projects.project_name'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'currency_symbol', name: 'currencies.currency_symbol'},
                {data: 'total', name: 'total'},
                {data: 'issue_date', name: 'issue_date'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('body').on('click', '.verify', function() {
            var id = $(this).data('invoice-id');

            var url = '{{ route('admin.all-invoices.payment-verify', ':id') }}'
            url = url.replace(':id', id);

            $.ajaxModal('#offlinePaymentDetails', url);
        });
    });

    // Show Payment detail modal
    function showPayments() {
        var url = '{{route('admin.all-invoices.payment-detail', $invoice->id)}}';
        $.ajaxModal('#paymentDetail', url);
    }

</script>
@endpush
