<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>@lang('app.estimate') #{{ (is_null($estimate->estimate_number)) ? $estimate->id : $estimate->estimate_number }}</title>
    <style>

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 100%;
            height: auto;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-size: 14px;
            font-family: 'DejaVu Sans', sans-serif;
        }

        h2 {
            font-weight:normal;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 11px;
        }

        #logo img {
            height: 55px;
            margin-bottom: 15px;
        }

        #company {

        }

        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {

        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 5px 10px 7px 10px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3 {
            color: #767676;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #767676;
            width: 5%;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }


        table .total {
            background: #767676;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total
        {
            font-size: 1em;
            text-align: center;
        }

        table td.unit{
            width: 40%;
        }

        table td.desc{
            width: 20%;
        }

        table td.qty{
            width: 8%;
        }

        .status {
            margin-top: 15px;
            padding: 1px 8px 5px;
            font-size: 1.3em;
            width: 80px;
            color: #fff;
            float: right;
            text-align: center;
            display: inline-block;
        }

        .status.unpaid {
            background-color: #E7505A;
        }
        .status.paid {
            background-color: #26C281;
        }
        .status.cancelled {
            background-color: #95A5A6;
        }
        .status.error {
            background-color: #F4D03F;
        }

        table tr.tax .desc {
            text-align: right;
            color: #1BA39C;
        }
        table tr.discount .desc {
            text-align: right;
            color: #E43A45;
        }
        table tr.subtotal .desc {
            text-align: right;
            color: #1d0707;
        }
        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 10px 10px 20px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-bottom: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }

        table.billing td {
            background-color: #fff;
        }

        table td div#invoiced_to {
            text-align: left;
        }

        #notes{
            color: #767676;
            font-size: 11px;
        }

        .item-summary{
            font-size: 12px
        }

        .logo {
            text-align: right;
        }
        .logo img {
            max-width: 150px;
        }


    </style>
</head>
<body>
<header class="clearfix">

    <table cellpadding="0" cellspacing="0" class="billing">
        <tr>
            <td>
                <div id="invoiced_to">
                    <small>@lang("app.client"):</small>
                    <h2 class="name">
                        @if(isset($estimate->client->client_detail))
                            {{ ucfirst($estimate->client->client_detail->name) }}
                        @else
                            {{ ucfirst($estimate->client->name) }}
                        @endif
                    </h2>
                    <div>
                        @if(isset($estimate->client->client_detail))
                            {!! nl2br($estimate->client->client_detail->address) !!}
                        @else
                            {!! nl2br($estimate->client->address) !!}
                        @endif
                    </div>
                </div>
            </td>
            <td>
                <div id="company">
                    <div class="logo">
                        <img src="{{ invoice_setting()->logo_url }}" alt="home" class="dark-logo" />
                    </div>
                    <small>@lang("modules.invoices.generatedBy"):</small>
                    <h2 class="name">{{ ucwords($global->company_name) }}</h2>
                    @if(!is_null($settings))
                        <div>{!! nl2br($global->address) !!}</div>
                        <div>P: {{ $global->company_phone }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>
</header>
<main>
    <div id="details" class="clearfix">

        <div id="invoice">
            <h1>@lang('app.estimate') {{ $estimate->estimate_number }}</h1>
            <div class="date">@lang("modules.estimates.validTill"): {{ $estimate->valid_till->format($global->date_format) }}</div>
        </div>

    </div>
    @php
        $colspan = ($invoiceSetting->hsn_sac_code_show) ? 5 : 4;
    @endphp
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th class="no">#</th>
            <th class="desc">@lang("modules.invoices.item")</th>
            @if($invoiceSetting->hsn_sac_code_show)
                <th class="desc"> @lang('modules.invoices.hsnSacCode')</th>
            @endif
            <th class="qty">@lang("modules.invoices.qty")</th>
            <th class="qty">@lang("modules.invoices.unitPrice") ({!! htmlentities($estimate->currency->currency_code)  !!})</th>
            <th class="unit">@lang("modules.invoices.price") ({!! htmlentities($estimate->currency->currency_code)  !!})</th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        @foreach($estimate->items as $item)
            @if($item->type == 'item')
            <tr style="page-break-inside: avoid;">
                <td class="no">{{ ++$count }}</td>
                <td class="desc"><h3>{{ ucfirst($item->item_name) }}</h3></td>
                @if($invoiceSetting->hsn_sac_code_show)
                    <td class="qty">{{ $item->hsn_sac_code ?? '--' }}</td>
                @endif
                <td class="qty"><h3>{{ $item->quantity }}</h3></td>
                <td class="qty"><h3>{{ $item->unit_price }}</h3></td>
                <td class="unit">{{ $item->amount }}</td>
            </tr>
            @endif
        @endforeach
        <tr style="page-break-inside: avoid;" class="subtotal">
            <td class="no">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            @if($invoiceSetting->hsn_sac_code_show)
                <td class="qty"> </td>
            @endif
            <td class="qty">&nbsp;</td>
            <td class="desc">@lang("modules.invoices.subTotal")</td>
            <td class="unit">{{ $estimate->sub_total }}</td>
        </tr>
        
        @if($discount != 0 && $discount != '')
        <tr style="page-break-inside: avoid;" class="discount">
            <td class="no">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            @if($invoiceSetting->hsn_sac_code_show)
                <td class="qty"> </td>
            @endif
            <td class="qty">&nbsp;</td>
            <td class="desc">@lang("modules.invoices.discount")</td>
            <td class="unit">-{{ $discount }}</td>
        </tr>
        @endif
        
        @foreach($taxes as $key=>$tax)
            <tr style="page-break-inside: avoid;" class="tax">
                <td class="no">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                @if($invoiceSetting->hsn_sac_code_show)
                    <td class="qty"> </td>
                @endif
                <td class="qty">&nbsp;</td>
                <td class="desc">{{ strtoupper($key) }}</td>
                <td class="unit">{{ number_format((float)$tax, 2, '.', '') }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr dontbreak="true">
            <td colspan="{{ $colspan }}">@lang("modules.invoices.total")</td>
            <td>{{ $estimate->total }}</td>
        </tr>
        </tfoot>
    </table>
    <p>&nbsp;</p>
    <hr>
    <p id="notes">
        {!! nl2br(ucfirst($estimate->note)) !!}
    </p>

</main>
</body>
</html>