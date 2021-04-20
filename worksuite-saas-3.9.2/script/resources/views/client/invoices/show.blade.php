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
            <div class="col-md-12 text-right">

                <a class="btn btn-default btn-outline"
                   href="{{ route('client.invoices.download', $invoice->id) }}"> <span><i
                                class="fa fa-file-pdf-o"></i> @lang('modules.invoices.downloadPdf')</span> </a>
            </div>
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

    .stripe-button-el{
        display: none;
    }
    .displayNone {
        display: none;
    }
    .checkbox-inline, .radio-inline {
        vertical-align: top !important;
    }
    .payment-type {
        border: 1px solid #e1e1e1;
        padding: 20px;
        background-color: #f3f3f3;
        border-radius: 10px;

    }
    .box-height {
        height: 78px;
    }
    .button-center{
        display: flex;
        justify-content: center;
    }
    .paymentMethods{display: none; transition: 0.3s;}
    .paymentMethods.show{display: block;}

    .stripePaymentForm{display: none; transition: 0.3s;}
    .stripePaymentForm.show{display: block;}

    .authorizePaymentForm{display: none; transition: 0.3s;}
    .authorizePaymentForm.show{display: block;}

    div#card-element{
        width: 100%;
        color: #4a5568;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        line-height: 1.25;
        border-width: 1px;
        border-radius: 0.25rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-style: solid;
        border-color: #e2e8f0;
    }
    .paystack-form {
        display: inline-block;
        position: relative;
    }
    .payment-type {
        margin: 0 5px;
        width: 100%;
    }
    .payment-type button{
        margin: 5px 5px;
        float: none;
    }
    .d-webkit-inline-box {
        display: inline;
    }
</style>
@endpush

