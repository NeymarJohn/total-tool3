<!DOCTYPE html>
<!--
  Invoice template by invoicebus.com
  To customize this template consider following this guide https://invoicebus.com/how-to-create-invoice-template/
  This template is under Invoicebus Template License, see https://invoicebus.com/templates/license/
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@lang('app.invoice')</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Invoice">

    <style>

        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/TH_Sarabun.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: bold;
            src: url("{{ storage_path('fonts/TH_SarabunBold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: italic;
            font-weight: bold;
            src: url("{{ storage_path('fonts/TH_SarabunBoldItalic.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabun';
            font-style: italic;
            font-weight: bold;
            src: url("{{ storage_path('fonts/TH_SarabunItalic.ttf') }}") format('truetype');
        }

        @php
            $font = '';
            if($company->locale == 'ja') {
                $font = 'ipag';
            } else if($company->locale == 'hi') {
                $font = 'hindi';
            } else if($company->locale == 'th') {
                $font = 'THSarabun';
            } else {
                $font = 'noto-sans';
            }
        @endphp

        * {
            font-family: {{$font}}, DejaVu Sans , sans-serif;
        }
        html {
            line-height: 1;
        }

        ol, ul {
            list-style: none;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        caption, th, td {
            text-align: left;
            font-weight: normal;
            vertical-align: middle;
        }

        q, blockquote {
            quotes: none;
        }
        q:before, q:after, blockquote:before, blockquote:after {
            content: "";
            content: none;
        }

        a img {
            border: none;
        }

        article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
            display: block;
        }

        /* Invoice styles */
        /**
         * DON'T override any styles for the <html> and <body> tags, as this may break the layout.
         * Instead wrap everything in one main <div id="container"> element where you may change
         * something like the font or the background of the invoice
         */
        html, body {
            /* MOVE ALONG, NOTHING TO CHANGE HERE! */
        }

        /**
         * IMPORTANT NOTICE: DON'T USE '!important' otherwise this may lead to broken print layout.
         * Some browsers may require '!important' in oder to work properly but be careful with it.
         */
        .clearfix {
            display: block;
            clear: both;
        }

        .hidden {
            display: none;
        }

        b, strong, .bold {
            font-weight: bold;
        }

        #container {
            font: normal 13px 'Open Sans', Sans-serif;
            margin: 0 auto;
            min-height: 1078px;
        }

        .invoice-top {
            background: #242424;
            color: #fff;
            padding: 40px 40px 30px 40px;
        }

        .invoice-body {
            padding: 50px 40px 40px 40px;
        }

        #memo .logo {
            float: left;
            margin-right: 20px;
        }
        #memo .logo img {
            max-width: 150px;
        }
        #memo .company-info {
            /*float: right;*/
            text-align: right;
        }
        #memo .company-info .company-name {
            color: #F8ED31;
            font-weight: bold;
            font-size: 32px;
        }
        #memo .company-info .spacer {
            height: 15px;
            display: block;
        }
        #memo .company-info div {
            font-size: 12px;
            float: right;
            margin: 0 18px 3px 0;
        }
        #memo:after {
            content: '';
            display: block;
            clear: both;
        }

        #invoice-info {
            float: left;
            margin-top: 50px;
        }

        #invoice-info table{
            width: 30%;
        }
        #invoice-info > div {
            float: left;
        }
        #invoice-info > div > span {
            display: block;
            min-width: 100px;
            min-height: 18px;
            margin-bottom: 3px;
        }
        #invoice-info > div:last-of-type {
            margin-left: 10px;
            text-align: right;
        }
        #invoice-info:after {
            content: '';
            display: block;
            clear: both;
        }

        #client-info {
            float: right;
            /*margin-top: 5px;*/
            margin-right: 30px;
            min-width: 220px;
        }
        #client-info > div {
            margin-bottom: 3px;
        }
        #client-info span {
            display: block;
        }
        #client-info > span {
            margin-bottom: 3px;
        }

        #invoice-title-number {
            margin-top: 30px;
        }
        #invoice-title-number #title {
            margin-right: 5px;
            text-align: right;
            font-size: 35px;
        }
        #invoice-title-number #number {
            margin-left: 5px;
            text-align: left;
            font-size: 20px;
            padding-bottom: 10px;
        }

        table {
            table-layout: fixed;
        }
        table th, table td {
            vertical-align: top;
            word-break: keep-all;
            word-wrap: break-word;
        }

        #items .first-cell, #items table th:first-child, #items table td:first-child {
            width: 18px;
            text-align: right;
        }
        #items table {
            border-collapse: separate;
            width: 100%;
        }
        #items table th {
            font-weight: bold;
            padding: 12px 10px;
            text-align: right;
            border-bottom: 1px solid #444;
            text-transform: uppercase;
        }
        #items table th:nth-child(2) {
            width: 30%;
            text-align: left;
        }
        #items table th:last-child {
            text-align: right;
        }
        #items table td {
            border-right: 1px solid #b6b6b6;
            padding: 15px 10px;
            text-align: right;
        }
        #items table td:first-child {
            text-align: left;itemsPayment
            border-right: none !important;
        }
        #items table td:nth-child(2) {
            text-align: left;
        }
        #items table td:last-child {
            border-right: none !important;
        }

        #itemsPayment .first-cell, #itemsPayment table th:first-child, #itemsPayment table td:first-child {
            width: 18px;
            text-align: right;
        }
        #itemsPayment table {
            border-collapse: separate;
            width: 100%;
        }
        #itemsPayment table th {
            font-weight: bold;
            padding: 12px 10px;
            text-align: right;
            border-bottom: 1px solid #444;
            text-transform: uppercase;
        }
        #itemsPayment table th:nth-child(2) {
            width: 30%;
            text-align: left;
        }
        #itemsPayment table th:last-child {
            text-align: right;
        }
        #itemsPayment table td {
            border-right: 1px solid #b6b6b6;
            padding: 15px 10px;
            text-align: right;
        }
        #itemsPayment table td:first-child {
            text-align: left;
            border-right: none !important;
        }
        #itemsPayment table td:nth-child(2) {
            text-align: left;
        }
        #itemsPayment table td:last-child {
            border-right: none !important;
        }

        #sums {
            float: right;
            /*margin-right: 100px;*/
            margin-top: 30px;
        }

        #sums table{
            width: 50%;
        }
        #sums table tr th, #sums table tr td {
            min-width: 100px;
            padding: 10px;
            text-align: right;
            font-weight: bold;
            font-size: 14px;
        }
        #sums table tr th {
            width: 70%;
            padding-right: 25px;
            color: #707070;
        }
        /*#sums table tr td:last-child {*/
            /*min-width: 0 !important;*/
            /*max-width: 0 !important;*/
            /*width: 0 !important;*/
            /*padding: 0 !important;*/
            /*overflow: visible;*/
        /*}*/
        #sums table tr.amount-total th {
            color: black;
        }
        #sums table tr.amount-total th, #sums table tr.amount-total td {
            font-weight: bold;
        }
        /*#sums table tr.amount-total td:last-child {*/
            /*position: relative;*/
        /*}*/
        /*#sums table tr.amount-total td:last-child .currency {*/
            /*position: absolute;*/
            /*top: 3px;*/
            /*left: -740px;*/
            /*font-weight: normal;*/
            /*font-style: italic;*/
            /*font-size: 12px;*/
            /*color: #707070;*/
        /*}*/
        /*#sums table tr.amount-total td:last-child:before {*/
            /*display: block;*/
            /*content: '';*/
            /*border-top: 1px solid #444;*/
            /*position: absolute;*/
            /*top: 0;*/
            /*left: -740px;*/
            /*right: 0;*/
        /*}*/
        /*#sums table tr:last-child th, #sums table tr:last-child td {*/
            /*color: black;*/
        /*}*/

        #terms {
            margin: 100px 0 15px 0;
        }
        #terms > div {
            min-height: 70px;
        }

        .payment-info {
            color: #707070;
            font-size: 12px;
        }
        .payment-info div {
            display: inline-block;
            min-width: 10px;
        }

        .ib_drop_zone {
            color: #F8ED31 !important;
            border-color: #F8ED31 !important;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        /**
         * If the printed invoice is not looking as expected you may tune up
         * the print styles (you can use !important to override styles)
         */
        @media print {
            /* Here goes your print styles */
        }
        .page_break { page-break-before: always; }
        .h3-border {
            border-bottom: 1px solid #AAAAAA;
        }
        table td.text-center
        {
            text-align: center;
        }

    </style>
</head>
<body>
<div id="container">
    @php
        $colspan = ($invoiceSetting->hsn_sac_code_show) ? 5 : 4;
    @endphp
    <div class="invoice-top">
        <section id="memo">
            <div class="logo">
                <img src="{{ $invoiceSetting->logo_url }}" alt="home" class="dark-logo" />

            </div>

            <div class="company-info">
                <span class="company-name">
                    {{ ucwords($company->company_name) }}
                </span>

                <span class="spacer"></span>

                <div>{!! nl2br($company->address) !!}</div>


                <span class="clearfix"></span>

                <div>{{ $company->company_phone }}</div>

                <span class="clearfix"></span>

                @if($invoiceSetting->show_gst == 'yes' && !is_null($invoiceSetting->gst_number))
                    <div>@lang('app.gstIn'): {{ $invoiceSetting->gst_number }}</div>
                @endif
            </div>

        </section>

        <section id="invoice-info">
            <table>
                <tr>
                    <td>@lang('app.menu.issues') @lang('app.date'):</td>
                    <td>{{ $invoice->issue_date->format($company->date_format) }}</td>
                </tr>
                @if($invoice->status == 'unpaid')
                    <tr>
                        <td>@lang('app.dueDate'):</td>
                        <td>{{ $invoice->due_date->format($company->date_format) }}</td>
                    </tr>
                @endif
                <tr>
                    <td>@lang('app.status'):</td>
                    <td>{{ __('app.'.$invoice->status) }}</td>
                </tr>
                @if($creditNote)
                    <tr>
                        <td>@lang('app.credit-note')</td>
                        <td>{{ $creditNote->cn_number }}</td>
                    </tr>
                @endif

            </table>


            <div class="clearfix"></div>

            <section id="invoice-title-number">

                <span id="title">{{ $invoiceSetting->invoice_prefix }}</span>
                <span id="number">{{ $invoice->original_invoice_number }}</span>

            </section>
            <div class="clearfix"></div>
        </section>
        @if(!is_null($invoice->project) && !is_null($invoice->project->client))
            <section id="client-info">
                @if(!is_null($invoice->project->client))
                    <span>@lang('modules.invoices.billedTo'):</span>
                    <div>
                        <span class="bold">{{ ucwords($invoice->project->client->name) }}</span>
                    </div>

                    <div>
                        <span>{{ ucwords($invoice->project->client->company_name) }}</span>
                    </div>

                    <div class="mb-3">
                            <b>@lang('app.address') :</b>
                            <div>{!! nl2br($invoice->project->clientdetails->address) !!}</div>
                        </div>
                        @if ($invoice->show_shipping_address === 'yes')
                            <div>
                                <b>@lang('app.shippingAddress') :</b>
                                <div>{!! nl2br($invoice->project->clientdetails->shipping_address) !!}</div>
                            </div>
                        @endif

                    <div>
                        <span>{{ $invoice->project->client->email }}</span>
                    </div>
                    @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->project->client) && !is_null($invoice->project->client->gst_number))
                        <div>
                            <span> @lang('app.gstIn'): {{ $invoice->project->client->gst_number }} </span>
                        </div>
                    @endif
                @endif
            </section>
        @elseif(!is_null($invoice->client_id))
            <section id="client-info">
                @if(!is_null($invoice->withoutGlobalScopeCompanyClient))
                    <span>@lang('modules.invoices.billedTo'):</span>
                    <div>
                        <span class="bold">{{ ucwords($invoice->withoutGlobalScopeCompanyClient->name) }}</span>
                    </div>

                    @if($invoice->clientdetails)
                        <div>
                            <span>{{ ucwords($invoice->clientdetails->company_name) }}</span>
                        </div>

                        <div class="mb-3">
                            <b>@lang('app.address') :</b>
                            <div>{!! nl2br($invoice->clientdetails->address) !!}</div>
                        </div>
                        @if ($invoice->show_shipping_address === 'yes')
                            <div>
                                <b>@lang('app.shippingAddress') :</b>
                                <div>{!! nl2br($invoice->clientdetails->shipping_address) !!}</div>
                            </div>
                        @endif
                    @endif

                    <div>
                        <span>{{ $invoice->withoutGlobalScopeCompanyClient->email }}</span>
                    </div>
                    @if($invoiceSetting->show_gst == 'yes' && !is_null($invoice->clientdetails) && !is_null($invoice->clientdetails->gst_number))
                        <div>
                            <span> @lang('app.gstIn'): {{ $invoice->clientdetails->gst_number }} </span>
                        </div>
                    @endif
                @endif
            </section>
        @endif
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>

    <div class="invoice-body">
        <section id="items">

            <table cellpadding="0" cellspacing="0">

                <tr>
                    <th>#</th> <!-- Dummy cell for the row number and row commands -->
                    <th>@lang("modules.invoices.item")</th>
                    @if($invoiceSetting->hsn_sac_code_show)
                        <th> @lang('modules.invoices.hsnSacCode')</th>
                    @endif
                    <th>@lang("modules.invoices.qty")</th>
                    <th>@lang("modules.invoices.unitPrice") ({!! htmlentities($invoice->currency->currency_code)  !!})</th>
                    <th>@lang("modules.invoices.price") ({!! htmlentities($invoice->currency->currency_code)  !!})</th>
                </tr>

                <?php $count = 0; ?>
                @foreach($invoice->items as $item)
                    @if($item->type == 'item')
                <tr data-iterate="item">
                    <td>{{ ++$count }}</td> <!-- Don't remove this column as it's needed for the row commands -->
                    <td>{{ ucfirst($item->item_name) }}</td>
                    @if($invoiceSetting->hsn_sac_code_show)
                        <td>{{ $item->hsn_sac_code ?? '--' }}</td>
                    @endif
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((float)$item->unit_price, 2, '.', '') }}</td>
                    <td>{{ number_format((float)$item->amount, 2, '.', '') }}</td>
                </tr>
                    @endif
                @endforeach

            </table>

        </section>

        <section id="sums">

            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th>@lang("modules.invoices.subTotal"):</th>
                    <td>{{ number_format((float)$invoice->sub_total, 2, '.', '') }}</td>
                </tr>
                @if($discount != 0 && $discount != '')
                <tr data-iterate="tax">
                    <th>@lang("modules.invoices.discount"):</th>
                    <td>-{{ number_format((float)$discount, 2, '.', '') }}</td>
                </tr>
                @endif
                @foreach($taxes as $key=>$tax)
                <tr data-iterate="tax">
                    <th>{{ strtoupper($key) }}:</th>
                    <td>{{ number_format((float)$tax, 2, '.', '') }}</td>
                </tr>
                @endforeach
                <tr class="amount-total">
                    <th>
                        <hr>@lang("modules.invoices.total"):
                    </th>
                    <td>
                        <hr>{{ number_format((float)$invoice->total, 2, '.', '') }}
                    </td>
                </tr>
                @if ($invoice->credit_notes()->count() > 0)
                    <tr>
                        <th>
                            <hr>@lang('modules.invoices.appliedCredits'):</th>
                        <td>
                            <hr>{{ number_format((float)$invoice->appliedCredits(), 2, '.', '') }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <th>
                        @lang("modules.invoices.total") @lang("modules.invoices.paid"):
                    </th>
                    <td>
                        {{ number_format((float)$invoice->amountPaid(), 2, '.', '') }}
                    </td>
                </tr>
                <tr>
                    <th>
                        @lang("modules.invoices.total") @lang("modules.invoices.due"):
                    </th>
                    <td>
                        {{ number_format((float)$invoice->amountDue(), 2, '.', '') }}
                    </td>
                </tr>
            </table>

        </section>

        <div class="clearfix"></div>
        <hr>
        <section id="terms">
            <span class="hidden">@lang('app.terms'):</span>
     
            @if(!is_null($invoice->note))
                <div>{!! nl2br($invoice->note) !!}</div>
            @endif
            <div>{!! nl2br($invoiceSetting->invoice_terms) !!}</div>

        </section>


        @if(!is_null($payments))
            <div class="clearfix"></div>
            <div class="page_break"></div>
            <div class="invoice-body b-all m-b-20">
                <h3 class="box-title m-t-20 text-center h3-border">@lang('modules.invoices.recentPayments')</h3>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive m-t-40" id="itemsPayment" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">@lang("modules.payments.amount")</th>
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

</body>
</html>
