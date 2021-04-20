<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $global->favicon_url }}">
    {{--<link rel="manifest" href="{{ asset('favicon/manifest.json') }}">--}}
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $global->favicon_url }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $invoice->invoice_number }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css'>
    <link rel='stylesheet prefetch'
          href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>

    <!-- This is Sidebar menu CSS -->
    <link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}"   rel="stylesheet">
    <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}"   rel="stylesheet">

    <!-- This is a Animation CSS -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

@stack('head-script')

<!-- This is a Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (default.css) for this starter
       page. However, you can choose any other skin from folder css / colors .
       -->
    <link href="{{ asset('css/colors/default.css') }}" id="theme"  rel="stylesheet">
    <link href="{{ asset('plugins/froiden-helper/helper.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/custom-new.css') }}"   rel="stylesheet">
    <link href="{{ asset('css/rounded.css') }}"   rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .sidebar .notify  {
            margin: 0 !important;
        }
        .sidebar .notify .heartbit {
            top: -23px !important;
            right: -15px !important;
        }
        .sidebar .notify .point {
            top: -13px !important;
        }
        @media (max-width:991px){
            .tablet-margin{margin-top: 35px;}
        }

        .admin-logo {
            max-height: 40px;
        }

        .ribbon {
            top: 12px !important;
            left: 0px;
        }

        .invoice-wrap.fixed-invoice-header{
            position: fixed;
            width: 100%;
            background-color: #fff;
            z-index: 1;
            background: #fff;
            -webkit-box-shadow: 0 1px 15px 1px rgb(90 90 90 / 8%);
            box-shadow: 0 1px 15px 1px rgb(90 90 90 / 8%);
            width: 100%!important;
            left: 0!important;

            transition: all position 20s;
        }
        .invoice-wrap.fixed-invoice-header .inv-box{
            width: 1143px;
            margin: 0 auto;
        }
        @media (max-width:768px){
            .invoice-wrap.fixed-invoice-header .inv-box{
                width: 100%;
            }
        }
    </style>
