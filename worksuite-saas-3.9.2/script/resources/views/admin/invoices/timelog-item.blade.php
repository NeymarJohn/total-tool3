@forelse ($timelogs as $item)
    <div class="col-xs-12 item-row margin-top-5">
        @if (!is_null($item->task_id))
            <div class="col-md-4">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                            <input type="text" value="{{ $item->task->heading }}" readonly class="form-control item_name" name="item_name[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-4">
                <div class="row">
                    <div class="form-group">
                        <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                            <input type="text" value="{{ __('app.others') }}" readonly class="form-control item_name" name="item_name[]">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-1">
            <div class="form-group">
                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>
                <input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >
            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
                <div class="form-group">
                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>
                    <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="{{ $item->sum }}" >
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.type')</label>
                <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="selectpicker customSequence form-control type">
                    @foreach($taxes as $tax)
                        <option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2 border-dark  text-center">
            <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>
            <p class="form-control-static"><span class="amount-html">{{ $item->sum }}</span></p>
            <input type="hidden" class="amount" name="amount[]" value="{{ $item->sum }}">
        </div>
        <div class="col-md-1 text-right visible-md visible-lg">
            <button type="button" class="btn remove-item btn-circle btn-danger"><i class="fa fa-remove"></i></button>
        </div>
        <div class="col-md-1 hidden-md hidden-lg">
            <div class="row">
                <button type="button" class="btn remove-item btn-danger"><i class="fa fa-remove"></i> @lang('app.remove')</button>
            </div>
        </div>
    </div>    
@empty
    <div class="col-xs-12 item-row margin-top-5">
        <div class="alert alert-danger text-center">@lang('messages.noLogTimeFound')</div>
    </div>
@endforelse