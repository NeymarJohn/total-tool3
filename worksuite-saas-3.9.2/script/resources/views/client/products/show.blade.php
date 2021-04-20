
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="ti-eye"></i> @lang('app.product') @lang('app.details')</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-xs-12">
                <h5>{{ ucwords($product->name) }}
                    @if($product->category_id)
                        <label class="label label-default text-dark m-l-5 font-light">{{ ucwords($product->category->category_name) }}</label>
                    @endif

                    @if($product->sub_category_id)
                        <label class="label label-default text-dark m-l-5 font-light">{{ ucwords($product->subcategory->category_name) }}</label>
                    @endif
                </h5>
                @if(!is_null($product->project_id))
                    <p><i class="icon-layers"></i> {{ ucfirst($product->project->project_name) }}</p>
                @endif
            </div>

            <div class="col-xs-6">
                <label class="font-12" for="">@lang('modules.estimates.price')</label><br>
                {{  $price }}
            </div>
            <div class="col-xs-6">
                <label class="font-12" for="">@lang('app.taxes')</label><br>
                @php
                    $flag = 0;
                @endphp
                @foreach($taxes as $tax)
                    @if (isset($product->taxes) && $product->taxes != "null"  && array_search($tax->id, json_decode($product->taxes)) !== false)
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
            <div class="col-xs-12 m-t-10">
                <label class="font-12" for="">@lang('app.description')</label><br>
                @if($product->description) {!! ucwords($product->description)  !!} @else -- @endif
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
</div>