</head>
<body class="fix-sidebar">
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<div id="wrapper">

    <!-- Left navbar-header end -->
    <!-- Page Content -->
    <div id="page-wrapper" style="margin-left: 0px !important;">
        <div class="container">

            <!-- .row -->
            <div class="row ">

                <div class="col-md-12 invoice-wrap">
                    <div class="inv-box">
                        <div class="col-md-8 m-t-20">
                            <img src="{{ $invoiceSetting->logo_url }}" alt="home" class="admin-logo"/>
                        </div>
                        <div class="col-md-4 m-t-25">
                            <div class="">
                                <a href="{{ route("front.invoiceDownload", md5($invoice->id)) }}" class="btn btn-default pull-right"><i class="fa fa-file-pdf-o"></i> @lang('app.download')</a>
                                @if($invoice->status != 'paid' && $credentials->show_pay)

                                    <div class="btn-group pull-right m-r-10 m-b-20" id="onlineBox">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                @lang('modules.invoices.payNow') <span class="caret"></span>
                                            </button>
                                            <ul role="menu" class="dropdown-menu">

                                                @if($credentials->paypal_status == 'active')
                                                    <li>
                                                        <a href="{{ route('client.paypal-public', [$invoice->id]) }}"><i
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
                                                                    class="fa fa-cc-stripe"></i>  @lang('modules.invoices.payRazorpay')  </a>
                                                    </li>
                                                @endif

                                                @if($credentials->paystack_status == 'active')
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#paystackModal">
                                                            <img height="15px" id="company-logo-img" src="https://s3-eu-west-1.amazonaws.com/pstk-integration-logos/paystack.jpg"> @lang('modules.invoices.payPaystack')</a>
                                                        {{--                                                                            <a href="{{ route('client.paystack-public', [$invoice->id]) }}">--}}
                                                        {{--                                                                                <img height="15px" id="company-logo-img" src="https://s3-eu-west-1.amazonaws.com/pstk-integration-logos/paystack.jpg"> @lang('modules.invoices.payPaystack')</a>--}}
                                                    </li>
                                                @endif

                                                @if($credentials->mollie_status == 'active')
                                                    <li class="divider"></li>
                                                    {{--<li>--}}
                                                    {{--<a href="{{ route('client.mollie-public', [$invoice->id]) }}"><img src="{{ asset('img/mollie.svg') }}" width="30px" class="display-small"> @lang('modules.invoices.mollie') </a>--}}
                                                    {{--</li>--}}
                                                    <li>
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#mollieModal">
                                                            <img height="15px" id="company-logo-img" src="{{ asset('img/mollie.svg') }}"> @lang('modules.invoices.mollie')</a>
                                                        {{--                                                                            <a href="{{ route('client.paystack-public', [$invoice->id]) }}">--}}
                                                        {{--                                                                                <img height="15px" id="company-logo-img" src="https://s3-eu-west-1.amazonaws.com/pstk-integration-logos/paystack.jpg"> @lang('modules.invoices.payPaystack')</a>--}}
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
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 m-b-40">

                <div class="card">
                    <div class="card-body">
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


                        <div class="white-box printableArea ribbon-wrapper" style="background: #ffffff !important;">
                            <div class="ribbon-content p-20" id="invoice_container">
                                @if($invoice->status == 'paid')
                                    <div class="ribbon ribbon-bookmark ribbon-success">@lang('modules.invoices.paid')</div>
                                @elseif($invoice->status == 'partial')
                                    <div class="ribbon ribbon-bookmark ribbon-info">@lang('modules.invoices.partial')</div>
                                @else
                                    <div class="ribbon ribbon-bookmark ribbon-danger">@lang('modules.invoices.unpaid')</div>
                                @endif

                                <h3 class="text-right"><b>{{ $invoice->invoice_number }}</b></h3>
                                <hr>
                                <div class="row tablet-margin">
                                    <div class="col-xs-12">

                                        <div class="pull-left">
                                            <address>
                                                <h3> &nbsp;<b>{{ ucwords($settings->company_name) }}</b></h3>
                                                @if(!is_null($settings))
                                                    <p class="text-muted m-l-5">{!! nl2br($settings->address) !!}</p>
                                                @endif
                                                @if($invoiceSetting->show_gst == 'yes' && !is_null($invoiceSetting->gst_number))
                                                    <p class="text-muted m-l-5"><b>@lang('app.gstIn')
                                                            :</b>{{ $invoiceSetting->gst_number }}</p>
                                                @endif
                                            </address>
                                        </div>
                                        <div class="pull-right text-right">
                                            <address>
                                                @if(!is_null($invoice->project) && !is_null($invoice->project->client))
                                                    <h3>To,</h3>
                                                    <h4 class="font-bold">{{ ucwords($invoice->project->client->name) }}</h4>
                                                    @if($invoice->project->client->address)
                                                        <p class="m-l-30">
                                                            <b>@lang('app.address') :</b>
                                                            <span class="text-muted">
                                                            {!! nl2br($invoice->project->client->address) !!}
                                                        </span>
                                                        </p>
                                                    @endif
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
                                                    <h3>To,</h3>
                                                    <h4 class="font-bold">{{ ucwords($invoice->withoutGlobalScopeCompanyClient->client_detail->name) }}</h4>
                                                    @if($invoice->withoutGlobalScopeCompanyClient->client_detail->address)
                                                        <p class="m-l-30">
                                                            <b>@lang('app.address') :</b>
                                                            <span class="text-muted">
                                                        {!! nl2br(c) !!}
                                                    </span>
                                                        </p>
                                                    @endif
                                                    @if($invoice->show_shipping_address === 'yes')
                                                        <p class="m-t-5">
                                                            <b>@lang('app.shippingAddress') :</b>
                                                            <span class="text-muted">
                                                        {!! nl2br($invoice->withoutGlobalScopeCompanyClient->client_detail->shipping_address) !!}
                                                    </span>
                                                        </p>
                                                    @endif
                                                    @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->withoutGlobalScopeCompanyClient->client_detail->gst_number))
                                                        <p class="m-t-5"><b>@lang('app.gstIn')
                                                                :</b>  {{ $invoice->withoutGlobalScopeCompanyClient->client_detail->gst_number }}
                                                        </p>
                                                    @endif
                                                @endif

                                                <p class="m-t-30"><b>@lang('modules.invoices.invoiceDate') :</b> <i
                                                            class="fa fa-calendar"></i> {{ $invoice->issue_date->format($settings->date_format) }}
                                                </p>

                                                <p><b>@lang('modules.dashboard.dueDate') :</b> <i
                                                            class="fa fa-calendar"></i> {{ $invoice->due_date->format($settings->date_format) }}
                                                </p>
                                                @if($invoice->recurring == 'yes')
                                                    <p><b class="text-danger">@lang('modules.invoices.billingFrequency') : </b> {{ $invoice->billing_interval . ' '. ucfirst($invoice->billing_frequency) }} ({{ ucfirst($invoice->billing_cycle) }} cycles)</p>
                                                @endif
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="table-responsive m-t-30" style="clear: both;">
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
                                        <div class="pull-right m-t-30 text-right m-b-30">
                                            <p>@lang("modules.invoices.subTotal")
                                                : {!! currency_position($invoice->sub_total,htmlentities($invoice->currency->currency_symbol)) !!}</p>

                                            @if ($discount > 0)
                                                <p>@lang("modules.invoices.discount")
                                                    : {!! currency_position($discount, htmlentities($invoice->currency->currency_symbol)) !!} </p>
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
                                            <p class="@if ($invoice->amountDue() > 0) text-danger @endif">
                                                @lang('modules.invoices.amountDue'): {{ currency_position($invoice->amountDue(), $invoice->currency->currency_symbol) }}
                                            </p>
                                        </div>

                                        @if(!is_null($invoice->note))
                                            <div class="col-xs-12">
                                                <p><strong>@lang('app.note')</strong>: {{ $invoice->note }}</p>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr>
                                        @endif
                                        <div class="col-xs-12">
                                            <p><strong>@lang('modules.invoiceSettings.invoiceTerms')</strong>: {!! nl2br($invoiceSetting->invoice_terms) !!}</p>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                {{--<div class="clearfix"></div>--}}
                                                <div class="col-md-12 p-l-0 text-left">
                                                    @if($invoice->status != 'paid' && $credentials->show_pay)

                                                        <div class="btn-group" id="onlineBox">
                                                            <div class="dropup">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    @lang('modules.invoices.payNow') <span class="caret"></span>
                                                                </button>
                                                                <ul role="menu" class="dropdown-menu">

                                                                    @if($credentials->paypal_status == 'active')
                                                                        <li>
                                                                            <a href="{{ route('client.paypal-public', [$invoice->id]) }}"><i
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
                                                                                        class="fa fa-cc-stripe"></i>  @lang('modules.invoices.payRazorpay')  </a>
                                                                        </li>
                                                                    @endif

                                                                    @if($credentials->paystack_status == 'active')
                                                                        <li class="divider"></li>
                                                                        <li>
                                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#paystackModal">
                                                                                <img height="15px" id="company-logo-img" src="https://s3-eu-west-1.amazonaws.com/pstk-integration-logos/paystack.jpg"> @lang('modules.invoices.payPaystack')</a>
                                                                            {{--                                                                            <a href="{{ route('client.paystack-public', [$invoice->id]) }}">--}}
                                                                            {{--                                                                                <img height="15px" id="company-logo-img" src="https://s3-eu-west-1.amazonaws.com/pstk-integration-logos/paystack.jpg"> @lang('modules.invoices.payPaystack')</a>--}}
                                                                        </li>
                                                                    @endif

                                                                    @if($credentials->mollie_status == 'active')
                                                                        <li class="divider"></li>
                                                                        {{--<li>--}}
                                                                        {{--<a href="{{ route('client.mollie-public', [$invoice->id]) }}"><img src="{{ asset('img/mollie.svg') }}" width="30px" class="display-small"> @lang('modules.invoices.mollie') </a>--}}
                                                                        {{--</li>--}}
                                                                        <li>
                                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#mollieModal">
                                                                                <img height="15px" id="company-logo-img" src="{{ asset('img/mollie.svg') }}"> @lang('modules.invoices.mollie')</a>
                                                                            {{--                                                                            <a href="{{ route('client.paystack-public', [$invoice->id]) }}">--}}
                                                                            {{--                                                                                <img height="15px" id="company-logo-img" src="https://s3-eu-west-1.amazonaws.com/pstk-integration-logos/paystack.jpg"> @lang('modules.invoices.payPaystack')</a>--}}
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
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-right">

                                            </div>
                                        </div>
                                        <div>
                                            <div class="col-xs-12">
                                                <span><p class="displayNone" id="methodDetail"></p></span>
                                            </div>
                                        </div>
                                    </div>
                                    @if(count($invoice->payment) > 0)
                                        <div class="col-xs-12">
                                            <h3>@lang("modules.invoices.paymentDetails")</h3>
                                            <hr>
                                            <div class="table-responsive m-t-40" style="clear: both;">
                                                <table class="table table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center"><b>#</b></th>
                                                        <th class="text-right"><b>@lang("modules.invoices.amount")</b></th>
                                                        <th><b>@lang("modules.invoices.paymentGateway")</b></th>
                                                        <th><b>@lang("modules.invoices.transactionID")</b></th>
                                                        <th><b>@lang("modules.invoices.paidOn")</b></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $count = 0; ?>
                                                    @forelse($invoice->payment as $payment)
                                                        <tr>
                                                            <td class="text-center">{{ $count=$count+1 }}</td>
                                                            <td class="text-right">{!! htmlentities($invoice->currency->currency_symbol)  !!}{{ $payment->amount }}</td>
                                                            <td>{{ htmlentities($payment->gateway)  }}</td>
                                                            <td>{{ $payment->transaction_id }}</td>
                                                            <td>@if(!is_null($payment->paid_on)) {{ $payment->paid_on->format($settings->date_format.' '.$settings->time_format) }} @endif</td>
                                                        </tr>
                                                        @if($payment->remarks)
                                                            <tr><td colspan="5"><b>@lang("modules.invoices.remark")</b> : {!! $payment->remarks !!}</td></tr>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<div class="modal" tabindex="-1" role="dialog" id="mollieModal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('client.mollie-public', [$invoice->id]) }}" id="mollieForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mollie Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="mollieName" id="mollieName" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="mollieEmail" id="mollieEmail" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" onclick="validateMollieForm(event);" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="paystackModal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('client.paystack-public', [$invoice->id]) }}" id="paystackForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Paystack Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" onclick="validateForm(event);" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
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
                                                <input type="number" class="form-control" id="amount" name="amount" value="{{ $invoice->total }}">
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
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js'></script>