@section('content')

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
                <div class="ribbon-content  m-t-40 b-all p-20" id="invoice_container">
                    @if($invoice->credit_note == 1 )
                        <div class="ribbon ribbon-bookmark ribbon-success">@lang('app.menu.credit-note')</div>
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

                    <h3><span class="pull-right">{{ $invoice->invoice_number }}</span></h3>
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
                                        <h3>@lang('modules.invoices.to'),</h3>
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
                                        <h4 class="font-bold">{{ ucwords($invoice->client->name) }}</h4>
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
                                                <td>{{ ucfirst($item->item_name) }}</td>
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

                                <p>@lang("modules.invoices.discount")
                                    : {!! currency_position($discount, htmlentities($invoice->currency->currency_symbol)) !!} </p>
                                @foreach($taxes as $key=>$tax)
                                    <p>{{ strtoupper($key) }}
                                        : {!! currency_position($tax, htmlentities($invoice->currency->currency_symbol)) !!} </p>
                                @endforeach
                                <hr>
                                <h3><b>@lang("modules.invoices.total")
                                        :</b> {!! currency_position($invoice->total, htmlentities($invoice->currency->currency_symbol)) !!}
                                </h3>
                                @if ($invoice->credit_notes()->count() > 0)
                                    <p>
                                        @lang('modules.invoices.appliedCredits'): {!! currency_position($invoice->appliedCredits(), htmlentities($invoice->currency->currency_symbol)) !!}
                                    </p>
                                @endif
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
                            <hr>
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    @if($invoice->status == 'unpaid' || $invoice->status == 'review')

                                    <div class="form-group">
                                        <div class="radio-list">
                                            @if(($credentials->show_pay))
                                                <label class="radio-inline p-0">
                                                    <div class="radio radio-info">
                                                        <input checked onchange="showButton('online')" type="radio" name="method" id="radio13" value="high">
                                                        <label for="radio13">@lang('modules.client.online')</label>
                                                    </div>
                                                </label>
                                            @endif
                                            @if($methods->count() > 0)
                                                <label class="radio-inline">
                                                    <div class="radio radio-info">
                                                        <input type="radio" onchange="showButton('offline')"  name="method" id="radio15">
                                                        <label for="radio15">@lang('modules.client.offline')</label>
                                                    </div>
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                    {{--<div class="clearfix"></div>--}}
                                    <div class="col-md-12 p-l-0 text-left">
                                        @if(($credentials->show_pay))
                                            <div class="btn-group displayNone" id="onlineBox">
                                                <div class="dropup">
                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    @lang('modules.invoices.payNow') <span class="caret"></span>
                                                </button>
                                                <ul role="menu" class="dropdown-menu">
                                                    @if($credentials->paypal_status == 'active')
                                                        <li>
                                                            <a href="{{ route('client.paypal', [$invoice->id]) }}"><i
                                                                        class="fa fa-paypal"></i> @lang('modules.invoices.payPaypal') </a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->stripe_status == 'active')
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#stripeModal"><i
                                                                class="fa fa-cc-stripe"></i> @lang('modules.invoices.payStripe') </a>
                                                            <a style="display:none;" href="javascript:void(0);" id="stripePaymentButton"><i class="fa fa-cc-stripe"></i> @lang('modules.invoices.payStripe') </a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->razorpay_status == 'active')
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);" id="razorpayPaymentButton"><i
                                                                        class="fa fa-credit-card"></i> @lang('modules.invoices.payRazorpay') </a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->paystack_status == 'active')
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="{{ route('client.paystack-public', [$invoice->id]) }}">
                                                                <img height="15px" id="company-logo-img" src="https://s3-eu-west-1.amazonaws.com/pstk-integration-logos/paystack.jpg"> @lang('modules.invoices.payPaystack')</a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->mollie_status == 'active')
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="{{ route('client.mollie-public', [$invoice->id]) }}">
                                                                <img height="10px" id="company-logo-img" src="{{ asset('img/mollie.svg') }}"> @lang('modules.invoices.mollie')</a>
                                                        </li>
                                                    @endif
                                                    @if($credentials->authorize_status == 'active')
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#authorizeModal">
                                                                <img height="15px" id="company-logo-img" src="{{ asset('img/authorize.jpg') }}"> @lang('modules.invoices.authorize')</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                                </div>

                                            </div>
                                        @endif
                                        @if($methods->count() > 0)
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

                                                @if(count($methods) > 0)
                                                    <div class="col-xs-12">
                                                        <button type="button" class="btn btn-info save-offline" onclick="offlinePayment(); return false;">@lang('app.uploadReceipt')</button>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
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
                    <hr>
                        <div class="row">
                            <h3 class="box-title">@lang('modules.invoices.OfflinePaymentRequest')</h3>
                            <div class="table-responsive">
                                <table class="table color-table info-table" id="users-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('app.menu.offlinePaymentMethod')</th>
                                        <th>@lang('app.status')</th>
                                        <th>@lang('app.description')</th>
                                        <th>@lang('app.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php
                                        $status = ['pending' => 'warning', 'approve' => 'success', 'reject' => 'danger'];
                                        $statusString = ['pending' => 'Pending', 'approve' => 'approved', 'reject' => 'rejected'];
                                    @endphp

                                    @forelse($invoice->offline_invoice_payment as $key => $request)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $request->payment_method->name }}</td>
                                            <td><label class="label label-{{$status[$request->status]}}">{{ ucwords($statusString[$request->status]) }}</label></td>
                                            <td>{{ $request->description }}</td>
                                            <td><a class="btn btn-primary btn-sm btn-circle" target="_blank" href="{{ $request->slip }}"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">@lang('messages.noRecordFound')</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg in" id="package-offline" role="dialog" aria-labelledby="myModalLabel"
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
                                <small>* @lang('messages.payementMessage') <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">@lang('messages.alphabetCode')</a></small>
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

    <div class="modal" tabindex="-1" role="dialog" id="authorizeModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="paymentCardInfo">
                    <div class="modal-header">
                        <h5 class="modal-title">Authorize Payment</h5>
                    </div>
                    <div class="modal-body">
                        @php
                            $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
                        @endphp
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="panel panel-primary">
                                    <div class="creditCardForm">
                                        <div class="payment">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group owner col-md-8">
                                                    <label for="owner">Owner</label>
                                                    <input type="text" class="form-control" id="owner" name="owner" value="">
                                                </div>
                                                <div class="form-group CVV col-md-4">
                                                    <label for="cvv">CVV</label>
                                                    <input type="number" class="form-control" id="cvv" name="cvv" value="">
                                                </div>
                                            </div>

                                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                                            <div class="row">
                                                <div class="form-group col-md-8" id="card-number-field">
                                                    <label for="cardNumber">Card Number</label>
                                                    <input type="text" class="form-control" id="card_number" name="card_number" value="">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="amount">Amount</label>
                                                    <input type="number" disabled class="form-control" id="amount" name="amount" value="{{ $invoice->total }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-6" id="expiration-date">
                                                    <label>Expiration Date</label><br/>
                                                    <select class="form-control" id="expiration-month" name="expiration-month" style="float: left; width: 100px; margin-right: 10px;">
                                                        @foreach($months as $k=>$v)
                                                            <option value="{{ $k }}" {{ old('expiration-month') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select class="form-control" id="expiration-year" name="expiration-year"  style="float: left; width: 100px;">

                                                        @for($i = date('Y'); $i <= (date('Y') + 15); $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6" id="credit_cards" style="margin-top: 22px;">
                                                    <img src="{{ asset('img/visa.jpg') }}" id="visa">
                                                    <img src="{{ asset('img/mastercard.jpg') }}" id="mastercard">
                                                    <img src="{{ asset('img/amex.jpg') }}" id="amex">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="confirmAuthorizePayment();return false">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
{{--<script src="https://checkout.stripe.com/checkout.js"></script>--}}
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
{{--<script src="https://js.stripe.com/v3/"></script>--}}

<script>
    $(function () {
        @if(($credentials->show_pay))
            showButton('online');
        @else
                @if($methods->count() > 0)
        showButton('offline');
                @endif
        @endif
                if ($("#radio15").prop("checked")) {
                    showButton('offline');
                }

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

     function offlinePayment() {

        let offlineId = $("input[name=offlineMethod]").val();

        $.ajaxModal('#package-offline', '{{ route('client.invoices.offline-payment')}}?offlineId='+offlineId+'&invoiceId='+'{{$invoice->id}}');

        {{--$.easyAjax({--}}
        {{--    url: '{{ route('client.invoices.store') }}',--}}
        {{--    type: "POST",--}}
        {{--    redirect: true,--}}
        {{--    data: {invoiceId: "{{ $invoice->id }}", "_token" : "{{ csrf_token() }}", "offlineId": offlineId}--}}
        {{--})--}}

    }

    @if($credentials->authorize_status == 'active')
        //Confirmation after transaction
        function confirmAuthorizePayment() {
            $.easyAjax({
                type:'POST',
                url:'{{route('client.authorize.pay-submit')}}',
                data: $('#paymentCardInfo').serialize(),
                success: function(res) {
                    if(res.status == 'success') {
                        window.location.reload();
                    }
                }
            })
        }
    @endif

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
            console.log('{{ $invoice->currency->currency_code }}');
                var amount = {{ $invoice->total*100 }};
                var invoiceId = {{ $invoice->id }};
                var clientEmail = "{{ $user->email }}";

                var options = {
                    "key": "{{ $credentials->razorpay_key }}",
                    "amount": amount,
                    "currency": 'INR',
                    "name": "{{ $companyName }}",
                    "description": "Invoice Payment",
                    "image": "{{ $global->logo_url }}",
                    "handler": function (response) {
                        confirmRazorpayPayment(response.razorpay_payment_id,invoiceId,response);
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
            function confirmRazorpayPayment(id,invoiceId,rData) {
                $.easyAjax({
                    type:'POST',
                    url:'{{route('client.pay-with-razorpay')}}',
                    data: {paymentId: id,invoiceId: invoiceId,rData: rData,_token:'{{csrf_token()}}'}
                })
            }

    @endif
</script>
@endpush
