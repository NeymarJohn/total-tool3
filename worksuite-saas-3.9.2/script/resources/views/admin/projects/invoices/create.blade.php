<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-plus"></i> @lang('modules.invoices.addInvoice') - @lang('app.project') # {{ $project->id.' '.$project->project_name }}</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">

        <!-- BEGIN FORM-->
        {!! Form::open(['id'=>'storePayments','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">

            {!! Form::hidden('project_id', $project->id) !!}
            {!! Form::hidden('company_name', $project->clientdetails->company_name ? $project->clientdetails->company_name : $project->clientdetails->name) !!}
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group" >
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">@lang('app.invoice') #</label>
                                    <div>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="invoicePrefix" data-prefix="{{ $invoiceSetting->invoice_prefix }}">{{ $invoiceSetting->invoice_prefix }}</span>#<span class="noOfZero" data-zero="{{ $invoiceSetting->invoice_digit }}">{{ $zero }}</span></div>
                                            <input type="text"  class="form-control" name="invoice_number" id="invoice_number" value="@if(is_null($lastInvoice))1 @else{{ ($lastInvoice) }}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">@lang('modules.invoices.currency')</label>
                        <select class="form-control" name="currency_id" id="currency_id">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" @if($global->currency_id == $currency->id) selected @endif>{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-md-6">

                    <div class="form-group" >
                        <label class="control-label">@lang('modules.invoices.invoiceDate')</label>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="input-icon">
                                    <input type="text" class="form-control" name="issue_date" id="invoice_date" value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">@lang('app.dueDate')</label>
                        <div class="input-icon">
                            <input type="text" class="form-control" name="due_date" autocomplete="off" id="due_date">
                        </div>
                    </div>

                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-xs-12  visible-md visible-lg">

                    <div class="col-md-3 font-bold" style="padding: 8px 15px">
                        @lang('modules.invoices.item')
                    </div>
                    <div class="col-md-1 font-bold" style="padding: 8px 15px">
                        @lang('modules.invoices.hsnSacCode')
                    </div>
                    <div class="col-md-1 font-bold" style="padding: 8px 15px">
                        @lang('modules.invoices.qty')
                    </div>

                    <div class="col-md-2 font-bold" style="padding: 8px 15px">
                        @lang('modules.invoices.unitPrice')
                    </div>

                    <div class="col-md-2 font-bold" style="padding: 8px 15px">
                        @lang('modules.invoices.tax') <a href="javascript:;" id="tax-settings" ><i class="ti-settings text-info"></i></a>
                    </div>

                    <div class="col-md-2 text-center font-bold" style="padding: 8px 15px">
                        @lang('modules.invoices.amount')
                    </div>

                    <div class="col-md-1" style="padding: 8px 15px">
                        &nbsp;
                    </div>

                </div>


                <div id="sortable">
                    <div class="col-xs-12 item-row margin-top-5">

                        <div class="col-md-3">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                        <input type="text" class="form-control item_name" name="item_name[]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>
                                </div>
                            </div>

                        </div>
                        @if($invoiceSetting->hsn_sac_code_show)
                            <div class="col-md-1">
                                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.hsnSacCode')</label>
                                <input type="text" class="form-control" name="hsn_sac_code[]" >

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
                                    <input type="text"  class="form-control cost_per_item" name="cost_per_item[]" value="0" >
                                </div>
                            </div>

                        </div>

                        <div class="col-md-2">

                            <div class="form-group">
                                <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.type')</label>
                                <select id="multiselect" name="taxes[0][]"  multiple="multiple" class="selectpicker form-control type">
                                    @foreach($taxes as $tax)
                                        <option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name }}: {{ $tax->rate_percent }}%</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-2 border-dark  text-center">
                            <label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>

                            <p class="form-control-static"><span class="amount-html">0.00</span></p>
                            <input type="hidden" class="amount" name="amount[]" value="0">
                        </div>

                        <div class="col-md-1 text-right visible-md visible-lg">
                            <button type="button" class="btn remove-item btn-circle btn-danger"><i class="fa fa-remove"></i></button>
                        </div>
                        <div class="col-md-1 hidden-md hidden-lg">
                            <div class="row">
                                <button type="button" class="btn btn-circle remove-item btn-danger"><i class="fa fa-remove"></i> @lang('app.remove')</button>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-xs-12 m-t-5">
                    <button type="button" class="btn btn-info" id="add-item"><i class="fa fa-plus"></i> @lang('modules.invoices.addItem')</button>
                </div>

                <div class="col-xs-12 ">


                    <div class="row">
                        <div class="col-md-offset-9 col-xs-6 col-md-1 text-right p-t-10" >@lang('modules.invoices.subTotal')</div>

                        <p class="form-control-static col-xs-6 col-md-2" >
                            <span class="sub-total">0.00</span>
                        </p>


                        <input type="hidden" class="sub-total-field" name="sub_total" value="0">
                    </div>

                    <div class="row">
                        <div class="col-md-offset-9 col-md-1 text-right p-t-10">
                            @lang('modules.invoices.discount')
                        </div>
                        <div class="form-group col-xs-6 col-md-1" >
                            <input type="number" min="0" value="0" name="discount_value" class="form-control discount_value">
                        </div>
                        <div class="form-group col-xs-6 col-md-1" >
                            <select class="form-control" name="discount_type" id="discount_type">
                                <option value="percent">%</option>
                                <option value="fixed">@lang('modules.invoices.amount')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-t-5" id="invoice-taxes">
                        <div class="col-md-offset-9 col-md-1 text-right p-t-10">
                            @lang('modules.invoices.tax')
                        </div>

                        <p class="form-control-static col-xs-6 col-md-2" >
                            <span class="tax-percent">0.00</span>
                        </p>
                    </div>

                    <div class="row m-t-5 font-bold">
                        <div class="col-md-offset-9 col-md-1 col-xs-6 text-right p-t-10" >@lang('modules.invoices.total')</div>

                        <p class="form-control-static col-xs-6 col-md-2" >
                            <span class="total">0.00</span>
                        </p>


                        <input type="hidden" class="total-field" name="total" value="0">
                    </div>

                </div>

            </div>
        </div>
        <div class="form-actions" style="margin-top: 70px">
            <div class="row">
                <div class="col-xs-12">
                    <button type="button" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script>

    $(".selectpicker").selectpicker({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#tax-settings').click(function () {
        var url = '{{ route('admin.taxes.create')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#taxModal', url);
    })

    jQuery('#invoice_date, #due_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });
    $('#invoice_number').on('keyup', function () {
        var invoiceNumber = $(this).val();
        var invoiceDigit = $('.noOfZero').data('zero');
        var invoiceZero = '';
        if(invoiceNumber.length < invoiceDigit){
            for ($i=0; $i<invoiceDigit-invoiceNumber.length; $i++){
                invoiceZero = invoiceZero+'0';
            }
        }

        // var invoice_no = invoicePrefix+'#'+invoiceZero;
        $('.noOfZero').text(invoiceZero);
    });

    $('#save-form').click(function(){

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
            url:'{{route('admin.invoices.store')}}',
            container:'#storePayments',
            type: "POST",
            redirect: true,
            data:$('#storePayments').serialize(),
            success: function (data) {
                if(data.status == 'success'){
                    $('#invoices-list-panel ul.list-group').html(data.html);
                    $('#add-invoice-modal').modal('hide');
                }
            }
        })
    });

    $('#add-item').click(function () {
        var i = $(document).find('.item_name').length;
        var item = '<div class="col-xs-12 item-row margin-top-5">'

            +'<div class="col-md-3">'
            +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.item')</label>'
            +'<div class="input-group">'
            +'<div class="input-group-addon"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'
            +'<input type="text" class="form-control item_name" name="item_name[]" >'
            +'</div>'
            +'</div>'

            +'<div class="form-group">'
            +'<textarea name="item_summary[]" class="form-control" placeholder="@lang('app.description')" rows="2"></textarea>'
            +'</div>'

            +'</div>'

            +'</div>'
            +'<div class="col-md-1">'

            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.hsnSacCode')</label>'
            +'<input type="text"  class="form-control" name="hsn_sac_code[]" >'
            +'</div>'
            +'</div>'
            +'<div class="col-md-1">'

            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.qty')</label>'
            +'<input type="number" min="1" class="form-control quantity" value="1" name="quantity[]" >'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2">'
            +'<div class="row">'
            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.unitPrice')</label>'
            +'<input type="text" min="0" class="form-control cost_per_item" value="0" name="cost_per_item[]">'
            +'</div>'
            +'</div>'

            +'</div>'


            +'<div class="col-md-2">'

            +'<div class="form-group">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.tax')</label>'
            +'<select id="multiselect'+i+'" name="taxes['+i+'][]"  multiple="multiple" class="selectpicker form-control type">'
                @foreach($taxes as $tax)
            +'<option data-rate="{{ $tax->rate_percent }}" value="{{ $tax->id }}">{{ $tax->tax_name.': '.$tax->rate_percent }}%</option>'
                @endforeach
            +'</select>'
            +'</div>'


            +'</div>'

            +'<div class="col-md-2 text-center">'
            +'<label class="control-label hidden-md hidden-lg">@lang('modules.invoices.amount')</label>'
            +'<p class="form-control-static"><span class="amount-html">0.00</span></p>'
            +'<input type="hidden" class="amount" name="amount[]">'
            +'</div>'

            +'<div class="col-md-1 text-right visible-md visible-lg">'
            +'<button type="button" class="btn remove-item btn-circle btn-danger"><i class="fa fa-remove"></i></button>'
            +'</div>'

            +'<div class="col-md-1 hidden-md hidden-lg">'
            +'<div class="row">'
            +'<button type="button" class="btn remove-item btn-danger"><i class="fa fa-remove"></i> @lang('app.remove')</button>'
            +'</div>'
            +'</div>'

            +'</div>';

        $(item).hide().appendTo("#sortable").fadeIn(500);
        $('#multiselect'+i).selectpicker();
        hsnSacColumn();
    });

    hsnSacColumn();
    function hsnSacColumn(){
        @if($invoiceSetting->hsn_sac_code_show)
        $('input[name="item_name[]"]').parent("div").parent('div').parent('div').parent('div').removeClass( "col-md-4");
        $('input[name="item_name[]"]').parent("div").parent('div').parent('div').parent('div').addClass( "col-md-3");
        $('input[name="hsn_sac_code[]"]').parent("div").parent('div').show();
        @else
        $('input[name="hsn_sac_code[]"]').parent("div").parent('div').hide();
        $('input[name="item_name[]"]').parent("div").parent('div').parent('div').parent('div').removeClass( "col-md-3");
        $('input[name="item_name[]"]').parent("div").parent('div').parent('div').parent('div').addClass( "col-md-4");
        @endif
    }

    $('#storePayments').on('click','.remove-item', function () {
        $(this).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            calculateTotal();
        });
    });

    $('#storePayments').on('keyup change','.quantity,.cost_per_item,.item_name, .discount_value', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();


    });

    $('#storePayments').on('change','.type, #discount_type', function () {
        var quantity = $(this).closest('.item-row').find('.quantity').val();

        var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();

        var amount = (quantity*perItemCost);

        $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
        $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

        calculateTotal();


    });

    function calculateTotal(){
        var subtotal = 0;
        var discount = 0;
        var tax = '';
        var taxList = new Object();
        var taxTotal = 0;
        $(".quantity").each(function (index, element) {
            var itemTax = [];
            var itemTaxName = [];
            $(this).closest('.item-row').find('select.type option:selected').each(function (index) {
                itemTax[index] = $(this).data('rate');
                itemTaxName[index] = $(this).text();
            });
            var itemTaxId = $(this).closest('.item-row').find('select.type').val();
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
                tax = tax+'<div class="col-md-offset-8 col-md-2 text-right p-t-10">'
                    +key
                    +'</div>'
                    +'<p class="form-control-static col-xs-6 col-md-2" >'
                    +'<span class="tax-percent">'+decimalupto2(value)+'</span>'
                    +'</p>';
                taxTotal = taxTotal+decimalupto2(value);
            }
        });

        if(isNaN(subtotal)){  subtotal = 0; }

        $('.sub-total').html(decimalupto2(subtotal));
        $('.sub-total-field').val(decimalupto2(subtotal));

        var discountType = $('#discount_type').val();
        var discountValue = $('.discount_value').val();

        if(discountValue != ''){
            if(discountType == 'percent'){
                discount = ((parseFloat(subtotal)/100)*parseFloat(discountValue));
            }
            else{
                discount = parseFloat(discountValue);
            }

        }

//       show tax
        $('#invoice-taxes').html(tax);

//            calculate total
        var totalAfterDiscount = decimalupto2(subtotal-discount);

        totalAfterDiscount = (totalAfterDiscount < 0) ? 0 : totalAfterDiscount;

        var total = decimalupto2(totalAfterDiscount+taxTotal);

        $('.total').html(total);
        $('.total-field').val(total);

    }

    function decimalupto2(num) {
        var amt =  Math.round(num * 100) / 100;
        return parseFloat(amt.toFixed(2));
    }

</script>