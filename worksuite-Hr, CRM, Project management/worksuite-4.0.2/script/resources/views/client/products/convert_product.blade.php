@extends('layouts.client-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.all-invoices.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    .dropdown-content {
        width: 250px;
        max-height: 250px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('app.product') @lang('app.purchase')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="control-label">@lang('app.invoice') #</label>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-icon">
                                                    <input type="text" readonly class="form-control"
                                                           name="invoice_number" id="invoice_number"
                                                           value="@if(is_null($lastInvoice)) {{ $invoiceSetting->invoice_prefix.'#1' }} @else {{ ($invoiceSetting->invoice_prefix.'#'.($lastInvoice->id+1)) }} @endif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="control-label">@lang('app.purchaseDate')</label>


                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-icon">
                                                    <input type="text" disabled class="form-control" name="issue_date"
                                                           id="invoice_date"
                                                           value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.invoices.currency')</label>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-icon">
                                                    <input type="text" disabled class="form-control"
                                                           value="{{ $global->currency->currency_symbol.' ('.$global->currency->currency_code.')' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <hr>
                            @if(sizeof($products) == 0)
                                <div class="text-center" id="no-item-info">
                                    <div class="empty-space" style="height: 200px;">
                                        <div class="empty-space-inner">
                                            <a href="{{ route('client.products.index') }}">
                                                <div class="icon" style="font-size:20px"><i
                                                            class="fa fa-plus"></i>
                                                </div>
                                            </a>
                                            <div class="title m-b-15">@lang("messages.addItem")
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row hide item-section">

                                <div class="col-xs-12  visible-md visible-lg">

                                    <div class="@if($invoiceSetting->hsn_sac_code_show) col-md-3 @else col-md-4 @endif font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.item')
                                    </div>
                                    @if($invoiceSetting->hsn_sac_code_show)
                                        <div class="col-md-1 font-bold" style="padding: 8px 15px">
                                            @lang('modules.invoices.hsnSacCode')
                                        </div>
                                    @endif

                                    <div class="col-md-1 font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.qty')
                                    </div>

                                    <div class="col-md-2 font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.unitPrice')
                                    </div>

                                    <div class="col-md-2 font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.tax')
                                    </div>

                                    <div class="col-md-2 text-center font-bold" style="padding: 8px 15px">
                                        @lang('modules.invoices.amount')
                                    </div>

                                    <div class="col-md-1" style="padding: 8px 15px">
                                        &nbsp;
                                    </div>

                                </div>

                                <div id="sortable">
                                    @foreach($products as $key => $items)
                                        <div class="row col-xs-12 item-row margin-top-5" id="itemRow{{$items->id}}">
                                            <div class="col-md-4" style=" padding: 10px 20px;">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                                                    <div class="input-group">
                                                        <input type="hidden" class="form-control item_name" name="item_name[]"
                                                               value="{{ $items->name }}" >

                                                    </div>
                                                    <span class="font-semi-bold">{{ $items->name }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="item_summary[]" class="form-control" placeholder="@lang('app.description')" value="{{ $items->description }}">
                                                    <span class="text-muted">{{ $items->description }}</span>
                                                </div>
                                            </div>
                                            @if($invoiceSetting->hsn_sac_code_show)
                                                <div class="col-md-1">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.hsnSacCode')</label>
                                                    <input type="text" class="form-control" id="hsn_sac_code{{ $items->id }}" value="{{ $hsnSacArray[$items->id] }}" data-item-id="{{ $items->id }}"  name="hsn_sac_code[]" >

                                                </div>
                                            @endif
                                            <div class="col-md-1"  style=" padding: 10px 20px;">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
                                                    <input type="number" min="1" class="form-control quantity" id="quantity{{ $items->id }}"  data-item-id="{{ $items->id }}"  value="{{ $quantityArray[$items->id] }}" name="quantity[]" >
                                                </div>
                                            </div>

                                            <div class="col-md-2"  style=" padding: 10px 20px;">
                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                                                    <input type="hidden"  class="form-control cost_per_item" id="cost_per_item{{ $items->id }}" name="cost_per_item[]" data-item-id="{{ $items->id }}" value="{{ $items->price }}">
                                                    {{ $items->price }}
                                                </div>
                                            </div>

                                            <div class="col-md-2">

                                                <div class="form-group">
                                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.type')</label>
                                                    @php
                                                        $flag = 0;
                                                    @endphp
                                                    @foreach($taxes as $tax)
                                                        @if (isset($items->taxes) && array_search($tax->id, json_decode($items->taxes)) !== false)
                                                            <input type="hidden" name="" id="" class="type" data-rate="{{ $tax->rate_percent }}" data-tax-name="{{ $tax->tax_name }}: {{ $tax->rate_percent }}%" value="{{ $tax->id }}">
                                                            <span class="clearfix">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</span>
                                                            @php
                                                                $flag = 1;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @if ($flag == 0)
                                                        NA
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-2 border-dark  text-center">
                                                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>

                                                <p class="form-control-static"><span class="amount-html" data-item-id="{{ $items->id }}">0</span></p>
                                                <input type="hidden" class="amount" name="amount[]" data-item-id="{{ $items->id }}">
                                            </div>

                                            <div class="col-md-1 text-right visible-md visible-lg">
                                                <button type="button"  data-item-id="{{ $items->id }}"   class="btn remove-item btn-circle btn-danger"><i class="fa fa-remove"></i></button>
                                            </div>

                                            <div class="col-xs-12 text-center hidden-md hidden-lg">
                                                <div class="row">
                                                    <button type="button"  data-item-id="{{ $items->id }}"  class="btn btn-circle remove-item btn-danger"><i class="fa fa-remove"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-xs-12 ">
                                    <div class="row">
                                        <div class="col-md-offset-9 col-xs-6 col-md-1 text-right p-t-10" >@lang('modules.invoices.subTotal')</div>

                                        <p class="form-control-static col-xs-6 col-md-2" >
                                            <span class="sub-total"></span>
                                        </p>


                                        <input type="hidden" class="sub-total-field" name="sub_total" value="">
                                    </div>

                                    <div class="row m-t-5" id="invoice-taxes">
                                        <div class="col-md-offset-9 col-md-1 text-right p-t-10">
                                            @lang('modules.invoices.tax')
                                        </div>

                                        <p class="form-control-static col-xs-6 col-md-2" >
                                            <span class="tax-percent"></span>
                                        </p>
                                    </div>

                                    <div class="row m-t-5 font-bold">
                                        <div class="col-md-offset-9 col-md-1 col-xs-6 text-right p-t-10" >@lang('modules.invoices.total')</div>

                                        <p class="form-control-static col-xs-6 col-md-2" >
                                            <span class="total"></span>
                                        </p>


                                        <input type="hidden" class="total-field" name="total" value="0">
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="form-group" >
                                    <label class="control-label">@lang('app.note')</label>
                                    <textarea class="form-control" name="note" id="note" rows="5"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions  item-section hide" style="margin-top: 70px">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" id="save-form" class="btn btn-success"><i
                                                class="fa fa-check"></i> @lang('app.save')
                                    </button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="taxModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script>
    $(function () {
        $( "#sortable" ).sortable();
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

            @forelse($products as $product)
    var prodID = {{ $product->id}};
    var quantity = $('#quantity'+prodID).closest('.item-row').find('.quantity').val();

    var perItemCost = $('#quantity'+prodID).closest('.item-row').find('.cost_per_item').val();

    var amount = (quantity*perItemCost);

    $('#quantity'+prodID).closest('.item-row').find('.amount').val(amount);
    $('#quantity'+prodID).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

    calculateTotal();
    @empty
    @endforelse

     @if($products)
$('.item-section').removeClass('hide');
    calculateTotal();
    @endif


$('#save-form').click(function(){
        calculateTotal();

        var discount = $('.discount-amount').html();
        var total = $('.total-field').val();

        if(parseFloat(discount) > parseFloat(total)){
            $.toast({
                heading: 'Error',
                text: 'Discount cannot be more than total amount.',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3500
            });
            return false;
        }

        $.easyAjax({
            url:'{{route('client.products.store')}}',
            container:'#storePayments',
            type: "POST",
            redirect: true,
            data:$('#storePayments').serialize()
        })
    });

    $('#storePayments').on('click','.remove-item', function () {
        var id = $(this).data('item-id');
        var url = "{{ route('client.products.remove-cart-item',':id') }}";
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            container:'#storePayments',
            type: "GET",
            success: function (response) {
                console.log(response);
                $('#itemRow'+id).remove();
                calculateTotal();
//
            }
        })


    });

    $('#storePayments').on('keyup change','.quantity,.cost_per_item,.item_name', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(amount);
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();


    });

    $('#storePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(amount);
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();


    });

    function calculateTotal()
    {
//            calculate subtotal
        var subtotal = 0;
        var discount = 0;
        var tax = '';
        var taxList = new Object();
        var taxTotal = 0;
        $(".quantity").each(function (index, element) {
            var itemTax = [];
            var itemTaxName = [];
            $(this).closest('.item-row').find('input.type').each(function (index) {
                itemTax[index] = $(this).data('rate');
                itemTaxName[index] = $(this).data('tax-name');
            });
            var itemTaxId = $(this).closest('.item-row').find('input.type').val();

            var amount = parseFloat($(this).closest('.item-row').find('.amount').val());

            if(isNaN(amount)){ amount = 0; }

            subtotal = parseFloat(subtotal)+parseFloat(amount);

            if(itemTaxId != ''){
                for(var i = 0; i<=itemTaxName.length; i++)
                {
                    if(typeof (taxList[itemTaxName[i]]) === 'undefined'){
                        taxList[itemTaxName[i]] = ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                    }
                    else{
                        taxList[itemTaxName[i]] = parseFloat(taxList[itemTaxName[i]]) + ((parseFloat(itemTax[i])/100)*parseFloat(amount));
                    }
                }
            }
        });

        $.each( taxList, function( key, value ) {
            if(!isNaN(value)){

                tax = tax+'<div class="col-md-offset-8 col-md-2 col-xs-6 text-right p-t-10">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+decimalupto2(value)+'</span>'
                    +'</p>';

                taxTotal = taxTotal+value;
            }

        });

        if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal));
        $('.sub-total-field').val(subtotal);


//       show tax
        $('#invoice-taxes').html(tax);

//            calculate total
        var totalAfterDiscount = decimalupto2(subtotal);

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        $('.total').html(total);
        $('.total-field').val(total);

    }

    function recurringPayment() {
        var recurring = $('#recurring_payment').val();

        if(recurring == 'yes')
        {
            $('.recurringPayment').show().fadeIn(300);
        } else {
            $('.recurringPayment').hide().fadeOut(300);
        }
    }

    function decimalupto2(num) {
        var amt =  Math.round(num * 100,2) / 100;
        return parseFloat(amt.toFixed(2));
    }

</script>
@if (request()->get('product') != "")
    <script>
        {{--var id = {{ request()->get('product') }}--}}
        {{--addProduct(id);--}}
    </script>
@endif
@endpush

