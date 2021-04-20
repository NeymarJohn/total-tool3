<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Template CSS -->
    <!-- <link type="text/css" rel="stylesheet" media="all" href="css/main.css"> -->

    <title>@lang('app.invoice') - {{ $invoice->invoice_number }}</title>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <style>
        body {
            margin: 0;
            font-family: Verdana, Arial, Helvetica, sans-serif;
        }

        .bg-grey {
            background-color: #F2F4F7;
        }

        .bg-white {
            background-color: #fff;
        }

        .border-radius-25 {
            border-radius: 0.25rem;
        }

        .p-25 {
            padding: 1.25rem;
        }

        .f-13 {
            font-size: 13px;
        }

        .f-14 {
            font-size: 14px;
        }

        .f-15 {
            font-size: 15px;
        }

        .f-21 {
            font-size: 21px;
        }

        .text-black {
            color: #28313c;
        }

        .text-grey {
            color: #616e80;
        }

        .font-weight-700 {
            font-weight: 700;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        .line-height {
            line-height: 24px;
        }

        .mt-1 {
            margin-top: 1rem;
        }

        .mb-0 {
            margin-bottom: 0px;
        }

        .b-collapse {
            border-collapse: collapse;
        }

        .heading-table-left {
            padding: 6px;
            border: 1px solid #DBDBDB;
            font-weight: bold;
            background-color: #f1f1f3;
            border-right: 0;
        }

        .heading-table-right {
            padding: 6px;
            border: 1px solid #DBDBDB;
            border-left: 0;
        }

        .unpaid {
            color: #000000;
            border: 1px solid #000000;
            position: relative;
            padding: 11px 22px;
            font-size: 15px;
            border-radius: 0.25rem;
            width: 100px;
            text-align: center;
        }

        .main-table-heading {
            border: 1px solid #DBDBDB;
            background-color: #f1f1f3;
            font-weight: 700;
        }

        .main-table-heading td {
            padding: 11px 10px;
            border: 1px solid #DBDBDB;
        }

        .main-table-items td {
            padding: 11px 10px;
            border: 1px solid #e7e9eb;
        }

        .total-box {
            border: 1px solid #e7e9eb;
            padding: 0px;
            border-bottom: 0px;
        }

        .subtotal {
            padding: 11px 10px;
            border: 1px solid #e7e9eb;
            border-top: 0;
            border-left: 0;
        }

        .subtotal-amt {
            padding: 11px 10px;
            border: 1px solid #e7e9eb;
            border-top: 0;
            border-right: 0;
        }

        .total {
            padding: 11px 10px;
            border: 1px solid #e7e9eb;
            border-top: 0;
            font-weight: 700;
            border-left: 0;
        }

        .total-amt {
            padding: 11px 10px;
            border: 1px solid #e7e9eb;
            border-top: 0;
            border-right: 0;
            font-weight: 700;
        }

        .balance {
            font-size: 16px;
            font-weight: bold;
            background-color: #f1f1f3;
        }

        .balance-left {
            padding: 11px 10px;
            border: 1px solid #e7e9eb;
            border-top: 0;
            border-left: 0;
        }

        .balance-right {
            padding: 11px 10px;
            border: 1px solid #e7e9eb;
            border-top: 0;
            border-right: 0;
        }

        .centered {
            margin: 0 auto;
        }

        .rightaligned {
            margin-right: 0;
            margin-left: auto;
        }

        .leftaligned {
            margin-left: 0;
            margin-right: auto;
        }

        .page_break {
            page-break-before: always;
        }
        #logo {
            height: 55px;
        }

    </style>

</head>

<body class="content-wrapper">
@php
    $colspan = ($invoiceSetting->hsn_sac_code_show) ? 3 : 2;
