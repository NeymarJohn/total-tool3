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
        /*! Invoice Templates @author: Invoicebus @email: info@invoicebus.com @web: https://invoicebus.com @version: 1.0.0 @updated: 2015-03-27 14:03:24 @license: Invoicebus */
        /* Reset styles */
        /*@import url("https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700&subset=cyrillic,cyrillic-ext,latin,greek-ext,greek,latin-ext,vietnamese");*/
        /*@import url("https://fonts.googleapis.com/css?family=Sanchez&subset=latin,latin-ext");*/
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
            font: inherit;
            font-size: 100%;
            vertical-align: baseline;
            font-family: 'DejaVu Sans', sans-serif;
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

        .x-hidden {
            display: none !important;
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
            position: relative;
        }

        .left-stripes {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 100px;
            background: url("{{ asset("img/stripe-bg.jpg") }}") repeat;
        }
        .left-stripes .circle {
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            background: #415472;
            width: 30px;
            height: 30px;
            position: absolute;
            left: 33%;
        }
        .left-stripes .circle.c-upper {
            top: 440px;
        }
        .left-stripes .circle.c-lower {
            top: 690px;
        }

        .right-creditNote {
            padding: 40px 30px 40px 130px;
            min-height: 1078px;
        }

        #memo .company-info {
            float: left;
        }
        #memo .company-info div {
            font-size: 28px;
            text-transform: uppercase;
            min-width: 20px;
            line-height: 1em;
        }
        #memo .company-info span {
            font-size: 12px;
            color: #858585;
            display: inline-block;
            min-width: 20px;
        }
        #memo .logo {
            float: right;
            margin-left: 15px;
        }
        #memo .logo img {
            max-width: 150px;
        }
        #memo:after {
            content: '';
            display: block;
            clear: both;
        }

        #creditNote-title-number {
            margin: 50px 0 20px 0;
            display: inline-block;
            float: left;
        }
        #creditNote-title-number .title-top {
            font-size: 15px;
            margin-bottom: 5px;
        }
        #creditNote-title-number .title-top span {
            display: inline-block;
            min-width: 20px;
        }
        #creditNote-title-number .title-top #number {
            text-align: right;
            float: right;
            color: #858585;
        }
        #creditNote-title-number .title-top:after {
            content: '';
            display: block;
            clear: both;
        }
        #creditNote-title-number #title {
            display: inline-block;
            background: #415472;
            color: white;
            font-size: 50px !important;
            padding: 20px 7px 5px 7px;
            font-family: Sanchez, Serif;
            line-height: 1em;
        }

        #client-info {
            /*float: right;*/
            text-align: right;
            /*margin-top: 50px;*/
            min-width: 220px;
        }
        .client-name {
            font-weight: bold !important;
            font-size: 15px !important;
            text-transform: uppercase;
            margin: 7px 0;
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
            color: #858585;
            font-size: 15px;
        }

        table {
            table-layout: fixed;
        }
        table th, table td {
            vertical-align: top;
            word-break: keep-all;
            word-wrap: break-word;
        }

        #creditNote-info {
            float: left;
            margin-top: 10px;
        }
        #creditNote-info div {
            margin-bottom: 3px;
        }
        #creditNote-info div span {
            display: inline-block;
            min-width: 20px;
            min-height: 18px;
        }
        #creditNote-info div span:first-child {
            font-weight: bold;
            text-transform: uppercase;
            margin-right: 10px;
        }
        #creditNote-info:after {
            content: '';
            display: block;
            clear: both;
        }

        .currency {
            margin-top: 20px;
            text-align: right;
            color: #858585;
            font-style: italic;
            font-size: 12px;
        }
        .currency span {
            display: inline-block;
            min-width: 20px;
        }

        #items {
            margin-top: 10px;
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
            font-family: Sanchez, Serif;
            font-size: 12px;
            text-transform: uppercase;
            padding: 5px 3px;
            text-align: center;
            background: #b0b4b3;
            color: white;
        }
        #items table th:nth-child(2) {
            width: 30%;
            text-align: left;
        }
        #items table th:last-child {
            /*text-align: right;*/
        }
        #items table td {
            padding: 10px 3px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        #items table td:first-child {
            text-align: left;
        }
        #items table td:nth-child(2) {
            text-align: left;
        }

        #sums {
            margin: 25px 30px 0 0;
            width: 100%;
        }
        #sums table {
            width: 70%;
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

        #sums table tr.amount-total td {
            background: #415472 !important;
            color: white;
            font-family: Sanchez, Serif;
            font-size: 35px !important;
            line-height: 1em;
            padding: 7px !important;
        }
        #sums table tr.due-amount th, #sums table tr.due-amount td {
            font-weight: bold;
        }

        #sums:after {
            content: '';
            display: block;
            clear: both;
        }

        #terms {
            float: left;
            margin-top: 60px !important;
        }
        #terms > span {
            font-weight: bold;
            display: inline-block;
            min-width: 20px;
            text-transform: uppercase;
        }
        #terms > div {
            min-height: 50px;
            min-width: 50px;
        }

        #terms .notes {
            min-height: 30px;
            min-width: 50px;
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
    <div class="left-stripes">
        <div class="circle c-upper"></div>
        <div class="circle c-lower"></div>
    </div>

    <div class="right-creditNote">
        <section id="memo">
            <div class="company-info">
                <div>
                    {{ ucwords($global->company_name) }}
                </div>
                <br>
                <span>{!! nl2br($global->address) !!}</span>
                <br>
                <span>{{ $global->company_phone }}</span>

            </div>

            <div class="logo">
                @if(is_null($global->logo))
                    <img src="{{ public_path().'/img/worksuite-logo.png' }}" />
                @else
                    <img src="{{ public_path().'/user-uploads/app-logo/'.$global->logo }}" alt="home" class="dark-logo"/>
                @endif
            </div>
        </section>

        <section id="creditNote-title-number">

            <div class="title-top">
                <span class="x-hidden">@lang('app.menu.issues') @lang('app.date'):</span>
                <span>{{ $creditNote->issue_date->format($global->date_format) }}</span>
            </div>

            <div id="title">{{ $creditNote->cn_number }}</div>

        </section>
        @if(!is_null($creditNote->project->client))
            <section id="client-info">
                <span>@lang('modules.credit-notes.billedTo'):</span>
                <div class="client-name">
                    <strong>{{ ucwords($creditNote->project->client->name) }}</strong>
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
            </section>
        @endif

        <div class="clearfix"></div>


        <section id="creditNote-info">
            @if($creditNote->status == 'open')
                <div>
                    <span>Due Date:</span> <span>{{ $creditNote->due_date->format($global->date_format) }}</span>
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

        <div class="clearfix"></div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>

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
                    <th>@lang("modules.credit-note.discount"):</th>
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
                    <!-- {amount_total_label} -->
                    <td colspan="2">{{ number_format((float)$creditNote->total, 2, '.', '') }}</td>
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

        </section>

        <div class="clearfix"></div>
        <p>&nbsp;</p>

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

    </div>
</div>

</body>
</html>
