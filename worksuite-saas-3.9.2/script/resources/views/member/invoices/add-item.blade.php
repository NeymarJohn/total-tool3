<div class="col-xs-12 item-row margin-top-5">
    <div class="col-md-3">
        <div class="row">
            <div class="form-group">
                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                <div class="input-group">
                    <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                    <input type="text" class="form-control item_name" name="item_name[]"
                           value="{{ $items->name }}" >
                </div>
            </div>
            <div class="form-group">
                <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2">{{ $items->description }}</textarea>
            </div>
        </div>
    </div>
    @if($invoiceSetting->hsn_sac_code_show)
        <div class="col-md-1 ">
            <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.hsnSacCode')</label>
            <input type="text" class="form-control"  data-item-id="{{ $items->id }}"  name="hsn_sac_code[]" >

        </div>
    @endif
    <div class="col-md-1">
        <div class="form-group">
            <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
            <input type="number" min="1" class="form-control quantity" data-item-id="{{ $items->id }}" value="1" name="quantity[]" >
        </div>
    </div>

    <div class="col-md-2">
        <div class="row">
            <div class="form-group">
                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" data-item-id="{{ $items->id }}" value="{{ $items->price }}">
            </div>
        </div>
    </div>

    <div class="col-md-2">

        <div class="form-group">
            <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.type')</label>
            <select id="" name=""  multiple="multiple" class="selectpicker form-control type">
                @foreach($taxes as $tax)
                    <option data-rate="{{ $tax->rate_percent }}"
                            @if (isset($items->taxes) && $items->taxes != "null"  && array_search($tax->id, json_decode($items->taxes)) !== false)
                            selected
                            @endif
                            value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                @endforeach
            </select>
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
