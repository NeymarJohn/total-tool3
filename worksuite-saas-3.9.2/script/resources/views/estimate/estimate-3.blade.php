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
            if(isset($company)){
                if($company->locale == 'ja') {
                    $font = 'ipag';
                } else if($company->locale == 'hi') {
                    $font = 'hindi';
                } else if($company->locale == 'th') {
                    $font = 'THSarabun';
                } else {
                    $font = 'noto-sans';
                }
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
            font: normal 13px/1.4em 'Open Sans', Sans-serif;
            margin: 0 auto;
            min-height: 1158px;
            background: #F7EDEB url("{{ asset("img/bg.png") }}") 0 0 no-repeat;
            background-size: 100% auto;
            color: #5B6165;
            position: relative;
        }

        #memo {
            padding-top: 40px;
            margin: 0 110px 0 60px;
            border-bottom: 1px solid #ddd;
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
        #memo .company-info > div:first-child {
            line-height: 1em;
            font-weight: bold;
            font-size: 22px;
            color: #B32C39;
        }
        #memo .company-info span {
            font-size: 11px;
            display: inline-block;
            min-width: 20px;
            width: 100%;
        }
        #memo:after {
            content: '';
            display: block;
            clear: both;
        }

        #invoice-title-number {
            font-weight: bold;
            margin: 30px 0;
        }
        #invoice-title-number span {
            line-height: 0.88em;
            display: inline-block;
            min-width: 20px;
        }
        #invoice-title-number #title {
            text-transform: uppercase;
            padding: 5px 2px 0 60px;
            font-size: 50px;
            background: #F4846F;
            color: white;
        }
        #invoice-title-number #number {
            margin-left: 10px;
            font-size: 35px;
            position: relative;
            top: -5px;
        }

        #client-info {
            float: left;
            margin-left: 60px;
            min-width: 220px;
        }
        #client-info > div {
            margin-bottom: 3px;
            min-width: 20px;
        }
        #client-info span {
            display: block;
            min-width: 20px;
        }
        #client-info > span {
            text-transform: uppercase;
        }

        table {
            table-layout: fixed;
        }
        table th, table td {
            vertical-align: top;
            word-break: keep-all;
            word-wrap: break-word;
        }

        #items {
            margin: 25px 30px 0 30px;
        }
        #items .first-cell, #items table th:first-child, #items table td:first-child {
            width: 40px !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            text-align: right;
        }
        #items table {
            border-collapse: separate;
            width: 100%;
        }
        #items table th {
            font-weight: bold;
            padding: 5px 8px;
            text-align: right;
            background: #B32C39;
            color: white;
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
            padding: 9px 8px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        #items table td:nth-child(2) {
            text-align: left;
        }

        #itemsPayment {
            margin: 25px 30px 0 30px;
        }
        #itemsPayment .first-cell, #itemsPayment table th:first-child, #itemsPayment table td:first-child {
            width: 40px !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            text-align: right;
        }
        #itemsPayment table {
            border-collapse: separate;
            width: 100%;
        }
        #itemsPayment table th {
            font-weight: bold;
            padding: 5px 8px;
            text-align: right;
            background: #B32C39;
            color: white;
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
            padding: 9px 8px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        #itemsPayment table td:nth-child(2) {
            text-align: left;
        }

        #sums {
            margin: 25px 30px 0 0;
            background: url("{{asset("img/total-stripe-firebrick.png")}}") right bottom no-repeat;
            width: 100%;
        }
        #sums table {
            width: 50%;
            float: right;
        }
        #sums table tr:last-child {
            color: white;
        }
        #sums table tr th, #sums table tr td {
            min-width: 100px;
            padding: 9px 8px;
            text-align: right;
        }
        #sums table tr th {
            width: 70%;
            font-weight: bold;
            padding-right: 35px;
        }
        #sums table tr td.last {
            min-width: 0 !important;
            max-width: 0 !important;
            width: 0 !important;
            padding: 0 !important;
            border: none !important;
        }
        #sums table tr.amount-total th {
            text-transform: uppercase;
        }
        #sums table tr.amount-total th, #sums table tr.amount-total td {
            font-size: 15px;
            font-weight: bold;
        }

        #invoice-info {
            float: left;
            margin: 50px 40px 0 60px;
        }
        #invoice-info > div > span {
            display: inline-block;
            min-width: 20px;
            min-height: 18px;
            margin-bottom: 3px;
        }
        #invoice-info > div > span:first-child {
            color: black;
        }
        #invoice-info > div > span:last-child {
            color: #aaa;
        }
        #invoice-info:after {
            content: '';
            display: block;
            clear: both;
        }

        #terms {
            float: left;
            margin-top: 50px;
        }
        #terms .notes {
            min-height: 30px;
            min-width: 50px;
            color: #B32C39;
        }
        #terms .payment-info div {
            margin-bottom: 3px;
            min-width: 20px;
        }

        .thank-you {
            margin: 10px 0 30px 0;
            display: inline-block;
            min-width: 20px;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 0.88em;
            float: right;
            padding: 5px 30px 0 2px;
            font-size: 50px;
            background: #F4846F;
            color: white;
        }

        .ib_bottom_row_commands {
            margin-left: 30px !important;
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
    <section id="memo">
        <div class="logo">
            <img src="{{ $invoiceSetting->logo_url }}" alt="home" class="dark-logo" />
        </div>

        <div class="company-info">
            <div>
                {{ ucwords($global->company_name) }}
            </div>

            <br />

            <span>{!! nl2br($global->address) !!}</span>

            <br />

            <span>{{ $global->company_phone }}</span>

            <br />
            
        </div>

    </section>
        
    <section id="invoice-title-number">
        <span id="title">{{ (is_null($estimate->estimate_number)) ? '#'.$estimate->id : $estimate->estimate_number}}</span>

       
    </section>

    <div class="clearfix"></div>
        <section id="client-info">
           
                <span>@lang("modules.estimates.validTill"):</span>
                <div>
                    <span class="bold">{{ $estimate->valid_till->format($global->date_format) }}</span>
                </div>

                

                <div class="mb-3">
                    <b>@lang('app.status') :</b>
                    <div>{{ __('app.'.$estimate->status) }}</div>
                </div>
        </section>
        @php
        $colspan = ($invoiceSetting->hsn_sac_code_show) ? 5 : 4;
    @endphp
    <div class="clearfix"></div>
    <br>
    <section id="items">

        <table >

            <tr>
                <th class="no">#</th> <!-- Dummy cell for the row number and row commands -->
                <th class="desc">@lang("modules.invoices.item")</th>
                @if($invoiceSetting->hsn_sac_code_show)
                    <th> @lang('modules.invoices.hsnSacCode')</th>
                @endif
                <th class="qty">@lang("modules.invoices.qty")</th>
                <th class="qty">@lang("modules.invoices.unitPrice") ({!! htmlentities($estimate->currency->currency_code)  !!})</th>
                <th class="unit">@lang("modules.invoices.price") ({!! htmlentities($estimate->currency->currency_code)  !!})</th>
            </tr>

            <?php $count = 0; ?>
            @foreach($estimate->items as $item)
                @if($item->type == 'item')
                    <tr style="page-break-inside: avoid;">
                        <td class="no">{{ ++$count }}</td>
                        <td class="desc"><h3>{{ ucfirst($item->item_name) }}</h3>
                            @if(!is_null($item->item_summary))
                                <p class="item-summary">{{ $item->item_summary }}</p>
                            @endif
                        </td>
                        @if($invoiceSetting->hsn_sac_code_show)
                            <td class="qty">{{ $item->hsn_sac_code ?? '--' }}</td>
                        @endif
                        <td class="qty">{{ $item->quantity }}</td>
                        <td class="qty">{{ number_format((float)$item->unit_price, 2, '.', '') }}</td>
                        <td class="unit">{{ number_format((float)$item->amount, 2, '.', '') }}</td>
                    </tr>
                @endif
            @endforeach

        </table>

    </section>

    <section id="sums">

        <table cellpadding="0" cellspacing="0">
            <tr>
                <th>@lang("modules.invoices.subTotal"):</th>
                    <td>{{ number_format((float)$estimate->sub_total, 2, '.', '') }}</td>
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
            
            
           
        </table>

        <div class="clearfix"></div>

    </section>

   

   
    <br>
    <div class="thank-you">@lang('app.thanks')!</div>



   
</div>
</body>
</html>
