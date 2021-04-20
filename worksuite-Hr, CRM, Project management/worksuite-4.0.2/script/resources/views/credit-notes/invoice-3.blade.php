<!DOCTYPE html>
<!--
  Invoice template by invoicebus.com
  To customize this template consider following this guide https://invoicebus.com/how-to-create-invoice-template/
  This template is under Invoicebus Template License, see https://invoicebus.com/templates/license/
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@lang('app.credit-note')</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="creditNote">


    <style>
        /*! Invoice Templates @author: Invoicebus @email: info@invoicebus.com @web: https://invoicebus.com @version: 1.0.0 @updated: 2015-02-27 16:02:34 @license: Invoicebus */
        /* Reset styles */
        /*@import url("https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=cyrillic,cyrillic-ext,latin,greek-ext,greek,latin-ext,vietnamese");*/
        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-family: 'DejaVu Sans', sans-serif;
            /*font-size: 80%;*/
            vertical-align: baseline;
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
            height: 85px;
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

        #creditNote-title-number {
            font-weight: bold;
            margin: 30px 0;
        }
        #creditNote-title-number span {
            line-height: 0.88em;
            display: inline-block;
            min-width: 20px;
        }
        #creditNote-title-number #title {
            text-transform: uppercase;
            padding: 5px 2px 0 60px;
            font-size: 50px;
            background: #F4846F;
            color: white;
        }
        #creditNote-title-number #number {
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

        #sums {
            margin: 25px 30px 0 0;
            background: url("{{asset("img/total-stripe-firebrick.png")}}") right bottom no-repeat;
            width: 100%;
        }
        #sums table {
            width: 50%;
            float: right;
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
        #creditNote-info {
            float: left;
            margin: 50px 40px 0 60px;
        }
        #creditNote-info > div > span {
            display: inline-block;
            min-width: 20px;
            min-height: 18px;
            margin-bottom: 3px;
        }
        #creditNote-info > div > span:first-child {
            color: black;
        }
        #creditNote-info > div > span:last-child {
            color: #aaa;
        }
        #creditNote-info:after {
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

        /**
         * If the printed invoice is not looking as expected you may tune up
         * the print styles (you can use !important to override styles)
         */
        @media print {
            /* Here goes your print styles */
        }

    </style>
</head>
<body>
<div id="container">
    <section id="memo">
        <div class="logo">
            @if(is_null($global->logo))
                <img src="{{ public_path().'/img/worksuite-logo.png' }}" />
            @else
                <img src="{{ public_path().'/user-uploads/app-logo/'.$global->logo }}" alt="home" class="dark-logo"/>
            @endif
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

            @if($creditNoteSetting->show_gst == 'yes' && !is_null($creditNoteSetting->gst_number))
                <div>@lang('app.gstIn'): {{ $creditNoteSetting->gst_number }}</div>
            @endif
        </div>

    </section>

    <section id="creditNote-title-number">

        <span id="title">{{ $creditNoteSetting->credit_note_prefix }}</span>
        <span id="number">{{ $creditNote->original_cn_number }}</span>

    </section>

    <div class="clearfix"></div>
    @if(!is_null($creditNote->project->client))
        <section id="client-info">
            @if(!is_null($creditNote->project->client))
            <span>@lang('modules.credit-notes.billedTo')</span>
            <div>
                <span class="bold">{{ ucwords($creditNote->project->client->name) }}</span>
            </div>

            <div>
                <span>{{ ucwords($creditNote->project->clientdetails->company_name) }}</span>
            </div>

            <div>
                <span>{!! nl2br($creditNote->project->clientdetails->address) !!}</span>
            </div>

            <div>
                <span>{{ $creditNote->project->client->email }}</span>
            </div>
                @if($creditNoteSetting->show_gst == 'yes' && !is_null($creditNote->project->clientdetails->gst_number))
                    <div>
                        <span> @lang('app.gstIn'): {{ $creditNote->project->clientdetails->gst_number }} </span>
                    </div>
                @endif
            @endif

        </section>
    @endif
    <div class="clearfix"></div>
    <br>
    <section id="items">

        <table cellpadding="0" cellspacing="0">

            <tr>
                <th>#</th> <!-- Dummy cell for the row number and row commands -->
                <th>@lang("modules.credit-notes.item")</th>
                <th>@lang("modules.credit-notes.qty")</th>
                <th>@lang("modules.credit-notes.unitPrice") ({!! htmlentities($creditNote->currency->currency_code)  !!})</th>
                <th>@lang("modules.credit-notes.price") ({!! htmlentities($creditNote->currency->currency_code)  !!})</th>
            </tr>

            <?php $count = 0; ?>
            @foreach($creditNote->items as $item)
                @if($item->type == 'item')
            <tr data-iterate="item">
                <td>{{ ++$count }}</td> <!-- Don't remove this column as it's needed for the row commands -->
                <td>{{ ucfirst($item->item_name) }}</td>
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
                <th>@lang("modules.credit-notes.subTotal"):</th>
                <td>{{ number_format((float)$creditNote->sub_total, 2, '.', '') }}</td>
            </tr>
            @if($discount != 0 && $discount != '')
                <tr data-iterate="tax">
                    <th>@lang("modules.credit-notes.discount"):</th>
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
                <th>@lang("modules.credit-notes.total"):</th>
                <td>{{ number_format((float)$creditNote->total, 2, '.', '') }}</td>
            </tr>
            <tr>
                <th>
                    @lang("modules.credit-notes.creditAmountUsed"):</th>
                <td>
                    {{ number_format((float)$creditNote->creditAmountUsed(), 2, '.', '') }}</td>
            </tr>
            <tr>
                <th>
                    @lang("modules.credit-notes.creditAmountRemaining"):</th>
                <td>
                    {{ number_format((float)$creditNote->creditAmountRemaining(), 2, '.', '') }}</td>
            </tr>
        </table>

        <div class="clearfix"></div>

    </section>

    <div class="clearfix"></div>
    <br>
    <section id="creditNote-info">
        <div>
            <span>@lang('app.menu.issues') @lang('app.date'):</span> <span>{{ $creditNote->issue_date->format($global->date_format) }}</span>
        </div>
        @if($creditNote->status == 'open')
        <div>
            <span>@lang('app.dueDate'):</span> <span>{{ $creditNote->due_date->format($global->date_format) }}</span>
        </div>
        @endif
        @if($invoiceNumber)
            <div>
                <span>@lang('app.invoiceNumber'):</span> <span>{{ $invoiceNumber->invoice_number }}</span>
            </div>
        @endif
        <div>
            <span>@lang('app.status'):</span> <span>{{ ucwords($creditNote->status) }}</span>
        </div>
    </section>

    <section id="terms">

        <div class="notes">
            @if(!is_null($creditNote->note))
                <br> {!! nl2br($creditNote->note) !!}
            @endif
            @if($creditNote->status == 'open')
                <br>{!! nl2br($creditNoteSetting->credit_note_terms) !!}
            @endif
        </div>

    </section>

    <div class="clearfix"></div>
    <br>
    <div class="thank-you">@lang('app.thanks')!</div>

    <div class="clearfix"></div>
</div>
</body>
</html>