@extends('layouts.client-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
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
<script src="https://js.stripe.com/v3/"></script>

<style>
    .ribbon-wrapper {
        background: #ffffff !important;
    }

    .ribbon {
        top: 12px !important;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
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
                <div class="ribbon-content m-t-40 b-all p-20" id="invoice_container">
                    @if($invoice->status == 'paid')
                        <div class="ribbon ribbon-bookmark ribbon-success">@lang('modules.invoices.paid')</div>
                    @elseif($invoice->status == 'partial')
                        <div class="ribbon ribbon-bookmark ribbon-info">@lang('modules.invoices.partial')</div>
                    @else
                        <div class="ribbon ribbon-bookmark ribbon-danger">@lang('modules.invoices.unpaid')</div>
                    @endif

                    <h4 class="text-right"><b>@lang('app.invoice')</b> <span class="pull-right m-l-10">{{ $invoice->invoice_number }}</span></h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">

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
                                        <h3>@lang('modules.invoices.to'),</h3>
                                        <h4 class="font-bold">{{ ucwords($invoice->project->client->name) }}</h4>
                                         @if(!is_null($invoice->project->client->client_details))
                                            <p class="m-l-30">
                                                <b>@lang('app.address') :</b>
                                                <span class="text-muted">
                                                    {!! nl2br($invoice->project->client->client_details->address) !!}
                                                </span>
                                            </p>
                                            @if($invoice->show_shipping_address === 'yes')
                                                <p class="m-t-5">
                                                    <b>@lang('app.shippingAddress') :</b>
                                                    <span class="text-muted">
                                                        {!! nl2br($invoice->project->client->client_details->shipping_address) !!}
                                                    </span>
                                                </p>
                                            @endif
                                            @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->project->client->client_details->gst_number))
                                                <p class="m-t-5"><b>@lang('app.gstIn')
                                                        :</b>  {{ $invoice->project->client->client_details->gst_number }}
                                                </p>
                                            @endif
                                        @endif
                                    @elseif(!is_null($invoice->client_id))
                                        <h3>@lang('modules.invoices.to'),</h3>
                                        <h4 class="font-bold">{{ ucwords($invoice->client->name) }}</h4>
                                        @if(!is_null($invoice->client->clientdetails))
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
                                            @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->client->clientdetails->gst_number))
                                                <p class="m-t-5"><b>@lang('app.gstIn')
                                                        :</b>  {{ $invoice->client->clientdetails->gst_number }}
                                                </p>
                                            @endif
                                        @endif
                                    @endif
                                    <p class="m-t-30"><b>@lang('modules.invoices.invoiceDate') :</b> <i
                                                class="fa fa-calendar"></i> {{ $invoice->issue_date->format($global->date_format) }}
                                    </p>

                                    <p><b>@lang('modules.dashboard.dueDate') :</b> <i
                                                class="fa fa-calendar"></i> {{ $invoice->due_date->format($global->date_format) }}
                                    </p>
                                    @if($invoice->recurring == 'yes')
                                        <p><b class="text-danger">@lang('modules.invoices.billingFrequency') : </b> {{ $invoice->billing_interval . ' '. ucfirst($invoice->billing_frequency) }} ({{ ucfirst($invoice->billing_cycle) }} cycles)</p>
                                    @endif
                                </address>
                            </div>
                        </div>
                        <div class="col-md-12">
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
                                                <td class="text-right"> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $item->unit_price }} </td>
                                                <td class="text-right"> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $item->amount }} </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <p>@lang("modules.invoices.subTotal")
                                    : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $invoice->sub_total }}</p>

                                @if ($discount > 0)
                                    <p>@lang("modules.invoices.discount")
                                    : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $discount }} </p>
                                @endif

                                @foreach($taxes as $key=>$tax)
                                    <p>{{ strtoupper($key) }}
                                        : {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $tax }} </p>
                                @endforeach
                                <hr>
                                <h3><b>@lang("modules.invoices.total")
                                        :</b> {!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $invoice->total }}
                                </h3>
                                @if ($invoice->credit_notes()->count() > 0)
                                    <p>
                                        @lang('modules.invoices.appliedCredits'): {{ $invoice->currency->currency_symbol.''.$invoice->appliedCredits() }}
                                    </p>
                                @endif
                                <p>
                                    @lang('modules.invoices.amountPaid'): {{ $invoice->currency->currency_symbol.''.$invoice->getPaidAmount() }}
                                </p>
                                <p class="@if ($invoice->amountDue() > 0) text-danger @endif">
                                    @lang('modules.invoices.amountDue'): {{ $invoice->currency->currency_symbol.''.$invoice->amountDue() }}
                                </p>
                            </div>

                            @if(!is_null($invoice->note))
                                <div class="col-md-12">
                                    <p><strong>@lang('app.note')</strong>: {{ $invoice->note }}</p>
                                </div>
                            @endif
                            <div class="clearfix"></div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 text-left">
                                    @if(($invoice->status == 'partial' || $invoice->status == 'unpaid') && ($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active' || $credentials->razorpay_status == 'active' || count($methods) > 0))

                                    <div class="form-group">
                                        <div class="radio-list">
                                            <label class="radio-inline p-0">
                                                <div class="radio radio-info">
                                                    <input  checked onchange="showButton('online')" type="radio" name="method" id="radio13" value="high">
                                                    <label for="radio13">@lang('modules.client.online')</label>
                                                </div>
                                            </label>
                                            <label class="radio-inline">
                                                <div class="radio radio-info">
                                                    <input type="radio"  onchange="showButton('offline')"  name="method" id="radio15">
                                                    <label for="radio15">@lang('modules.client.offline')</label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    {{--<div class="clearfix"></div>--}}
                                        <div class="col-md-12 p-l-0 text-left">

                                            <div class="btn-group displayNone" id="onlineBox">
                                                <div class="dropup">
                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    @lang('modules.invoices.payNow') <span class="caret"></span>
                                                </button>
                                                <ul role="menu" class="dropdown-menu">
                                                    @php
                                                        $flag = 0;
                                                    @endphp
                                                    @if($credentials->paypal_status == 'active')
                                                        @php
                                                            $flag = 1;
                                                        @endphp

                                                        <li>
                                                            <a href="{{ route('client.paypal', [$invoice->id]) }}"><i
                                                                        class="fa fa-paypal"></i> @lang('modules.invoices.payPaypal') </a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->razorpay_status == 'active')
                                                        @php
                                                            $flag = 1;
                                                        @endphp
                                                        @if ($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active')
                                                            <li class="divider"></li>
                                                        @endif
                                                        <li>
                                                            <a href="javascript:void(0);" id="razorpayPaymentButton"><i
                                                                        class="fa fa-credit-card"></i> @lang('modules.invoices.payRazorpay') </a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->stripe_status == 'active')
                                                        @php
                                                            $flag = 1;
                                                        @endphp
                                                        @if ($credentials->paypal_status == 'active' || $credentials->razorpay_status == 'active')
                                                            <li class="divider"></li>
                                                        @endif
                                                        <li>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#stripeModal"><i
                                                                class="fa fa-cc-stripe"></i> @lang('modules.invoices.payStripe') </a>
                                                            <a style="display:none;" href="javascript:void(0);" id="stripePaymentButton" onClick="window.elementsModal.toggleElementsModalVisibility();"><i class="fa fa-cc-stripe"></i> @lang('modules.invoices.payStripe') </a>
                                                        </li>
                                                    @endif
                                                    @if ($flag == 0)
                                                        <li class="p-10">
                                                            @lang('messages.noOnlinePyamentGateway')
                                                        </li>
                                                    @endif
                                                </ul>
                                                </div>

                                            </div>

                                            {!! Form::open(['id'=>'createPayment','class'=>'ajax-form','method'=>'POST']) !!}
                                                <input type="hidden" name="invoiceId" value="{{ $invoice->id }}">
                                                
                                                <div class="form-group displayNone" id="offlineBox">
                                                    <div class="radio-list">
                                                        @forelse($methods as $key => $method)
                                                            <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                <div class="radio radio-info" >
                                                                    <input @if($key == 0) checked @endif onchange="showDetail('{{ $method->id }}')" type="radio" name="offlineMethod" id="offline{{$key}}"
                                                                        value="{{ $method->id }}">
                                                                    <label for="offline{{$key}}" class="text-info" >
                                                                        {{ ucfirst($method->name) }} </label>
                                                                </div>
                                                                <div class="displayNone" id="method-desc-{{ $method->id }}">
                                                                    {!! $method->description !!}
                                                                </div>
                                                            </label>
                                                        @empty
                                                        @endforelse
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 displayNone" id="methodDetail">
                                                        </div>

                                                        <div class="col-md-8 m-t-20">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang('app.receipt')</label>
                                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                                    <div class="form-control" data-trigger="fileinput"> 
                                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                                                                    </div>
                                                                    <span class="input-group-addon btn btn-default btn-file"> 
                                                                        <span class="fileinput-new">@lang('app.selectFile')</span> 
                                                                        <span class="fileinput-exists">@lang('app.change')</span>
                                                                        <input type="file" name="bill" id="bill">
                                                                    </span> 
                                                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">@lang('app.remove')</a> 
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if(count($methods) > 0)
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-info save-offline">@lang('app.save')</button>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 text-right">

                                    <a class="btn btn-default btn-outline"
                                       href="{{ route('client.invoices.download', $invoice->id) }}"> <span><i
                                                    class="fa fa-file-pdf-o"></i> @lang('modules.invoices.downloadPdf')</span> </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="modal" tabindex="-1" role="dialog" id="stripeModal">--}}
        {{--<div class="modal-dialog" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<form id="stripeAddress">--}}
                    {{--<div class="modal-header">--}}
                        {{--<h5 class="modal-title">@lang('modules.stripeCustomerAddress.details')</h5>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-12">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>@lang('modules.stripeCustomerAddress.name')</label>--}}
                                    {{--<input type="text" required name="clientName" id="clientName" class="form-control">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-12">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>@lang('modules.stripeCustomerAddress.line1')</label>--}}
                                    {{--<input type="text" required name="line1" id="line1" class="form-control">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>@lang('modules.stripeCustomerAddress.postalCode')</label>--}}
                                    {{--<input type="text" required name="postal_code" id="postal_code" class="form-control">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>@lang('modules.stripeCustomerAddress.city')</label>--}}
                                    {{--<input type="text" required name="city" id="city" class="form-control">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>@lang('modules.stripeCustomerAddress.state')</label>--}}
                                    {{--<input type="text" required name="state" id="state" class="form-control">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>@lang('modules.stripeCustomerAddress.country')</label>--}}
                                    {{--<input type="text" required name="country" maxlength="2" id="country" class="form-control">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-12">--}}
                                {{--<small>* Address country must be a valid <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">2-alphabet ISO-3166 code</a></small>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="submit" class="btn btn-primary stripeAddressSubmit">Submit</button>--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="modal" tabindex="-1" role="dialog" id="stripeModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="stripeAddress" method="POST" action="{{ route('client.stripe', [$invoice->id]) }}">
                    {{ csrf_field() }}
                    <input type="hidden" id="invoiceIdInput" name="invoice_id"  value="{{ $invoice->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stripeModalHeading">@lang('modules.stripeCustomerAddress.details')</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="margin-bottom:20px;" id="client-info">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>@lang('modules.stripeCustomerAddress.name')</label>
                                    <input type="text" required name="clientName" id="clientName" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>@lang('modules.stripeCustomerAddress.line1')</label>
                                    <input type="text" required name="line1" id="line1" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>@lang('modules.stripeCustomerAddress.city')</label>
                                    <input type="text" required name="city" id="city" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>@lang('modules.stripeCustomerAddress.state')</label>
                                    <input type="text" required name="state" id="state" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>@lang('modules.stripeCustomerAddress.country')</label>
                                    <input type="text" required name="country" id="country" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <small>* Address country must be a valid <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">2-alphabet ISO-3166 code</a></small>
                            </div>
                        </div>
                        <div class="row" id="stripe-modal" style="margin-bottom:20px;">
                            <!-- Stripe Elements Placeholder -->
                            <div class="flex flex-wrap mt-6" style="margin-top: 15px; text-align: center">
                                <button type="button" id="next-button"  class="btn btn-success inline-block align-middle text-center select-none border font-bold whitespace-no-wrap py-2 px-4 rounded text-base leading-normal no-underline text-gray-100 bg-blue-500 hover:bg-blue-700">
                                    @lang('app.submit')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    $(function () {
        showButton('online');

        var table = $('#invoices-table').dataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('client.invoices.create') }}',
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

    });

    $('#next-button').click( function () {
        $('#next-button').attr('disabled', true);
        var url = "{{ route('client.invoices.stripe-modal')}}";
        $.easyAjax({
            type:'POST',
            url:url,
            data: $('#stripeAddress').serialize(),
            success: function(res) {
                $('#stripeModalHeading').html('@lang('app.cardDetails')');
                $('#stripe-modal').html(res.view);
                $('#client-info').hide();
            }
        })
    })

    @if($credentials->razorpay_status == 'active')
        $('#razorpayPaymentButton').click(function() {
            var amount = {{ $invoice->total*100 }};
            var invoiceId = {{ $invoice->id }};
            var clientEmail = "{{ $user->email }}";

            var options = {
                "key": "{{ $credentials->razorpay_key }}",
                "amount": amount,
                "currency": '{{ $invoice->currency->currency_code }}',
                "name": "{{ $companyName }}",
                "description": "Invoice Payment",
                "image": "{{ $global->logo_url }}",
                "handler": function (response) {
                    confirmRazorpayPayment(response.razorpay_payment_id,invoiceId);
                },
                "modal": {
                    "ondismiss": function () {
                        // On dismiss event
                    }
                },
                "prefill": {
                    "email": clientEmail
                },
                "notes": {
                    "purchase_id": invoiceId //invoice ID
                }
            };
            var rzp1 = new Razorpay(options);

            rzp1.open();

        })

        //Confirmation after transaction
        function confirmRazorpayPayment(id,invoiceId) {
            $.easyAjax({
                type:'POST',
                url:'{{route('client.pay-with-razorpay')}}',
                data: {paymentId: id,invoiceId: invoiceId,_token:'{{csrf_token()}}'}
            })
        }

    @endif

    // Show offline method detail
    function showDetail(id){
        var detail = $('#method-desc-'+id).html();
        $('#methodDetail').html(detail);
        $('#methodDetail').show();
    }

    // Payment mode
    function showButton(type){

        if(type == 'online'){
            $('#methodDetail').hide();
            $('#offlineBox').hide();
            $('#onlineBox').show();
        }else{
            $('#offline0').change();
            $('#offlineBox').show();
            $('#onlineBox').hide();
        }
    }

    $('.save-offline').click(function() {

        $.easyAjax({
            url: '{{ route('client.invoices.store') }}',
            container: '#createPayment',
            type: "POST",
            redirect: true,
            file: true,
            data: $('#createPayment').serialize()
        })

    })
</script>
@endpush