@endphp
    <table class="bg-white" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
        <tbody>
            <!-- Table Row Start -->
            <tr>
                <td><img src="{{ invoice_setting()->logo_url }}" alt="{{ ucwords($global->company_name) }}" id="logo" /></td>
                <td align="right" class="f-21 text-black font-weight-700 text-uppercase">@lang('app.invoice')</td>
            </tr>
            <!-- Table Row End -->
            <!-- Table Row Start -->
            <tr>
                <td>
                    <p class="line-height mt-1 mb-0 f-14 text-black">
                        {{ ucwords($global->company_name) }}<br>
                        @if (!is_null($settings))
                            {!! nl2br($global->address) !!}<br>
                            {{ $global->company_phone }}
                        @endif
                        @if ($invoiceSetting->show_gst == 'yes' && !is_null($invoiceSetting->gst_number))
                            <br>@lang('app.gstIn'): {{ $invoiceSetting->gst_number }}
                        @endif
                    </p>
                </td>
                <td>
                    <table class="text-black mt-1 f-13 b-collapse rightaligned">
                        <tr>
                            <td class="heading-table-left">@lang('modules.invoices.invoiceNumber')</td>
                            <td class="heading-table-right">{{ $invoice->invoice_number }}</td>
                        </tr>
                        @if ($creditNote)
                            <tr>
                                <td class="heading-table-left">@lang('app.credit-note')</td>
                                <td class="heading-table-right">{{ $creditNote->cn_number }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="heading-table-left">@lang('modules.invoices.invoiceDate')</td>
                            <td class="heading-table-right">{{ $invoice->issue_date->format($global->date_format) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="heading-table-left">@lang('app.dueDate')</td>
                            <td class="heading-table-right">{{ $invoice->due_date->format($global->date_format) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- Table Row End -->
            <!-- Table Row Start -->
            <tr>
                <td height="10"></td>
            </tr>
            <!-- Table Row End -->
            <!-- Table Row Start -->
            <tr>
                <td colspan="2">
                    @if (!is_null($invoice->project) && !is_null($invoice->project->client))
                        @php
                            $client = $invoice->project;
                        @endphp
                    @elseif(!is_null($invoice->client_id) && !is_null($invoice->clientdetails))
                        @php
                            $client = $invoice->client;
                        @endphp
                    @endif

                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td class="f-14 text-black">

                                <p class="line-height mb-0">
                                    <span
                                        class="text-grey text-capitalize">@lang("modules.invoices.billedTo")</span><br>
                                    {{ ucwords($client->client_details->company_name) }}<br>
                                    {!! nl2br($client->client_details->address) !!}
                                </p>

                                @if ($invoiceSetting->show_gst == 'yes' && !is_null($client->client_details->gst_number))
                                    <br>@lang('app.gstIn'):
                                    {{ $client->client_details->gst_number }}
                                @endif
                            </td>
                            <td class="f-14 text-black">
                                @if ($invoice->show_shipping_address === 'yes')
                                    <p class="line-height"><span
                                            class="text-grey text-capitalize">@lang('app.shippingAddress')</span><br>
                                        {!! nl2br($client->client_details->address) !!}</p>
                                @endif
                            </td>
                            <td align="right">
                                <div class="text-uppercase bg-white unpaid rightaligned">
                                    {{ ucwords($invoice->status) }}</div>
                            </td>
                        </tr>
                    </table>
                </td>


            </tr>
            <!-- Table Row End -->
            <!-- Table Row Start -->
            <tr>
                <td height="30" colspan="2"></td>
            </tr>
            <!-- Table Row End -->
            <!-- Table Row Start -->
            <tr>
                <td colspan="2">
                    <table width="100%" class="f-14 b-collapse">
                        <!-- Table Row Start -->
                        <tr class="main-table-heading text-grey">
                            <td>@lang('app.description')</td>
                            @if($invoiceSetting->hsn_sac_code_show)
                                <th> @lang('modules.invoices.hsnSacCode')</th>
                            @endif
                            <td align="right">@lang("modules.invoices.qty")</td>
                            <td align="right">@lang("modules.invoices.unitPrice") ({{ $invoice->currency->currency_code }})</td>
                            <td align="right">@lang("modules.invoices.amount") ({{ $invoice->currency->currency_code }})</td>
                        </tr>
                        <!-- Table Row End -->
                        @foreach ($invoice->items as $item)
                            @if ($item->type == 'item')
                                <!-- Table Row Start -->
                                <tr class="main-table-items text-black">
                                    <td>{{ ucfirst($item->item_name) }}</td>
                                    @if($invoiceSetting->hsn_sac_code_show)
                                        <td>{{ $item->hsn_sac_code ?? '--' }}</td>
                                    @endif
                                    <td align="right">{{ $item->quantity }}</td>
                                    <td align="right">{{ number_format((float) $item->unit_price, 2, '.', '') }}</td>
                                    <td align="right">{{ $invoice->currency->currency_symbol }}
                                        {{ number_format((float) $item->amount, 2, '.', '') }}</td>
                                </tr>
                                <!-- Table Row End -->
                                @if (!is_null($item->item_summary))
                                    <!-- Table Row Start -->
                                    <tr class="main-table-items text-black f-13">
                                        <td colspan="4">{{ $item->item_summary }}</td>
                                    </tr>
                                    <!-- Table Row End -->
                                @endif
                            @endif
                        @endforeach
                        <!-- Table Row Start -->
                        <tr>
                            <td colspan="{{ $colspan }}"></td>
                            <td colspan="{{ $colspan }}" class="total-box">
                                <table width="100%" class="b-collapse">
                                    <!-- Table Row Start -->
                                    <tr align="right" class="text-grey">
                                        <td width="50%" class="subtotal">@lang("modules.invoices.subTotal")</td>
                                        <td class="subtotal-amt">
                                            {{ number_format((float) $invoice->sub_total, 2, '.', '') }}</td>
                                    </tr>
                                    <!-- Table Row End -->
                                    @if ($discount != 0 && $discount != '')
                                        <!-- Table Row Start -->
                                        <tr align="right" class="text-grey">
                                            <td width="50%" class="subtotal">@lang("modules.invoices.discount")</td>
                                            <td class="subtotal-amt">{{ number_format((float) $discount, 2, '.', '') }}</td>
                                        </tr>
                                        <!-- Table Row End -->
                                    @endif
                                    @foreach ($taxes as $key => $tax)
                                        <!-- Table Row Start -->
                                        <tr align="right" class="text-grey">
                                            <td width="50%" class="subtotal">{{ strtoupper($key) }}</td>
                                            <td class="subtotal-amt">{{ number_format((float) $tax, 2, '.', '') }}</td>
                                        </tr>
                                        <!-- Table Row End -->
                                    @endforeach
                                    <!-- Table Row Start -->
                                    <tr align="right" class="text-grey">
                                        <td width="50%" class="total">@lang("modules.invoices.total")</td>
                                        <td class="total-amt f-15">{{ number_format((float) $invoice->total, 2, '.', '') }}</td>
                                    </tr>
                                    <!-- Table Row End -->
                                    <!-- Table Row Start -->
                                    <tr align="right" class="balance text-black">
                                        <td width="50%" class="balance-left">@lang("modules.invoices.total")
                                            @lang("modules.invoices.due")</td>
                                        <td class="balance-right">{{ number_format((float) $invoice->amountDue(), 2, '.', '') }}
                                            {{ $invoice->currency->currency_code }}</td>
                                    </tr>
                                    <!-- Table Row End -->
                                </table>
                            </td>
                        </tr>
                        <!-- Table Row End -->
                    </table>
                </td>
            </tr>
            <!-- Table Row End -->
            <!-- Table Row Start -->
            <tr>
                <td height="30" colspan="2"></td>
            </tr>
            <!-- Table Row End -->
            <!-- Table Row Start -->
            <tr>
                <td width="50%" class="f-14">@lang('app.note')</td>
                <td width="50%" class="f-14">@lang('modules.invoiceSettings.invoiceTerms')</td>
            </tr>
            <!-- Table Row End -->
            <!-- Table Row Start -->
            <tr class="text-grey">
                <td width="50%" class="f-14 line-height">{!! nl2br($invoice->note) !!}</td>
                <td width="50%" class="f-14 line-height">{!! nl2br($invoiceSetting->invoice_terms) !!}</td>
            </tr>
            <!-- Table Row End -->
        </tbody>
    </table>

    @if (!is_null($payments))
        <div class="page_break"></div>
        <h3>@lang('app.menu.payments') ({{ $invoice->invoice_number }})</h3>
        <table class="f-14 b-collapse" width="100%">
            <tr class="main-table-heading text-grey">
                <td class="text-center">#</td>
                <td class="text-center">@lang("modules.invoices.price")</td>
                <td class="text-center">@lang("modules.invoices.paymentMethod")</td>
                <td class="text-center">@lang("modules.invoices.paidOn")</td>
            </tr>

            @forelse($payments as $key => $payment)
                <tr class="main-table-items">
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center">{{ number_format((float) $payment->amount, 2, '.', '') }} {{ $invoice->currency->currency_code }}</td>
                    <td class="text-center">
                        @php($method = !is_null($payment->offline_method_id) ? $payment->offlineMethod->name :
                            $payment->gateway)
                            {{ $method }}
                        </td>
                        <td class="text-center"> {{ $payment->paid_on->format($global->date_format) }} </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4">@lang('messages.noRecordFound') </td>
                        </tr>
            @endforelse
            </table>
            @endif
        </body>

        </html>