<!-- Sidebar menu plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<!--Slimscroll JavaScript For custom scroll-->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

{{--sticky note script--}}
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.init.js') }}"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{{--<script src="https://checkout.stripe.com/checkout.js"></script>--}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
    @if (count($errors) > 0)
    $('#paystackModal').modal('show');
    @endif

    function validateForm(event) {
        event.preventDefault();

        $('#paystackForm').find(".has-error").each(function () {
            $(this).find(".help-block").text("");
            $(this).removeClass("has-error");
        });

        var name = $('#name').val();
        var email = $('#email').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(name  == '') {
            $('#name').parent('div').addClass('has-error');
            var grp = $('#name').closest(".form-group");
            var helpBlockContainer = $(grp);
            helpBlockContainer.append('<div class="help-block">The name field is required</div>');
            $(grp).addClass("has-error");
            return;
        }
        if(email  == '') {
            $('#email').parent('div').addClass('has-error');
            var grp = $('#email').closest(".form-group");
            var helpBlockContainer = $(grp);
            helpBlockContainer.append('<div class="help-block">The email field is required</div>');
            $(grp).addClass("has-error");
            return;
        }

        if (mailformat.test(email) == false)
        {
            $('#email').parent('div').addClass('has-error');
            var grp = $('#email').closest(".form-group");
            var helpBlockContainer = $(grp);
            helpBlockContainer.append('<div class="help-block">The given email is not a valid email.</div>');
            $(grp).addClass("has-error");
            return;
        }

        $('#paystackForm').submit();
    }


    function validateMollieForm(event) {
        event.preventDefault();

        $('#mollieForm').find(".has-error").each(function () {
            $(this).find(".help-block").text("");
            $(this).removeClass("has-error");
        });

        var name = $('#mollieName').val();
        var email = $('#mollieEmail').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(name  == '') {
            $('#mollieName').parent('div').addClass('has-error');
            var grp = $('#mollieName').closest(".form-group");
            var helpBlockContainer = $(grp);
            helpBlockContainer.append('<div class="help-block">The name field is required</div>');
            $(grp).addClass("has-error");
            return;
        }
        if(email  == '') {
            $('#mollieEmail').parent('div').addClass('has-error');
            var grp = $('#mollieEmail').closest(".form-group");
            var helpBlockContainer = $(grp);
            helpBlockContainer.append('<div class="help-block">The email field is required</div>');
            $(grp).addClass("has-error");
            return;
        }

        if (mailformat.test(email) == false)
        {
            $('#mollieEmail').parent('div').addClass('has-error');
            var grp = $('#mollieEmail').closest(".form-group");
            var helpBlockContainer = $(grp);
            helpBlockContainer.append('<div class="help-block">The given email is not a valid email.</div>');
            $(grp).addClass("has-error");
            return;
        }

        $('#mollieForm').submit();
    }

    $('#next-button').click( function () {
        $('#next-button').attr('disabled', true);
        var url = "{{ route('front.stripe-modal')}}";
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
        var clientEmail = "";

        var options = {
            "key": "{{ $credentials->razorpay_key }}",
            "amount": amount,
            "currency": 'INR',
            "name": "{{ $companyName }}",
            "description": "Invoice Payment",
            "image": "{{ $settings->logo_url }}",
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
            url:'{{route('public.pay-with-razorpay')}}',
            data: {paymentId: id,invoiceId: invoiceId,rData: rData,_token:'{{csrf_token()}}'}
        })
    }



    @endif
    //Confirmation after transaction
    function confirmAuthorizePayment() {
        $.easyAjax({
            type:'POST',
            url:'{{route('client.authorize.pay-submit')}}',
            data: $('#paymentCardInfo').serialize(),
            success: function(res) {
                console.log('Hello from response', res);
                if(res.status == 'success') {
                    window.location.reload();
                }
            }
        })
    }
    // Show offline method detail
    function showDetail(id){
        var detail = $('#method-desc-'+id).html();
        $('#methodDetail').html(detail);
        $('#methodDetail').show();
    }
</script>

<!--sticky header-->
<script>
    $(window).scroll(function(){
        if ($(window).scrollTop() >= 80) {
            $('.invoice-wrap').addClass('fixed-invoice-header');
            // $('invoice-wrap').addClass('visible-title');
        }
        else {
            $('.invoice-wrap').removeClass('fixed-invoice-header');
            // $('invoice-wrap').removeClass('visible-title');
        }
    });
</script>

</body>
</html>
