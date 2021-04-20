<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">Edit @lang('modules.invoices.tax')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createTax','class'=>'ajax-form','method'=>'PUT']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label>@lang('modules.invoices.taxName')</label>
                        <input type="text" name="tax_name" id="tax_name" class="form-control" value="{{ $tax->tax_name }}">
                    </div>
                </div>
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label>@lang('modules.invoices.rate') %</label>
                        <input type="text" name="rate_percent" id="rate_percent" class="form-control" value="{{ $tax->rate_percent }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-tax" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $('#save-tax').click(function () {
        var url = "{{ route('admin.taxes.update',':id') }}";
        url = url.replace(':id', {{ $tax->id }});
        $.easyAjax({
            url: url,
            container: '#taxModal',
            type: "POST",
            data: $('#createTax').serialize(),
            success: function (response) {
                console.log(response);
                if(response.status == 'success'){
                    $('.modal-content').html(response.view);
                }
            }
        });
        return false;
    })
</script>