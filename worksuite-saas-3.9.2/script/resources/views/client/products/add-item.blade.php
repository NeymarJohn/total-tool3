<div class="row col-xs-12 item-row margin-top-5">
    <div class="col-md-3" style=" padding: 10px 20px;">
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
        <div class="col-md-1 " style=" padding: 10px 20px;">
            <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.hsnSacCode')</label>
            <input type="text" class="form-control" data-item-id="{{ $items->id }}"  name="hsn_sac_code[]" >

        </div>
    @endif
    <div class="col-md-1"  style=" padding: 10px 20px;">
        <div class="form-group">
            <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
            <input type="number" min="1" class="form-control quantity" data-item-id="{{ $items->id }}" value="1" name="quantity[]" >
        </div>
    </div>

    <div class="col-md-2"  style=" padding: 10px 20px;">
            <div class="form-group">
                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                <input type="hidden"  class="form-control cost_per_item" name="cost_per_item[]" data-item-id="{{ $items->id }}" value="{{ $items->price }}">
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
                @if (isset($items->taxes) && $items->taxes != "null"  && array_search($tax->id, json_decode($items->taxes)) !== false)
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
        <button type="button" class="btn remove-item btn-circle btn-danger"><i class="fa fa-remove"></i></button>
    </div>

    <div class="col-xs-12 text-center hidden-md hidden-lg">
        <div class="row">
            <button type="button" class="btn btn-circle remove-item btn-danger"><i class="fa fa-remove"></i></button>
        </div>
    </div>

    <script>
        $(function () {
            var quantity = $('#sortable').find('.quantity[data-item-id="{{ $items->id }}"]').val();
            var perItemCost = $('#sortable').find('.cost_per_item[data-item-id="{{ $items->id }}"]').val();
            var amount = (quantity*perItemCost);
            $('#sortable').find('.amount[data-item-id="{{ $items->id }}"]').val(amount);
            $('#sortable').find('.amount-html[data-item-id="{{ $items->id }}"]').html(amount);

            calculateTotal();
        });
    </script>
</div>
