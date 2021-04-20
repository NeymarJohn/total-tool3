<style>
    @media print {
        table {
            -webkit-print-color-adjust: exact;
        }
    }
</style>
<div style="margin: 0 auto; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;width: 100%;min-width: 610px">
    <table style="border-collapse: collapse; width: 100%; border-bottom: 1px solid #e5e5e5; font-size:10pt; color: #000; margin-bottom: 10px;">
        <tr>
            <td style="vertical-align: top; width: 33.33%; padding-bottom: 30px">
                <table style="border-collapse: collapse; width: 100%; font-size:10pt; color: #000;">
                    <tr>
                        <td style="font-weight: bold; padding: 10px 15px 5px;">
                            To: {{ $owner->company_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 15px; line-height: 20px;">
                            {{$owner->address}}
                            <a style="color: #000; text-decoration: none" href="https://{{ $owner->website }}"
                               target="_blank">https://{{ $owner->website }}</a><br>
                            <a style="color: #000; text-decoration: none" href="mailto:{{$owner->company_email}}">{{$owner->company_email}}</a>
                        </td>
                    </tr>
                    ​
                </table>
            </td>
            <td style="width: 33.33%; text-align: center; padding: 10px 15px 30px; vertical-align: top">
                @if(!is_null($logo))
                    <img src="{{ asset_url('app-logo/'.$logo) }}"
                         alt=""/>
                @endif
            </td>
            <td style="vertical-align: top; width: 33.33%; padding-bottom: 30px">
                <table style="border-collapse: collapse;width: 100%; font-size:10pt; color: #000;">
                    <tr>
                        <td style="font-weight: bold; padding: 10px 15px 5px">{{ $vendor }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 15px; line-height: 20px;">
                            {!! $global->address !!}
                            <br>
                            <a style="color: #000; text-decoration: none" href="{{ $global->website }}"
                               target="_blank">{{ $global->website }}</a><br>
                            <a style="color: #000; text-decoration: none" href="mailto:{{ $global->company_email }}">{{ $global->company_email }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="border-collapse: collapse; width: 100%; font-size:10pt; color: #000; margin-bottom: 20px">
        <tr>
            <td style="padding: 10px 0;"><b>Date: </b>{{ $invoice->date()->toFormattedDateString()}}</td>
        </tr>
    </table>
    <table style="border-collapse: collapse; width: 100%; text-align: center; color: #000; margin-bottom: 30px">
        ​
        ​
        <tr>
            <td style="font-weight: bold; font-size:19pt; padding: 0 15px;">@lang('app.invoice') /
                Receipt {{ 6000 + array_sum(str_split($invoice->date())) }}</td>
        </tr>
        ​
    </table>
    <table style="border-collapse: collapse; width: 100%; font-size:11pt; font-weight: bold; color: #000;">
        <tr>
            <td style="padding: 10px 0;">@lang('app.invoice') / Receipt</td>
        </tr>
    </table>
    <table style="border-collapse: collapse; width: 100%; font-size:11pt; color: #000; margin-bottom: 25px">
        <thead>
        <tr>
            <th style="background-color: #f8dfd7; font-weight: bold; text-align: left; padding: 10px 15px;">
                Description
            </th>
            <th style="background-color: #f8dfd7; font-weight: bold; padding: 10px 15px;">
                Date
            </th>
            <th style="background-color: #f8dfd7; font-weight: bold; padding: 10px 15px; text-align: right; width: 100px;">
                Amount
            </th>
        </tr>
        </thead>
        <tbody>
        ​
        <!-- Display The Invoice Items -->
        @foreach ($invoice->invoiceItems() as $item)
            <tr>
                <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px;">{{ $item->description }}</td>
                <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px;"></td>
                <td class="amount" style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px; text-align: right;">{{ $item->total() }}</td>
            </tr>
        @endforeach
        ​
        <!-- Display The Subscriptions -->
        @foreach ($invoice->subscriptions() as $subscription)
            <tr>
                <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px;">{{ $subscription->plan->nickname }}</td>
                <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px;">
                    {{ $subscription->startDateAsCarbon()->formatLocalized('%B %e, %Y') }} -
                    {{ $subscription->endDateAsCarbon()->formatLocalized('%B %e, %Y') }}
                </td>
                <td class="amount" style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px; text-align: right;">{{ $subscription->total() }}</td>
            </tr>
        @endforeach
        ​
        <!-- Display The Discount -->
        @if ($invoice->hasDiscount())
            <tr>
                @if ($invoice->discountIsPercentage())
                    <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px;">{{ $invoice->coupon() }} ({{ $invoice->percentOff() }}% Off)</td>
                @else
                    <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px;">{{ $invoice->coupon() }} ({{ $invoice->amountOff() }} Off)</td>
                @endif
                <td>&nbsp;</td>
                <td class="amount" style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px; text-align: right;">-{{ $invoice->discount() }}</td>
            </tr>
        @endif
        ​
        <!-- Display The Tax Amount -->
        @if ($invoice->tax_percent)
            <tr >
                <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px;">Tax ({{ $invoice->tax_percent }}%)</td>
                <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px; text-align: right;">&nbsp;</td>
                <td class="amount" style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px; text-align: right;">{{ Laravel\Cashier\Cashier::formatAmount($invoice->tax) }}</td>
            </tr>
        @endif
        ​
        <!-- Display The Final Total -->
        <tr>
            <td style="padding: 10px 15px;">&nbsp;</td>
            <td style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px; text-align: right;" ><strong>Total</strong></td>
            <td class="amount" style="border-bottom: 1px solid #e5e5e5; padding: 10px 15px; text-align: right;"><strong>{{ $invoice->total() }}</strong></td>
        </tr>

        ​
        </tbody>
    </table>

</div>
