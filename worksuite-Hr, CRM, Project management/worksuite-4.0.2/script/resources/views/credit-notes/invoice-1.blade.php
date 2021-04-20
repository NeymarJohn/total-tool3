<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>@lang('app.credit-note')</title>
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
            font-family: Verdana, Arial, Helvetica, sans-serif;
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
            font-size: 1.2em;
            font-weight: normal;
            margin: 0;
        }

        #creditNote {

        }

        #creditNote h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #creditNote .date {
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

        table td.desc h3, table td.qty h3 {
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #57B223;
            width: 10%;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }


        table .total {
            background: #57B223;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total
        {
            font-size: 1.2em;
            text-align: center;
        }

        table td.unit{
            width: 35%;
        }

        table td.desc{
            width: 45%;
        }

        table td.qty{
            width: 5%;
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

        table td div#creditNoted_to {
            text-align: left;
        }

        #notes{
            color: #767676;
            font-size: 11px;
        }

    </style>
</head>
<body>
<header class="clearfix">

    <table cellpadding="0" cellspacing="0" class="billing">
        <tr>
            <td>
                <div id="creditNoted_to">
                    @if(!is_null($creditNote->project_id) && !is_null($creditNote->project->clientdetails))
                    <small>@lang("modules.credit-notes.billedTo"):</small>
                    <h3 class="name">{{ ucwords($creditNote->project->clientdetails->company_name) }}</h3>
                    <div>{!! nl2br($creditNote->project->clientdetails->address) !!}</div>
                        @if($creditNoteSetting->show_gst == 'yes' && !is_null($creditNote->project->clientdetails->gst_number))
                            <div> @lang('app.gstIn'): {{ $creditNote->project->clientdetails->gst_number }} </div>
                        @endif
                    @elseif(!is_null($creditNote->client_id))
                    <small>@lang("modules.credit-notes.billedTo"):</small>
                    <h3 class="name">{{ ucwords($creditNote->clientdetails->company_name) }}</h3>
                    <div>{!! nl2br($creditNote->clientdetails->address) !!}</div>
                        @if($creditNoteSetting->show_gst == 'yes' && !is_null($creditNote->clientdetails->gst_number))
                            <div> @lang('app.gstIn'): {{ $creditNote->clientdetails->gst_number }} </div>
                        @endif
                    @endif
                </div>
            </td>
            <td>
                <div id="company">
                    <small>@lang("modules.credit-notes.generatedBy"):</small>
                    <h3 class="name">{{ ucwords($global->company_name) }}</h3>
                    @if(!is_null($settings))
                        <div>{!! nl2br($global->address) !!}</div>
                        <div>{{ $global->company_phone }}</div>
                    @endif
                    @if($creditNoteSetting->show_gst == 'yes' && !is_null($creditNoteSetting->gst_number))
                        <div>@lang('app.gstIn'): {{ $creditNoteSetting->gst_number }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>
</header>
<main>
    <div id="details" class="clearfix">

        <div id="creditNote">
            <h1>{{ $creditNote->cn_number }}</h1>
            <div class="date">@lang('app.menu.issues') Date: {{ $creditNote->issue_date->format($global->date_format) }}</div>
            @if($creditNote->status == 'open')
                <div class="date">@lang('app.dueDate'): {{ $creditNote->due_date->format($global->date_format) }}</div>
            @endif
            @if($invoiceNumber)
                <div class="">@lang('app.invoiceNumber'): {{ $invoiceNumber->invoice_number }}</div>
            @endif
            <div class="">@lang('app.status'): {{ ucwords($creditNote->status) }}</div>
        </div>

    </div>
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th class="no">#</th>
            <th class="desc">@lang("modules.credit-notes.item")</th>
            <th class="qty">@lang("modules.credit-notes.qty")</th>
            <th class="qty">@lang("modules.credit-notes.unitPrice") ({!! htmlentities($creditNote->currency->currency_code)  !!})</th>
            <th class="unit">@lang("modules.credit-notes.price") ({!! htmlentities($creditNote->currency->currency_code)  !!})</th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        @foreach($creditNote->items as $item)
            @if($item->type == 'item')
            <tr style="page-break-inside: avoid;">
                <td class="no">{{ ++$count }}</td>
                <td class="desc"><h3>{{ ucfirst($item->item_name) }}</h3></td>
                <td class="qty"><h3>{{ $item->quantity }}</h3></td>
                <td class="qty"><h3>{{ number_format((float)$item->unit_price, 2, '.', '') }}</h3></td>
                <td class="unit">{{ number_format((float)$item->amount, 2, '.', '') }}</td>
            </tr>
            @endif
        @endforeach
        <tr style="page-break-inside: avoid;" class="subtotal">
            <td class="no">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="desc">@lang("modules.credit-notes.subTotal")</td>
            <td class="unit">{{ number_format((float)$creditNote->sub_total, 2, '.', '') }}</td>
        </tr>
        @if($discount != 0 && $discount != '')
        <tr style="page-break-inside: avoid;" class="discount">
            <td class="no">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="desc">@lang("modules.credit-notes.discount")</td>
            <td class="unit">-{{ number_format((float)$discount, 2, '.', '') }}</td>
        </tr>
        @endif
        @foreach($taxes as $key=>$tax)
            <tr style="page-break-inside: avoid;" class="tax">
                <td class="no">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                <td class="desc">{{ strtoupper($key) }}</td>
                <td class="unit">{{ number_format((float)$tax, 2, '.', '') }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr dontbreak="true">
            <td colspan="4">@lang("modules.credit-notes.total")</td>
            <td style="text-align: center">{{ number_format((float)$creditNote->total, 2, '.', '') }}</td>
        </tr>
        <tr dontbreak="true">
            <td colspan="4">@lang('modules.credit-notes.creditAmountUsed')</td>
            <td style="text-align: center">{{ number_format((float)$creditNote->creditAmountUsed(), 2, '.', '') }}</td>
        </tr>
        <tr dontbreak="true">
            <td colspan="4">@lang('modules.credit-notes.creditAmountRemaining')</td>
            <td style="text-align: center">{{ number_format((float)$creditNote->creditAmountRemaining(), 2, '.', '') }}</td>
        </tr>
        </tfoot>
    </table>
    <p>&nbsp;</p>
    <hr>
    <p id="notes">
        @if(!is_null($creditNote->note))
            {!! nl2br($creditNote->note) !!}<br>
        @endif
        @if($creditNote->status == 'open')
        {!! nl2br($creditNoteSetting->credit_note_terms) !!}
        @endif

    </p>

</main>
</body>
</html>