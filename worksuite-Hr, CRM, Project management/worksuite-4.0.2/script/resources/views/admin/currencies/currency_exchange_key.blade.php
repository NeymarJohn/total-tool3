<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.accountSettings.currencyConverterKey')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="alert alert-info ">
            <i class="fa fa-info-circle"></i> @lang('messages.currencyConvertApiKeyUrl') <a href="https://www.currencyconverterapi.com" target="_blank" class="text-white"> https://www.currencyconverterapi.com</a>
        </div>
        {!! Form::open(['id'=>'createCurrencyKey','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.accountSettings.currencyConverterKey')</label>
                        <input type="password" name="currency_converter_key" id="currency_converter_key" value="{{ $global->currency_converter_key }}" class="form-control">
                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-category" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.currency.exchange-key-store')}}',
            container: '#createCurrencyKey',
            type: "POST",
            data: $('#createCurrencyKey').serialize(),
            success: function (response) {
              $('#projectCategoryModal').modal('hide');
            }
        })
    });
</script>
