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
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $invoice->invoice_number }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
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

        .admin-logo {
            max-height: 40px;
        }

        .ribbon {
            top: 12px !important;
            left: 0px;
        }
        .signature-pad {
            position: absolute;
            left: 0;
            top: 0;
            width:100%;
            height: 100%;
            background-color: white;
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
        <div class="container-fluid">

        <!-- .row -->
            <div class="row">
                <div class="col-md-offset-2 col-md-8 m-t-40 m-b-40">
                        <img src="{{ $global->logo_url }}" alt="home" class="admin-logo"/>
                </div>

                <div class="col-md-offset-2 col-md-8 col-md-offset-2">
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

                                            @if(is_null($invoice->project) && !is_null($invoice->estimate) && !is_null($invoice->estimate->client))
                                                <h3>@lang('modules.invoices.to'),</h3>
                                                <h4 class="font-bold">{{ ucwords($invoice->estimate->client->name) }}</h4>
                                                @if(!is_null($invoice->estimate->client->client_details))
                                                    <p class="m-l-30">
                                                        <b>@lang('app.address') :</b>
                                                        <span class="text-muted">
                                                            {!! nl2br($invoice->estimate->client->client_details->address) !!}
                                                        </span>
                                                    </p>
                                                    @if($invoice->show_shipping_address === 'yes')
                                                        <p class="m-t-5">
                                                            <b>@lang('app.shippingAddress') :</b>
                                                            <span class="text-muted">
                                                                {!! nl2br($invoice->estimate->client->client_details->shipping_address) !!}
                                                            </span>
                                                        </p>
                                                    @endif
                                                    @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->estimate->client->client_details->gst_number))
                                                        <p class="m-t-5"><b>@lang('app.gstIn')
                                                                :</b>  {{ $invoice->estimate->client->client_details->gst_number }}
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
                                        <hr>
                                        @if ($invoice->credit_notes()->count() > 0)
                                            <p>
                                                @lang('modules.invoices.appliedCredits'): {{ $invoice->currency->currency_symbol.''.$invoice->appliedCredits() }}
                                            </p>
                                        @endif
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
                                        <div class="col-md-6 text-left">
                                            {{--<div class="clearfix"></div>--}}
                                            @if ($invoice->total > 0)
                                                <div class="col-md-12 p-l-0 text-left">
                                                    @if($invoice->status != 'paid' && ($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active'  || $credentials->razorpay_status == 'active'))

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
                                                                    @if($credentials->razorpay_status == 'active')
                                                                        @if ($credentials->paypal_status == 'active' || $credentials->stripe_status == 'active')
                                                                            <li class="divider"></li>
                                                                        @endif
                                                                        <li>
                                                                            <a href="javascript:void(0);" id="razorpayPaymentButton"><i
                                                                                        class="fa fa-credit-card"></i> @lang('modules.invoices.payRazorpay') </a>
                                                                        </li>
                                                                    @endif
                                                                    @if($credentials->stripe_status == 'active')
                                                                        @if ($credentials->paypal_status == 'active' || $credentials->razorpay_status == 'active')
                                                                            <li class="divider"></li>
                                                                        @endif
                                                                        <li>
                                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#stripeModal"><i
                                                                                class="fa fa-cc-stripe"></i> @lang('modules.invoices.payStripe') </a>
                                                                            <a style="display:none;" href="javascript:void(0);" id="stripePaymentButton"><i class="fa fa-cc-stripe"></i> @lang('modules.invoices.payStripe') </a>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>

                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="col-md-12 p-l-0 text-left">
                                                   <strong>@lang('messages.amountIsZero')</strong>
                                                </div>
                                            @endif


                                        </div>
                                        <div class="col-md-6 text-right">

                                        </div>
                                    </div>
                                    <div>
                                        <div class="col-md-12">
                                            <span><p class="displayNone" id="methodDetail"></p></span>
                                        </div>
                                    </div>
                                </div>
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
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src='https:{{ asset('js/bootstrap-select.min.js') }}'></script>

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
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


<script>
    @if($credentials->stripe_status == 'active')

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

    @endif

    @if($credentials->razorpay_status == 'active')
        $('#razorpayPaymentButton').click(function() {
            var amount = {{ $invoice->total*100 }};
            var invoiceId = {{ $invoice->id }};
            @if($invoice->project && $invoice->project->client)
                var clientEmail = "{{ $invoice->project->client->email }}";
            @else
                var clientEmail = "{{ $invoice->client->email }}";
            @endif

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
</script>

</body>
</html>
